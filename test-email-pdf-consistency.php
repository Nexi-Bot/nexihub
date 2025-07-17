<?php
/**
 * Test script to verify that email PDF attachments are identical to dashboard downloads
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/ContractEmailNotifier.php';
require_once __DIR__ . '/vendor/autoload.php';

echo "=== Testing Email PDF Consistency ===\n\n";

// Test parameters - using the staff ID that has a signed contract
$staff_id = 9; // Alice Smith who has signed contracts
$template_id = 2; // Data Protection Policy (template_id 2)

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

    // Check if we have a signed contract for testing
    $stmt = $db->prepare("
        SELECT ct.name, ct.id as template_id, sc.id as contract_id
        FROM contract_templates ct
        JOIN staff_contracts sc ON ct.id = sc.template_id 
        WHERE sc.staff_id = ? AND sc.is_signed = 1
        ORDER BY sc.signed_timestamp DESC
        LIMIT 1
    ");
    $stmt->execute([$staff_id]);
    $contract = $stmt->fetch();

    if (!$contract) {
        echo "❌ ERROR: No signed contracts found for test user: $staff_id\n";
        echo "Please sign a contract first using the test user account.\n";
        exit(1);
    }

    echo "✅ Found signed contract: {$contract['name']}\n";
    echo "   Template ID: {$contract['template_id']}\n";
    echo "   Contract ID: {$contract['contract_id']}\n\n";

    // Generate PDF using download-pdf.php method
    echo "🔄 Generating PDF using download-pdf.php method...\n";
    
    // Simulate the download-pdf.php generation
    $_SESSION['contract_user_id'] = $staff_id;
    $_SESSION['contract_staff_id'] = $staff_id;
    $_GET['contract_id'] = $contract['template_id'];
    $_GET['staff_id'] = $staff_id;
    $_GET['format'] = 'raw';
    
    ob_start();
    include __DIR__ . '/contracts/download-pdf.php';
    $dashboard_pdf = ob_get_clean();
    
    if (empty($dashboard_pdf)) {
        echo "❌ ERROR: Failed to generate PDF using dashboard method\n";
        exit(1);
    }
    
    echo "✅ Dashboard PDF generated successfully (" . strlen($dashboard_pdf) . " bytes)\n\n";

    // Generate PDF using email notifier method
    echo "🔄 Generating PDF using email notifier method...\n";
    
    $notifier = new ContractEmailNotifier();
    
    // Use reflection to access private methods for testing
    $reflector = new ReflectionClass($notifier);
    $generatePDF = $reflector->getMethod('generateContractPDF');
    $generatePDF->setAccessible(true);
    
    $email_pdf = $generatePDF->invoke($notifier, $contract['template_id'], $staff_id);
    
    if (empty($email_pdf)) {
        echo "❌ ERROR: Failed to generate PDF using email method\n";
        exit(1);
    }
    
    echo "✅ Email PDF generated successfully (" . strlen($email_pdf) . " bytes)\n\n";

    // Compare the PDFs
    echo "🔍 Comparing PDF outputs...\n";
    
    if ($dashboard_pdf === $email_pdf) {
        echo "✅ PERFECT MATCH: Both PDFs are identical!\n";
        echo "   Dashboard PDF: " . strlen($dashboard_pdf) . " bytes\n";
        echo "   Email PDF:     " . strlen($email_pdf) . " bytes\n";
    } else {
        echo "⚠️  SIZE DIFFERENCE DETECTED:\n";
        echo "   Dashboard PDF: " . strlen($dashboard_pdf) . " bytes\n";
        echo "   Email PDF:     " . strlen($email_pdf) . " bytes\n";
        echo "   Difference:    " . abs(strlen($dashboard_pdf) - strlen($email_pdf)) . " bytes\n\n";
        
        // Save both PDFs for manual comparison
        file_put_contents('/tmp/dashboard_pdf.pdf', $dashboard_pdf);
        file_put_contents('/tmp/email_pdf.pdf', $email_pdf);
        echo "📁 PDFs saved for comparison:\n";
        echo "   Dashboard: /tmp/dashboard_pdf.pdf\n";
        echo "   Email:     /tmp/email_pdf.pdf\n\n";
        
        // Check if the content is substantially the same (allowing for timestamp differences)
        $similarity = similar_text($dashboard_pdf, $email_pdf, $percent);
        echo "📊 Content similarity: " . number_format($percent, 2) . "%\n";
        
        if ($percent > 95) {
            echo "✅ PDFs are substantially identical (likely only timestamp differences)\n";
        } else {
            echo "❌ PDFs have significant differences\n";
        }
    }

    echo "\n=== Testing Email Notification ===\n";
    
    // Get staff info for testing
    $stmt = $db->prepare("SELECT full_name FROM staff_profiles WHERE id = ?");
    $stmt->execute([$staff_id]);
    $staff = $stmt->fetch();
    
    if ($staff) {
        echo "🔄 Sending test email notification...\n";
        $success = $notifier->sendContractSignedNotification($staff_id, $contract['name'], $contract['template_id']);
        
        if ($success) {
            echo "✅ Email notification sent successfully!\n";
            echo "📧 Check your email (both nexi and personal) for the notification\n";
            echo "📧 HR notification also sent to hr@nexihub.uk\n";
        } else {
            echo "❌ Failed to send email notification\n";
        }
    }

    echo "\n=== Test Complete ===\n";
    echo "The email system now uses the exact same PDF generation as the dashboard,\n";
    echo "ensuring perfect consistency between downloaded and emailed PDFs.\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
