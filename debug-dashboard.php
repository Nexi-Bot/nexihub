<?php
/**
 * Debug script to check what's causing the HTTP 500 error on dashboard
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Debug: Testing dashboard components...\n";

try {
    echo "1. Loading config...\n";
    require_once __DIR__ . '/config/config.php';
    echo "✓ Config loaded successfully\n";
    
    echo "2. Testing database connection...\n";
    if (isset($pdo)) {
        echo "✓ PDO connection exists\n";
        $test = $pdo->query("SELECT 1");
        echo "✓ Database query works\n";
    } else {
        echo "✗ PDO connection not found\n";
    }
    
    echo "3. Testing authentication check...\n";
    // Simulate logged in state for testing
    $_SESSION['staff_id'] = 1;
    $_SESSION['discord_verified'] = true;
    $_SESSION['email_verified'] = true;
    $_SESSION['two_fa_verified'] = true;
    
    if (function_exists('requireAuth')) {
        echo "✓ requireAuth function exists\n";
    } else {
        echo "✗ requireAuth function not found\n";
    }
    
    echo "4. Testing dashboard file syntax...\n";
    $dashboard_file = __DIR__ . '/staff/dashboard.php';
    if (file_exists($dashboard_file)) {
        echo "✓ Dashboard file exists\n";
        
        // Check for syntax errors
        $output = shell_exec("php -l $dashboard_file 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✓ Dashboard syntax is valid\n";
        } else {
            echo "✗ Dashboard syntax error:\n";
            echo $output . "\n";
        }
    } else {
        echo "✗ Dashboard file not found\n";
    }
    
    echo "\nDebug complete. If no errors above, the issue might be in dashboard execution.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
