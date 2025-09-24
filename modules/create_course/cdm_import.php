
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

    public function __construct() {
        $this->upload_path = $GLOBALS['webDir'] . '/courses/temp_uploads/';

        // Create upload directory if it doesn't exist
        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0755, true);
        }
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
            'lesson_info' => $lesson_info
        ];
    }

    /**
     * Activate essential course modules
     */
    private function activateCourseModules() {
        // Essential modules for CDM courses
        $essential_modules = [
            1 => 1,   // Announcements - Active
            2 => 1,   // Agenda - Active
            3 => 1,   // Documents - Active (needed for CDM materials)
            4 => 1,   // Video/Multimedia - Active (needed for CDM videos)
            5 => 1,   // Exercises - Active (needed for CDM quizzes)
            6 => 0,   // Assignments - Inactive
            7 => 1,   // Glossary - Active
            8 => 1,   // Learning Path - Active
            9 => 1,   // Links - Active
            10 => 1,  // Course Units - Active (needed for Think-Pair-Share structure)
            11 => 0,  // E-Book - Inactive
            12 => 0,  // Questionnaire - Inactive
            13 => 0,  // Wiki - Inactive
            14 => 0,  // Wall/Social - Inactive
            15 => 0,  // Chat - Inactive
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
    private function importActivities($activities, $parent_id = null) {
        foreach ($activities as $activity) {
            $this->createCourseModule($activity);

            // Import children activities
            if (!empty($activity['children'])) {
                $this->importActivities($activity['children'], $activity['id']);
            }
        }
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
                case 'video':
                    $this->createVideoLink($activity);
                    break;
                case 'quiz':
                    $this->createExercise($activity);
                    break;
                default:
                    $this->createDocument($activity);
                    break;
            }
        } else {
            // Create comprehensive document for activity types
            $this->createDocument($activity);
        }
    }

    /**
     * Create enhanced document from activity with all available details
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

        // Create the document
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title) . '.html';
        $file_path = '/' . $filename;
        $course_dir = $GLOBALS['webDir'] . '/courses/' . $this->course_code . '/document';

        // Create document directory if it doesn't exist
        if (!is_dir($course_dir)) {
            mkdir($course_dir, 0755, true);
        }

        // Write content to file
        file_put_contents($course_dir . '/' . $filename, $content);

        $file_creator = $_SESSION['givenname'] . ' ' . $_SESSION['surname'];
        $current_date = date('Y-m-d G:i:s');

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
            date_modified = ?t",
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
}

// Handle file upload and processing
$data = array();
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

        $data['success_message'] = "Course successfully imported with full CDM details!";
        $data['course_info'] = $result;
        $data['course_url'] = $urlAppend . "/courses/" . $result['course_code'] . "/";

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
            <h1>🎓 Enhanced CDM Course Import</h1>
            <p>Import educational content from Course Design Model (CDM) files with complete metadata extraction</p>
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
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #cce7ff;">
                            <a href="<?php echo htmlspecialchars($data['course_url']); ?>" class="btn btn-success">
                                👁️ View Course
                            </a>
                            <a href="<?php echo $urlAppend; ?>/modules/auth/info_course.php?c=<?php echo $data['course_info']['course_id']; ?>" class="btn btn-info">
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
                <h4>🎯 Activity Classification</h4>
                <p>Imports activities with type classification, target audience, facilitator roles, and learning objectives</p>
            </div>
            <div class="feature">
                <h4>📺 Resource Integration</h4>
                <p>Seamlessly imports videos, quizzes, documents, and external resources with proper linking</p>
            </div>
            <div class="feature">
                <h4>👥 Role Mapping</h4>
                <p>Maps course participants, learner groups, staff roles, and facilitator instructions accurately</p>
            </div>
            <div class="feature">
                <h4>🏗️ Course Structure</h4>
                <p>Creates organized course units, learning phases, and maintains educational sequences</p>
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