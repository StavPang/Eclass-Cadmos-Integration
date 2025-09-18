<?php
require_once 'include/init.php';

echo "<h2>Fix Existing CDM Course Modules</h2>";

$course_code = '112';
$course = Database::get()->querySingle("SELECT id, code, title FROM course WHERE code = ?s", $course_code);

if (!$course) {
    echo "<p>❌ Course not found</p>";
    exit;
}

echo "<h3>Activating modules for course: {$course->title} (ID: {$course->id})</h3>";

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

$module_names = [
    1 => 'Announcements', 2 => 'Agenda', 3 => 'Documents', 4 => 'Video/Multimedia',
    5 => 'Exercises', 6 => 'Assignments', 7 => 'Glossary', 8 => 'Learning Path',
    9 => 'Links', 10 => 'Course Units', 11 => 'E-Book', 12 => 'Questionnaire',
    13 => 'Wiki', 14 => 'Wall/Social', 15 => 'Chat', 16 => 'Forum', 17 => 'Groups',
    18 => 'Dropbox', 19 => 'User Progress', 20 => 'Usage Statistics'
];

echo "<h4>Module Activation Results:</h4>";
echo "<ul>";

foreach ($essential_modules as $module_id => $visible) {
    try {
        Database::get()->query("INSERT INTO course_module (course_id, module_id, visible) VALUES (?d, ?d, ?d)
                               ON DUPLICATE KEY UPDATE visible = ?d",
                               $course->id, $module_id, $visible, $visible);

        $module_name = $module_names[$module_id] ?? "Module $module_id";
        $status = $visible ? "✅ Activated" : "⚪ Deactivated";
        echo "<li>$status $module_name</li>";

    } catch (Exception $e) {
        $module_name = $module_names[$module_id] ?? "Module $module_id";
        echo "<li>❌ Failed to set $module_name: " . htmlspecialchars($e->getMessage()) . "</li>";
    }
}

echo "</ul>";

echo "<h4>Verification:</h4>";
$activated_modules = Database::get()->queryArray("SELECT module_id, visible FROM course_module WHERE course_id = ?d AND visible = 1", $course->id);

echo "<p><strong>Now Active Modules:</strong></p>";
echo "<ul>";
foreach ($activated_modules as $mod) {
    $module_name = $module_names[$mod->module_id] ?? "Module {$mod->module_id}";
    echo "<li>✅ $module_name</li>";
}
echo "</ul>";

echo "<h4>Test Course Access:</h4>";
echo "<p><a href='/courses/{$course->code}/' target='_blank' style='color: blue; font-weight: bold;'>🔗 Test Course Access Now</a></p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
ul { margin: 10px 0; }
li { margin: 3px 0; }
</style>