<?php
/**
 * Email debugging script
 * Tests different email sending methods to diagnose the issue
 */

require_once __DIR__ . '/config/config.php';

echo "<h2>Email System Debugging</h2>";

// Test 1: Basic mail() function
echo "<h3>Test 1: Basic mail() function</h3>";
$to = 'hr@nexihub.uk';
$subject = 'Test Email from Contract System';
$message = 'This is a test email to verify the mail system is working.';
$headers = 'From: noreply-contracts@nexihub.uk' . "\r\n" .
           'Reply-To: noreply-contracts@nexihub.uk' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

$success = mail($to, $subject, $message, $headers);

if ($success) {
    echo "<p style='color: green;'>✅ Basic mail() function succeeded</p>";
} else {
    echo "<p style='color: red;'>❌ Basic mail() function failed</p>";
}

// Test 2: Check PHP mail configuration
echo "<h3>Test 2: PHP Mail Configuration</h3>";
echo "<ul>";
echo "<li><strong>sendmail_path:</strong> " . ini_get('sendmail_path') . "</li>";
echo "<li><strong>SMTP:</strong> " . ini_get('SMTP') . "</li>";
echo "<li><strong>smtp_port:</strong> " . ini_get('smtp_port') . "</li>";
echo "<li><strong>sendmail_from:</strong> " . ini_get('sendmail_from') . "</li>";
echo "</ul>";

// Test 3: Error logging
echo "<h3>Test 3: Error Logging Test</h3>";
error_log("TEST: Email system debug - " . date('Y-m-d H:i:s'));
echo "<p>Check your PHP error log for this test message.</p>";

// Test 4: Contract email notification test with debugging
echo "<h3>Test 4: Contract Email Notification (with debug)</h3>";

try {
    // Get the contract user we just created
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
    
    $stmt = $db->prepare("SELECT id FROM staff_profiles WHERE nexi_email = 'contract@nexihub.uk'");
    $stmt->execute();
    $staff = $stmt->fetch();
    
    if ($staff) {
        echo "<p>Found contract user with ID: " . $staff['id'] . "</p>";
        
        // Create a fake signed contract for testing
        $signed_timestamp = date('Y-m-d H:i:s');
        $fake_signature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
        
        $stmt = $db->prepare("
            INSERT OR REPLACE INTO staff_contracts 
            (staff_id, template_id, signed_at, signature_data, is_signed, 
             signer_full_name, signer_position, signer_date_of_birth, is_under_17,
             guardian_full_name, guardian_email, guardian_signature_data, signed_timestamp) 
            VALUES (?, 1, ?, ?, 1, 'Contract User', 'Test Position', '1990-01-01', 0, NULL, NULL, NULL, ?)
        ");
        
        $stmt->execute([
            $staff['id'], 
            $signed_timestamp, 
            $fake_signature, 
            $signed_timestamp
        ]);
        
        echo "<p>Created test signed contract</p>";
        
        // Test the email notification
        require_once __DIR__ . '/includes/ContractEmailNotifier.php';
        $emailNotifier = new ContractEmailNotifier();
        
        echo "<p>Attempting to send email notification...</p>";
        $emailSent = $emailNotifier->sendContractSignedNotification($staff['id'], 'Test Contract', 1);
        
        if ($emailSent) {
            echo "<p style='color: green;'>✅ Email notification completed</p>";
        } else {
            echo "<p style='color: red;'>❌ Email notification failed</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Contract user not found</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h3>Debug Information</h3>";
echo "<p><strong>System:</strong> " . PHP_OS . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p><strong>Mail Function Available:</strong> " . (function_exists('mail') ? 'Yes' : 'No') . "</p>";

echo "<h3>Recommendations</h3>";
echo "<ul>";
echo "<li>Check your local mail server configuration (postfix, sendmail, etc.)</li>";
echo "<li>For production, configure SMTP authentication with your hosting provider</li>";
echo "<li>Check PHP error logs for mail-related errors</li>";
echo "<li>Verify that noreply-contracts@nexihub.uk is a valid email address</li>";
echo "<li>Test with a simple email first before using the contract system</li>";
echo "</ul>";
?>
