<?php
require_once 'config/db.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Test database connection
    $stmt = $pdo->query('SELECT DATABASE()');
    $dbname = $stmt->fetchColumn();
    echo "<p>✓ Connected to database: " . htmlspecialchars($dbname) . "</p>";
    
    // Test if tables exist
    $tables = ['users', 'products', 'orders', 'order_items'];
    $missingTables = [];
    
    foreach ($tables as $table) {
        try {
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() > 0) {
                echo "<p>✓ Table '$table' exists</p>";
            } else {
                $missingTables[] = $table;
                echo "<p style='color: red;'>✗ Table '$table' is missing</p>";
            }
        } catch (PDOException $e) {
            $missingTables[] = $table;
            echo "<p style='color: red;'>✗ Error checking table '$table': " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    if (empty($missingTables)) {
        echo "<p style='color: green;'>✓ All required tables exist</p>";
    } else {
        echo "<p style='color: red;'>✗ Missing tables: " . implode(', ', $missingTables) . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h3>System Check</h3>";

// Check PHP version
$phpVersion = phpversion();
$minPHPVersion = '7.4.0';
if (version_compare($phpVersion, $minPHPVersion, '>=')) {
    echo "<p>✓ PHP Version: $phpVersion (>= $minPHPVersion required)</p>";
} else {
    echo "<p style='color: red;'>✗ PHP Version $phpVersion is below the required $minPHPVersion</p>";
}

// Check if PDO is available
if (extension_loaded('pdo')) {
    echo "<p>✓ PDO extension is loaded</p>";
} else {
    echo "<p style='color: red;'>✗ PDO extension is not loaded</p>";
}

// Check if PDO MySQL driver is available
if (in_array('mysql', PDO::getAvailableDrivers())) {
    echo "<p>✓ PDO MySQL driver is available</p>";
} else {
    echo "<p style='color: red;'>✗ PDO MySQL driver is not available</p>";
}
?>