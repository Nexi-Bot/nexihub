<?php
require_once __DIR__ . '/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Finding TESTFINAL staff member ===\n";
    $stmt = $db->prepare("SELECT id, full_name, email, job_title FROM staff_profiles WHERE full_name LIKE '%TESTFINAL%' OR email LIKE '%TESTFINAL%'");
    $stmt->execute();
    $staff = $stmt->fetchAll();
    
    if (empty($staff)) {
        echo "No TESTFINAL staff member found.\n";
        exit;
    }
    
    foreach ($staff as $member) {
        echo "Staff ID: " . $member['id'] . "\n";
        echo "Name: " . $member['full_name'] . "\n";
        echo "Email: " . $member['email'] . "\n";
        echo "Job Title: " . $member['job_title'] . "\n\n";
        
        $staff_id = $member['id'];
        
        // Check contracts
        echo "=== Contracts for staff ID $staff_id ===\n";
        $stmt2 = $db->prepare("SELECT id, template_id, is_signed, signed_at FROM staff_contracts WHERE staff_id = ?");
        $stmt2->execute([$staff_id]);
        $contracts = $stmt2->fetchAll();
        
        foreach ($contracts as $contract) {
            echo "Contract ID: " . $contract['id'] . ", Template ID: " . $contract['template_id'] . ", Signed: " . ($contract['is_signed'] ? 'Yes' : 'No') . "\n";
        }
        echo "\n";
        
        // Check staff_users table
        echo "=== Login records for staff ID $staff_id ===\n";
        $stmt3 = $db->prepare("SELECT id, username, email FROM staff_users WHERE staff_id = ? OR email LIKE '%TESTFINAL%'");
        $stmt3->execute([$staff_id]);
        $users = $stmt3->fetchAll();
        
        foreach ($users as $user) {
            echo "User ID: " . $user['id'] . ", Username: " . $user['username'] . ", Email: " . $user['email'] . "\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
