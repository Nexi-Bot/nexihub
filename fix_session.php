<?php
session_start();

// Set session variables for Oliver Reaney
$_SESSION['contract_user_id'] = 9;  // Oliver's staff_id
$_SESSION['contract_staff_id'] = 9;
$_SESSION['contract_user_email'] = 'ollie.r@nexihub.uk';
$_SESSION['contract_user_name'] = 'Oliver Reaney';

echo "Session updated successfully!\n";
echo "contract_user_id: " . $_SESSION['contract_user_id'] . "\n";
echo "contract_staff_id: " . $_SESSION['contract_staff_id'] . "\n";
echo "contract_user_email: " . $_SESSION['contract_user_email'] . "\n";
echo "contract_user_name: " . $_SESSION['contract_user_name'] . "\n";
?>
