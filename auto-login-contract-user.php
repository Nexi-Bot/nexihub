<?php
/**
 * Auto-login script for contract@nexihub.uk user
 */

require_once __DIR__ . '/config/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    
    // Find contract user
    $stmt = $db->prepare("SELECT id, full_name, nexi_email FROM staff_profiles WHERE nexi_email = ?");
    $stmt->execute(['contract@nexihub.uk']);
    $user = $stmt->fetch();
    
    if ($user) {
        // Set contract portal session
        $_SESSION['contract_user_id'] = $user['id'];
        $_SESSION['contract_staff_id'] = $user['id'];
        $_SESSION['contract_full_name'] = $user['full_name'];
        $_SESSION['contract_email'] = $user['nexi_email'];
        
        echo "<h2>Auto-Login Successful</h2>";
        echo "<p>Logged in as: <strong>" . htmlspecialchars($user['full_name']) . "</strong></p>";
        echo "<p>Email: <strong>" . htmlspecialchars($user['nexi_email']) . "</strong></p>";
        echo "<p><a href='contracts/dashboard.php' style='background: #e64f21; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Contract Dashboard →</a></p>";
        
        // Check current contract status
        $stmt = $db->prepare("
            SELECT ct.name, sc.is_signed
            FROM contract_templates ct
            LEFT JOIN staff_contracts sc ON ct.id = sc.template_id AND sc.staff_id = ?
            ORDER BY ct.id
        ");
        $stmt->execute([$user['id']]);
        $contracts = $stmt->fetchAll();
        
        echo "<h3>Contract Status:</h3>";
        echo "<ul>";
        foreach ($contracts as $contract) {
            $status = $contract['is_signed'] ? '✅ Signed' : '⏳ Unsigned';
            echo "<li><strong>" . htmlspecialchars($contract['name']) . ":</strong> " . $status . "</li>";
        }
        echo "</ul>";
        
        echo "<p><em>All contracts have been reset to unsigned for testing.</em></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Contract user not found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
