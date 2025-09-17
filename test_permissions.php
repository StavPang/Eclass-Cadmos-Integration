<?php
echo "<h2>Permission Test</h2>";
echo "<p>Current user: " . get_current_user() . "</p>";
echo "<p>UID: " . getmyuid() . "</p>";
echo "<p>GID: " . getmygid() . "</p>";
echo "<p>Current working directory: " . getcwd() . "</p>";

// Test if we can create a file in /tmp
if (file_put_contents("/tmp/test_file.txt", "test")) {
    echo "<p>✅ Can create files in /tmp directory</p>";
    unlink("/tmp/test_file.txt");
} else {
    echo "<p>❌ Cannot create files in /tmp directory</p>";
}

// Test if we can create a file in the current directory
if (file_put_contents("test_file.txt", "test")) {
    echo "<p>✅ Can create files in current directory</p>";
    unlink("test_file.txt");
} else {
    echo "<p>❌ Cannot create files in current directory</p>";
    echo "<p>Error: " . error_get_last()['message'] . "</p>";
}

// Test if we can create a file in courses directory
if (file_put_contents("courses/test_file.txt", "test")) {
    echo "<p>✅ Can create files in courses directory</p>";
    unlink("courses/test_file.txt");
} else {
    echo "<p>❌ Cannot create files in courses directory</p>";
    echo "<p>Error: " . error_get_last()['message'] . "</p>";
}

// Show permissions and ownership
echo "<h3>Directory Details:</h3>";
echo "<pre>";
$stat = stat(".");
echo "Current dir: owner=" . $stat['uid'] . " group=" . $stat['gid'] . " perms=" . substr(sprintf('%o', $stat['mode']), -4) . "\n";
$stat = stat("courses");
echo "Courses: owner=" . $stat['uid'] . " group=" . $stat['gid'] . " perms=" . substr(sprintf('%o', $stat['mode']), -4) . "\n";
$stat = stat("video");
echo "Video: owner=" . $stat['uid'] . " group=" . $stat['gid'] . " perms=" . substr(sprintf('%o', $stat['mode']), -4) . "\n";
echo "</pre>";
?>