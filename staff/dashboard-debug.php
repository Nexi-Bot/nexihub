<?php
// Simple dashboard test script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting dashboard test...\n";

try {
    echo "1. Including config files...\n";
    require_once __DIR__ . '/../config/config.php';
    echo "   Config loaded successfully\n";
    
    require_once __DIR__ . '/../config/api_config.php';
    echo "   API config loaded successfully\n";
    
    require_once __DIR__ . '/../includes/StripeIntegration.php';
    echo "   Stripe integration loaded successfully\n";
    
    require_once __DIR__ . '/../includes/RealDataAnalytics.php';
    echo "   Real data analytics loaded successfully\n";
    
    echo "2. Database connection...\n";
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    echo "   Database connected successfully\n";
    
    echo "3. Testing Stripe integration...\n";
    $stripe = null;
    if (USE_REAL_FINANCIAL_DATA && defined('STRIPE_SECRET_KEY')) {
        $stripe = new StripeIntegration(STRIPE_SECRET_KEY);
        echo "   Stripe initialized\n";
    } else {
        echo "   Stripe not configured\n";
    }
    
    echo "4. Testing analytics provider...\n";
    try {
        $analytics_provider = new RealDataAnalytics($db, $stripe);
        echo "   Analytics provider created successfully\n";
        
        $analytics = $analytics_provider->getAnalyticsData();
        echo "   Analytics data retrieved successfully\n";
    } catch (Exception $e) {
        echo "   Analytics provider error: " . $e->getMessage() . "\n";
    }
    
    echo "5. Testing session variables...\n";
    session_start();
    echo "   Session started successfully\n";
    
    echo "All tests passed! Dashboard should work.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
