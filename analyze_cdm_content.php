<?php
require_once 'include/init.php';

echo "<h2>Analyze CDM File Content Structure</h2>";

try {
    $cdm_file = '/var/www/html/Omada1.cdm';

    if (!file_exists($cdm_file)) {
        throw new Exception("CDM file not found");
    }

    // Extract CDM to temp directory
    $extract_dir = sys_get_temp_dir() . '/cdm_analyze_' . uniqid();
    mkdir($extract_dir, 0755, true);

    $zip = new ZipArchive();
    if ($zip->open($cdm_file) !== TRUE) {
        throw new Exception("Failed to open CDM file");
    }

    $zip->extractTo($extract_dir);
    $zip->close();

    echo "<p>✅ CDM extracted to: $extract_dir</p>";

    // Read source.json
    $source_json = $extract_dir . '/source.json';
    if (!file_exists($source_json)) {
        throw new Exception("source.json not found");
    }

    $cdm_data = json_decode(file_get_contents($source_json), true);
    if (!$cdm_data) {
        throw new Exception("Invalid JSON in source.json");
    }

    echo "<h3>🔍 Analyzing Content Structure for Videos, Quizzes, and Resources</h3>";

    // Check Activities section for videos and quizzes
    if (isset($cdm_data['data']['Activities'])) {
        echo "<h4>Activities Section:</h4>";
        foreach ($cdm_data['data']['Activities'] as $key => $activity) {
            if (isset($activity['ModalData'])) {
                $modal = $activity['ModalData'];
                $title = $modal['Title'] ?? 'Unknown';
                $type = $modal['Type'] ?? 'Unknown';

                echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px;'>";
                echo "<strong>Activity:</strong> " . htmlspecialchars($title) . "<br>";
                echo "<strong>Type:</strong> " . htmlspecialchars($type) . "<br>";

                // Look for URLs/links
                if (isset($modal['Link']) && !empty($modal['Link'])) {
                    echo "<strong>🔗 Link:</strong> <a href='" . htmlspecialchars($modal['Link']) . "' target='_blank'>" . htmlspecialchars($modal['Link']) . "</a><br>";
                }

                if (isset($modal['Description'])) {
                    echo "<strong>Description:</strong> " . htmlspecialchars(substr($modal['Description'], 0, 200)) . "...<br>";
                }

                // Check if it's a video or quiz
                if (stripos($type, 'video') !== false || stripos($title, 'video') !== false) {
                    echo "<span style='background: red; color: white; padding: 2px 5px;'>📹 VIDEO</span><br>";
                }
                if (stripos($type, 'quiz') !== false || stripos($title, 'quiz') !== false) {
                    echo "<span style='background: green; color: white; padding: 2px 5px;'>❓ QUIZ</span><br>";
                }

                echo "</div>";
            }
        }
    }

    // Check Resources section
    if (isset($cdm_data['data']['Resources'])) {
        echo "<h4>Resources Section:</h4>";
        foreach ($cdm_data['data']['Resources'] as $key => $resource) {
            if (isset($resource['ModalData'])) {
                $modal = $resource['ModalData'];
                $title = $modal['Title'] ?? 'Unknown';
                $type = $modal['Type'] ?? 'Unknown';

                echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px;'>";
                echo "<strong>Resource:</strong> " . htmlspecialchars($title) . "<br>";
                echo "<strong>Type:</strong> " . htmlspecialchars($type) . "<br>";

                if (isset($modal['Link']) && !empty($modal['Link'])) {
                    echo "<strong>🔗 Link:</strong> <a href='" . htmlspecialchars($modal['Link']) . "' target='_blank'>" . htmlspecialchars($modal['Link']) . "</a><br>";
                }

                echo "</div>";
            }
        }
    }

    // Check Flow section for embedded content
    if (isset($cdm_data['data']['Flow'])) {
        echo "<h4>Flow Section (Learning Sequence):</h4>";

        function analyzeFlowRecursive($flowItem, $level = 0) {
            $indent = str_repeat('&nbsp;&nbsp;', $level * 2);

            if (isset($flowItem['ModalData'])) {
                $modal = $flowItem['ModalData'];
                $title = $modal['Title'] ?? 'Unknown';
                $type = $flowItem['type'] ?? 'Unknown';

                echo "<div style='margin-left: " . ($level * 20) . "px; border-left: 2px solid #ddd; padding-left: 10px; margin: 5px;'>";
                echo "<strong>Flow Item:</strong> " . htmlspecialchars($title) . "<br>";
                echo "<strong>Type:</strong> " . htmlspecialchars($type) . "<br>";

                if (isset($modal['Link']) && !empty($modal['Link'])) {
                    echo "<strong>🔗 Link:</strong> <a href='" . htmlspecialchars($modal['Link']) . "' target='_blank'>" . htmlspecialchars($modal['Link']) . "</a><br>";
                }

                // Highlight videos and quizzes
                if (stripos($type, 'video') !== false || stripos($title, 'video') !== false ||
                    stripos($modal['Link'] ?? '', 'youtube') !== false || stripos($modal['Link'] ?? '', 'vimeo') !== false) {
                    echo "<span style='background: red; color: white; padding: 2px 5px;'>📹 VIDEO FOUND</span><br>";
                }
                if (stripos($type, 'quiz') !== false || stripos($title, 'quiz') !== false) {
                    echo "<span style='background: green; color: white; padding: 2px 5px;'>❓ QUIZ FOUND</span><br>";
                }

                echo "</div>";
            }

            // Check children recursively
            if (isset($flowItem['children']) && is_array($flowItem['children'])) {
                foreach ($flowItem['children'] as $child) {
                    analyzeFlowRecursive($child, $level + 1);
                }
            }
        }

        if (isset($cdm_data['data']['Flow']['FlowSub'])) {
            foreach ($cdm_data['data']['Flow']['FlowSub'] as $flowItem) {
                analyzeFlowRecursive($flowItem);
            }
        }
    }

    // Clean up
    exec("rm -rf $extract_dir");
    echo "<p>🧹 Cleanup completed</p>";

} catch (Exception $e) {
    echo "<p>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
div { margin-bottom: 5px; }
</style>