<?php
session_start();

// Set session variables for Oliver Reaney (staff_id = 1 in MySQL production)
$_SESSION['contract_user_id'] = 1;
$_SESSION['contract_staff_id'] = 1;
$_SESSION['contract_user_email'] = 'ollie.r@nexihub.uk';
$_SESSION['contract_user_name'] = 'Oliver Reaney';
$_SESSION['LAST_ACTIVITY'] = time();

echo "Session variables set for Oliver Reaney (production MySQL):\n";
echo "contract_user_id: " . $_SESSION['contract_user_id'] . "\n";
echo "contract_staff_id: " . $_SESSION['contract_staff_id'] . "\n";
echo "contract_user_email: " . $_SESSION['contract_user_email'] . "\n";
echo "contract_user_name: " . $_SESSION['contract_user_name'] . "\n";
echo "\n✅ Session ready for contract dashboard testing.\n";
?>
