<?php
echo "<h2>PHP Configuration Check</h2>";
echo "<p><strong>open_basedir:</strong> " . (ini_get('open_basedir') ?: 'Not set') . "</p>";
echo "<p><strong>disable_functions:</strong> " . (ini_get('disable_functions') ?: 'Not set') . "</p>";
echo "<p><strong>safe_mode:</strong> " . (ini_get('safe_mode') ? 'On' : 'Off') . "</p>";
echo "<p><strong>allow_url_fopen:</strong> " . (ini_get('allow_url_fopen') ? 'On' : 'Off') . "</p>";
echo "<p><strong>file_uploads:</strong> " . (ini_get('file_uploads') ? 'On' : 'Off') . "</p>";
echo "<p><strong>upload_tmp_dir:</strong> " . (ini_get('upload_tmp_dir') ?: 'Default') . "</p>";

echo "<h3>Effective User/Group Check:</h3>";
echo "<p>Process user: " . posix_getpwuid(posix_getuid())['name'] . "</p>";
echo "<p>Process group: " . posix_getgrgid(posix_getgid())['name'] . "</p>";
echo "<p>Effective user: " . posix_getpwuid(posix_geteuid())['name'] . "</p>";
echo "<p>Effective group: " . posix_getgrgid(posix_getegid())['name'] . "</p>";
?>