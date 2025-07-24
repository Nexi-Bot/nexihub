<?php
require_once __DIR__ . '/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $staff_id = 3; // TESTFINAL staff ID
    
    echo "=== TESTFINAL Contracts ===\n";
    $stmt = $db->prepare("SELECT * FROM staff_contracts WHERE staff_id = ?");
    $stmt->execute([$staff_id]);
    $contracts = $stmt->fetchAll();
    
    if (empty($contracts)) {
        echo "No contracts found for TESTFINAL.\n";
    } else {
        foreach ($contracts as $contract) {
            echo "Contract ID: " . $contract['id'] . "\n";
            echo "Template ID: " . $contract['template_id'] . "\n";
            echo "Is Signed: " . ($contract['is_signed'] ? 'Yes' : 'No') . "\n";
            echo "Signed At: " . ($contract['signed_at'] ?? 'NULL') . "\n";
            echo "---\n";
        }
    }
    
    echo "\n=== DELETING TESTFINAL DATA ===\n";
    
    // Start transaction
    $db->beginTransaction();
    
    try {
        // Delete contracts first (foreign key dependency)
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
