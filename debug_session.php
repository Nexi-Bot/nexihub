<?php
require_once __DIR__ . '/config/config.php';

echo "<h1>SESSION DEBUG FOR CONTRACTS</h1>";

// Check what session exists
echo "<h2>Current Session:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Specific Contract Variables:</h2>";
echo "contract_user_id: " . ($_SESSION['contract_user_id'] ?? 'NOT SET') . "<br>";
echo "contract_staff_id: " . ($_SESSION['contract_staff_id'] ?? 'NOT SET') . "<br>";
echo "contract_user_email: " . ($_SESSION['contract_user_email'] ?? 'NOT SET') . "<br>";

// Check if user needs to log in
if (!isset($_SESSION['contract_user_id'])) {
    echo "<p style='color: red;'><strong>NO CONTRACT SESSION - USER NEEDS TO LOGIN</strong></p>";
    echo "<p><a href='/contracts/'>Go to contracts login</a></p>";
} else {
    echo "<p style='color: green;'><strong>User is logged in</strong></p>";
}

// Database connection to verify staff exists
try {
    $db_path = realpath(__DIR__ . "/database/nexihub.db");
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_SESSION['contract_staff_id'])) {
        $stmt = $db->prepare("SELECT id, full_name FROM staff_profiles WHERE id = ?");
        $stmt->execute([$_SESSION['contract_staff_id']]);
        $staff = $stmt->fetch();
        
        if ($staff) {
            echo "<h3>Staff Profile Found:</h3>";
            echo "ID: " . $staff['id'] . "<br>";
            echo "Name: " . $staff['full_name'] . "<br>";
        } else {
            echo "<p style='color: red;'>Staff ID " . $_SESSION['contract_staff_id'] . " NOT FOUND in database</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}
?>
