<?php
require_once 'include/baseTheme.php';
require_once 'include/lib/course.class.php';
require_once 'modules/create_course/functions.php';

echo "<h2>Detailed Course Creation Debug</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_course'])) {
    echo "<h3>Step-by-Step Course Creation Test:</h3>";

    try {
        // Step 1: Check parameters
        $course_title = 'Debug Test Course - ' . date('Y-m-d H:i:s');
        $course_description = 'Debug test description';
        $prof_name = ($_SESSION['givenname'] ?? 'Test') . ' ' . ($_SESSION['surname'] ?? 'User');

        echo "<p><strong>Step 1: Parameters</strong></p>";
        echo "<ul>";
        echo "<li>Title: " . htmlspecialchars($course_title) . "</li>";
        echo "<li>Description: " . htmlspecialchars($course_description) . "</li>";
        echo "<li>Professor: " . htmlspecialchars($prof_name) . "</li>";
        echo "</ul>";

        // Step 2: Get department
        echo "<p><strong>Step 2: Department Selection</strong></p>";
        $department = Database::get()->querySingle("SELECT id, name FROM hierarchy WHERE allow_course = 1 ORDER BY id LIMIT 1");
        if ($department) {
            echo "<p>✅ Found department: ID " . $department->id . " - " . htmlspecialchars($department->name) . "</p>";
            $departments = [$department->id];
        } else {
            echo "<p>❌ No departments found</p>";
            exit;
        }

        // Step 3: Check user permissions
        echo "<p><strong>Step 3: User Permissions</strong></p>";
        echo "<ul>";
        echo "<li>User ID: " . ($_SESSION['uid'] ?? 'Not set') . "</li>";
        echo "<li>Status: " . ($_SESSION['status'] ?? 'Not set') . "</li>";
        echo "<li>Is Teacher: " . (isset($_SESSION['status']) && $_SESSION['status'] == USER_TEACHER ? 'Yes' : 'No') . "</li>";
        echo "</ul>";

        // Step 4: Test database queries that create_course might use
        echo "<p><strong>Step 4: Database Tests</strong></p>";

        // Check if we can insert into course table
        $test_course_code = 'TEST' . uniqid();
        try {
            $result = Database::get()->query("INSERT INTO course SET
                code = ?s,
                lang = ?s,
                title = ?s,
                description = ?s,
                prof_names = ?s,
                visible = ?d,
                created = NOW(),
                public_code = ?s",
                $test_course_code,
                'el',
                'Test Course Insert',
                'Test description',
                $prof_name,
                2,
                $test_course_code
            );

            $course_id = $result->lastInsertID;
            echo "<p>✅ Direct course insertion successful - Course ID: " . $course_id . "</p>";

            // Test course_department insertion
            try {
                Database::get()->query("INSERT INTO course_department SET course = ?d, department = ?d", $course_id, $department->id);
                echo "<p>✅ Course-department link successful</p>";
            } catch (Exception $e) {
                echo "<p>❌ Course-department link failed: " . htmlspecialchars($e->getMessage()) . "</p>";
            }

            // Test course_user insertion
            try {
                Database::get()->query("INSERT INTO course_user SET course_id = ?d, user_id = ?d, status = ?d", $course_id, $_SESSION['uid'], USER_TEACHER);
                echo "<p>✅ Course-user link successful</p>";
            } catch (Exception $e) {
                echo "<p>❌ Course-user link failed: " . htmlspecialchars($e->getMessage()) . "</p>";
            }

            // Clean up test course
            Database::get()->query("DELETE FROM course_user WHERE course_id = ?d", $course_id);
            Database::get()->query("DELETE FROM course_department WHERE course = ?d", $course_id);
            Database::get()->query("DELETE FROM course WHERE id = ?d", $course_id);
            echo "<p>🧹 Test course cleaned up</p>";

        } catch (Exception $e) {
            echo "<p>❌ Direct course insertion failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        }

        // Step 5: Try create_course function with debugging
        echo "<p><strong>Step 5: create_course() Function Test</strong></p>";

        // Enable error reporting
        $old_error_reporting = error_reporting(E_ALL);
        $old_display_errors = ini_get('display_errors');
        ini_set('display_errors', 1);

        echo "<p>Calling create_course('', 'el', '$course_title', '$course_description', [" . $department->id . "], 2, '$prof_name')</p>";

        // Test directory creation first
        echo "<p><strong>Step 5.1: Test Directory Creation</strong></p>";
        echo "<p><strong>Issue identified:</strong> Process runs as www-data but directories owned by stav</p>";
        echo "<p>This explains why create_course() fails - it can't create course directories</p>";

        // Test with temp directory that www-data can write to
        $test_code = strtoupper('TEST' . uniqid());
        echo "<p>Testing directory creation in /tmp for course code: $test_code</p>";

        $base = "/tmp/courses_test/$test_code";
        $dirs = [$base, "$base/image", "$base/document", "$base/dropbox",
            "$base/page", "$base/work", "$base/group", "$base/temp",
            "$base/scormPackages", "/tmp/video_test/$test_code"];

        $dir_creation_success = true;
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                if (mkdir($dir, 0755, true)) {
                    echo "<p>✅ Created directory: $dir</p>";
                } else {
                    echo "<p>❌ Failed to create directory: $dir</p>";
                    $dir_creation_success = false;
                }
            } else {
                echo "<p>ℹ️ Directory already exists: $dir</p>";
            }
        }

        if ($dir_creation_success) {
            echo "<p>✅ All directories created successfully in /tmp</p>";
            // Clean up test directories
            exec("rm -rf /tmp/courses_test /tmp/video_test");
            echo "<p>🧹 Test directories cleaned up</p>";
        } else {
            echo "<p>❌ Directory creation failed even in /tmp</p>";
        }

        echo "<p><strong>Step 5.2: Call create_course Function</strong></p>";
        $result = create_course('', 'el', $course_title, $course_description, $departments, 2, $prof_name);

        // Restore error settings
        error_reporting($old_error_reporting);
        ini_set('display_errors', $old_display_errors);

        if ($result) {
            list($course_code, $course_id) = $result;
            echo "<p>✅ create_course() succeeded!</p>";
            echo "<p>Course ID: " . $course_id . ", Course Code: " . $course_code . "</p>";
        } else {
            echo "<p>❌ create_course() returned false</p>";

            // Let's examine the create_course function
            echo "<p><strong>Examining create_course function...</strong></p>";

            if (function_exists('create_course')) {
                $reflection = new ReflectionFunction('create_course');
                echo "<p>Function file: " . $reflection->getFileName() . "</p>";
                echo "<p>Function line: " . $reflection->getStartLine() . "</p>";
            }
        }

    } catch (Exception $e) {
        echo "<p>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>File: " . $e->getFile() . "</p>";
        echo "<p>Line: " . $e->getLine() . "</p>";
    } catch (Error $e) {
        echo "<p>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>File: " . $e->getFile() . "</p>";
        echo "<p>Line: " . $e->getLine() . "</p>";
    }
}
?>

<form method="post">
    <button type="submit" name="test_course" style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px;">
        🔍 Detailed Debug Test
    </button>
</form>

<p><strong>This will test each step of course creation individually to find the exact problem.</strong></p>