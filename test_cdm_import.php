<?php
require_once 'include/baseTheme.php';

// Must be logged in as teacher
if (!$_SESSION['uid'] || $_SESSION['status'] != USER_TEACHER) {
    redirect_to_home_page();
}

echo "<h2>CDM Import Test</h2>";

if ($_FILES['cdmFile']) {
    echo "<h3>Testing CDM Import with Fixed Permissions</h3>";

    $cdm_file = $_FILES['cdmFile']['tmp_name'];
    echo "<p>Uploaded file: " . $_FILES['cdmFile']['name'] . "</p>";
    echo "<p>File size: " . $_FILES['cdmFile']['size'] . " bytes</p>";

    try {
        // Include the functions we need
        require_once 'modules/create_course/functions.php';

        // Test CDM extraction using system temp directory
        $extract_dir = sys_get_temp_dir() . '/cdm_extract_' . uniqid();

        if (!mkdir($extract_dir, 0755, true)) {
            throw new Exception("Failed to create extraction directory");
        }

        echo "<p>✅ Created extraction directory: $extract_dir</p>";

        // Extract CDM file (it's a ZIP)
        $zip = new ZipArchive();
        if ($zip->open($cdm_file) !== TRUE) {
            throw new Exception("Failed to open CDM file as ZIP");
        }

        $zip->extractTo($extract_dir);
        $zip->close();

        echo "<p>✅ CDM file extracted successfully</p>";

        // List extracted files
        $files = glob($extract_dir . '/*');
        echo "<p>Extracted files:</p><ul>";
        foreach ($files as $file) {
            echo "<li>" . basename($file) . "</li>";
        }
        echo "</ul>";

        // Check for source.json
        $source_json = $extract_dir . '/source.json';
        if (file_exists($source_json)) {
            echo "<p>✅ Found source.json file</p>";

            $cdm_data = json_decode(file_get_contents($source_json), true);
            if ($cdm_data && isset($cdm_data['data']['LessonInfo'])) {
                echo "<p>✅ Successfully parsed CDM JSON data</p>";

                // Show key information
                $lesson_info = $cdm_data['data']['LessonInfo'];
                echo "<h4>CDM Course Information:</h4>";
                echo "<ul>";
                echo "<li><strong>Title:</strong> " . htmlspecialchars($lesson_info['CourseName'] ?? 'N/A') . "</li>";
                echo "<li><strong>Strategy:</strong> " . htmlspecialchars($lesson_info['StrategyName'] ?? 'N/A') . "</li>";
                echo "<li><strong>Domain:</strong> " . htmlspecialchars($lesson_info['Domain'] ?? 'N/A') . "</li>";
                echo "<li><strong>Target Group:</strong> " . htmlspecialchars($lesson_info['TargetGroup'] ?? 'N/A') . "</li>";
                echo "</ul>";

                // Test course creation
                echo "<h4>Testing Course Creation:</h4>";

                $departments = [2]; // Use the department we found in debug
                $course_title = $lesson_info['CourseName'] ?? 'CDM Imported Course - ' . date('Y-m-d H:i:s');
                $course_description = $lesson_info['Domain'] ?? 'CDM Imported Course';
                $prof_name = $_SESSION['givenname'] . ' ' . $_SESSION['surname'];

                $result = create_course('', 'el', $course_title, $course_description, $departments, 2, $prof_name);

                if ($result) {
                    list($course_code, $course_id) = $result;
                    echo "<p>✅ Course created successfully!</p>";
                    echo "<p>Course ID: $course_id, Course Code: $course_code</p>";

                    // Store complete CDM metadata
                    $complete_metadata = $lesson_info;
                    if (isset($cdm_data['data']['LessonInfoExtras'])) {
                        $complete_metadata = array_merge($complete_metadata, $cdm_data['data']['LessonInfoExtras']);
                        echo "<p>✅ Merged LessonInfoExtras data</p>";
                    }

                    Database::get()->query("UPDATE course SET keywords = ?s WHERE id = ?d",
                        json_encode($complete_metadata), $course_id);

                    echo "<p>✅ CDM metadata stored in course keywords field</p>";
                    echo "<p><strong>🎉 CDM Import completed successfully!</strong></p>";
                    echo "<p><a href='modules/auth/info_course.php?c=$course_code' target='_blank'>View imported course info</a></p>";

                } else {
                    echo "<p>❌ Course creation failed</p>";
                }

            } else {
                echo "<p>❌ Invalid CDM data format</p>";
            }

        } else {
            echo "<p>❌ source.json not found in CDM file</p>";
        }

        // Clean up
        exec("rm -rf $extract_dir");
        echo "<p>🧹 Cleaned up extraction directory</p>";

    } catch (Exception $e) {
        echo "<p>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

} else {
    echo "<p>Upload a CDM file to test the import functionality:</p>";
}
?>

<form method="post" enctype="multipart/form-data">
    <div style="margin-bottom: 10px;">
        <label for="cdmFile">Select CDM File:</label><br>
        <input type="file" name="cdmFile" id="cdmFile" accept=".cdm" required>
    </div>
    <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px;">
        📁 Import CDM File
    </button>
</form>

<p><strong>This test will:</strong></p>
<ul>
    <li>Extract the CDM file contents</li>
    <li>Parse the source.json data</li>
    <li>Create a course with CDM information</li>
    <li>Store complete metadata including LessonInfoExtras</li>
    <li>Provide a link to view the course info page</li>
</ul>