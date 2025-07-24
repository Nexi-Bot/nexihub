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
    
    echo "=== JSON ENCODING TEST ===\n";
    echo "Raw JSON output:\n";
    $json = json_encode($contracts);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON ERROR: " . json_last_error_msg() . "\n";
    } else {
        echo $json . "\n";
    }
    
    echo "\n=== PROBLEMATIC FIELDS CHECK ===\n";
    foreach ($contracts as $i => $contract) {
        echo "Contract $i:\n";
        foreach ($contract as $field => $value) {
            if (is_string($value) && (strpos($value, '"') !== false || strpos($value, '\n') !== false || strpos($value, '\r') !== false)) {
                echo "  ⚠️  Field '$field' contains special characters:\n";
                echo "    Length: " . strlen($value) . "\n";
                echo "    Preview: " . substr($value, 0, 100) . "...\n";
                echo "    JSON Test: " . json_encode($value) . "\n";
            }
        }
        echo "\n";
    }
    
    echo "=== SAFE JSON TEST ===\n";
    // Try encoding with JSON_HEX_QUOT to escape quotes
    $safe_json = json_encode($contracts, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE);
    echo "Safe JSON (first 500 chars):\n";
    echo substr($safe_json, 0, 500) . "...\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
