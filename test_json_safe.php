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
    } else {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS);
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
    
    echo "=== TESTING JSON ENCODING WITH FLAGS ===\n";
    $json_safe = json_encode($contracts, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE);
    if ($json_safe === false) {
        echo "JSON encoding failed: " . json_last_error_msg() . "\n";
    } else {
        echo "JSON encoding successful with flags, length: " . strlen($json_safe) . " chars\n";
        
        // Create a minimal JavaScript test
        echo "\n=== TESTING JAVASCRIPT EXECUTION ===\n";
        echo "Creating test HTML to verify JS doesn't break...\n";
        
        $test_html = "
        <script>
        const contracts = $json_safe;
        console.log('Contracts loaded:', contracts.length);
        contracts.forEach((contract, index) => {
            console.log('Contract ' + index + ':', {
                contract_record_id: contract.contract_record_id,
                name: contract.name,
                is_signed: contract.is_signed
            });
        });
        </script>
        ";
        
        file_put_contents(__DIR__ . '/test_js.html', $test_html);
        echo "Created test_js.html - you can open this in a browser to test\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
