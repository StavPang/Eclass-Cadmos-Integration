<?php
require_once 'include/baseTheme.php';

echo "<h2>Test Course Access URLs</h2>";

if (!$_SESSION['uid']) {
    echo "<p>❌ Not logged in - please log in first</p>";
    echo "<p><a href='/main/login_form.php'>Login here</a></p>";
    exit;
}

echo "<p>✅ Logged in as User ID: " . $_SESSION['uid'] . "</p>";

$course_code = '112';
$course = Database::get()->querySingle("SELECT id, code, title FROM course WHERE code = ?s", $course_code);

if (!$course) {
    echo "<p>❌ Course not found</p>";
    exit;
}

echo "<h3>Testing URLs for Course: {$course->title}</h3>";

// Check if user has access to the course
$user_course_access = Database::get()->querySingle("SELECT * FROM course_user WHERE course_id = ?d AND user_id = ?d",
    $course->id, $_SESSION['uid']);

if (!$user_course_access) {
    echo "<p>⚠️ User not enrolled in course - some links may not work</p>";

    // Try to enroll user for testing
    try {
        Database::get()->query("INSERT INTO course_user SET course_id = ?d, user_id = ?d, status = ?d, reg_date = NOW()",
            $course->id, $_SESSION['uid'], USER_STUDENT);
        echo "<p>✅ Auto-enrolled user for testing</p>";
    } catch (Exception $e) {
        echo "<p>⚠️ Could not auto-enroll: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>✅ User has course access</p>";
}

// Test different URL patterns
echo "<h4>Testing URL Patterns:</h4>";

$test_urls = [
    'Main Course' => "/courses/{$course->code}/",
    'Course Home' => "/courses/{$course->code}/index.php",
    'Course Portal' => "/main/portfolio.php?course={$course->code}",
    'Documents Module' => "/modules/document/index.php?course={$course->code}",
    'Exercise Module' => "/modules/exercise/index.php?course={$course->code}",
    'Video Module' => "/modules/video/index.php?course={$course->code}",
    'Specific Exercise' => "/modules/exercise/index.php?course={$course->code}&exerciseId=11"
];

foreach ($test_urls as $name => $url) {
    echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
    echo "<strong>$name:</strong><br>";
    echo "<a href='$url' target='_blank'>$url</a><br>";

    // Test the URL
    $full_url = "http://localhost" . $url;
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Cookie: " . $_SERVER['HTTP_COOKIE'] . "\r\n"
        ]
    ]);

    $response = @file_get_contents($full_url, false, $context);

    if ($response !== false) {
        if (strpos($response, '404') !== false) {
            echo "<span style='color: red;'>❌ 404 Not Found</span>";
        } elseif (strpos($response, 'login') !== false || strpos($response, 'Login') !== false) {
            echo "<span style='color: orange;'>⚠️ Redirects to login</span>";
        } elseif (strpos($response, $course->title) !== false || strpos($response, 'course') !== false) {
            echo "<span style='color: green;'>✅ Loads correctly</span>";
        } else {
            echo "<span style='color: blue;'>ℹ️ Unknown response</span>";
        }
    } else {
        echo "<span style='color: red;'>❌ Failed to load</span>";
    }
    echo "</div>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
div { margin-bottom: 5px; }
</style>