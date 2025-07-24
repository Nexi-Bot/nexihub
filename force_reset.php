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

echo "<h2>FORCE RESET OLIVER'S CONTRACTS</h2>";

// Find Oliver
$stmt = $db->prepare("SELECT id, full_name FROM staff_profiles WHERE full_name LIKE '%Oliver%'");
$stmt->execute();
$oliver = $stmt->fetch();

if ($oliver) {
    echo "<p>Found Oliver - ID: " . $oliver['id'] . "</p>";
    
    // Force clear all his contracts to unsigned
    $stmt = $db->prepare("UPDATE staff_contracts SET is_signed = 0, signed_at = NULL, signature_data = NULL, signer_full_name = NULL, signer_position = NULL, signer_date_of_birth = NULL, is_under_17 = 0, guardian_full_name = NULL, guardian_email = NULL, guardian_signature_data = NULL, signed_timestamp = NULL WHERE staff_id = ?");
    $result = $stmt->execute([$oliver['id']]);
    
    if ($result) {
        echo "<p style='color: green;'>✓ SUCCESSFULLY CLEARED ALL CONTRACTS FOR OLIVER</p>";
        
        // Verify
        $stmt = $db->prepare("SELECT sc.id, ct.name, sc.is_signed FROM staff_contracts sc JOIN contract_templates ct ON sc.template_id = ct.id WHERE sc.staff_id = ?");
        $stmt->execute([$oliver['id']]);
        $contracts = $stmt->fetchAll();
        
        echo "<h3>Oliver's contracts after reset:</h3>";
        foreach ($contracts as $contract) {
            echo "<p>- " . $contract['name'] . ": " . ($contract['is_signed'] ? 'SIGNED' : 'NOT SIGNED') . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ FAILED TO UPDATE</p>";
    }
} else {
    echo "<p style='color: red;'>Oliver not found!</p>";
}

// Clear any existing sessions
session_start();
session_destroy();
echo "<p style='color: blue;'>✓ SESSION CLEARED</p>";

echo "<p><strong>Now refresh the contracts dashboard page</strong></p>";
?>
