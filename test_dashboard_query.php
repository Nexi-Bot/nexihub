<?php
session_start();

// Set session variables for Oliver
$_SESSION['contract_user_id'] = 9;
$_SESSION['contract_staff_id'] = 9;
$_SESSION['contract_user_email'] = 'ollie.r@nexihub.uk';
$_SESSION['contract_user_name'] = 'Oliver Reaney';

// Test SQLite connection exactly like dashboard
$db_path = realpath(__DIR__ . "/database/nexihub.db");
$db = new PDO("sqlite:" . $db_path);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== EXACT DASHBOARD QUERY TEST ===\n";
echo "Session staff_id: " . $_SESSION['contract_staff_id'] . "\n";
echo "Database path: " . $db_path . "\n\n";

// Run the exact same query as the dashboard
$stmt = $db->prepare("
    SELECT ct.id, ct.name, ct.content, ct.created_at, ct.updated_at,
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
$stmt->execute([$_SESSION['contract_staff_id']]);
$contracts = $stmt->fetchAll();

echo "Number of contracts found: " . count($contracts) . "\n\n";

foreach ($contracts as $i => $contract) {
    echo "=== CONTRACT " . ($i + 1) . " ===\n";
    echo "Contract Record ID: " . $contract['contract_record_id'] . "\n";
    echo "Template Name: " . $contract['name'] . "\n";
    echo "Is Signed: " . $contract['is_signed'] . "\n";
    echo "Signed At: " . ($contract['signed_at'] ?: 'NULL') . "\n";
    echo "Signature Data: " . ($contract['signature_data'] ? 'HAS DATA' : 'NULL') . "\n";
    echo "Signer Name: " . ($contract['signer_full_name'] ?: 'NULL') . "\n";
    echo "\n";
}

// Also check raw data in staff_contracts table
echo "=== RAW STAFF_CONTRACTS TABLE ===\n";
$stmt = $db->prepare("SELECT * FROM staff_contracts WHERE staff_id = ?");
$stmt->execute([9]);
$raw_contracts = $stmt->fetchAll();

foreach ($raw_contracts as $contract) {
    echo "ID: {$contract['id']}, Staff: {$contract['staff_id']}, Template: {$contract['template_id']}, Signed: {$contract['is_signed']}, Date: {$contract['signed_at']}\n";
}
?>
