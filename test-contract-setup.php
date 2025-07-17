<?php
require_once __DIR__ . '/config/config.php';

// Database connection
try {
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Link the contract user to Oliver's staff profile for testing
$stmt = $db->prepare("UPDATE contract_users SET staff_id = (SELECT id FROM staff_profiles WHERE staff_id = 'NXH001') WHERE email = 'contract@nexihub.uk'");
$stmt->execute();

echo "Contract user linked to Oliver's profile\n";

// Check current status
$stmt = $db->prepare("
    SELECT cu.email, cu.staff_id, sp.full_name, sp.staff_id as staff_code 
    FROM contract_users cu 
    LEFT JOIN staff_profiles sp ON cu.staff_id = sp.id 
    WHERE cu.email = 'contract@nexihub.uk'
");
$stmt->execute();
$result = $stmt->fetch();

echo "Current status:\n";
print_r($result);

// Assign all contracts to Oliver for testing
$stmt = $db->prepare("
    INSERT OR IGNORE INTO staff_contracts (staff_id, template_id, is_signed) 
    SELECT sp.id, ct.id, 0
    FROM staff_profiles sp, contract_templates ct
    WHERE sp.staff_id = 'NXH001'
");
$stmt->execute();

echo "All contracts assigned to Oliver\n";
?>
