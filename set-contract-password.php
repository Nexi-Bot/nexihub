<?php
/**
 * Set password for contract@nexihub.uk user
 */

require_once __DIR__ . '/config/config.php';

// Set the password for contract@nexihub.uk
$email = 'contract@nexihub.uk';
$password = 'nexitest123'; // Simple test password

try {
    // Connect to database
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Update the user's password
    $stmt = $db->prepare("UPDATE contract_users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$password_hash, $email]);
    
    if ($stmt->rowCount() > 0) {
        echo "<h2>✅ Password Set Successfully</h2>";
        echo "<p><strong>Email:</strong> contract@nexihub.uk</p>";
        echo "<p><strong>Password:</strong> nexitest123</p>";
        echo "<p><a href='contracts/index.php'>Go to Nexi HR Portal Login</a></p>";
    } else {
        echo "<h2>❌ Error</h2>";
        echo "<p>User not found or password not updated.</p>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
