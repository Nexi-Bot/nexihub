<?php
/**
 * Test script for dashboard functionality without session
 */

// Suppress session warnings for testing
error_reporting(E_ERROR | E_PARSE);

try {
    require_once __DIR__ . '/config/api_config.php';
    echo "✓ API config loaded\n";
    
    require_once __DIR__ . '/includes/StripeIntegration.php';
    echo "✓ Stripe integration loaded\n";
    
    require_once __DIR__ . '/includes/RealDataAnalytics.php';
    echo "✓ Analytics provider loaded\n";
    
    // Test database connection
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
        echo "✓ MySQL database connected\n";
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✓ SQLite database connected\n";
    }
    
    // Test Stripe integration
    if (USE_REAL_FINANCIAL_DATA && defined('STRIPE_SECRET_KEY')) {
        $stripe = new StripeIntegration(STRIPE_SECRET_KEY);
        echo "✓ Stripe integration initialized\n";
        echo "  - Stripe configured: " . ($stripe->isConfigured() ? 'Yes' : 'No') . "\n";
    }
    
    // Test basic database queries first
    echo "🔍 Testing database queries...\n";
    
    // Check if tables exist
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "  - Available tables: " . implode(', ', $tables) . "\n";
    
    // Check staff table structure
    if (in_array('staff', $tables)) {
        $columns = $db->query("DESCRIBE staff")->fetchAll(PDO::FETCH_COLUMN);
        echo "  - Staff table columns: " . implode(', ', $columns) . "\n";
    }
    
    // Test analytics provider
    $analytics_provider = new RealDataAnalytics($db, $stripe ?? null);
    echo "✓ Analytics provider initialized\n";
    
    // Test basic analytics
    $analytics = $analytics_provider->getAnalyticsData();
    echo "✓ Analytics data retrieved\n";
    echo "  - Staff count: " . $analytics['total_staff'] . "\n";
    echo "  - Monthly revenue: £" . number_format($analytics['monthly_revenue'], 2) . "\n";
    echo "  - Active projects: " . $analytics['active_projects'] . "\n";
    
    echo "\n🎉 All systems working correctly!\n";
    echo "Dashboard should now load without HTTP 500 errors.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
