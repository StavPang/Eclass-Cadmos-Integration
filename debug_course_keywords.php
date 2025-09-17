<?php
require_once 'include/init.php';

echo "<h2>Debug Course Keywords Field</h2>";

// Get course with code 112
$course_code = '112';

$course = Database::get()->querySingle("SELECT id, code, title, keywords FROM course WHERE code = ?s", $course_code);

if ($course) {
    echo "<h3>Course Information:</h3>";
    echo "<p><strong>ID:</strong> " . $course->id . "</p>";
    echo "<p><strong>Code:</strong> " . $course->code . "</p>";
    echo "<p><strong>Title:</strong> " . htmlspecialchars($course->title) . "</p>";

    echo "<h3>Keywords Field Content:</h3>";
    if (empty($course->keywords)) {
        echo "<p><strong>❌ Keywords field is EMPTY</strong></p>";
        echo "<p>This means no CDM data was stored in the database.</p>";
    } else {
        echo "<p><strong>✅ Keywords field has content:</strong></p>";
        echo "<pre style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px; overflow-x: auto;'>";
        echo htmlspecialchars($course->keywords);
        echo "</pre>";

        // Try to decode as JSON
        $cdm_data = json_decode($course->keywords, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "<h3>Decoded JSON Structure:</h3>";
            echo "<pre style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px; overflow-x: auto;'>";
            print_r($cdm_data);
            echo "</pre>";

            // Check what fields are available
            echo "<h3>Available CDM Fields:</h3>";
            echo "<ul>";
            foreach ($cdm_data as $key => $value) {
                echo "<li><strong>$key:</strong> " . (is_array($value) ? '(Array)' : htmlspecialchars($value)) . "</li>";
            }
            echo "</ul>";

            // Check specifically for the field that info_course.php is looking for
            if (isset($cdm_data['StrategyName'])) {
                echo "<p>✅ <strong>StrategyName field found:</strong> " . htmlspecialchars($cdm_data['StrategyName']) . "</p>";
            } else {
                echo "<p>❌ <strong>StrategyName field NOT found</strong></p>";
                echo "<p>Available fields that could indicate CDM data:</p>";
                $cdm_indicators = ['CourseName', 'Domain', 'TargetGroup', 'Simple_activity_types', 'Resource_types'];
                foreach ($cdm_indicators as $indicator) {
                    if (isset($cdm_data[$indicator])) {
                        echo "<p>✅ <strong>$indicator:</strong> Found</p>";
                    }
                }
            }
        } else {
            echo "<p>❌ <strong>Invalid JSON in keywords field</strong></p>";
            echo "<p>JSON Error: " . json_last_error_msg() . "</p>";
        }
    }
} else {
    echo "<p>❌ Course with ID $course_id not found</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
pre { font-size: 12px; }
</style>