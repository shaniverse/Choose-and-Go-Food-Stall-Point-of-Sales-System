<?php
/**
 * Script to update file paths throughout the application
 * 
 * This script will update all PHP, JS, and CSS files to use the correct paths
 * based on the new directory structure.
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the root directory
$rootDir = __DIR__;

// Path updates for system organization
$updates = [
    // Old path => New path
    'config/db.php' => 'config/db.php',
    'core/functions.php' => 'core/functions.php',
    'error_pages/error_pages/403.php' => 'error_pages/error_pages/error_pages/403.php',
    'error_pages/error_pages/404.php' => 'error_pages/error_pages/error_pages/404.php',
    'tests/tests/test_db.php' => 'tests/tests/tests/test_db.php',
    'tests/tests/test_connection.php' => 'tests/tests/tests/test_connection.php',
    'monitoring/monitoring/monitor.php' => 'monitoring/monitoring/monitoring/monitor.php',
    'monitoring/monitoring/monitor_data.php' => 'monitoring/monitoring/monitoring/monitor_data.php',
    'config/config/database.sql' => 'config/config/config/database.sql',
    'config/config/init_db.php' => 'config/config/config/init_db.php',
    'auth/login.php' => 'auth/login.php',
    'auth/logout.php' => 'auth/logout.php',
    'auth/register_customer.php' => 'auth/register_customer.php',
    'auth/register.php' => 'auth/register.php',
    // Add more path mappings as needed
    '../core/' => '../core/',
    'core/' => 'core/',
    'auth/' => 'auth/'
];

/**
 * Recursively update file paths in all PHP, JS, and CSS files
 * 
 * @param string $dir Directory to scan
 * @param array $updates Array of path updates
 * @return array Array of updated files and changes made
 */
function updateFilePaths($dir, $updates) {
    $results = [
        'updated' => [],
        'errors' => []
    ];
    
    try {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile() && in_array($file->getExtension(), ['php', 'js', 'css', 'html'])) {
                $filePath = $file->getPathname();
                $content = file_get_contents($filePath);
                $originalContent = $content;
                $fileUpdated = false;
                
                // Skip vendor directories and other non-essential directories
                if (strpos($filePath, '/vendor/') !== false || 
                    strpos($filePath, '/node_modules/') !== false) {
                    continue;
                }

                // Update paths
                foreach ($updates as $old => $new) {
                    if (strpos($content, $old) !== false) {
                        $content = str_replace($old, $new, $content);
                        $fileUpdated = true;
                    }
                }


                // Save changes if updated
                if ($fileUpdated && $content !== $originalContent) {
                    if (file_put_contents($filePath, $content) !== false) {
                        $results['updated'][] = [
                            'file' => $filePath,
                            'changes' => array_diff(explode("\n", $content), explode("\n", $originalContent))
                        ];
                    } else {
                        $results['errors'][] = "Failed to update: " . $filePath;
                    }
                }
            }
        }
    } catch (Exception $e) {
        $results['errors'][] = "Error: " . $e->getMessage();
    }
    
    return $results;
}

// Run the path updates
$results = updateFilePaths($rootDir, $updates);

// Output results
echo "<h2>Path Update Results</h2>";

echo "<h3>Updated Files (" . count($results['updated']) . ")</h3>";
echo "<ul>";
foreach ($results['updated'] as $update) {
    echo "<li><strong>" . htmlspecialchars($update['file']) . "</strong>";
    if (!empty($update['changes'])) {
        echo "<ul>";
        foreach ($update['changes'] as $change) {
            if (trim($change) !== '') {
                echo "<li>" . htmlspecialchars($change) . "</li>";
            }
        }
        echo "</ul>";
    }
    echo "</li>";
}
echo "</ul>";

if (!empty($results['errors'])) {
    echo "<h3>Errors (" . count($results['errors']) . ")</h3>";
    echo "<ul>";
    foreach ($results['errors'] as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
}

echo "<p>Path update process completed.</p>";

// Also update the .htaccess file
$htaccessPath = $rootDir . '/.htaccess';
if (file_exists($htaccessPath)) {
    $htaccess = file_get_contents($htaccessPath);
    $htaccess = str_replace('auth/', 'auth/', $htaccess);
    $htaccess = str_replace('core/', 'core/', $htaccess);
    file_put_contents($htaccessPath, $htaccess);
    echo "<p>Updated .htaccess file paths</p>";
}

// Create necessary directories if they don't exist
$requiredDirs = [
    $rootDir . '/assets',
    $rootDir . '/auth',
    $rootDir . '/config',
    $rootDir . '/core',
    $rootDir . '/error_pages',
    $rootDir . '/modules',
    $rootDir . '/templates',
    $rootDir . '/uploads',
    $rootDir . '/views'
];

foreach ($requiredDirs as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "<p>Created directory: " . htmlspecialchars($dir) . "</p>";
        } else {
            echo "<p>Failed to create directory: " . htmlspecialchars($dir) . "</p>";
        }
    }
}

echo "<p>Directory structure has been verified and updated if necessary.</p>";

// Base directory of the project
$baseDir = __DIR__;
updateFilePaths($baseDir, $updates);

echo "File path updates completed.";
?>
