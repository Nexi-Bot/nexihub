<?php
// Debug database connection

echo "Testing database connections...\n";

echo "1. Direct SQLite connection test:\n";
try {
    $db_path = __DIR__ . "/database/nexihub.db";
    echo "   Database path: $db_path\n";
    echo "   File exists: " . (file_exists($db_path) ? 'Yes' : 'No') . "\n";
    
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✓ Direct SQLite connection successful\n";
} catch (Exception $e) {
    echo "   ✗ Direct SQLite connection failed: " . $e->getMessage() . "\n";
}

echo "\n2. Config.php connection test:\n";
try {
    require_once __DIR__ . '/config/config.php';
    echo "   ✓ Config loaded successfully\n";
    echo "   IS_LOCAL_DEV: " . (IS_LOCAL_DEV ? 'true' : 'false') . "\n";
    echo "   DB_TYPE: " . DB_TYPE . "\n";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM staff");
    $result = $stmt->fetch();
    echo "   ✓ Database query successful - Staff count: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "   ✗ Config connection failed: " . $e->getMessage() . "\n";
}

echo "\n3. Testing from browser context:\n";
echo "   HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'not set') . "\n";
echo "   SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'not set') . "\n";

?>
