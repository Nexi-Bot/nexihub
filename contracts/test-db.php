<?php
// Test database connection for contract portal
echo "<h2>Database Connection Test</h2>";

echo "<p>Current working directory: " . getcwd() . "</p>";
echo "<p>Script directory: " . __DIR__ . "</p>";
echo "<p>Database path: " . __DIR__ . "/../database/nexihub.db" . "</p>";
echo "<p>Database file exists: " . (file_exists(__DIR__ . "/../database/nexihub.db") ? "YES" : "NO") . "</p>";
echo "<p>Database file readable: " . (is_readable(__DIR__ . "/../database/nexihub.db") ? "YES" : "NO") . "</p>";
echo "<p>Database file writable: " . (is_writable(__DIR__ . "/../database/nexihub.db") ? "YES" : "NO") . "</p>";

try {
    $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test query
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>Tables found:</p><ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Test contract tables specifically
    echo "<h3>Contract Tables Test</h3>";
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM contract_templates");
    $result = $stmt->fetch();
    echo "<p>Contract templates: " . $result['count'] . "</p>";
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM staff_contracts");
    $result = $stmt->fetch();
    echo "<p>Staff contracts: " . $result['count'] . "</p>";
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM contract_users");
    $result = $stmt->fetch();
    echo "<p>Contract users: " . $result['count'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}
?>
