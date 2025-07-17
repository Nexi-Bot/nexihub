<?php
// Minimal contract dashboard test - set session properly
require_once 'config/config.php';

// Set contract session data
$_SESSION['contract_user_id'] = 2;
$_SESSION['contract_user_email'] = 'contract@nexihub.uk';
$_SESSION['contract_user_role'] = 'staff';
$_SESSION['contract_staff_id'] = 1;

// Prevent session timeout
$_SESSION['LAST_ACTIVITY'] = time();

echo "<!DOCTYPE html><html><head><title>Contract Test</title></head><body>";
echo "<h2>Nexi HR Portal Test</h2>";
echo "<p>Session set. <a href='contracts/dashboard.php'>Go to Contract Dashboard</a></p>";

// Also create a direct link with session verification
echo "<hr>";
echo "<h3>Direct Dashboard Access:</h3>";

// Check if we can access the dashboard logic
try {
    // Set up database connection manually
    $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Database connection successful</p>";
    
    // Check if contract user exists
    $stmt = $db->prepare("SELECT * FROM contract_users WHERE id = ?");
    $stmt->execute([2]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>✅ Contract user found: " . $user['email'] . "</p>";
    } else {
        echo "<p>❌ Contract user not found</p>";
    }
    
    // Check if staff profile exists
    $stmt = $db->prepare("SELECT * FROM staff_profiles WHERE id = ?");
    $stmt->execute([1]);
    $staff = $stmt->fetch();
    
    if ($staff) {
        echo "<p>✅ Staff profile found: " . $staff['full_name'] . "</p>";
    } else {
        echo "<p>❌ Staff profile not found</p>";
    }
    
    // Check contracts
    $stmt = $db->prepare("SELECT ct.*, sc.is_signed FROM contract_templates ct LEFT JOIN staff_contracts sc ON ct.id = sc.template_id AND sc.staff_id = ? ORDER BY ct.name");
    $stmt->execute([1]);
    $contracts = $stmt->fetchAll();
    
    echo "<p>✅ Found " . count($contracts) . " contracts</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<p><strong><a href='contracts/dashboard.php' style='color: blue; font-size: 18px;'>→ Go to Contract Dashboard</a></strong></p>";
echo "</body></html>";
?>
