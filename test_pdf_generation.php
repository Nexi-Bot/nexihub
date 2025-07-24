<?php
session_start();

// Set Oliver's session
$_SESSION['contract_user_id'] = 1;
$_SESSION['contract_staff_id'] = 1;
$_SESSION['contract_user_email'] = 'ollie.r@nexihub.uk';
$_SESSION['contract_user_name'] = 'Oliver Reaney';

echo "Session set for Oliver. Now testing PDF generation...\n\n";

// Test database connection and contract lookup
require_once __DIR__ . '/config/config.php';

try {
    if (DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connected successfully.\n";
    
    // Find Oliver's signed contracts
    $stmt = $db->prepare("
        SELECT ct.name, ct.id as template_id,
               sc.id as contract_record_id, sc.is_signed, sc.signed_at,
               sc.staff_id
        FROM contract_templates ct
        JOIN staff_contracts sc ON ct.id = sc.template_id 
        WHERE sc.staff_id = ? AND sc.is_signed = 1
    ");
    $stmt->execute([1]); // Oliver's staff_id
    $contracts = $stmt->fetchAll();
    
    echo "Found " . count($contracts) . " signed contracts for Oliver:\n";
    foreach ($contracts as $contract) {
        echo "- {$contract['name']} (Record ID: {$contract['contract_record_id']}, Template ID: {$contract['template_id']}, Signed: " . ($contract['is_signed'] ? 'Yes' : 'No') . ")\n";
    }
    
    if (!empty($contracts)) {
        $first_contract = $contracts[0];
        echo "\nTesting PDF generation for first contract (Record ID: {$first_contract['contract_record_id']})...\n";
        echo "Test URL: http://localhost:8000/contracts/download-pdf.php?contract_id={$first_contract['contract_record_id']}&staff_id=1\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
