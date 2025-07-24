<?php
require_once __DIR__ . '/config/config.php';

echo "=== DATABASE CONNECTION TEST ===\n";

// Test SQLite connection
try {
    $db_path = realpath(__DIR__ . "/database/nexihub.db");
    echo "SQLite DB Path: " . $db_path . "\n";
    echo "File exists: " . (file_exists($db_path) ? "YES" : "NO") . "\n";
    
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check Oliver's contracts in SQLite
    $stmt = $db->prepare("SELECT c.id, c.staff_id, c.template_id, c.is_signed, c.signed_at, t.name as template_name FROM staff_contracts c JOIN contract_templates t ON c.template_id = t.id WHERE c.staff_id = 9 ORDER BY c.id");
    $stmt->execute();
    $sqlite_contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n=== SQLITE CONTRACTS (staff_id=9) ===\n";
    foreach ($sqlite_contracts as $contract) {
        echo "ID: {$contract['id']}, Template: {$contract['template_name']}, Signed: {$contract['is_signed']}, Date: {$contract['signed_at']}\n";
    }
    
} catch (Exception $e) {
    echo "SQLite Error: " . $e->getMessage() . "\n";
}

// Test what the dashboard is actually using
echo "\n=== CONFIG CHECK ===\n";
echo "DB_TYPE from config: " . (defined('DB_TYPE') ? DB_TYPE : 'NOT DEFINED') . "\n";
echo "DB_HOST from config: " . (defined('DB_HOST') ? DB_HOST : 'NOT DEFINED') . "\n";

// Test MySQL connection if it exists
if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
    try {
        echo "\n=== TESTING MYSQL CONNECTION ===\n";
        $mysql_dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $mysql_db = new PDO($mysql_dsn, DB_USER, DB_PASS);
        $mysql_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check if staff_contracts table exists in MySQL
        $stmt = $mysql_db->prepare("SHOW TABLES LIKE 'staff_contracts'");
        $stmt->execute();
        $table_exists = $stmt->fetch();
        
        echo "staff_contracts table exists in MySQL: " . ($table_exists ? "YES" : "NO") . "\n";
        
        if ($table_exists) {
            $stmt = $mysql_db->prepare("SELECT c.id, c.staff_id, c.template_id, c.is_signed, c.signed_at, t.name as template_name FROM staff_contracts c JOIN contract_templates t ON c.template_id = t.id WHERE c.staff_id = 9 ORDER BY c.id");
            $stmt->execute();
            $mysql_contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "\n=== MYSQL CONTRACTS (staff_id=9) ===\n";
            foreach ($mysql_contracts as $contract) {
                echo "ID: {$contract['id']}, Template: {$contract['template_name']}, Signed: {$contract['is_signed']}, Date: {$contract['signed_at']}\n";
            }
        }
        
    } catch (Exception $e) {
        echo "MySQL Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== SESSION CHECK ===\n";
session_start();
echo "contract_staff_id: " . ($_SESSION['contract_staff_id'] ?? 'NOT SET') . "\n";
echo "contract_user_id: " . ($_SESSION['contract_user_id'] ?? 'NOT SET') . "\n";
?>
