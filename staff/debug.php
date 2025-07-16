<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting debug...\n";

try {
    echo "1. Checking config file...\n";
    require_once __DIR__ . '/../config/config.php';
    echo "✓ Config loaded successfully\n";
    
    echo "2. Checking API config...\n";
    require_once __DIR__ . '/../config/api_config.php';
    echo "✓ API config loaded successfully\n";
    
    echo "3. Checking StripeIntegration...\n";
    require_once __DIR__ . '/../includes/StripeIntegration.php';
    echo "✓ StripeIntegration loaded successfully\n";
    
    echo "4. Checking RealDataAnalytics...\n";
    require_once __DIR__ . '/../includes/RealDataAnalytics.php';
    echo "✓ RealDataAnalytics loaded successfully\n";
    
    echo "5. Session status: " . session_status() . "\n";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        echo "✓ Session started\n";
    } else {
        echo "✓ Session already active\n";
    }
    
    echo "6. Checking authentication...\n";
    // Comment out the requireAuth() to see if that's the issue
    // requireAuth();
    echo "✓ Auth check bypassed for debugging\n";
    
    echo "All checks passed! Dashboard should work.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
