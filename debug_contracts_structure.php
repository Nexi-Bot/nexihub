<?php
require_once __DIR__ . '/config/config.php';

// Force session for Oliver
$_SESSION['contract_user_id'] = 1;
$_SESSION['contract_staff_id'] = 1;
$_SESSION['contract_user_email'] = 'ollie.r@nexihub.uk';
$_SESSION['contract_user_name'] = 'Oliver Reaney';

try {
    // Connect using same logic as dashboard
    if (DB_TYPE === 'sqlite') {
        $db_path = realpath(__DIR__ . "/database/nexihub.db");
        $db = new PDO("sqlite:" . $db_path);
        echo "Using SQLite\n";
    } else {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS);
        echo "Using MySQL\n";
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Run the exact same query as dashboard
    $stmt = $db->prepare("
        SELECT ct.*,
               sc.id as contract_record_id,
               sc.is_signed,
               sc.signed_at, sc.signature_data,
               sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
               sc.is_under_17, sc.guardian_full_name, sc.guardian_email,
               sc.guardian_signature_data, sc.signed_timestamp,
               sp.shareholder_percentage, sp.is_shareholder
        FROM contract_templates ct
        INNER JOIN staff_contracts sc ON ct.id = sc.template_id 
        LEFT JOIN staff_profiles sp ON sc.staff_id = sp.id
        WHERE sc.staff_id = ?
        ORDER BY ct.name, sc.id DESC
    ");
    $stmt->execute([1]); // Oliver's staff_id in MySQL
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n=== CONTRACTS DATA STRUCTURE ===\n";
    echo "Number of contracts: " . count($contracts) . "\n\n";
    
    foreach ($contracts as $i => $contract) {
        echo "Contract $i:\n";
        echo "  Template ID (ct.id): " . $contract['id'] . "\n";
        echo "  Contract Record ID (sc.id): " . $contract['contract_record_id'] . "\n";
        echo "  Name: " . $contract['name'] . "\n";
        echo "  Is Signed: " . $contract['is_signed'] . "\n";
        echo "  Signed At: " . ($contract['signed_at'] ?: 'NULL') . "\n";
        echo "  Has Signature Data: " . ($contract['signature_data'] ? 'YES' : 'NO') . "\n";
        echo "\n";
    }
    
    // Test the exact JavaScript logic
    echo "=== TESTING JAVASCRIPT LOGIC ===\n";
    foreach ($contracts as $contract) {
        $contract_record_id = $contract['contract_record_id'];
        $is_signed = $contract['is_signed'];
        
        echo "Testing contract_record_id=$contract_record_id, is_signed=$is_signed\n";
        
        // This is what the JavaScript does:
        // const contract = contracts.find(c => c.contract_record_id == contractId);
        // if (!contract || !contract.is_signed) { ... error ... }
        
        if (!$contract || !$is_signed) {
            echo "  ❌ Would fail JavaScript check\n";
        } else {
            echo "  ✅ Would pass JavaScript check\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
