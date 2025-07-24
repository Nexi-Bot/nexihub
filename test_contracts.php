<?php
require_once __DIR__ . '/config/config.php';

// Set Oliver's session
$_SESSION['contract_user_id'] = 1;
$_SESSION['contract_staff_id'] = 1;
$_SESSION['contract_user_email'] = 'ollie.r@nexihub.uk';
$_SESSION['contract_user_name'] = 'Oliver Reaney';

// Database connection
try {
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
    
    // Same query as dashboard
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
    $stmt->execute([1]); // Oliver's staff ID
    $contracts = $stmt->fetchAll();
    
    echo "\n=== CONTRACTS DATA ===\n";
    echo "Number of contracts: " . count($contracts) . "\n\n";
    
    foreach ($contracts as $i => $contract) {
        echo "Contract $i:\n";
        echo "  Template ID: " . $contract['id'] . "\n";
        echo "  Contract Record ID: " . $contract['contract_record_id'] . "\n";
        echo "  Name: " . $contract['name'] . "\n";
        echo "  Is Signed: " . $contract['is_signed'] . " (type: " . gettype($contract['is_signed']) . ")\n";
        echo "  Signed At: " . ($contract['signed_at'] ?: 'NULL') . "\n";
        echo "  Has Signature Data: " . ($contract['signature_data'] ? 'YES' : 'NO') . "\n";
        echo "\n";
    }
    
    // Test JSON encoding
    echo "=== JSON ENCODING TEST ===\n";
    $json = json_encode($contracts);
    if ($json === false) {
        echo "JSON encoding failed: " . json_last_error_msg() . "\n";
    } else {
        echo "JSON encoding successful, length: " . strlen($json) . " chars\n";
        // Check if there are any problematic characters
        if (strpos($json, "\\n") !== false) {
            echo "Contains newlines in JSON\n";
        }
        if (strpos($json, '"') !== false) {
            echo "Contains quotes in JSON\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
