<?php
// Test PDO MySQL connection
try {
    echo "Testing PDO MySQL connection...\n";
    
    // Test connection parameters
    $host = 'localhost';
    $dbname = 'choose_and_go_foodstall';
    $username = 'root';
    $password = '';
    
    // Try to connect
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✓ Successfully connected to the database!\n";
    
    // Test a simple query
    $stmt = $pdo->query('SELECT 1');
    $result = $stmt->fetch();
    echo "✓ Database query successful: " . ($result ? 'Query returned results' : 'No results') . "\n";
    
} catch (PDOException $e) {
    echo "✗ PDO Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    
    // Check if the database exists
    if ($e->getCode() == '1049') { // Unknown database
        echo "The database '{$dbname}' does not exist.\n";
    }
    
    // Check if the MySQL server is running
    if ($e->getCode() == '2002') {
        echo "Could not connect to MySQL server. Make sure MySQL is running in XAMPP.\n";
    }
    
    // Check if the PDO MySQL driver is installed
    if (!extension_loaded('pdo_mysql')) {
        echo "PDO MySQL driver is not installed or enabled.\n";
    }
}
?>
