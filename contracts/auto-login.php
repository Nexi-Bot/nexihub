<?php
// Bypass login for testing
session_start();
$_SESSION['contract_user_id'] = 2;
$_SESSION['contract_user_email'] = 'contract@nexihub.uk';
$_SESSION['contract_staff_id'] = 1;

// Redirect to dashboard
header('Location: dashboard.php');
exit;
?>
