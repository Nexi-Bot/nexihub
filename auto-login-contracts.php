<?php
require_once 'config/config.php';

// Auto-login for testing
$_SESSION['contract_user_id'] = 2;
$_SESSION['contract_user_email'] = 'contract@nexihub.uk';
$_SESSION['contract_user_role'] = 'staff';
$_SESSION['contract_staff_id'] = 1;
$_SESSION['LAST_ACTIVITY'] = time();

echo "<!DOCTYPE html><html><head><title>Auto Login</title></head><body>";
echo "<h2>Auto-login successful!</h2>";
echo "<p>Session data set:</p>";
echo "<ul>";
echo "<li>User ID: " . $_SESSION['contract_user_id'] . "</li>";
echo "<li>Email: " . $_SESSION['contract_user_email'] . "</li>";
echo "<li>Role: " . $_SESSION['contract_user_role'] . "</li>";
echo "<li>Staff ID: " . $_SESSION['contract_staff_id'] . "</li>";
echo "</ul>";
echo "<p><a href='/contracts/dashboard.php'>Go to Contract Dashboard</a></p>";
echo "<script>setTimeout(function(){ window.location.href = '/contracts/dashboard.php'; }, 2000);</script>";
echo "</body></html>";
?>
