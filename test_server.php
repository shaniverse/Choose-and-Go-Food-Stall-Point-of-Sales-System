<?php
// Test PHP configuration
echo "<h2>PHP Configuration Test</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

// Test mod_rewrite
echo "<h3>mod_rewrite Test</h3>";
if (in_array('mod_rewrite', apache_get_modules())) {
    echo "✓ mod_rewrite is enabled<br>";
} else {
    echo "✗ mod_rewrite is not enabled<br>";
}

// Test required PHP extensions
$required_extensions = ['pdo_mysql', 'session', 'mbstring', 'openssl'];
echo "<h3>Required Extensions</h3>";
foreach ($required_extensions as $ext) {
    echo extension_loaded($ext) ? "✓ $ext is loaded<br>" : "✗ $ext is not loaded<br>";
}

// Test file permissions
$writable_dirs = ['assets/uploads', 'config'];
echo "<h3>Directory Permissions</h3>";
foreach ($writable_dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!file_exists($path)) {
        echo "✗ Directory '$dir' does not exist<br>";
        continue;
    }
    echo is_writable($path) ? "✓ '$dir' is writable<br>" : "✗ '$dir' is not writable<br>";
}

// Test database connection
try {
    require_once 'config/db.php';
    echo "<h3>Database Connection</h3>";
    $stmt = $pdo->query('SELECT DATABASE()');
    $dbname = $stmt->fetchColumn();
    echo "✓ Connected to database: " . htmlspecialchars($dbname) . "<br>";
} catch (PDOException $e) {
    echo "✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "<br>";
}
?>
