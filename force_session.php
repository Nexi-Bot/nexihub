<?php
require_once __DIR__ . '/config/config.php';

// FORCE RESET SESSION FOR OLIVER
session_destroy();
session_start();

$_SESSION['contract_user_id'] = 11;
$_SESSION['contract_staff_id'] = 11;
$_SESSION['contract_user_email'] = 'oliver.reaney@nexihub.uk';

echo "<h1>SESSION FORCED FOR OLIVER</h1>";
echo "<p>✓ contract_user_id: " . $_SESSION['contract_user_id'] . "</p>";
echo "<p>✓ contract_staff_id: " . $_SESSION['contract_staff_id'] . "</p>";
echo "<p>✓ contract_user_email: " . $_SESSION['contract_user_email'] . "</p>";

echo "<p><strong><a href='/contracts/dashboard.php'>NOW GO TO DASHBOARD</a></strong></p>";
?>
