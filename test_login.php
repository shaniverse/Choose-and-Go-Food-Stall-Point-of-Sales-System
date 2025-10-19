<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/test_errors.log');

// Set default timezone
date_default_timezone_set('Asia/Manila');

// Create logs directory if it doesn't exist
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}

// Function to log messages
function logMessage($message) {
    $logFile = __DIR__ . '/logs/test_errors.log';
    $timestamp = date('[Y-m-d H:i:s]');
    file_put_contents($logFile, "$timestamp $message\n", FILE_APPEND);
}

logMessage("=== Starting Login Test ===");

// Test 1: Check if required files exist
$requiredFiles = [
    'config/db.php',
    'core/functions.php',
    'auth/login.php'
];

logMessage("Checking required files...");
foreach ($requiredFiles as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        logMessage("✓ $file exists");
    } else {
        logMessage("✗ $file is missing");
    }
}

// Test 2: Check database connection
try {
    logMessage("Testing database connection...");
    require_once __DIR__ . '/config/db.php';
    
    if (isset($pdo) && $pdo instanceof PDO) {
        logMessage("✓ Database connection successful");
        
        // Test a simple query
        $stmt = $pdo->query("SELECT 1");
        if ($stmt->fetchColumn() === '1') {
            logMessage("✓ Database query successful");
        } else {
            logMessage("✗ Database query failed");
        }
    } else {
        logMessage("✗ Database connection failed: PDO not initialized");
    }
} catch (Exception $e) {
    logMessage("✗ Database error: " . $e->getMessage());
}

// Test 3: Check session functions
try {
    logMessage("Testing session functions...");
    require_once __DIR__ . '/core/functions.php';
    
    // Test session start
    ensureSession();
    logMessage("✓ Session started successfully");
    
    // Test isLoggedIn
    $isLoggedIn = isLoggedIn();
    logMessage("isLoggedIn(): " . ($isLoggedIn ? 'true' : 'false'));
    
    // Test session regeneration
    $oldSessionId = session_id();
    regenerateSession();
    $newSessionId = session_id();
    logMessage("Session regenerated. Old ID: $oldSessionId, New ID: $newSessionId");
    
} catch (Exception $e) {
    logMessage("✗ Session error: " . $e->getMessage());
}

// Test 4: Check login form
logMessage("Checking login form...");
$loginFormPath = __DIR__ . '/auth/login.php';
if (file_exists($loginFormPath)) {
    $loginFormContent = file_get_contents($loginFormPath);
    
    if (strpos($loginFormContent, '<form') !== false) {
        logMessage("✓ Login form found with form tag");
    } else {
        logMessage("✗ Login form is missing form tag");
    }
    
    // Check for required fields
    $requiredFields = ['name="login_type"', 'name="emp_id"', 'name="password"'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (strpos($loginFormContent, $field) === false) {
            $missingFields[] = $field;
        }
    }
    
    if (empty($missingFields)) {
        logMessage("✓ All required form fields found");
    } else {
        logMessage("✗ Missing form fields: " . implode(', ', $missingFields));
    }
} else {
    logMessage("✗ Login form not found at $loginFormPath");
}

logMessage("=== Login Test Completed ===\n");

echo "Login test completed. Check the log file at " . __DIR__ . "/logs/test_errors.log for details.\n";
?>
