<?php
require_once __DIR__ . '/config/config.php';

// Force start session like the real dashboard
session_start();

// Set the session like the contracts dashboard would
$_SESSION['contract_staff_id'] = 11; // Oliver's ID

// Database connection
try {
    $db_path = realpath(__DIR__ . "/database/nexihub.db");
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

echo "<h1>EXACT DASHBOARD QUERY DEBUG</h1>";

// Use the EXACT same query as the dashboard
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
$stmt->execute([$_SESSION['contract_staff_id'] ?? 0]);
$contracts = $stmt->fetchAll();

echo "<h2>Session Info:</h2>";
echo "<p>contract_staff_id: " . ($_SESSION['contract_staff_id'] ?? 'NOT SET') . "</p>";

echo "<h2>Query Results:</h2>";
foreach ($contracts as $contract) {
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px; background: " . ($contract['is_signed'] ? '#ffe6e6' : '#e6ffe6') . "'>";
    echo "<h3>Contract: " . htmlspecialchars($contract['name']) . "</h3>";
    echo "<p><strong>is_signed:</strong> " . ($contract['is_signed'] ? 'YES (1)' : 'NO (0)') . "</p>";
    echo "<p><strong>signed_at:</strong> " . ($contract['signed_at'] ?? 'NULL') . "</p>";
    echo "<p><strong>signature_data:</strong> " . (empty($contract['signature_data']) ? 'EMPTY' : 'HAS DATA') . "</p>";
    echo "<p><strong>contract_record_id:</strong> " . $contract['contract_record_id'] . "</p>";
    echo "<p><strong>template_id:</strong> " . $contract['id'] . "</p>";
    echo "</div>";
}

echo "<p><strong>Total contracts found: " . count($contracts) . "</strong></p>";
?>
