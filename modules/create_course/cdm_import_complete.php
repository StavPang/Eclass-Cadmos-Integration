<?php
/*
 *  ========================================================================
 *  * Open eClass - CDM Complete Import Module
 *  * Fixed version that preserves ALL CDM metadata
 *  * ========================================================================
 */

$require_login = TRUE;
$require_teacher = TRUE;

require_once '../../include/baseTheme.php';
require_once 'include/lib/fileUploadLib.inc.php';
require_once 'include/lib/course.class.php';
require_once 'functions.php';

$toolName = "CDM Complete Course Import";
$pageName = "Import Course from CDM File (Complete Version)";

// Enhanced CDM Importer that preserves ALL metadata
class CompleteCDMImporter {
    private $course_id;
    private $course_code;
    private $upload_path;
    private $cdm_data;

    public function __construct() {
        $this->upload_path = $GLOBALS['webDir'] . '/courses/temp_uploads/';

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

        $zip = new ZipArchive;
        if ($zip->open($file_path) === TRUE) {
            $zip->extractTo($temp_dir);
            $zip->close();

            $json_file = $temp_dir . 'source.json';
            if (file_exists($json_file)) {
                $json_content = file_get_contents($json_file);
                $data = json_decode($json_content, true);

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
     * Create course from CDM data - COMPLETE VERSION
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

        // Create enhanced description with all CDM metadata
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

        // Get first available department that allows courses
        $department = Database::get()->querySingle("SELECT id FROM hierarchy WHERE allow_course = 1 ORDER BY id LIMIT 1");
        if (!$department) {
            throw new Exception('No departments available for course creation');
        }
        $departments = [$department->id];

        // Use OpenEClass create_course function
        $result = create_course('', 'el', $course_title, $full_description, $departments, 2, $prof_name);

        if (!$result) {
            throw new Exception('Failed to create course');
        }

        list($this->course_code, $this->course_id) = $result;

        // *** FIXED: Store COMPLETE CDM metadata ***
        $this->storeCompleteCDMMetadata($cdm_data);

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
     * Store COMPLETE CDM metadata - FIXED VERSION
     */
    private function storeCompleteCDMMetadata($cdm_data) {
        // Merge LessonInfo with LessonInfoExtras for complete metadata
        $complete_metadata = $cdm_data['data']['LessonInfo'];

        // Add the missing fields that the template expects
        if (isset($cdm_data['data']['LessonInfoExtras'])) {
            $complete_metadata = array_merge($complete_metadata, $cdm_data['data']['LessonInfoExtras']);
        }

        // Store the COMPLETE metadata (not just LessonInfo)
        Database::get()->query("UPDATE course SET
            keywords = ?s
            WHERE id = ?d",
            json_encode($complete_metadata),  // *** FIXED: Complete metadata, not just LessonInfo ***
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

        if (!is_dir($course_dir)) {
            mkdir($course_dir, 0755, true);
        }

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
     * Import flow units as course sections
     */
    private function importFlowUnits($flow_sub) {
        $order = 1;
        foreach ($flow_sub as $phase) {
            if (isset($phase['type']) && $phase['type'] === 'flowPhase') {
                $title = $phase['text'] ?? 'Phase ' . $order;

                $description = "<h3>Learning Phase: " . htmlspecialchars($title) . "</h3>";
                $description .= "<p>This phase is part of the Think-Pair-Share learning methodology.</p>";

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

        if ($upload_file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . $upload_file['error']);
        }

        if (pathinfo($upload_file['name'], PATHINFO_EXTENSION) !== 'cdm') {
            throw new Exception('Invalid file type. Please upload a .cdm file.');
        }

        // Process CDM file with COMPLETE metadata preservation
        $importer = new CompleteCDMImporter();
        $cdm_data = $importer->extractCDM($upload_file['tmp_name']);
        $result = $importer->createCourse($cdm_data);

        $data['success_message'] = "Course successfully imported with COMPLETE CDM metadata!";
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
    <title>CDM Complete Course Import - OpenEClass</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; color: #2c3e50; }
        .alert { padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .alert-danger { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .btn { padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin: 5px; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .upload-zone { border: 2px dashed #007bff; padding: 40px; text-align: center; border-radius: 8px; background: #f8f9ff; }
        .course-details { background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 CDM Complete Course Import</h1>
            <p>Import CDM files with <strong>ALL</strong> metadata preserved (Fixed Version)</p>
            <div style="background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;">
                <strong>⚠️ FIXED:</strong> This version preserves Activity Types, Resource Types, and Copyright info that were missing in the original importer!
            </div>
        </div>

        <?php if (isset($data['success_message'])): ?>
            <div class="alert alert-success">
                <h3>✅ Import Successful!</h3>
                <p><?php echo htmlspecialchars($data['success_message']); ?></p>

                <?php if (isset($data['course_info'])): ?>
                    <div class="course-details">
                        <h4>Course Information</h4>
                        <p><strong>Course Code:</strong> <?php echo htmlspecialchars($data['course_info']['course_code']); ?></p>
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($data['course_info']['title']); ?></p>

                        <a href="<?php echo htmlspecialchars($data['course_url']); ?>" class="btn btn-success">
                            👁️ View Course
                        </a>
                        <a href="<?php echo $urlAppend; ?>/modules/auth/info_course.php?c=<?php echo $data['course_info']['course_id']; ?>" class="btn btn-primary">
                            📋 View Complete Course Info (Now with ALL CDM data!)
                        </a>
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
                <p>This <strong>enhanced version</strong> will preserve ALL CDM metadata including:</p>
                <ul style="text-align: left; display: inline-block;">
                    <li>✅ Activity Types (Creating, Evaluating, etc.)</li>
                    <li>✅ Resource Types (Video, Quiz, Hypertext, etc.)</li>
                    <li>✅ Copyright Information (free, proprietary)</li>
                    <li>✅ All original LessonInfo data</li>
                </ul>

                <div style="margin: 20px 0;">
                    <input type="file" name="cdm_file" accept=".cdm" required style="padding: 10px; width: 300px;">
                </div>

                <button type="submit" class="btn btn-primary">
                    🚀 Import with Complete Metadata
                </button>
            </div>
        </form>

        <div style="text-align: center; margin-top: 30px;">
            <a href="<?php echo $urlAppend; ?>/modules/create_course/" class="btn" style="background: #6c757d; color: white;">
                ← Back to Course Creation
            </a>
        </div>
    </div>
</body>
</html>