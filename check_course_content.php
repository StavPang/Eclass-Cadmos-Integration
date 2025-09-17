<?php
require_once 'include/init.php';

echo "<h2>Check Course Content from CDM Import</h2>";

// Check course 112 content
$course_code = '112';
$course = Database::get()->querySingle("SELECT id, code, title FROM course WHERE code = ?s", $course_code);

if ($course) {
    echo "<h3>Course: {$course->title} (ID: {$course->id})</h3>";

    // Check course units
    echo "<h4>Course Units:</h4>";
    $units = Database::get()->queryArray("SELECT id, title, comments, visible FROM course_units WHERE course_id = ?d ORDER BY `order`", $course->id);

    if ($units) {
        foreach ($units as $unit) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
            echo "<strong>Unit:</strong> " . htmlspecialchars($unit->title) . "<br>";
            echo "<strong>Description:</strong> " . htmlspecialchars($unit->comments) . "<br>";
            echo "<strong>Visible:</strong> " . ($unit->visible ? 'Yes' : 'No') . "<br>";
            echo "</div>";
        }
    } else {
        echo "<p>❌ No course units found</p>";
    }

    // Check documents
    echo "<h4>Documents:</h4>";
    $documents = Database::get()->queryArray("SELECT id, title, filename, comment FROM document WHERE course_id = ?d ORDER BY id", $course->id);

    if ($documents) {
        foreach ($documents as $doc) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
            echo "<strong>Document:</strong> " . htmlspecialchars($doc->title) . "<br>";
            echo "<strong>Filename:</strong> " . htmlspecialchars($doc->filename) . "<br>";
            echo "<strong>Comment:</strong> " . htmlspecialchars($doc->comment) . "<br>";
            echo "</div>";
        }
    } else {
        echo "<p>❌ No documents found</p>";
    }

    // Check video links
    echo "<h4>Video Links:</h4>";
    $videos = Database::get()->queryArray("SELECT id, title, url, description FROM videolink WHERE course_id = ?d ORDER BY id", $course->id);

    if ($videos) {
        foreach ($videos as $video) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
            echo "<strong>Video:</strong> " . htmlspecialchars($video->title) . "<br>";
            echo "<strong>URL:</strong> " . htmlspecialchars($video->url) . "<br>";
            echo "<strong>Description:</strong> " . htmlspecialchars($video->description) . "<br>";
            echo "</div>";
        }
    } else {
        echo "<p>❌ No video links found</p>";
    }

    // Check exercises/quizzes
    echo "<h4>Exercises/Quizzes:</h4>";
    $exercises = Database::get()->queryArray("SELECT id, title, description FROM exercise WHERE course_id = ?d ORDER BY id", $course->id);

    if ($exercises) {
        foreach ($exercises as $exercise) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
            echo "<strong>Exercise:</strong> " . htmlspecialchars($exercise->title) . "<br>";
            echo "<strong>Description:</strong> " . htmlspecialchars($exercise->description) . "<br>";
            echo "</div>";
        }
    } else {
        echo "<p>❌ No exercises/quizzes found</p>";
    }

    // Check what's in the CDM data to see what should be imported
    echo "<h4>CDM Data Analysis - What Should Be Imported:</h4>";
    $cdm_course = Database::get()->querySingle("SELECT keywords FROM course WHERE code = ?s", $course_code);
    if ($cdm_course && !empty($cdm_course->keywords)) {
        $cdm_data = json_decode($cdm_course->keywords, true);

        // Let's check the original CDM file for content structure
        echo "<p>Let's check what content should be imported from the original CDM file...</p>";
    }

} else {
    echo "<p>❌ Course not found</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
div { margin-bottom: 10px; }
</style>