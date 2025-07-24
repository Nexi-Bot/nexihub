<?php
session_start();

echo "=== CONTRACT DASHBOARD SESSION DEBUG ===\n";
echo "contract_user_id: " . ($_SESSION['contract_user_id'] ?? 'NOT SET') . "\n";
echo "contract_staff_id: " . ($_SESSION['contract_staff_id'] ?? 'NOT SET') . "\n";
echo "contract_user_email: " . ($_SESSION['contract_user_email'] ?? 'NOT SET') . "\n";
echo "contract_user_name: " . ($_SESSION['contract_user_name'] ?? 'NOT SET') . "\n";
echo "\nAll session variables:\n";
print_r($_SESSION);
?>
