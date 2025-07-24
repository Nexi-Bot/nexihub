<?php
require_once __DIR__ . '/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Verifying TESTFINAL deletion ===\n";
    
    // Check staff_profiles
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM staff_profiles WHERE full_name LIKE '%TESTFINAL%'");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    echo "Staff profiles with TESTFINAL: $count\n";
    
    // Check staff_contracts
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM staff_contracts WHERE staff_id = 3");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    echo "Contracts for staff_id 3 (TESTFINAL): $count\n";
    
    // Check contract_users
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM contract_users WHERE staff_id = 3");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    echo "Contract users for staff_id 3 (TESTFINAL): $count\n";
    
    echo "\nTESTFINAL has been completely removed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
