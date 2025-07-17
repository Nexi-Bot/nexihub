<?php
/**
 * Reset contracts for contract@nexihub.uk user
 * Remove all signed documents and set up 3 new ones to sign
 */

require_once __DIR__ . '/config/config.php';

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
    
    // Get the staff_id for contract@nexihub.uk
    $stmt = $db->prepare("SELECT staff_id FROM contract_users WHERE email = ?");
    $stmt->execute(['contract@nexihub.uk']);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception("contract@nexihub.uk user not found");
    }
    
    $staff_id = $user['staff_id'];
    echo "<h2>Resetting Contracts for contract@nexihub.uk</h2>";
    echo "<p><strong>Staff ID:</strong> $staff_id</p>";
    
    // Step 1: Remove all existing signed contracts for this user
    echo "<h3>Step 1: Removing existing signed contracts...</h3>";
    $stmt = $db->prepare("DELETE FROM staff_contracts WHERE staff_id = ?");
    $stmt->execute([$staff_id]);
    $deleted_count = $stmt->rowCount();
    echo "<p>✅ Removed $deleted_count existing contracts</p>";
    
    // Step 2: Clear existing contract templates to start fresh
    echo "<h3>Step 2: Setting up new contract templates...</h3>";
    
    // Clear existing templates
    $db->exec("DELETE FROM contract_templates");
    echo "<p>✅ Cleared existing contract templates</p>";
    
    // Step 3: Add 3 new contract templates
    $contracts = [
        [
            'name' => 'Employment Agreement',
            'type' => 'employment',
            'content' => '<h2>EMPLOYMENT AGREEMENT</h2>
            <p>This Employment Agreement ("Agreement") is entered into between Nexi Bot LTD, a company incorporated in England and Wales ("Company") and the employee ("Employee").</p>
            
            <h3>1. POSITION AND DUTIES</h3>
            <p>The Employee agrees to perform the duties and responsibilities as assigned by the Company in a professional and competent manner.</p>
            
            <h3>2. COMPENSATION</h3>
            <p>The Company shall pay the Employee compensation as agreed upon and detailed in the separate compensation schedule.</p>
            
            <h3>3. CONFIDENTIALITY</h3>
            <p>Employee acknowledges that they may have access to confidential information and agrees to maintain strict confidentiality.</p>
            
            <h3>4. TERM</h3>
            <p>This agreement shall commence on the start date and continue until terminated by either party in accordance with the terms herein.</p>
            
            <h3>5. GOVERNING LAW</h3>
            <p>This Agreement shall be governed by the laws of England and Wales.</p>
            
            <p><em>By signing below, both parties acknowledge they have read, understood, and agree to be bound by the terms of this Agreement.</em></p>'
        ],
        [
            'name' => 'Data Protection Policy',
            'type' => 'policy',
            'content' => '<h2>DATA PROTECTION POLICY ACKNOWLEDGMENT</h2>
            <p>Nexi Bot LTD is committed to protecting personal data in accordance with UK GDPR and Data Protection Act 2018.</p>
            
            <h3>1. SCOPE</h3>
            <p>This policy applies to all employees, contractors, and third parties who process personal data on behalf of Nexi Bot LTD.</p>
            
            <h3>2. DATA PROTECTION PRINCIPLES</h3>
            <ul>
                <li>Process data lawfully, fairly and transparently</li>
                <li>Collect data for specified, explicit and legitimate purposes</li>
                <li>Ensure data is adequate, relevant and limited to what is necessary</li>
                <li>Keep data accurate and up to date</li>
                <li>Retain data only as long as necessary</li>
                <li>Process data securely</li>
            </ul>
            
            <h3>3. EMPLOYEE RESPONSIBILITIES</h3>
            <p>All employees must:</p>
            <ul>
                <li>Handle personal data responsibly and securely</li>
                <li>Report any data breaches immediately</li>
                <li>Complete mandatory data protection training</li>
                <li>Follow all company data protection procedures</li>
            </ul>
            
            <h3>4. CONSEQUENCES OF NON-COMPLIANCE</h3>
            <p>Failure to comply with this policy may result in disciplinary action, including termination of employment.</p>
            
            <p><em>By signing, I acknowledge that I have read, understood, and agree to comply with this Data Protection Policy.</em></p>'
        ],
        [
            'name' => 'Health and Safety Guidelines',
            'type' => 'safety',
            'content' => '<h2>HEALTH AND SAFETY GUIDELINES</h2>
            <p>Nexi Bot LTD is committed to providing a safe and healthy working environment for all employees.</p>
            
            <h3>1. GENERAL SAFETY RESPONSIBILITIES</h3>
            <p>All employees are responsible for:</p>
            <ul>
                <li>Following all safety procedures and guidelines</li>
                <li>Reporting hazards, incidents, and near-misses</li>
                <li>Using personal protective equipment when required</li>
                <li>Participating in safety training programs</li>
            </ul>
            
            <h3>2. WORKPLACE SAFETY</h3>
            <p>Employees must:</p>
            <ul>
                <li>Maintain a clean and organized workspace</li>
                <li>Follow proper ergonomic practices</li>
                <li>Report any workplace injuries immediately</li>
                <li>Know the location of emergency exits and equipment</li>
            </ul>
            
            <h3>3. REMOTE WORK SAFETY</h3>
            <p>For remote workers:</p>
            <ul>
                <li>Ensure home workspace meets safety standards</li>
                <li>Take regular breaks to prevent strain</li>
                <li>Maintain proper lighting and ventilation</li>
                <li>Keep emergency contact information accessible</li>
            </ul>
            
            <h3>4. INCIDENT REPORTING</h3>
            <p>All incidents, accidents, and near-misses must be reported to management within 24 hours.</p>
            
            <h3>5. COMPLIANCE</h3>
            <p>This policy complies with the Health and Safety at Work Act 1974 and related regulations.</p>
            
            <p><em>By signing, I acknowledge that I have read, understood, and agree to follow these Health and Safety Guidelines.</em></p>'
        ]
    ];
    
    // Insert the new contract templates
    foreach ($contracts as $index => $contract) {
        $stmt = $db->prepare("
            INSERT INTO contract_templates (name, type, content, created_at) 
            VALUES (?, ?, ?, datetime('now'))
        ");
        $stmt->execute([$contract['name'], $contract['type'], $contract['content']]);
        echo "<p>✅ Added: " . htmlspecialchars($contract['name']) . "</p>";
    }
    
    echo "<h3>Step 3: Verification</h3>";
    
    // Verify the new contracts are available
    $stmt = $db->prepare("SELECT id, name, type FROM contract_templates ORDER BY id");
    $stmt->execute();
    $templates = $stmt->fetchAll();
    
    echo "<p><strong>Available contracts for signing:</strong></p>";
    echo "<ul>";
    foreach ($templates as $template) {
        echo "<li>" . htmlspecialchars($template['name']) . " (" . htmlspecialchars($template['type']) . ")</li>";
    }
    echo "</ul>";
    
    echo "<h3>✅ Reset Complete!</h3>";
    echo "<p>The contract@nexihub.uk user now has 3 new contracts available to sign:</p>";
    echo "<ol>";
    echo "<li><strong>Employment Agreement</strong> - Core employment terms</li>";
    echo "<li><strong>Data Protection Policy</strong> - GDPR compliance requirements</li>";
    echo "<li><strong>Health and Safety Guidelines</strong> - Workplace safety requirements</li>";
    echo "</ol>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li><a href='auto-login-contract-user.php'>Auto-login as contract@nexihub.uk</a></li>";
    echo "<li><a href='contracts/index.php'>Or login manually with: contract@nexihub.uk / nexitest123</a></li>";
    echo "<li>Sign the contracts and test the email notifications</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
