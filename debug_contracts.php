<?php
require_once __DIR__ . '/config/config.php';

// Database connection
try {
    $db_path = realpath(__DIR__ . "/database/nexihub.db");
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

echo "<h2>Debug: Oliver's Contracts</h2>";

// Check what staff_id Oliver has
$stmt = $db->prepare("SELECT id, full_name FROM staff_profiles WHERE full_name LIKE '%Oliver%'");
$stmt->execute();
$oliver = $stmt->fetch();
echo "<p>Oliver's staff_id: " . ($oliver['id'] ?? 'NOT FOUND') . "</p>";

if ($oliver) {
    // Use the exact same query as the contracts dashboard
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
        ORDER BY ct.name, sc.is_signed ASC, sc.id DESC
    ");
    $stmt->execute([$oliver['id']]);
    $all_contracts = $stmt->fetchAll();
    
    echo "<h3>Raw Query Results:</h3>";
    foreach ($all_contracts as $contract) {
        echo "<p>Contract: " . $contract['name'] . " | is_signed: " . $contract['is_signed'] . " | signed_at: " . ($contract['signed_at'] ?? 'NULL') . "</p>";
    }
    
    // Group contracts by template_id like the dashboard does
    $contract_groups = [];
    foreach ($all_contracts as $contract) {
        $template_id = $contract['id'];
        if (!isset($contract_groups[$template_id])) {
            $contract_groups[$template_id] = $contract;
        }
    }
    
    $contracts = array_values($contract_groups);
    echo "<h3>After Grouping:</h3>";
    foreach ($contracts as $contract) {
        echo "<p>Contract: " . $contract['name'] . " | is_signed: " . $contract['is_signed'] . " | signed_at: " . ($contract['signed_at'] ?? 'NULL') . "</p>";
    }
}
?>
