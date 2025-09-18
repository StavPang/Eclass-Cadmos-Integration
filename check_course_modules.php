<?php
require_once 'include/init.php';

echo "<h2>Check Course Module Status</h2>";

// Check course 112 (CDM imported)
$course_code_cdm = '112';
$course_cdm = Database::get()->querySingle("SELECT id, code, title FROM course WHERE code = ?s", $course_code_cdm);

if ($course_cdm) {
    echo "<h3>CDM Course: {$course_cdm->title} (Code: {$course_cdm->code})</h3>";

    // Check course modules table
    echo "<h4>Module Activation Status:</h4>";
    $modules_cdm = Database::get()->queryArray("SELECT module_id, visible FROM course_module WHERE course_id = ?d", $course_cdm->id);

    if ($modules_cdm) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Module ID</th><th>Status</th><th>Module Name</th></tr>";

        // Get module names
        $module_names = [
            1 => 'Announcements', 2 => 'Agenda', 3 => 'Documents', 4 => 'Video/Multimedia',
            5 => 'Exercises', 6 => 'Assignments', 7 => 'Glossary', 8 => 'Learning Path',
            9 => 'Links', 10 => 'Course Units', 11 => 'E-Book', 12 => 'Questionnaire',
            13 => 'Wiki', 14 => 'Wall/Social', 15 => 'Chat', 16 => 'Forum', 17 => 'Groups',
            18 => 'Dropbox', 19 => 'User Progress', 20 => 'Usage Statistics'
        ];

        foreach ($modules_cdm as $mod) {
            $status = $mod->visible == 1 ? '✅ Active' : '❌ Inactive';
            $name = $module_names[$mod->module_id] ?? 'Unknown';
            echo "<tr><td>{$mod->module_id}</td><td>$status</td><td>$name</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No module settings found for CDM course</p>";
    }

    echo "<br><h4>Course Access:</h4>";
    echo "<p><a href='/courses/{$course_cdm->code}/' target='_blank'>Test CDM Course Access</a></p>";
}

echo "<hr>";

// Check all courses to find a manually created one
echo "<h3>Available Courses (to find manually created test course):</h3>";
$all_courses = Database::get()->queryArray("SELECT id, code, title, created FROM course WHERE visible != 3 ORDER BY id DESC LIMIT 10");

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Code</th><th>Title</th><th>Created</th><th>Test Access</th></tr>";

foreach ($all_courses as $course) {
    echo "<tr>";
    echo "<td>{$course->id}</td>";
    echo "<td>{$course->code}</td>";
    echo "<td>" . htmlspecialchars($course->title) . "</td>";
    echo "<td>{$course->created}</td>";
    echo "<td><a href='/courses/{$course->code}/' target='_blank'>Test</a></td>";
    echo "</tr>";
}
echo "</table>";

// Let's check the latest course that's not CDM imported
$manual_course = Database::get()->querySingle("SELECT id, code, title FROM course WHERE keywords IS NULL OR keywords = '' ORDER BY id DESC LIMIT 1");

if ($manual_course) {
    echo "<h3>Manual Course (Non-CDM): {$manual_course->title} (Code: {$manual_course->code})</h3>";

    echo "<h4>Module Activation Status:</h4>";
    $modules_manual = Database::get()->queryArray("SELECT module_id, visible FROM course_module WHERE course_id = ?d", $manual_course->id);

    if ($modules_manual) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Module ID</th><th>Status</th><th>Module Name</th></tr>";

        foreach ($modules_manual as $mod) {
            $status = $mod->visible == 1 ? '✅ Active' : '❌ Inactive';
            $name = $module_names[$mod->module_id] ?? 'Unknown';
            echo "<tr><td>{$mod->module_id}</td><td>$status</td><td>$name</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ No module settings found for manual course</p>";
    }

    echo "<br><h4>Course Access:</h4>";
    echo "<p><a href='/courses/{$manual_course->code}/' target='_blank'>Test Manual Course Access</a></p>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background: #f0f0f0; }
</style>