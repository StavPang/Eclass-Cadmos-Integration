<?php
require_once 'include/init.php';

echo "<h2>Available Courses</h2>";

$courses = Database::get()->queryArray("SELECT id, code, title, keywords FROM course ORDER BY id DESC LIMIT 10");

if ($courses) {
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f8f9fa;'>";
    echo "<th>ID</th><th>Code</th><th>Title</th><th>Has Keywords</th><th>Keywords Preview</th>";
    echo "</tr>";

    foreach ($courses as $course) {
        echo "<tr>";
        echo "<td>" . $course->id . "</td>";
        echo "<td>" . $course->code . "</td>";
        echo "<td>" . htmlspecialchars($course->title) . "</td>";
        echo "<td>" . (empty($course->keywords) ? '❌ No' : '✅ Yes') . "</td>";
        echo "<td>" . (empty($course->keywords) ? '-' : substr(htmlspecialchars($course->keywords), 0, 100) . '...') . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Find courses with CDM data
    echo "<h3>Courses with Potential CDM Data:</h3>";
    foreach ($courses as $course) {
        if (!empty($course->keywords)) {
            $cdm_data = json_decode($course->keywords, true);
            if (json_last_error() === JSON_ERROR_NONE && (
                isset($cdm_data['CourseName']) ||
                isset($cdm_data['StrategyName']) ||
                isset($cdm_data['Domain']) ||
                isset($cdm_data['Simple_activity_types'])
            )) {
                echo "<p>✅ <strong>Course {$course->id} ({$course->code}):</strong> " . htmlspecialchars($course->title) . "</p>";
                echo "<p><a href='modules/auth/info_course.php?c={$course->code}' target='_blank'>View Course Info Page</a></p>";
            }
        }
    }
} else {
    echo "<p>No courses found</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin-top: 20px; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; }
</style>