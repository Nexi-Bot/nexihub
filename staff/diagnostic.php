<?php
// Simple diagnostic script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing dashboard components...\n";

// Test 1: Check if files exist
$files = [
    __DIR__ . '/../config/config.php',
    __DIR__ . '/../config/api_config.php', 
    __DIR__ . '/../includes/StripeIntegration.php',
    __DIR__ . '/../includes/RealDataAnalytics.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ File exists: $file\n";
    } else {
        echo "✗ File missing: $file\n";
    }
}

// Test 2: Try including config
try {
    require_once __DIR__ . '/../config/config.php';
    echo "✓ Config loaded successfully\n";
} catch (Exception $e) {
    echo "✗ Config error: " . $e->getMessage() . "\n";
}

// Test 3: Try including other files
try {
    require_once __DIR__ . '/../config/api_config.php';
    echo "✓ API config loaded\n";
} catch (Exception $e) {
    echo "✗ API config error: " . $e->getMessage() . "\n";
}

try {
    require_once __DIR__ . '/../includes/StripeIntegration.php';
    echo "✓ StripeIntegration loaded\n";
} catch (Exception $e) {
    echo "✗ StripeIntegration error: " . $e->getMessage() . "\n";
}

try {
    require_once __DIR__ . '/../includes/RealDataAnalytics.php';
    echo "✓ RealDataAnalytics loaded\n";
} catch (Exception $e) {
    echo "✗ RealDataAnalytics error: " . $e->getMessage() . "\n";
}

// Test 4: Database connection
try {
    $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connection successful\n";
} catch (PDOException $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "Diagnostic complete.\n";
?>
