<?php
require_once 'include/init.php';

echo "<h2>Check Correct URLs for Course Content</h2>";

$course_code = '112';
$course = Database::get()->querySingle("SELECT id, code, title FROM course WHERE code = ?s", $course_code);

if ($course) {
    echo "<h3>Course: {$course->title} (Code: {$course->code})</h3>";

    // Check exercises and their proper URLs
    echo "<h4>Exercises/Quizzes URLs:</h4>";
    $exercises = Database::get()->queryArray("SELECT id, title, description FROM exercise WHERE course_id = ?d ORDER BY id", $course->id);

    foreach ($exercises as $exercise) {
        echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
        echo "<strong>Exercise:</strong> " . htmlspecialchars($exercise->title) . "<br>";
        echo "<strong>ID:</strong> " . $exercise->id . "<br>";
        echo "<strong>Proper URL:</strong> <a href='/modules/exercise/index.php?course={$course->code}&exerciseId={$exercise->id}' target='_blank'>";
        echo "/modules/exercise/index.php?course={$course->code}&exerciseId={$exercise->id}</a><br>";
        echo "</div>";
    }

    // Check documents and their proper URLs
    echo "<h4>Documents URLs:</h4>";
    $documents = Database::get()->queryArray("SELECT id, title, filename, path FROM document WHERE course_id = ?d ORDER BY id LIMIT 5", $course->id);

    foreach ($documents as $document) {
        echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
        echo "<strong>Document:</strong> " . htmlspecialchars($document->title) . "<br>";
        echo "<strong>ID:</strong> " . $document->id . "<br>";
        echo "<strong>Filename:</strong> " . htmlspecialchars($document->filename) . "<br>";
        echo "<strong>Proper URL:</strong> <a href='/modules/document/index.php?course={$course->code}&openDir=/' target='_blank'>";
        echo "/modules/document/index.php?course={$course->code}</a><br>";
        echo "</div>";
    }

    // Check video links and their proper URLs
    echo "<h4>Video Links URLs:</h4>";
    $videos = Database::get()->queryArray("SELECT id, title, url FROM videolink WHERE course_id = ?d ORDER BY id", $course->id);

    foreach ($videos as $video) {
        echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
        echo "<strong>Video:</strong> " . htmlspecialchars($video->title) . "<br>";
        echo "<strong>ID:</strong> " . $video->id . "<br>";
        echo "<strong>External URL:</strong> <a href='" . htmlspecialchars($video->url) . "' target='_blank'>" . htmlspecialchars($video->url) . "</a><br>";
        echo "<strong>Internal URL:</strong> <a href='/modules/video/index.php?course={$course->code}' target='_blank'>";
        echo "/modules/video/index.php?course={$course->code}</a><br>";
        echo "</div>";
    }

    // Main course URL
    echo "<h4>Main Course URL:</h4>";
    echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
    echo "<strong>Main Course Page:</strong> <a href='/courses/{$course->code}/' target='_blank'>/courses/{$course->code}/</a><br>";
    echo "<strong>Course Home:</strong> <a href='/courses/{$course->code}/index.php' target='_blank'>/courses/{$course->code}/index.php</a><br>";
    echo "</div>";

} else {
    echo "<p>❌ Course not found</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
div { margin-bottom: 10px; }
</style>