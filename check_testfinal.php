<?php
require_once __DIR__ . '/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Staff Profiles Table Structure ===\n";
    $stmt = $db->prepare("DESCRIBE staff_profiles");
    $stmt->execute();
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . "\n";
    }
    echo "\n";
    
    echo "=== Finding TESTFINAL staff member ===\n";
    $stmt = $db->prepare("SELECT * FROM staff_profiles WHERE full_name LIKE '%TESTFINAL%'");
    $stmt->execute();
    $staff = $stmt->fetchAll();
    
    if (empty($staff)) {
        echo "No TESTFINAL staff member found in staff_profiles.\n";
    } else {
        foreach ($staff as $member) {
            echo "Staff found:\n";
            foreach ($member as $key => $value) {
                if (!is_numeric($key)) {
                    echo "  $key: $value\n";
                }
            }
            echo "\n";
        }
    }
    
    echo "=== Checking staff_users table ===\n";
    $stmt = $db->prepare("SELECT * FROM staff_users WHERE email LIKE '%TESTFINAL%' OR username LIKE '%TESTFINAL%'");
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    if (empty($users)) {
        echo "No TESTFINAL user found in staff_users.\n";
    } else {
        foreach ($users as $user) {
            echo "User found:\n";
            foreach ($user as $key => $value) {
                if (!is_numeric($key)) {
                    echo "  $key: $value\n";
                }
            }
            echo "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
