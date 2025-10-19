<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 'choose_and_go_foodstall';
$username = 'root';
$password = '';

// Test MySQL connection
function testMysqlConnection($host, $username, $password, $dbname = null) {
    try {
        $dsn = "mysql:host=$host";
        if ($dbname) {
            $dsn .= ";dbname=$dbname";
        }
        $dsn .= ";charset=utf8mb4";
        
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        return [
            'success' => true,
            'message' => 'Successfully connected to ' . ($dbname ? "database '$dbname'" : 'MySQL server'),
            'version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION)
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'code' => $e->getCode()
        ];
    }
}

// Test 1: Check if MySQL server is running
$serverTest = testMysqlConnection($host, $username, $password);

// Test 2: Check if database exists
$dbTest = null;
if ($serverTest['success']) {
    $dbTest = testMysqlConnection($host, $username, $password, $dbname);
}

// Output results as HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .test { margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Database Connection Test</h1>
    
    <div class="test">
        <h2>1. MySQL Server Connection</h2>
        <?php if ($serverTest['success']): ?>
            <p class="success">✓ Successfully connected to MySQL server</p>
            <p>Server version: <?php echo htmlspecialchars($serverTest['version']); ?></p>
        <?php else: ?>
            <p class="error">✗ Failed to connect to MySQL server</p>
            <p>Error: <?php echo htmlspecialchars($serverTest['error']); ?></p>
            <p>Error code: <?php echo htmlspecialchars($serverTest['code']); ?></p>
        <?php endif; ?>
    </div>
    
    <?php if ($serverTest['success']): ?>
    <div class="test">
        <h2>2. Database Connection</h2>
        <?php if ($dbTest && $dbTest['success']): ?>
            <p class="success">✓ Successfully connected to database '<?php echo htmlspecialchars($dbname); ?>'</p>
        <?php else: ?>
            <p class="error">✗ Failed to connect to database '<?php echo htmlspecialchars($dbname); ?>'</p>
            <?php if ($dbTest): ?>
                <p>Error: <?php echo htmlspecialchars($dbTest['error']); ?></p>
                <p>Error code: <?php echo htmlspecialchars($dbTest['code']); ?></p>
                <?php if ($dbTest['code'] == 1049): ?>
                    <p>The database does not exist. You need to create it first.</p>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="test">
        <h2>3. Next Steps</h2>
        <?php if ($dbTest && $dbTest['success']): ?>
            <p>✅ Your database connection is working correctly!</p>
        <?php elseif ($dbTest && $dbTest['code'] == 1049): ?>
            <p>To create the database, run this SQL command in phpMyAdmin or MySQL client:</p>
            <pre>CREATE DATABASE `<?php echo htmlspecialchars($dbname); ?>` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</pre>
        <?php else: ?>
            <p>Please check your database configuration in <code>config/db.php</code> and ensure that:</p>
            <ul>
                <li>MySQL server is running</li>
                <li>Username and password are correct</li>
                <li>Database exists and is accessible by the user</li>
            </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</body>
</html>
