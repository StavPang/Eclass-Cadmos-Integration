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

// CDM to OpenEClass mapping class
class CDMImporter {
    private $course_id;
    private $course_code;
    private $upload_path;

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

        $lesson_info = $cdm_data['data']['LessonInfo'];

        // Create course using OpenEClass function
        $course_title = $lesson_info['StrategyName'] ?? 'Imported Course';
        $course_description = $lesson_info['Description'] ?? '';

        // Create course goals text
        $course_goals = '';
        if (!empty($lesson_info['Goals'])) {
            $course_goals = implode("\n• ", $lesson_info['Goals']);
            $course_goals = "• " . $course_goals;
        }

        $full_description = $course_description;
        if ($course_goals) {
            $full_description .= "\n\nLearning Goals:\n" . $course_goals;
        }

        $prof_name = $_SESSION['givenname'] . ' ' . $_SESSION['surname'];

        // Use OpenEClass create_course function
        $departments = [1]; // Default department
        $result = create_course('', 'el', $course_title, $full_description, $departments, 2, $prof_name);

        if (!$result) {
            throw new Exception('Failed to create course');
        }

        list($this->course_code, $this->course_id) = $result;

        // Import course content
        $this->importCourseContent($cdm_data['data']);

        return [
            'course_id' => $this->course_id,
            'course_code' => $this->course_code,
            'title' => $course_title
        ];
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
            // Create document for other activity types
            $this->createDocument($activity);
        }
    }

    /**
     * Create document from activity
     */
    private function createDocument($activity) {
        $modal_data = $activity['ModalData'] ?? [];
        $title = $modal_data['Title'] ?? 'Activity';
        $description = $modal_data['Description'] ?? '';
        $content = nl2br(htmlspecialchars($description));

        // Add learning goals if available
        if (!empty($modal_data['LearningGoal'])) {
            $content .= "<h3>Learning Goals:</h3><ul>";
            foreach ($modal_data['LearningGoal'] as $goal) {
                $content .= "<li>" . htmlspecialchars($goal) . "</li>";
            }
            $content .= "</ul>";
        }

        // Add resource link if available
        if (!empty($modal_data['ResourceLocation'])) {
            $content .= "<p><strong>Resource:</strong> <a href='" . htmlspecialchars($modal_data['ResourceLocation']) . "' target='_blank'>" . htmlspecialchars($modal_data['ResourceLocation']) . "</a></p>";
        }

        // Add activity type and actor info
        if (!empty($modal_data['Type'])) {
            $content .= "<p><strong>Activity Type:</strong> " . htmlspecialchars($modal_data['Type']) . "</p>";
        }
        if (!empty($modal_data['Actor'])) {
            $content .= "<p><strong>Target Audience:</strong> " . htmlspecialchars($modal_data['Actor']) . "</p>";
        }

        // Create the document content in the course directory
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
        $file_date = date('Y-m-d G:i:s');

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
            date_modified = ?t,
            format = 'html'",
            $this->course_id,
            $file_path,
            $filename,
            $description,
            $title,
            $file_creator,
            $file_date
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
                $description = 'Learning Phase: ' . $title;

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

        $data['success_message'] = "Course successfully imported!";
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
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; color: #2c3e50; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .upload-zone {
            border: 3px dashed #ddd;
            padding: 50px 20px;
            text-align: center;
            border-radius: 10px;
            background: #fafafa;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        .upload-zone:hover { border-color: #007bff; background: #f8f9ff; }
        .btn { padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; transition: all 0.2s; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-primary:hover { background-color: #0056b3; transform: translateY(-1px); }
        .btn-success { background-color: #28a745; color: white; }
        .btn-success:hover { background-color: #1e7e34; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0; }
        .feature { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; }
        .feature h4 { color: #007bff; margin-bottom: 10px; }
        .course-info { background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .file-input { margin: 20px 0; }
        .file-input input { padding: 10px; border: 2px solid #ddd; border-radius: 5px; width: 100%; max-width: 400px; margin: 0 auto; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 CDM Course Import</h1>
            <p>Import educational content from CDM files into OpenEClass</p>
        </div>

        <?php if (isset($data['success_message'])): ?>
            <div class="alert alert-success">
                <h4>✅ Import Successful!</h4>
                <p><?php echo htmlspecialchars($data['success_message']); ?></p>

                <?php if (isset($data['course_info'])): ?>
                    <div class="course-info">
                        <h4>Course Details:</h4>
                        <p><strong>Course Code:</strong> <?php echo htmlspecialchars($data['course_info']['course_code']); ?></p>
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($data['course_info']['title']); ?></p>
                        <a href="<?php echo htmlspecialchars($data['course_url']); ?>" class="btn btn-success">
                            👁️ View Course
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($data['error_message'])): ?>
            <div class="alert alert-danger">
                <h4>❌ Import Failed</h4>
                <p><?php echo htmlspecialchars($data['error_message']); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="upload-zone">
                <h3>📤 Upload CDM File</h3>
                <p>Select a .cdm file exported from your course design tool</p>

                <div class="file-input">
                    <input type="file" name="cdm_file" accept=".cdm" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    🚀 Import Course
                </button>
            </div>
        </form>

        <div class="features">
            <div class="feature">
                <h4>🎯 Smart Mapping</h4>
                <p>Automatically maps CDM activities to OpenEClass modules</p>
            </div>
            <div class="feature">
                <h4>📺 Video Integration</h4>
                <p>Imports YouTube and video links seamlessly</p>
            </div>
            <div class="feature">
                <h4>📚 Content Creation</h4>
                <p>Creates documents with learning goals and resources</p>
            </div>
            <div class="feature">
                <h4>🔄 Learning Flow</h4>
                <p>Preserves pedagogical structure and sequences</p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo $urlAppend; ?>/modules/create_course/" class="btn btn-secondary">
                ← Back to Course Creation
            </a>
        </div>
    </div>
</body>
</html>