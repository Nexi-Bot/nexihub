<?php
// Debug timeoff portal
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== TIMEOFF PORTAL DEBUG ===\n";

try {
    echo "1. Testing config...\n";
    require_once __DIR__ . '/config/config.php';
    echo "✓ Config loaded\n";
    
    echo "2. Testing database...\n";
    $stmt = $pdo->query("SELECT 1");
    echo "✓ Database connected\n";
    
    echo "3. Testing session...\n";
    echo "Session status: " . session_status() . "\n";
    echo "Session ID: " . session_id() . "\n";
    
    echo "4. Testing timeoff tables...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'time_off_requests'");
    if ($stmt->rowCount() > 0) {
        echo "✓ time_off_requests table exists\n";
    } else {
        echo "❌ time_off_requests table missing\n";
    }
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'time_off_audit_log'");
    if ($stmt->rowCount() > 0) {
        echo "✓ time_off_audit_log table exists\n";
    } else {
        echo "❌ time_off_audit_log table missing\n";
    }
    
    echo "5. Testing function definition...\n";
    if (function_exists('sendTimeOffEmail')) {
        echo "❌ sendTimeOffEmail function already defined! (This causes 500 error)\n";
    } else {
        echo "✓ sendTimeOffEmail function not yet defined\n";
    }
    
    echo "\n=== TRYING TO INCLUDE TIMEOFF ===\n";
    ob_start();
    include __DIR__ . '/timeoff/index.php';
    $output = ob_get_clean();
    echo "✓ Timeoff portal included successfully\n";
    
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
?>
