<?php
/**
 * Test script for email notifications
 * This tests the email notification system when a contract is signed
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/ContractEmailNotifier.php';

echo "<h2>Testing Contract Email Notifications</h2>";

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
    
    // Find a test staff member with signed contracts
    $stmt = $db->prepare("
        SELECT sp.id as staff_id, sp.full_name, sp.nexi_email, sp.private_email,
               ct.id as template_id, ct.name as contract_name,
               sc.signed_timestamp
        FROM staff_profiles sp
        JOIN staff_contracts sc ON sp.id = sc.staff_id
        JOIN contract_templates ct ON sc.template_id = ct.id
        WHERE sc.is_signed = 1
        ORDER BY sc.signed_timestamp DESC
        LIMIT 1
    ");
    $stmt->execute();
    $test_contract = $stmt->fetch();
    
    if (!$test_contract) {
        echo "<p style='color: orange;'>⚠️ No signed contracts found. Please sign a contract first.</p>";
        echo "<p><a href='contracts/dashboard.php'>Go to Contract Dashboard</a></p>";
        exit;
    }
    
    echo "<h3>Found Test Data:</h3>";
    echo "<ul>";
    echo "<li><strong>Staff:</strong> " . htmlspecialchars($test_contract['full_name']) . "</li>";
    echo "<li><strong>Email:</strong> " . htmlspecialchars($test_contract['nexi_email']) . "</li>";
    echo "<li><strong>Contract:</strong> " . htmlspecialchars($test_contract['contract_name']) . "</li>";
    echo "<li><strong>Signed:</strong> " . htmlspecialchars($test_contract['signed_timestamp']) . "</li>";
    echo "</ul>";
    
    // Test the email notifier
    echo "<h3>Testing Email Notification...</h3>";
    
    $emailNotifier = new ContractEmailNotifier();
    $result = $emailNotifier->sendContractSignedNotification(
        $test_contract['staff_id'], 
        $test_contract['contract_name'], 
        $test_contract['template_id']
    );
    
    if ($result) {
        echo "<p style='color: green;'>✅ <strong>Email notification system test successful!</strong></p>";
        echo "<p>Emails should have been sent to:</p>";
        echo "<ul>";
        if ($test_contract['nexi_email']) {
            echo "<li>" . htmlspecialchars($test_contract['nexi_email']) . " (Nexi email)</li>";
        }
        if ($test_contract['private_email']) {
            echo "<li>" . htmlspecialchars($test_contract['private_email']) . " (Private email)</li>";
        }
        echo "<li>hr@nexihub.uk (HR notification)</li>";
        echo "</ul>";
        echo "<p><em>Note: Check your email server configuration and mail logs to ensure emails are being delivered.</em></p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>Email notification failed!</strong></p>";
        echo "<p>Check the error logs for more details.</p>";
    }
    
    echo "<h3>Manual Integration Test</h3>";
    echo "<p>To test the integration with contract signing:</p>";
    echo "<ol>";
    echo "<li><a href='contracts/dashboard.php'>Go to Contract Dashboard</a></li>";
    echo "<li>Sign a new contract (or re-sign an existing one)</li>";
    echo "<li>Check if you receive email notifications</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h3>Email Configuration</h3>";
echo "<p>Current email configuration (update in ContractEmailNotifier.php):</p>";
echo "<ul>";
echo "<li><strong>SMTP Host:</strong> webmail.nexihub.uk</li>";
echo "<li><strong>SMTP Port:</strong> 587</li>";
echo "<li><strong>From Email:</strong> noreply-contracts@nexihub.uk</li>";
echo "<li><strong>HR Email:</strong> hr@nexihub.uk</li>";
echo "</ul>";
echo "<p><em>⚠️ Remember to update the SMTP password in ContractEmailNotifier.php for production use.</em></p>";
?>
