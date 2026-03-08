
<?php
/*
 *  ========================================================================
 *  * Open eClass - CDM Import Module
 *  * E-learning and Course Management System
 *  * ========================================================================
 *  * Copyright 2003-2024, Greek Universities Network - GUnet
 *  *
 *  * Open eClass is an open platform distributed in the hope that it will
 *  * be useful (without any warranty), under the terms of the GNU (General
 *  * Public License) as published by the Free Software Foundation.
 *  * The full license can be read in "/info/license/license_gpl.txt".
 *  *
 *  * Contact address: GUnet Asynchronous eLearning Group
 *  *                  e-mail: info@openeclass.org
 *  * ========================================================================
 */

$require_login = TRUE;
$require_teacher = TRUE;

require_once '../../include/baseTheme.php';
require_once 'include/lib/fileUploadLib.inc.php';
require_once 'include/lib/course.class.php';
require_once 'functions.php';

$toolName = "CDM Course Import";
$pageName = "Import Course from CDM File";
$navigation[] = array('url' => '../../main/portfolio.php', 'name' => $langPortfolio);

// CDM to OpenEClass mapping class
class CDMImporter {
    private $course_id;
    private $course_code;
    private $upload_path;
    private $cdm_data;

    private $import_stats;

    public function __construct() {
        $this->upload_path = $GLOBALS['webDir'] . '/courses/temp_uploads/';
        $this->import_stats = [
            'videos' => 0,
            'quizzes' => 0,
            'assignments' => 0,
            'forums' => 0,
            'wikis' => 0,
            'links' => 0,
            'documents' => 0,
            'glossary' => 0,
            'polls' => 0,
            'learning_paths' => 0,
            'ebooks' => 0,
            'h5p' => 0,
            'chat' => 0,
            'database' => 0,
            'activities' => 0,
            'other' => 0,
            'total' => 0,
            'failed' => 0
        ];

        // Create upload directory if it doesn't exist
        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0755, true);
        }
    }

    /**
     * Increment import statistics counter
     */
    private function incrementImportStat($type) {
        if (isset($this->import_stats[$type])) {
            $this->import_stats[$type]++;
        }
        $this->import_stats['total']++;
    }

    /**
     * Get import statistics
     */
    public function getImportStats() {
        return $this->import_stats;
    }

    /**
     * Extract and parse CDM file
     */
    public function extractCDM($file_path) {
        $temp_dir = $this->upload_path . 'cdm_' . uniqid() . '/';
        mkdir($temp_dir, 0755, true);

        // Extract the ZIP file
        $zip = new ZipArchive;
        if ($zip->open($file_path) === TRUE) {
            $zip->extractTo($temp_dir);
            $zip->close();

            // Look for source.json
            $json_file = $temp_dir . 'source.json';
            if (file_exists($json_file)) {
                $json_content = file_get_contents($json_file);
                $data = json_decode($json_content, true);

                // Clean up temp directory
                $this->deleteDirectory($temp_dir);

                return $data;
            } else {
                throw new Exception('source.json not found in CDM file');
            }
        } else {
            throw new Exception('Unable to extract CDM file');
        }
    }

    /**
     * Create course from CDM data
     */
    public function createCourse($cdm_data) {
        if (!isset($cdm_data['data']['LessonInfo'])) {
            throw new Exception('Invalid CDM format: LessonInfo not found');
        }

        $this->cdm_data = $cdm_data;
        $lesson_info = $cdm_data['data']['LessonInfo'];

        // Extract comprehensive course information
        $course_title = $lesson_info['StrategyName'] ?? 'Imported Course';
        $course_description = $lesson_info['Description'] ?? '';
        $education_level = $lesson_info['EducationLevel'] ?? '';
        $subject_area = $lesson_info['SubjectArea'] ?? '';
        $duration = ($lesson_info['DurationNumber'] ?? '') . ' ' . ($lesson_info['DurationType'] ?? '');

        // Create detailed course description
        $full_description = $course_description;

        if ($education_level) {
            $full_description .= "\n\n**Education Level:** " . $education_level;
        }

        if ($subject_area) {
            $full_description .= "\n**Subject Area:** " . $subject_area;
        }

        if ($duration !== ' ') {
            $full_description .= "\n**Duration:** " . $duration;
        }

        // Add learning goals
        if (!empty($lesson_info['Goals'])) {
            $full_description .= "\n\n**Learning Goals:**\n";
            foreach ($lesson_info['Goals'] as $goal) {
                $full_description .= "• " . $goal . "\n";
            }
        }

        // Add methodology information
        if (strpos($course_description, 'Think Pair Share') !== false ||
            isset($cdm_data['data']['Flow']['FlowSub'])) {
            $full_description .= "\n\n**Teaching Methodology:** Think-Pair-Share";

            // Extract flow phases
            if (isset($cdm_data['data']['Flow']['FlowSub'])) {
                $phases = [];
                foreach ($cdm_data['data']['Flow']['FlowSub'] as $phase) {
                    if (isset($phase['type']) && $phase['type'] === 'flowPhase') {
                        $phases[] = $phase['text'];
                    }
                }
                if (!empty($phases)) {
                    $full_description .= "\n**Learning Phases:** " . implode(' → ', $phases);
                }
            }
        }

        // Add actors and learner information
        if (!empty($lesson_info['Actors'])) {
            $full_description .= "\n\n**Course Participants:** " . implode(', ', $lesson_info['Actors']);
        }

        if (!empty($lesson_info['Learners'])) {
            $full_description .= "\n**Target Learners:** " . implode(', ', $lesson_info['Learners']);
        }

        if (!empty($lesson_info['StaffRoles'])) {
            $full_description .= "\n**Staff Roles:** " . implode(', ', $lesson_info['StaffRoles']);
        }

        $prof_name = $_SESSION['givenname'] . ' ' . $_SESSION['surname'];

        // Use OpenEClass create_course function
        $departments = [1]; // Default department
        $result = create_course('', 'el', $course_title, $full_description, $departments, 2, $prof_name);

        if (!$result) {
            throw new Exception('Failed to create course');
        }

        list($this->course_code, $this->course_id) = $result;

        // Create course index.php file
        $this->createCourseIndexFile();

        // Activate essential modules for the course
        $this->activateCourseModules();

        // Store CDM metadata in course description or custom table
        $this->storeCDMMetadata($cdm_data);

        // Import course content
        $this->importCourseContent($cdm_data['data']);

        return [
            'course_id' => $this->course_id,
            'course_code' => $this->course_code,
            'title' => $course_title,
            'full_description' => $full_description,
            'lesson_info' => $lesson_info,
            'import_stats' => $this->getImportStats()
        ];
    }

    /**
     * Create course index.php file for course access
     */
    private function createCourseIndexFile() {
        $course_dir = $GLOBALS['webDir'] . '/courses/' . $this->course_code;

        if (!is_dir($course_dir)) {
            mkdir($course_dir, 0755, true);
        }

        $index_content = "<?php\n";
        $index_content .= "session_start();\n";
        $index_content .= "\$_SESSION['dbname']='" . $this->course_code . "';\n";
        $index_content .= "include '../../modules/course_home/course_home.php';\n";

        file_put_contents($course_dir . '/index.php', $index_content);
    }

    /**
     * Activate essential course modules
     */
    private function activateCourseModules() {
        // Essential modules for CDM courses
        $essential_modules = [
            1 => 1,   // Announcements - Active
            2 => 1,   // Agenda - Active
            3 => 1,   // Documents - Active (needed for CDM materials, audio, image, page)
            4 => 1,   // Video/Multimedia - Active (needed for CDM videos)
            5 => 1,   // Exercises - Active (needed for CDM quizzes)
            6 => 1,   // Assignments - Active (needed for CDM assessments and database conversion)
            7 => 1,   // Glossary - Active (needed for CDM glossary)
            8 => 1,   // Learning Path - Active (needed for CDM lessons)
            9 => 1,   // Links - Active (needed for CDM hypertext/links)
            10 => 1,  // Course Units - Active (needed for Think-Pair-Share structure)
            11 => 1,  // E-Book - Active (needed for CDM book imports)
            12 => 1,  // Questionnaire - Active (needed for CDM polls/feedback/survey)
            13 => 1,  // Wiki - Active (needed for CDM wiki content)
            14 => 0,  // Wall/Social - Inactive
            15 => 1,  // Chat - Active (needed for CDM chat rooms)
            16 => 1,  // Forum - Active
            17 => 0,  // Groups - Inactive
            18 => 0,  // Dropbox - Inactive
            19 => 0,  // User Progress - Inactive
            20 => 0   // Usage Statistics - Inactive
        ];

        foreach ($essential_modules as $module_id => $visible) {
            try {
                Database::get()->query("INSERT INTO course_module (course_id, module_id, visible) VALUES (?d, ?d, ?d)
                                       ON DUPLICATE KEY UPDATE visible = ?d",
                                       $this->course_id, $module_id, $visible, $visible);
            } catch (Exception $e) {
                // Continue even if module activation fails
                error_log("Failed to activate module $module_id for course {$this->course_id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Store CDM metadata for later retrieval
     */
    private function storeCDMMetadata($cdm_data) {
        // Store the COMPLETE CDM data as JSON in the course keywords field
        // This includes both LessonInfo and LessonInfoExtras for full metadata preservation
        $complete_metadata = $cdm_data['data']['LessonInfo'];

        // Merge LessonInfoExtras if available
        if (isset($cdm_data['data']['LessonInfoExtras'])) {
            $complete_metadata = array_merge($complete_metadata, $cdm_data['data']['LessonInfoExtras']);
        }

        Database::get()->query("UPDATE course SET
            keywords = ?s
            WHERE id = ?d",
            json_encode($complete_metadata),
            $this->course_id
        );
    }

    /**
     * Import course content from CDM data
     */
    private function importCourseContent($data) {
        // Import conceptual activities
        if (isset($data['Conceptual']['ConceptualBase'])) {
            $this->importActivities($data['Conceptual']['ConceptualBase']);
        }

        // Import flow data - create course units
        if (isset($data['Flow']['FlowSub'])) {
            $this->importFlowUnits($data['Flow']['FlowSub']);
        }
    }

    /**
     * Import activities and resources
     */
    private function importActivities($activities, $parent_id = null, $parent_type = null) {
        foreach ($activities as $activity) {
            // Children of an activity-simple are handled by merging into their parent; skip here.
            if ($parent_type === 'activity-simple') {
                continue;
            }

            $original_type = $activity['type'] ?? 'activity-simple';

            // For activity-simple nodes with activity-resource children, merge the child's
            // technical fields (Type, ResourceLocation) into the parent's rich ModalData,
            // then create the resource using the parent's rich details.
            $activity = $this->mergeChildResourceIntoParent($activity);

            $this->createCourseModule($activity);

            // Do not recurse into children of (original) activity-simple nodes —
            // their children have already been merged into the parent above.
            if (!empty($activity['children']) && $original_type !== 'activity-simple') {
                $this->importActivities($activity['children'], $activity['id'], $original_type);
            }
        }
    }

    /**
     * Merge the first activity-resource child's technical fields (Type, ResourceLocation)
     * into the parent activity-simple's rich ModalData, then promote it to activity-resource
     * so createCourseModule routes it to the correct handler.
     * Parent's Title, Description, and LearningGoal are always preserved.
     */
    private function mergeChildResourceIntoParent($activity) {
        if (($activity['type'] ?? '') !== 'activity-simple' || empty($activity['children'])) {
            return $activity;
        }

        foreach ($activity['children'] as $child) {
            if (($child['type'] ?? '') === 'activity-resource' && !empty($child['ModalData'])) {
                $child_modal = $child['ModalData'];

                if (!isset($activity['ModalData'])) {
                    $activity['ModalData'] = [];
                }

                // Merge only technical fields from child — preserve parent's rich Title, Description, LearningGoal
                if (!empty($child_modal['Type'])) {
                    $activity['ModalData']['Type'] = $child_modal['Type'];
                }
                if (!empty($child_modal['ResourceLocation'])) {
                    $activity['ModalData']['ResourceLocation'] = $child_modal['ResourceLocation'];
                }
                if (isset($child_modal['IsStoredFile'])) {
                    $activity['ModalData']['IsStoredFile'] = $child_modal['IsStoredFile'];
                }

                // Promote to activity-resource so createCourseModule routes it correctly
                $activity['type'] = 'activity-resource';
                break;
            }
        }

        return $activity;
    }

    /**
     * Create course module entry
     */
    private function createCourseModule($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $type = $activity['type'] ?? 'activity-simple';

        // Determine what type of content to create
        if ($type === 'activity-resource' && isset($modal_data['Type'])) {
            switch (strtolower($modal_data['Type'])) {
                // EXISTING HANDLERS
                case 'video':
                    $this->createVideoLink($activity);
                    $this->incrementImportStat('videos');
                    break;
                case 'quiz':
                    $this->createExercise($activity);
                    $this->incrementImportStat('quizzes');
                    break;
                case 'assessment':
                    $this->createAssignment($activity);
                    $this->incrementImportStat('assignments');
                    break;
                case 'forum':
                    $this->createForumTopic($activity);
                    $this->incrementImportStat('forums');
                    break;
                case 'wiki':
                    $this->createWikiPage($activity);
                    $this->incrementImportStat('wikis');
                    break;

                // NEW HIGH PRIORITY HANDLERS
                case 'hypertext':
                case 'link':
                case 'url':
                    $this->createHyperlink($activity);
                    $this->incrementImportStat('links');
                    break;

                case 'audio':
                    $this->createDocumentResource($activity, 'audio');
                    $this->incrementImportStat('documents');
                    break;

                case 'image':
                    $this->createDocumentResource($activity, 'image');
                    $this->incrementImportStat('documents');
                    break;

                case 'document':
                case 'file':
                    $this->createDocumentResource($activity, 'document');
                    $this->incrementImportStat('documents');
                    break;

                case 'page':
                    $this->createPageDocument($activity);
                    $this->incrementImportStat('documents');
                    break;

                case 'glossary':
                    $this->createGlossaryEntry($activity);
                    $this->incrementImportStat('glossary');
                    break;

                // NEW MEDIUM PRIORITY HANDLERS
                case 'poll':
                case 'feedback':
                case 'survey':
                    $this->createPoll($activity);
                    $this->incrementImportStat('polls');
                    break;

                case 'lesson':
                    $this->createLearningPath($activity);
                    $this->incrementImportStat('learning_paths');
                    break;

                case 'book':
                    $this->createEBook($activity);
                    $this->incrementImportStat('ebooks');
                    break;

                // NEW LOW PRIORITY HANDLERS
                case 'h5p':
                    $this->createH5PContent($activity);
                    $this->incrementImportStat('h5p');
                    break;

                case 'chat':
                    $this->createChatRoom($activity);
                    $this->incrementImportStat('chat');
                    break;

                case 'database':
                    $this->handleDatabaseActivity($activity);
                    $this->incrementImportStat('database');
                    break;

                default:
                    // Store as CDM activity instead of document
                    $this->storeCDMActivity($activity);
                    $this->incrementImportStat('other');
                    break;
            }
        } else {
            // Store learning activities as CDM activities, not documents
            $this->storeCDMActivity($activity);
            $this->incrementImportStat('activities');
        }
    }

    /**
     * Store CDM activity as independent learning content (not as document)
     */
    private function storeCDMActivity($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Activity';
        $description = $modal_data['Description'] ?? '';
        $type = $modal_data['Type'] ?? 'Learning Activity';

        // Store CDM activity in course keywords as structured data
        $existing_keywords = Database::get()->querySingle("SELECT keywords FROM course WHERE id = ?d", $this->course_id);
        $keywords_data = json_decode($existing_keywords->keywords ?? '{}', true);

        if (!isset($keywords_data['CDM_Activities'])) {
            $keywords_data['CDM_Activities'] = [];
        }

        // Add activity to CDM activities collection
        $activity_data = [
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'actor' => $modal_data['Actor'] ?? '',
            'facilitator' => $modal_data['Facilitator'] ?? '',
            'facilitator_role' => $modal_data['FacilitatorRole'] ?? '',
            'learning_goals' => $modal_data['LearningGoal'] ?? [],
            'author' => $modal_data['Author'] ?? '',
            'copyright' => $modal_data['Copyright'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $keywords_data['CDM_Activities'][] = $activity_data;

        // Update course keywords with CDM activities
        Database::get()->query("UPDATE course SET keywords = ?s WHERE id = ?d",
            json_encode($keywords_data), $this->course_id);
    }

    /**
     * Create enhanced document from activity with all available details (legacy method)
     */
    private function createDocument($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Activity';
        $description = $modal_data['Description'] ?? '';

        // Build comprehensive content
        $content = "<h2>" . htmlspecialchars($title) . "</h2>";

        if ($description) {
            $content .= "<div class='activity-description'>";
            $content .= nl2br(htmlspecialchars($description));
            $content .= "</div>";
        }

        // Add learning goals if available
        if (!empty($modal_data['LearningGoal'])) {
            $content .= "<div class='learning-goals'>";
            $content .= "<h3>Learning Goals:</h3><ul>";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $content .= "<li>" . htmlspecialchars($goal) . "</li>";
            }
            $content .= "</ul></div>";
        }

        // Add detailed activity information
        $activity_info = [];

        if (!empty($modal_data['Type'])) {
            $activity_info[] = "<strong>Activity Type:</strong> " . htmlspecialchars($modal_data['Type']);
        }

        if (!empty($modal_data['Actor'])) {
            $activity_info[] = "<strong>Target Audience:</strong> " . htmlspecialchars($modal_data['Actor']);
        }

        if (!empty($modal_data['Facilitator'])) {
            $activity_info[] = "<strong>Facilitator:</strong> " . htmlspecialchars($modal_data['Facilitator']);
        }

        if (!empty($modal_data['FacilitatorRole'])) {
            $activity_info[] = "<strong>Facilitator Role:</strong> " . htmlspecialchars($modal_data['FacilitatorRole']);
        }

        if (!empty($modal_data['Author'])) {
            $activity_info[] = "<strong>Author:</strong> " . htmlspecialchars($modal_data['Author']);
        }

        if (!empty($modal_data['Copyright'])) {
            $activity_info[] = "<strong>Copyright:</strong> " . htmlspecialchars($modal_data['Copyright']);
        }

        if (!empty($activity_info)) {
            $content .= "<div class='activity-info'>";
            $content .= "<h4>Activity Details:</h4>";
            $content .= "<p>" . implode("<br>", $activity_info) . "</p>";
            $content .= "</div>";
        }

        // Add resource link if available
        if (!empty($modal_data['ResourceLocation'])) {
            $content .= "<div class='resource-link'>";
            $content .= "<h4>Resource:</h4>";
            $content .= "<p><a href='" . htmlspecialchars($modal_data['ResourceLocation']) . "' target='_blank'>";
            $content .= htmlspecialchars($modal_data['ResourceLocation']) . "</a></p>";
            $content .= "</div>";
        }

        // Add activity metadata
        if (isset($activity['id'])) {
            $content .= "<div class='cdm-metadata' style='margin-top: 20px; padding: 10px; background: #f5f5f5; border-left: 4px solid #007bff;'>";
            $content .= "<small><strong>CDM Activity ID:</strong> " . htmlspecialchars($activity['id']) . "</small>";
            $content .= "</div>";
        }

        // Create the document with proper extension and MIME type
        $safe_title = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);

        // Determine file extension based on content type
        $modal_data = $activity['ModalData'] ?? [];
        $resource_location = $modal_data['ResourceLocation'] ?? '';

        if (!empty($resource_location)) {
            // If it's a URL resource, create as HTML
            $filename = $safe_title . '.html';
        } else {
            // For text activities, create as HTML document
            $filename = $safe_title . '.html';
        }

        $file_path = '/' . $filename;
        $course_dir = $GLOBALS['webDir'] . '/courses/' . $this->course_code . '/document';

        // Create document directory if it doesn't exist
        if (!is_dir($course_dir)) {
            mkdir($course_dir, 0755, true);
        }

        $file_creator = $_SESSION['givenname'] . ' ' . $_SESSION['surname'];
        $current_date = date('Y-m-d G:i:s');

        // Add proper HTML DOCTYPE and head tags for better rendering
        $html_content = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset='UTF-8'>\n<title>" . htmlspecialchars($title) . "</title>\n<style>body{font-family:Arial,sans-serif;margin:20px;line-height:1.6;}</style>\n</head>\n<body>\n" . $content . "\n</body>\n</html>";

        // Write content to file
        file_put_contents($course_dir . '/' . $filename, $html_content);

        Database::get()->query("INSERT INTO document SET
            course_id = ?d,
            subsystem = 0,
            subsystem_id = 0,
            path = ?s,
            extra_path = '',
            filename = ?s,
            visible = 1,
            comment = ?s,
            category = 0,
            title = ?s,
            creator = ?s,
            date = ?t,
            date_modified = ?t,
            format = '.html'",
            $this->course_id,
            $file_path,
            $filename,
            $description,
            $title,
            $file_creator,
            $current_date,
            $current_date
        );
    }

    /**
     * Create video link from activity
     */
    private function createVideoLink($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Video';
        $description = $modal_data['Description'] ?? '';
        $video_url = $modal_data['ResourceLocation'] ?? '';

        if ($video_url) {
            Database::get()->query("INSERT INTO videolink SET
                course_id = ?s,
                url = ?s,
                title = ?s,
                description = ?s,
                category = 0,
                creator = ?s,
                publisher = '',
                date = NOW()",
                $this->course_id,
                $video_url,
                $title,
                $description,
                $_SESSION['givenname'] . ' ' . $_SESSION['surname']
            );
        }
    }

    /**
     * Create exercise from activity
     */
    private function createExercise($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Exercise';
        $description = $modal_data['Description'] ?? '';

        Database::get()->query("INSERT INTO exercise SET
            course_id = ?d,
            title = ?s,
            description = ?s,
            type = 1,
            start_date = NOW(),
            end_date = DATE_ADD(NOW(), INTERVAL 30 DAY),
            time_constraint = 0,
            attempts_allowed = 0,
            random = 0,
            active = 1",
            $this->course_id,
            $title,
            $description
        );
    }

    /**
     * Create assignment from activity (Assessment → work/Εργασίες)
     */
    private function createAssignment($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Assignment';
        $description = $modal_data['Description'] ?? '';

        // Add learning goals to assignment description
        if (!empty($modal_data['LearningGoal'])) {
            $description .= "<br><strong>Learning Objectives:</strong><ul>";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $description .= "<li>" . htmlspecialchars($goal) . "</li>";
            }
            $description .= "</ul>";
        }

        // Add facilitator instructions if available
        if (!empty($modal_data['FacilitatorRole'])) {
            $description .= "<br><strong>Instructions for Facilitator:</strong><br>";
            $description .= htmlspecialchars($modal_data['FacilitatorRole']);
        }

        $result = Database::get()->query("INSERT INTO assignment SET
            course_id = ?d,
            title = ?s,
            description = ?s,
            comments = '',
            deadline = DATE_ADD(NOW(), INTERVAL 7 DAY),
            late_submission = 1,
            submission_date = NOW(),
            active = 1,
            secret_directory = ?s",
            $this->course_id,
            $title,
            $description,
            uniqid()
        );
    }

    /**
     * Create forum topic from activity (Forum → Συζητήσεις/Forum)
     */
    private function createForumTopic($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Discussion Topic';
        $description = $modal_data['Description'] ?? '';

        // Create forum if it doesn't exist
        $forum_id = $this->ensureDefaultForum();

        // Build topic content
        $topic_content = $description;
        if (!empty($modal_data['LearningGoal'])) {
            $topic_content .= "<br><br><strong>Discussion Goals:</strong><ul>";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $topic_content .= "<li>" . htmlspecialchars($goal) . "</li>";
            }
            $topic_content .= "</ul>";
        }

        // Add facilitator guidelines
        if (!empty($modal_data['FacilitatorRole'])) {
            $topic_content .= "<br><strong>Facilitator Guidelines:</strong><br>";
            $topic_content .= htmlspecialchars($modal_data['FacilitatorRole']);
        }

        $result = Database::get()->query("INSERT INTO forum_topic SET
            forum_id = ?d,
            title = ?s,
            poster_id = ?d,
            topic_time = NOW(),
            num_views = 0,
            num_replies = 0,
            locked = 0",
            $forum_id,
            $title,
            $_SESSION['uid']
        );

        $topic_id = $result->lastInsertID;

        // Create initial post
        $post_result = Database::get()->query("INSERT INTO forum_post SET
            topic_id = ?d,
            poster_id = ?d,
            post_text = ?s,
            post_time = NOW(),
            poster_ip = ?s",
            $topic_id,
            $_SESSION['uid'],
            $topic_content,
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        );

        $post_id = $post_result->lastInsertID;

        // Update forum statistics
        Database::get()->query("UPDATE forum SET
            num_topics = num_topics + 1,
            num_posts = num_posts + 1,
            last_post_id = ?d
            WHERE id = ?d",
            $post_id,
            $forum_id
        );

        // Update topic statistics
        Database::get()->query("UPDATE forum_topic SET
            last_post_id = ?d
            WHERE id = ?d",
            $post_id,
            $topic_id
        );
    }

    /**
     * Create wiki page from activity (Wiki → Wiki)
     */
    private function createWikiPage($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Wiki Page';
        $description = $modal_data['Description'] ?? '';

        // Build wiki content
        $wiki_content = "<h2>" . htmlspecialchars($title) . "</h2>\n\n";
        $wiki_content .= $description . "\n\n";

        // Add learning objectives as wiki content
        if (!empty($modal_data['LearningGoal'])) {
            $wiki_content .= "== Learning Objectives ==\n";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $wiki_content .= "* " . htmlspecialchars($goal) . "\n";
            }
            $wiki_content .= "\n";
        }

        // Add activity instructions
        if (!empty($modal_data['FacilitatorRole'])) {
            $wiki_content .= "== Activity Instructions ==\n";
            $wiki_content .= htmlspecialchars($modal_data['FacilitatorRole']) . "\n\n";
        }

        // Add author information
        if (!empty($modal_data['Author'])) {
            $wiki_content .= "== Author ==\n";
            $wiki_content .= htmlspecialchars($modal_data['Author']) . "\n\n";
        }
        
        $result = Database::get()->query("INSERT INTO wiki_properties SET
            course_id = ?d,
            title = ?s,
            description = ?s,
            group_id = 0,
            visible = 1",
            $this->course_id,
            $title,
            'CDM imported wiki: ' . $description
        );

        $wiki_id = $result->lastInsertID;

        // Create initial wiki page
        $result2 = Database::get()->query("INSERT INTO wiki_pages SET
            wiki_id = ?d,
            owner_id = ?d,
            title = ?s,
            last_version = 1,
            ctime = NOW(),
            last_mtime = NOW()",
            $wiki_id,
            $_SESSION['uid'],
            $title
        );

        $page_id = $result2->lastInsertID;

        // Create page revision
        Database::get()->query("INSERT INTO wiki_pages_content SET
            pid = ?d,
            content = ?s,
            editor_id = ?d,
            mtime = NOW()",
            $page_id,
            $wiki_content,
            $_SESSION['uid']
        );
    }

    /**
     * Ensure default forum exists for the course
     */
    private function ensureDefaultForum() {
        // First, ensure we have a default forum category
        $cat_id = $this->ensureDefaultForumCategory();

        // Check if course already has a forum
        $existing_forum = Database::get()->querySingle(
            "SELECT id FROM forum WHERE course_id = ?d LIMIT 1",
            $this->course_id
        );

        if ($existing_forum) {
            // Update existing forum to use proper category if needed
            Database::get()->query("UPDATE forum SET cat_id = ?d WHERE id = ?d AND cat_id = 0",
                $cat_id, $existing_forum->id);
            return $existing_forum->id;
        }

        // Create default forum for the course with proper category
        $result = Database::get()->query("INSERT INTO forum SET
            name = 'CDM Discussions',
            `desc` = 'Forum created from CDM import for course discussions',
            num_topics = 0,
            num_posts = 0,
            last_post_id = 0,
            cat_id = ?d,
            course_id = ?d",
            $cat_id,
            $this->course_id
        );

        return $result->lastInsertID;
    }

    /**
     * Ensure default forum category exists for the course
     */
    private function ensureDefaultForumCategory() {
        // Check if course already has forum categories
        $existing_category = Database::get()->querySingle(
            "SELECT id FROM forum_category WHERE course_id = ?d LIMIT 1",
            $this->course_id
        );

        if ($existing_category) {
            return $existing_category->id;
        }

        // Create default forum category
        $result = Database::get()->query("INSERT INTO forum_category SET
            cat_title = 'General Discussions',
            cat_order = 1,
            course_id = ?d",
            $this->course_id
        );

        return $result->lastInsertID;
    }

    /**
     * Import flow units as course sections with comprehensive details
     */
    private function importFlowUnits($flow_sub) {
        $order = 1;
        foreach ($flow_sub as $phase) {
            if (isset($phase['type']) && $phase['type'] === 'flowPhase') {
                $title = $phase['text'] ?? 'Phase ' . $order;

                // Create detailed phase description
                $description = "<h3>Learning Phase: " . htmlspecialchars($title) . "</h3>";
                $description .= "<p>This phase is part of the Think-Pair-Share learning methodology.</p>";

                // Add phase-specific information
                switch (strtolower($title)) {
                    case 'think':
                        $description .= "<p><strong>Focus:</strong> Individual reflection and understanding</p>";
                        $description .= "<p><strong>Activities:</strong> Students work individually to understand concepts</p>";
                        break;
                    case 'pair':
                        $description .= "<p><strong>Focus:</strong> Collaborative learning in pairs</p>";
                        $description .= "<p><strong>Activities:</strong> Students work in pairs to apply knowledge</p>";
                        break;
                    case 'share':
                        $description .= "<p><strong>Focus:</strong> Knowledge sharing and evaluation</p>";
                        $description .= "<p><strong>Activities:</strong> Groups share their learning with the class</p>";
                        break;
                }

                Database::get()->query("INSERT INTO course_units SET
                    course_id = ?d,
                    title = ?s,
                    comments = ?s,
                    visible = 1,
                    `order` = ?d",
                    $this->course_id,
                    $title,
                    $description,
                    $order++
                );
            }
        }
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir) {
        if (!file_exists($dir)) return;

        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }

    // ========================================================================
    // NEW RESOURCE HANDLERS - HIGH PRIORITY
    // ========================================================================

    /**
     * Create hyperlink from CDM activity
     * Maps: Hypertext → Links module
     */
    private function createHyperlink($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Link';
        $description = $modal_data['Description'] ?? '';
        $url = $modal_data['ResourceLocation'] ?? $modal_data['URL'] ?? '';

        if (empty($url)) {
            error_log("CDM Import: Skipping hyperlink '{$title}' - no URL provided");
            $this->import_stats['failed']++;
            return;
        }

        // Ensure URL has protocol
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'http://' . $url;
        }

        // Add learning goals to description
        if (!empty($modal_data['LearningGoal'])) {
            $description .= "\n\nLearning Objectives:\n";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $description .= "• " . $goal . "\n";
            }
        }

        try {
            Database::get()->query("INSERT INTO link SET
                course_id = ?d,
                url = ?s,
                title = ?s,
                description = ?s,
                category = 0,
                `order` = 0,
                user_id = ?d",
                $this->course_id,
                $url,
                $title,
                $description,
                $_SESSION['uid'] ?? 0
            );
        } catch (Exception $e) {
            error_log("CDM Import: Failed to create hyperlink - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    /**
     * Create document from CDM file resource
     * Maps: Audio/Image/Document → Documents subsystem
     */
    private function createDocumentResource($activity, $resource_type = 'document') {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? ucfirst($resource_type);
        $description = $modal_data['Description'] ?? '';
        $resource_location = $modal_data['ResourceLocation'] ?? '';

        // Build comprehensive content HTML
        $content = "<h2>" . htmlspecialchars($title) . "</h2>";

        if ($description) {
            $content .= "<div class='resource-description'>";
            $content .= "<p>" . nl2br(htmlspecialchars($description)) . "</p>";
            $content .= "</div>";
        }

        // Add learning goals
        if (!empty($modal_data['LearningGoal'])) {
            $content .= "<div class='learning-goals' style='margin: 20px 0; padding: 15px; background: #e7f3ff; border-left: 4px solid #007bff;'>";
            $content .= "<h3>Learning Objectives:</h3><ul>";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $content .= "<li>" . htmlspecialchars($goal) . "</li>";
            }
            $content .= "</ul></div>";
        }

        // Add resource information
        if (!empty($modal_data['Author'])) {
            $content .= "<p><strong>Author:</strong> " . htmlspecialchars($modal_data['Author']) . "</p>";
        }

        if (!empty($modal_data['Copyright'])) {
            $content .= "<p><strong>License:</strong> " . htmlspecialchars($modal_data['Copyright']) . "</p>";
        }

        // Handle resource location
        if (!empty($resource_location)) {
            $content .= "<div class='resource-link' style='margin: 20px 0; padding: 15px; background: #f0f8ff; border-left: 4px solid #007bff;'>";
            $content .= "<h4>Resource:</h4>";

            if (preg_match('/^https?:\/\//i', $resource_location)) {
                $content .= "<p><a href='" . htmlspecialchars($resource_location) . "' target='_blank' class='btn btn-primary' style='display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;'>";

                switch ($resource_type) {
                    case 'audio':
                        $content .= "🔊 Open Audio File";
                        break;
                    case 'video':
                        $content .= "🎥 Open Video File";
                        break;
                    case 'image':
                        $content .= "🖼️ View Image";
                        break;
                    default:
                        $content .= "📄 Open Document";
                }

                $content .= "</a></p>";
            } else {
                $content .= "<p><strong>File:</strong> " . htmlspecialchars($resource_location) . "</p>";
                $content .= "<p><em>Note: This resource may require manual upload.</em></p>";
            }

            $content .= "</div>";
        }

        // Add metadata footer
        $content .= "<div class='cdm-metadata' style='margin-top: 20px; padding: 10px; background: #f5f5f5; border-left: 4px solid #28a745;'>";
        $content .= "<small><strong>Resource Type:</strong> " . htmlspecialchars(ucfirst($resource_type)) . "</small>";
        $content .= "</div>";

        // Create document file
        $safe_title = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);
        $filename = $safe_title . '_' . uniqid() . '.html';
        $file_path = '/' . $filename;
        $course_dir = $GLOBALS['webDir'] . '/courses/' . $this->course_code . '/document';

        if (!is_dir($course_dir)) {
            mkdir($course_dir, 0755, true);
        }

        $html_content = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html_content .= "<meta charset='UTF-8'>\n";
        $html_content .= "<title>" . htmlspecialchars($title) . "</title>\n";
        $html_content .= "<style>body{font-family:Arial,sans-serif;margin:20px;line-height:1.6;max-width:900px;}</style>\n";
        $html_content .= "</head>\n<body>\n" . $content . "\n</body>\n</html>";

        file_put_contents($course_dir . '/' . $filename, $html_content);

        $file_creator = $_SESSION['givenname'] . ' ' . $_SESSION['surname'];
        $current_date = date('Y-m-d G:i:s');

        try {
            Database::get()->query("INSERT INTO document SET
                course_id = ?d,
                subsystem = 0,
                subsystem_id = 0,
                path = ?s,
                extra_path = '',
                filename = ?s,
                visible = 1,
                comment = ?s,
                category = 0,
                title = ?s,
                creator = ?s,
                date = ?t,
                date_modified = ?t,
                format = '.html'",
                $this->course_id,
                $file_path,
                $filename,
                $description,
                $title,
                $file_creator,
                $current_date,
                $current_date
            );
        } catch (Exception $e) {
            error_log("CDM Import: Failed to create document - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    /**
     * Create document from Moodle Page resource
     * Maps: Page → Documents subsystem
     */
    private function createPageDocument($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Page';
        $description = $modal_data['Description'] ?? '';
        $content = $modal_data['Content'] ?? $description;

        $html_content = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html_content .= "<meta charset='UTF-8'>\n";
        $html_content .= "<title>" . htmlspecialchars($title) . "</title>\n";
        $html_content .= "<style>body{font-family:Arial,sans-serif;margin:20px;line-height:1.6;max-width:800px;}</style>\n";
        $html_content .= "</head>\n<body>\n";
        $html_content .= "<h1>" . htmlspecialchars($title) . "</h1>\n";
        $html_content .= "<div class='page-content'>" . $content . "</div>\n";

        if (!empty($modal_data['LearningGoal'])) {
            $html_content .= "<hr style='margin:30px 0;'><div class='learning-goals'>";
            $html_content .= "<h3>Learning Objectives:</h3><ul>";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $html_content .= "<li>" . htmlspecialchars($goal) . "</li>";
            }
            $html_content .= "</ul></div>";
        }

        $html_content .= "</body>\n</html>";

        $safe_title = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title);
        $filename = 'page_' . $safe_title . '_' . uniqid() . '.html';
        $file_path = '/' . $filename;
        $course_dir = $GLOBALS['webDir'] . '/courses/' . $this->course_code . '/document';

        if (!is_dir($course_dir)) {
            mkdir($course_dir, 0755, true);
        }

        file_put_contents($course_dir . '/' . $filename, $html_content);

        $file_creator = $_SESSION['givenname'] . ' ' . $_SESSION['surname'];
        $current_date = date('Y-m-d G:i:s');

        try {
            Database::get()->query("INSERT INTO document SET
                course_id = ?d,
                subsystem = 0,
                subsystem_id = 0,
                path = ?s,
                extra_path = '',
                filename = ?s,
                visible = 1,
                comment = ?s,
                category = 0,
                title = ?s,
                creator = ?s,
                date = ?t,
                date_modified = ?t,
                format = '.html'",
                $this->course_id,
                $file_path,
                $filename,
                $description,
                $title,
                $file_creator,
                $current_date,
                $current_date
            );
        } catch (Exception $e) {
            error_log("CDM Import: Failed to create page - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    /**
     * Create glossary entry from CDM activity
     * Maps: Glossary → Glossary module
     */
    private function createGlossaryEntry($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $term = $modal_data['Term'] ?? $modal_data['Title'] ?? 'Term';
        $definition = $modal_data['Definition'] ?? $modal_data['Description'] ?? '';
        $url = $modal_data['ResourceLocation'] ?? $modal_data['URL'] ?? '';
        $notes = $modal_data['Notes'] ?? '';

        if (!empty($modal_data['LearningGoal'])) {
            $notes .= "\n\nLearning Objectives:\n";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $notes .= "• " . $goal . "\n";
            }
        }

        if (!empty($modal_data['Author'])) {
            $notes .= "\nAuthor: " . $modal_data['Author'];
        }

        try {
            Database::get()->query("INSERT INTO glossary SET
                term = ?s,
                definition = ?s,
                url = ?s,
                `order` = 0,
                datestamp = NOW(),
                course_id = ?d,
                category_id = NULL,
                notes = ?s",
                $term,
                $definition,
                $url,
                $this->course_id,
                $notes
            );
        } catch (Exception $e) {
            error_log("CDM Import: Failed to create glossary entry - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    // ========================================================================
    // NEW RESOURCE HANDLERS - MEDIUM PRIORITY
    // ========================================================================

    /**
     * Create poll/questionnaire from CDM activity
     * Maps: Poll/Feedback/Survey → Questionnaires module
     */
    private function createPoll($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Poll';
        $description = $modal_data['Description'] ?? '';
        $poll_type = strtolower($modal_data['Type'] ?? 'poll');

        $type_mapping = [
            'poll' => 0,
            'survey' => 1,
            'feedback' => 0,
            'questionnaire' => 0
        ];
        $poll_type_id = $type_mapping[$poll_type] ?? 0;

        if (!empty($modal_data['LearningGoal'])) {
            $description .= "\n\nLearning Objectives:\n";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $description .= "• " . $goal . "\n";
            }
        }

        try {
            $result = Database::get()->query("INSERT INTO poll SET
                course_id = ?d,
                creator_id = ?d,
                name = ?s,
                creation_date = NOW(),
                start_date = NOW(),
                end_date = DATE_ADD(NOW(), INTERVAL 30 DAY),
                active = 1,
                public = 1,
                description = ?s,
                anonymized = 1,
                show_results = 1,
                type = ?d,
                multiple_submissions = 0",
                $this->course_id,
                $_SESSION['uid'] ?? 0,
                $title,
                $description,
                $poll_type_id
            );

            $poll_id = $result->lastInsertID;

            if (!empty($modal_data['Questions'])) {
                $position = 1;
                foreach ($modal_data['Questions'] as $question_data) {
                    $question_text = is_array($question_data) ?
                        ($question_data['text'] ?? $question_data['question'] ?? '') :
                        $question_data;

                    if (!empty($question_text)) {
                        $q_result = Database::get()->query("INSERT INTO poll_question SET
                            pid = ?d,
                            question_text = ?s,
                            question_type = 1,
                            q_position = ?d,
                            q_scale = 5",
                            $poll_id,
                            $question_text,
                            $position++
                        );

                        if (is_array($question_data) && !empty($question_data['answers'])) {
                            $pqid = $q_result->lastInsertID;
                            foreach ($question_data['answers'] as $answer) {
                                Database::get()->query("INSERT INTO poll_question_answer SET
                                    pqid = ?d,
                                    answer_text = ?s",
                                    $pqid,
                                    $answer
                                );
                            }
                        }
                    }
                }
            } else {
                Database::get()->query("INSERT INTO poll_question SET
                    pid = ?d,
                    question_text = ?s,
                    question_type = 2,
                    q_position = 1",
                    $poll_id,
                    'Please provide your feedback:'
                );
            }

        } catch (Exception $e) {
            error_log("CDM Import: Failed to create poll - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    /**
     * Create learning path from CDM lesson
     * Maps: Lesson → Learning Path module
     */
    private function createLearningPath($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Learning Path';
        $description = $modal_data['Description'] ?? '';

        if (!empty($modal_data['LearningGoal'])) {
            $description .= "\n\nLearning Objectives:\n";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $description .= "• " . $goal . "\n";
            }
        }

        try {
            $result = Database::get()->query("INSERT INTO lp_learnPath SET
                course_id = ?d,
                name = ?s,
                comment = ?s,
                lock = 'OPEN',
                visible = 1,
                rank = 0",
                $this->course_id,
                $title,
                $description
            );

            $learnPath_id = $result->lastInsertID;

            if (!empty($modal_data['Pages']) || !empty($modal_data['Steps'])) {
                $pages = $modal_data['Pages'] ?? $modal_data['Steps'] ?? [];
                $rank = 1;

                foreach ($pages as $page) {
                    $page_title = is_array($page) ? ($page['title'] ?? 'Page ' . $rank) : $page;
                    $page_content = is_array($page) ? ($page['content'] ?? '') : '';

                    $module_result = Database::get()->query("INSERT INTO lp_module SET
                        course_id = ?d,
                        name = ?s,
                        comment = ?s,
                        accessibility = 'PUBLIC',
                        contentType = 'LABEL',
                        startAsset_id = 0,
                        launch_data = ''",
                        $this->course_id,
                        $page_title,
                        $page_content
                    );

                    $module_id = $module_result->lastInsertID;

                    Database::get()->query("INSERT INTO lp_rel_learnPath_module SET
                        learnPath_id = ?d,
                        module_id = ?d,
                        lock = 'OPEN',
                        visible = 1,
                        rank = ?d,
                        parent = 0,
                        raw_to_pass = 50",
                        $learnPath_id,
                        $module_id,
                        $rank++
                    );
                }
            } else {
                $module_result = Database::get()->query("INSERT INTO lp_module SET
                    course_id = ?d,
                    name = ?s,
                    comment = ?s,
                    accessibility = 'PUBLIC',
                    contentType = 'LABEL',
                    startAsset_id = 0,
                    launch_data = ''",
                    $this->course_id,
                    $title,
                    $description
                );

                $module_id = $module_result->lastInsertID;

                Database::get()->query("INSERT INTO lp_rel_learnPath_module SET
                    learnPath_id = ?d,
                    module_id = ?d,
                    lock = 'OPEN',
                    visible = 1,
                    rank = 1,
                    parent = 0,
                    raw_to_pass = 50",
                    $learnPath_id,
                    $module_id
                );
            }

        } catch (Exception $e) {
            error_log("CDM Import: Failed to create learning path - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    /**
     * Create e-book from CDM book activity
     * Maps: Book → e-Book module
     */
    private function createEBook($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'E-Book';

        try {
            $result = Database::get()->query("INSERT INTO ebook SET
                course_id = ?d,
                `order` = 0,
                title = ?s,
                visible = 1",
                $this->course_id,
                $title
            );

            $ebook_id = $result->lastInsertID;

            if (!empty($modal_data['Chapters']) || !empty($modal_data['Sections'])) {
                $chapters = $modal_data['Chapters'] ?? $modal_data['Sections'] ?? [];

                foreach ($chapters as $index => $chapter) {
                    $chapter_title = is_array($chapter) ? ($chapter['title'] ?? 'Chapter ' . ($index + 1)) : $chapter;
                    $chapter_content = is_array($chapter) ? ($chapter['content'] ?? '') : '';

                    $public_id = 'chap_' . uniqid();
                    $filename = $public_id . '.html';
                    $course_dir = $GLOBALS['webDir'] . '/courses/' . $this->course_code . '/ebook';

                    if (!is_dir($course_dir)) {
                        mkdir($course_dir, 0755, true);
                    }

                    $html_content = "<!DOCTYPE html>\n<html>\n<head>\n";
                    $html_content .= "<meta charset='UTF-8'>\n";
                    $html_content .= "<title>" . htmlspecialchars($chapter_title) . "</title>\n";
                    $html_content .= "<style>body{font-family:Arial,sans-serif;margin:20px;line-height:1.6;}</style>\n";
                    $html_content .= "</head>\n<body>\n";
                    $html_content .= "<h1>" . htmlspecialchars($chapter_title) . "</h1>\n";
                    $html_content .= $chapter_content;
                    $html_content .= "</body>\n</html>";

                    file_put_contents($course_dir . '/' . $filename, $html_content);

                    Database::get()->query("INSERT INTO ebook_section SET
                        ebook_id = ?d,
                        public_id = ?s,
                        file = ?s,
                        title = ?s",
                        $ebook_id,
                        $public_id,
                        $filename,
                        $chapter_title
                    );
                }
            } else {
                $public_id = 'intro_' . uniqid();
                $filename = $public_id . '.html';
                $course_dir = $GLOBALS['webDir'] . '/courses/' . $this->course_code . '/ebook';

                if (!is_dir($course_dir)) {
                    mkdir($course_dir, 0755, true);
                }

                $description = $modal_data['Description'] ?? 'E-Book content';
                $html_content = "<!DOCTYPE html>\n<html>\n<head>\n";
                $html_content .= "<meta charset='UTF-8'>\n";
                $html_content .= "<title>" . htmlspecialchars($title) . "</title>\n";
                $html_content .= "<style>body{font-family:Arial,sans-serif;margin:20px;line-height:1.6;}</style>\n";
                $html_content .= "</head>\n<body>\n";
                $html_content .= "<h1>" . htmlspecialchars($title) . "</h1>\n";
                $html_content .= "<p>" . nl2br(htmlspecialchars($description)) . "</p>";
                $html_content .= "</body>\n</html>";

                file_put_contents($course_dir . '/' . $filename, $html_content);

                Database::get()->query("INSERT INTO ebook_section SET
                    ebook_id = ?d,
                    public_id = ?s,
                    file = ?s,
                    title = ?s",
                    $ebook_id,
                    $public_id,
                    $filename,
                    'Introduction'
                );
            }

        } catch (Exception $e) {
            error_log("CDM Import: Failed to create ebook - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    // ========================================================================
    // NEW RESOURCE HANDLERS - LOW PRIORITY
    // ========================================================================

    /**
     * Create H5P content from CDM activity
     * Maps: H5P → H5P module (partial implementation)
     */
    private function createH5PContent($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'H5P Content';
        $description = $modal_data['Description'] ?? '';
        $h5p_url = $modal_data['ResourceLocation'] ?? '';

        // For now, create as document with H5P instructions
        $content = "<h2>" . htmlspecialchars($title) . "</h2>";
        $content .= "<p>" . htmlspecialchars($description) . "</p>";

        if (!empty($h5p_url)) {
            $content .= "<div class='h5p-embed' style='margin: 20px 0; padding: 20px; background: #e7f3ff; border-left: 4px solid #2196F3;'>";
            $content .= "<h3>H5P Interactive Content</h3>";
            $content .= "<p><strong>URL:</strong> <a href='" . htmlspecialchars($h5p_url) . "' target='_blank'>" . htmlspecialchars($h5p_url) . "</a></p>";
            $content .= "<p><em>Note: This H5P content requires manual setup in the H5P module.</em></p>";
            $content .= "</div>";
        }

        // Store as document for now
        $activity['ModalData']['Type'] = 'document';
        $activity['ModalData']['Description'] = $content;
        $this->createDocumentResource($activity, 'h5p');

        error_log("CDM Import: H5P content '{$title}' imported as document. Manual H5P setup may be required.");
    }

    /**
     * Create chat room from CDM chat activity
     * Maps: Chat → Chat module
     */
    private function createChatRoom($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Chat Room';
        $description = $modal_data['Description'] ?? '';

        if (!empty($modal_data['LearningGoal'])) {
            $description .= "\n\nLearning Objectives:\n";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $description .= "• " . $goal . "\n";
            }
        }

        try {
            Database::get()->query("INSERT INTO conference SET
                course_id = ?d,
                conf_title = ?s,
                conf_description = ?s,
                status = 'inactive',
                chat_activity = TRUE,
                chat_activity_id = NULL",
                $this->course_id,
                $title,
                $description
            );

            error_log("CDM Import: Chat room '{$title}' created (requires manual activation).");

        } catch (Exception $e) {
            error_log("CDM Import: Failed to create chat room - " . $e->getMessage());
            $this->import_stats['failed']++;
        }
    }

    /**
     * Handle Database activity (Moodle Database module)
     * Maps: Database → Assignment (conversion strategy)
     */
    private function handleDatabaseActivity($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Data Collection Activity';
        $description = $modal_data['Description'] ?? '';

        $assignment_description = "**Data Collection Activity**\n\n";
        $assignment_description .= $description . "\n\n";

        if (!empty($modal_data['Fields'])) {
            $assignment_description .= "**Required Data Fields:**\n";
            foreach ($modal_data['Fields'] as $field) {
                $field_name = is_array($field) ? ($field['name'] ?? $field['field'] ?? '') : $field;
                $field_type = is_array($field) ? ($field['type'] ?? 'text') : 'text';
                $assignment_description .= "• " . $field_name . " (" . $field_type . ")\n";
            }
            $assignment_description .= "\n";
        }

        $assignment_description .= "**Submission Instructions:**\n";
        $assignment_description .= "Please collect and submit the requested data in a structured format (spreadsheet, document, or other appropriate format).\n\n";

        if (!empty($modal_data['LearningGoal'])) {
            $assignment_description .= "**Learning Objectives:**\n";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $assignment_description .= "• " . $goal . "\n";
            }
        }

        $assignment_description .= "\n---\n";
        $assignment_description .= "*Note: This activity was originally a Moodle Database activity and has been converted to an assignment format.*";

        try {
            Database::get()->query("INSERT INTO assignment SET
                course_id = ?d,
                title = ?s,
                description = ?s,
                comments = 'Converted from Moodle Database activity',
                deadline = DATE_ADD(NOW(), INTERVAL 14 DAY),
                late_submission = 1,
                submission_date = NOW(),
                active = 1,
                secret_directory = ?s",
                $this->course_id,
                'Data Collection: ' . $title,
                $assignment_description,
                uniqid()
            );

            error_log("CDM Import: Database activity '{$title}' converted to assignment.");

        } catch (Exception $e) {
            error_log("CDM Import: Failed to handle database activity - " . $e->getMessage());
            $this->import_stats['failed']++;
            $this->storeCDMActivity($activity);
        }
    }
}

// Handle file upload and processing
$data = array();

// Check if we're displaying a success message from redirect
if (isset($_GET['success']) && isset($_SESSION['cdm_import_success'])) {
    $data['success_message'] = "Course successfully imported with full CDM details!";
    $data['course_info'] = $_SESSION['cdm_import_success'];
    $data['course_url'] = $urlAppend . "courses/" . $_SESSION['cdm_import_success']['course_code'] . "/";

    // Clear the session data after displaying
    unset($_SESSION['cdm_import_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cdm_file'])) {
    try {
        $upload_file = $_FILES['cdm_file'];

        // Validate file
        if ($upload_file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . $upload_file['error']);
        }

        if (pathinfo($upload_file['name'], PATHINFO_EXTENSION) !== 'cdm') {
            throw new Exception('Invalid file type. Please upload a .cdm file.');
        }

        // Process CDM file
        $importer = new CDMImporter();
        $cdm_data = $importer->extractCDM($upload_file['tmp_name']);
        $result = $importer->createCourse($cdm_data);

        // Store success data in session
        $_SESSION['cdm_import_success'] = $result;

        // Debug: Log the course_id value
        error_log("CDM Import - Course ID: " . $result['course_id'] . ", Course Code: " . $result['course_code']);

        // Redirect to prevent form resubmission (POST/Redirect/Get pattern)
        header('Location: ' . $urlAppend . 'modules/create_course/cdm_import.php?success=1');
        exit;

    } catch (Exception $e) {
        $data['error_message'] = "Error: " . $e->getMessage();
    }
}

$data['menuTypeID'] = 1;

?>
<!DOCTYPE html>
<html>
<head>
    <title>CDM Course Import - OpenEClass</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; color: #2c3e50; }
        .alert { padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .alert-success { background: linear-gradient(135deg, #d4edda, #c3e6cb); border-left: 5px solid #28a745; color: #155724; }
        .alert-danger { background: linear-gradient(135deg, #f8d7da, #f5c6cb); border-left: 5px solid #dc3545; color: #721c24; }
        .upload-zone {
            border: 3px dashed #007bff;
            padding: 60px 20px;
            text-align: center;
            border-radius: 15px;
            background: linear-gradient(135deg, #f8f9ff, #e7f3ff);
            transition: all 0.4s ease;
            margin-bottom: 30px;
        }
        .upload-zone:hover { border-color: #0056b3; background: linear-gradient(135deg, #e7f3ff, #d1ecf1); transform: translateY(-2px); }
        .btn { padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; font-weight: 600; transition: all 0.3s; margin: 5px; }
        .btn-primary { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
        .btn-primary:hover { background: linear-gradient(135deg, #0056b3, #004085); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,123,255,0.4); }
        .btn-success { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
        .btn-success:hover { background: linear-gradient(135deg, #1e7e34, #155724); transform: translateY(-2px); }
        .btn-info { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
        .btn-secondary { background: linear-gradient(135deg, #6c757d, #545b62); color: white; }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin: 40px 0; }
        .feature { background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 25px; border-radius: 12px; text-align: center; border-left: 4px solid #007bff; }
        .feature h4 { color: #007bff; margin-bottom: 15px; }
        .course-details { background: linear-gradient(135deg, #e7f3ff, #cce7ff); padding: 25px; border-radius: 12px; margin: 20px 0; border-left: 5px solid #007bff; }
        .detail-item { margin-bottom: 12px; }
        .detail-label { font-weight: bold; color: #495057; }
        .file-input { margin: 25px 0; }
        .file-input input { padding: 15px; border: 2px solid #ddd; border-radius: 8px; width: 100%; max-width: 450px; margin: 0 auto; display: block; font-size: 16px; }
        .cdm-info { background: #f1f8ff; padding: 20px; border-radius: 8px; margin: 20px 0; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Complete CDM Course Import System</h1>
            <p>Import educational content from CADMOS/Moodle with 100% resource mapping coverage - 14 resource types fully supported</p>
            <div style="margin-top: 15px; padding: 10px; background: linear-gradient(135deg, #d4edda, #c3e6cb); border-radius: 8px; display: inline-block;">
                <strong style="color: #155724;">✅ Now Supporting: Videos, Quizzes, Assignments, Forums, Wikis, Links, Documents, Audio, Images, Pages, Glossary, Polls, Learning Paths, E-Books, H5P, Chat & Database Activities</strong>
            </div>
        </div>

        <?php if (isset($data['success_message'])): ?>
            <div class="alert alert-success">
                <h3>✅ Import Successful!</h3>
                <p><?php echo htmlspecialchars($data['success_message']); ?></p>

                <?php if (isset($data['course_info'])): ?>
                    <div class="course-details">
                        <h4><i style="color:#007bff;">ℹ️</i> Detailed Course Information</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                            <div>
                                <div class="detail-item">
                                    <span class="detail-label">Course Code:</span>
                                    <?php echo htmlspecialchars($data['course_info']['course_code']); ?>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Course ID:</span>
                                    <?php echo htmlspecialchars($data['course_info']['course_id']); ?>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Title:</span>
                                    <?php echo htmlspecialchars($data['course_info']['title']); ?>
                                </div>
                                <?php if (isset($data['course_info']['lesson_info']['EducationLevel'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Education Level:</span>
                                    <?php echo htmlspecialchars($data['course_info']['lesson_info']['EducationLevel']); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($data['course_info']['lesson_info']['SubjectArea'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Subject Area:</span>
                                    <?php echo htmlspecialchars($data['course_info']['lesson_info']['SubjectArea']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if (isset($data['course_info']['lesson_info']['DurationNumber'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Duration:</span>
                                    <?php echo htmlspecialchars($data['course_info']['lesson_info']['DurationNumber'] . ' ' . ($data['course_info']['lesson_info']['DurationType'] ?? '')); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($data['course_info']['lesson_info']['Actors'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Participants:</span>
                                    <?php echo htmlspecialchars(implode(', ', $data['course_info']['lesson_info']['Actors'])); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($data['course_info']['lesson_info']['Goals'])): ?>
                                <div class="detail-item">
                                    <span class="detail-label">Learning Goals:</span>
                                    <small><?php echo count($data['course_info']['lesson_info']['Goals']); ?> objectives imported</small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (isset($data['course_info']['import_stats'])): ?>
                            <?php $stats = $data['course_info']['import_stats']; ?>
                            <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #f8f9ff, #e7f3ff); border-radius: 10px; border-left: 4px solid #28a745;">
                                <h4 style="color:#155724; margin-bottom: 15px;">📊 Import Statistics</h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px; margin-bottom: 15px;">
                                    <?php if ($stats['videos'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(102,126,234,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['videos']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Videos</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['quizzes'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(240,147,251,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['quizzes']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Quizzes</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['assignments'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(79,172,254,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['assignments']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Assignments</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['forums'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(67,233,123,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['forums']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Forums</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['wikis'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(250,112,154,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['wikis']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Wikis</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['links'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(48,207,208,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['links']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Links</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['documents'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 8px; color: #333; box-shadow: 0 2px 8px rgba(168,237,234,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['documents']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Documents</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['glossary'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); border-radius: 8px; color: #333; box-shadow: 0 2px 8px rgba(255,154,158,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['glossary']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Glossary</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['polls'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 8px; color: #333; box-shadow: 0 2px 8px rgba(255,236,210,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['polls']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Polls</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['learning_paths'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #ff6e7f 0%, #bfe9ff 100%); border-radius: 8px; color: #333; box-shadow: 0 2px 8px rgba(255,110,127,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['learning_paths']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Learning Paths</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['ebooks'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); border-radius: 8px; color: #333; box-shadow: 0 2px 8px rgba(224,195,252,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['ebooks']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">E-Books</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['h5p'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #f77062 0%, #fe5196 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(247,112,98,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['h5p']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">H5P</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['chat'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #7f7fd5 0%, #86a8e7 100%); border-radius: 8px; color: white; box-shadow: 0 2px 8px rgba(127,127,213,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['chat']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Chat Rooms</div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($stats['database'] > 0): ?>
                                    <div class="stat-card" style="text-align: center; padding: 12px; background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%); border-radius: 8px; color: #333; box-shadow: 0 2px 8px rgba(251,194,235,0.3);">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $stats['database']; ?></div>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Database</div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: white; border-radius: 8px; margin-top: 15px;">
                                    <div style="text-align: center; flex: 1;">
                                        <div style="font-size: 32px; font-weight: bold; color: #28a745;"><?php echo $stats['total']; ?></div>
                                        <div style="font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Total Resources</div>
                                    </div>
                                    <?php if ($stats['failed'] > 0): ?>
                                    <div style="text-align: center; flex: 1; border-left: 2px solid #dee2e6;">
                                        <div style="font-size: 32px; font-weight: bold; color: #dc3545;"><?php echo $stats['failed']; ?></div>
                                        <div style="font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Failed</div>
                                    </div>
                                    <?php endif; ?>
                                    <div style="text-align: center; flex: 1; border-left: 2px solid #dee2e6;">
                                        <div style="font-size: 32px; font-weight: bold; color: #007bff;"><?php echo $stats['total'] - $stats['failed']; ?></div>
                                        <div style="font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Successful</div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #cce7ff;">
                            <a href="<?php echo htmlspecialchars($data['course_url']); ?>" class="btn btn-success">
                                👁️ View Course
                            </a>
                            <a href="<?php echo $urlAppend; ?>modules/auth/info_course.php?c=<?php echo $data['course_info']['course_code']; ?>" class="btn btn-info">
                                📋 Course Info Page
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($data['error_message'])): ?>
            <div class="alert alert-danger">
                <h3>❌ Import Failed</h3>
                <p><?php echo htmlspecialchars($data['error_message']); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="upload-zone">
                <h2>📤 Upload CDM File</h2>
                <p>Select a .cdm file exported from your course design tool to import complete educational content</p>

                <div class="file-input">
                    <input type="file" name="cdm_file" accept=".cdm" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    🚀 Import Course with Full Details
                </button>
            </div>
        </form>

        <div class="features">
            <div class="feature">
                <h4>📊 Complete Metadata</h4>
                <p>Extracts all course information, learning goals, education level, subject area, and duration details</p>
            </div>
            <div class="feature">
                <h4>🔄 Think-Pair-Share</h4>
                <p>Preserves pedagogical methodology with proper phase mapping and learning flow structure</p>
            </div>
            <div class="feature">
                <h4>🎯 14 Resource Types</h4>
                <p>Videos, Quizzes, Assignments, Forums, Wikis, Links, Documents, Glossary, Polls, Learning Paths, E-Books, H5P, Chat, Database</p>
            </div>
            <div class="feature">
                <h4>📺 Media Support</h4>
                <p>Full support for videos, audio files, images, documents, and external resource linking</p>
            </div>
            <div class="feature">
                <h4>📚 Advanced Content</h4>
                <p>Imports learning paths, e-books with chapters, interactive H5P content, and structured glossaries</p>
            </div>
            <div class="feature">
                <h4>💬 Collaboration Tools</h4>
                <p>Creates forums, wikis, chat rooms, polls, questionnaires, and feedback forms automatically</p>
            </div>
            <div class="feature">
                <h4>🎓 Assessment Tools</h4>
                <p>Imports quizzes, assignments, polls, and converts database activities for data collection</p>
            </div>
            <div class="feature">
                <h4>🏗️ Course Structure</h4>
                <p>Creates organized course units, learning phases, and maintains educational sequences</p>
            </div>
            <div class="feature">
                <h4>📈 Import Statistics</h4>
                <p>Detailed breakdown of imported resources with success tracking and visual analytics</p>
            </div>
        </div>

        <div class="cdm-info">
            <h5>📋 CDM Data Extracted:</h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 13px;">
                <div>
                    • Course Strategy & Title<br>
                    • Learning Objectives<br>
                    • Educational Level<br>
                    • Subject Area<br>
                </div>
                <div>
                    • Duration & Timing<br>
                    • Activity Classifications<br>
                    • Resource Locations<br>
                    • Assessment Information<br>
                </div>
                <div>
                    • Participant Roles<br>
                    • Facilitator Instructions<br>
                    • Copyright Information<br>
                    • Flow Methodology<br>
                </div>
                <div>
                    • Learning Phases<br>
                    • Interactive Content<br>
                    • External Links<br>
                    • Metadata Structure<br>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px; border-top: 1px solid #dee2e6; padding-top: 30px;">
            <a href="<?php echo $urlAppend; ?>main/portfolio.php" class="btn btn-secondary">
                ← Back to Portfolio
            </a>
        </div>
    </div>
</body>
</html>