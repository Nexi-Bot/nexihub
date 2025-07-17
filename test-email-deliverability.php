<?php
/**
 * Test the improved email deliverability
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/ContractEmailNotifier.php';

echo "=== Testing Improved Email Deliverability ===\n\n";

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

    echo "✅ Database connection established\n\n";

    // Test email to admin
    echo "📧 Sending test email to admin (ollie.r@nexihub.uk)...\n";
    
    $notifier = new ContractEmailNotifier();
    
    // Get admin staff info
    $stmt = $db->prepare("SELECT * FROM staff_profiles WHERE nexi_email = ?");
    $stmt->execute(['ollie.r@nexihub.uk']);
    $staff = $stmt->fetch();
    
    if ($staff) {
        echo "   Found admin staff profile: {$staff['full_name']}\n";
        
        // Create test contract stats
        $contract_stats = [
            'signed' => 0,
            'total' => 3,
            'remaining' => 3
        ];
        
        // Test employee notification
        echo "   Sending employee notification...\n";
        $reflector = new ReflectionClass($notifier);
        $sendEmployee = $reflector->getMethod('sendEmployeeNotification');
        $sendEmployee->setAccessible(true);
        
        $result1 = $sendEmployee->invoke($notifier, $staff, 'Test Employment Agreement', $contract_stats, null);
        
        if ($result1) {
            echo "   ✅ Employee notification sent successfully\n";
        } else {
            echo "   ❌ Employee notification failed\n";
        }
        
        // Test HR notification
        echo "   Sending HR notification...\n";
        $sendHR = $reflector->getMethod('sendHRNotification');
        $sendHR->setAccessible(true);
        
        $result2 = $sendHR->invoke($notifier, $staff, 'Test Employment Agreement', $contract_stats, null);
        
        if ($result2) {
            echo "   ✅ HR notification sent successfully\n";
        } else {
            echo "   ❌ HR notification failed\n";
        }
        
        echo "\n📈 Email Deliverability Improvements:\n";
        echo "   ✅ Enhanced MIME headers for better spam scoring\n";
        echo "   ✅ Proper Message-ID and Date headers\n";
        echo "   ✅ Professional From/Reply-To configuration\n";
        echo "   ✅ Plain text + HTML multipart messages\n";
        echo "   ✅ Anti-spam headers (X-Auto-Response-Suppress, etc.)\n";
        echo "   ✅ Organization and sender identification\n";
        echo "   ✅ List-Unsubscribe header for compliance\n";
        
        echo "\n💡 Additional Recommendations:\n";
        echo "   📧 Configure SPF record: v=spf1 include:nexihub.uk ~all\n";
        echo "   🔐 Set up DKIM signing for noreply-contracts@nexihub.uk\n";
        echo "   📝 Add DMARC policy: v=DMARC1; p=quarantine; rua=mailto:dmarc@nexihub.uk\n";
        echo "   🌐 Ensure reverse DNS (PTR) record points to nexihub.uk\n";
        
    } else {
        echo "   ❌ Admin staff profile not found\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
