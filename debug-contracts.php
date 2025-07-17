<?php
// Debug the contract dashboard redirect issue
require_once 'config/config.php';

echo "<!DOCTYPE html><html><head><title>Contract Debug</title></head><body>";
echo "<h2>Nexi HR Portal Debug</h2>";

// Force set session data
$_SESSION['contract_user_id'] = 2;
$_SESSION['contract_user_email'] = 'contract@nexihub.uk';
$_SESSION['contract_user_role'] = 'staff';
$_SESSION['contract_staff_id'] = 1;

echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Testing Contract Dashboard Access:</h3>";

// Check if the contract dashboard file exists
$dashboard_path = __DIR__ . '/contracts/dashboard.php';
echo "<p>Dashboard file path: $dashboard_path</p>";
echo "<p>Dashboard file exists: " . (file_exists($dashboard_path) ? 'YES' : 'NO') . "</p>";

// Test the session check logic from the dashboard
if (!isset($_SESSION['contract_user_id'])) {
    echo "<p style='color: red;'>❌ Session check FAILED - contract_user_id not set</p>";
} else {
    echo "<p style='color: green;'>✅ Session check PASSED - contract_user_id = " . $_SESSION['contract_user_id'] . "</p>";
}

echo "<h3>Navigation:</h3>";
echo "<p><a href='/contracts/dashboard.php' target='_blank'>Test Contract Dashboard (new tab)</a></p>";
echo "<p><a href='contracts/dashboard.php' target='_blank'>Test Contract Dashboard - relative (new tab)</a></p>";

// Create a direct inclusion test
echo "<h3>Direct Include Test:</h3>";
echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
echo "<h4>Including contracts/dashboard.php directly:</h4>";

ob_start();
try {
    // Set up the expected environment
    $_GET = []; $_POST = [];
    
    // Capture any output from the dashboard
    include 'contracts/dashboard.php';
    $dashboard_output = ob_get_contents();
} catch (Exception $e) {
    echo "<p style='color: red;'>Error including dashboard: " . $e->getMessage() . "</p>";
    $dashboard_output = '';
}
ob_end_clean();

if (!empty($dashboard_output)) {
    echo "<p style='color: green;'>✅ Dashboard loaded successfully!</p>";
    echo "<p>Output length: " . strlen($dashboard_output) . " characters</p>";
} else {
    echo "<p style='color: red;'>❌ Dashboard did not produce output or redirected</p>";
}

echo "</div>";

echo "</body></html>";
?>
