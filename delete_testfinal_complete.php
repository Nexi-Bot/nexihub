<?php
require_once __DIR__ . '/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $staff_id = 3; // TESTFINAL staff ID
    
    echo "=== Checking contract_users table ===\n";
    $stmt = $db->prepare("SELECT * FROM contract_users WHERE staff_id = ?");
    $stmt->execute([$staff_id]);
    $contract_users = $stmt->fetchAll();
    
    foreach ($contract_users as $user) {
        echo "Contract User ID: " . $user['id'] . ", Staff ID: " . $user['staff_id'] . ", Email: " . $user['email'] . "\n";
    }
    
    echo "\n=== DELETING TESTFINAL DATA (Complete) ===\n";
    
    // Start transaction
    $db->beginTransaction();
    
    try {
        // Delete from contract_users first
        $stmt = $db->prepare("DELETE FROM contract_users WHERE staff_id = ?");
        $stmt->execute([$staff_id]);
        $users_deleted = $stmt->rowCount();
        echo "Deleted $users_deleted contract users.\n";
        
        // Delete contracts
        $stmt = $db->prepare("DELETE FROM staff_contracts WHERE staff_id = ?");
        $stmt->execute([$staff_id]);
        $contracts_deleted = $stmt->rowCount();
        echo "Deleted $contracts_deleted contracts.\n";
        
        // Delete from staff_profiles
        $stmt = $db->prepare("DELETE FROM staff_profiles WHERE id = ?");
        $stmt->execute([$staff_id]);
        $profiles_deleted = $stmt->rowCount();
        echo "Deleted $profiles_deleted staff profile.\n";
        
        // Commit transaction
        $db->commit();
        echo "Successfully deleted TESTFINAL and all associated data!\n";
        
    } catch (Exception $e) {
        $db->rollback();
        echo "Error during deletion: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
