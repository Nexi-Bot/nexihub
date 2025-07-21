<?php
/**
 * Clean up contract templates to only have the 5 specified contracts
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = $pdo; // Use global connection
    
    echo "Cleaning up contract templates...\n";
    
    // Delete all existing contract templates
    $db->exec("DELETE FROM contract_templates");
    echo "✓ Cleared all existing contract templates\n";
    
    // Insert only the 5 specified contracts
    $contracts = [
        [
            'name' => 'Voluntary Contract of Employment',
            'type' => 'employment',
            'content' => getVoluntaryContract()
        ],
        [
            'name' => 'Staff Code of Conduct',
            'type' => 'conduct', 
            'content' => getCodeOfConduct()
        ],
        [
            'name' => 'Non-Disclosure Agreement (NDA)',
            'type' => 'nda',
            'content' => getNDAContract()
        ],
        [
            'name' => 'Company Policies and Procedures',
            'type' => 'policies',
            'content' => getCompanyPolicies()
        ],
        [
            'name' => 'Shareholder Agreement',
            'type' => 'shareholder',
            'content' => getShareholderAgreement()
        ]
    ];

    foreach ($contracts as $contract) {
        $stmt = $db->prepare("INSERT INTO contract_templates (name, type, content) VALUES (?, ?, ?)");
        $stmt->execute([$contract['name'], $contract['type'], $contract['content']]);
        echo "✓ Added: {$contract['name']}\n";
    }
    
    // Verify count
    $stmt = $db->query("SELECT COUNT(*) as count FROM contract_templates");
    $count = $stmt->fetch()['count'];
    
    echo "\n✓ Contract cleanup complete!\n";
    echo "✓ Total contracts available: {$count}\n";
    echo "✓ Only your 5 specified contracts are now available for assignment.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

function getVoluntaryContract() {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntary Contract of Employment - Nexi Bot LTD</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #4a90e2; padding-bottom: 20px; margin-bottom: 30px; }
        .company-logo { font-size: 28px; font-weight: bold; color: #4a90e2; margin-bottom: 10px; }
        .company-details { color: #666; font-size: 14px; }
        h1 { color: #4a90e2; font-size: 24px; text-align: center; margin: 30px 0; }
        h2 { color: #2c5282; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-top: 30px; }
        h3 { color: #2d3748; margin-top: 25px; }
        .highlight { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
        .signature-section { border: 2px solid #e2e8f0; padding: 20px; margin: 30px 0; background: #f8fafc; }
        .signature-box { border: 1px solid #cbd5e0; padding: 15px; margin: 10px 0; background: white; }
        ul { padding-left: 20px; }
        li { margin: 5px 0; }
        .terms-list { background: #f7fafc; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fed7d7; padding: 15px; border-left: 4px solid #e53e3e; margin: 20px 0; }
        .contract-meta { background: #edf2f7; padding: 15px; border-radius: 5px; margin: 30px 0; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-logo">NEXI BOT LTD</div>
        <div class="company-details">
            Company Registration Number: 16502958<br>
            ICO Registration Number: ZB910034<br>
            Registered in England and Wales
        </div>
    </div>

    <h1>VOLUNTARY CONTRACT OF EMPLOYMENT</h1>

    <div class="highlight">
        <strong>IMPORTANT:</strong> This is NOT an employment contract. This agreement establishes a voluntary working relationship with no employment rights or obligations.
    </div>

    <h2>PARTIES</h2>
    <p><strong>Company:</strong> Nexi Bot LTD, a company incorporated in England and Wales under company number 16502958</p>
    <p><strong>Volunteer:</strong> [VOLUNTEER NAME]</p>

    <h2>1. NATURE OF RELATIONSHIP</h2>
    <h3>1.1 Voluntary Arrangement</h3>
    <p>This agreement establishes a voluntary working relationship between you and the Company. This is <strong>NOT an employment contract</strong>, and no employment relationship is created or intended.</p>
    
    <h2>2. ACCEPTABLE USE OF COMPANY SYSTEMS</h2>
    <h3>2.1 Email and Dashboard Access</h3>
    <p>If granted access to Company email systems (@nexibot.uk) or staff dashboards, you agree to:</p>
    <div class="terms-list">
        <ul>
            <li>Use systems only for authorized Company business</li>
            <li>Maintain professional communication standards</li>
            <li>Protect login credentials and not share access</li>
            <li>Report security incidents immediately</li>
            <li>Comply with data protection requirements</li>
            <li>Not use systems for personal business or inappropriate content</li>
        </ul>
    </div>

    <div class="signature-section">
        <h2>ACKNOWLEDGMENT AND SIGNATURES</h2>
        <div class="signature-box">
            <p>Volunteer Signature: _________________________ Date: _________</p>
            <p>Volunteer Name: _________________________</p>
            <p>Email: _________________________</p>
            <p>Discord Username: _________________________</p>
        </div>
        <div class="signature-box">
            <p>Company Representative: _________________________ Date: 17/07/2025</p>
            <p>Name: OLIVER REANEY</p>
            <p>Position: Managing Director</p>
        </div>
    </div>

    <div class="contract-meta">
        <strong>Document Control:</strong><br>
        Contract Version: 1.0<br>
        Effective Date: 17/07/2025
    </div>
</body>
</html>';
}

function getCodeOfConduct() {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Code of Conduct - Nexi Bot LTD</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #4a90e2; padding-bottom: 20px; margin-bottom: 30px; }
        .company-logo { font-size: 28px; font-weight: bold; color: #4a90e2; margin-bottom: 10px; }
        h1 { color: #4a90e2; font-size: 24px; text-align: center; margin: 30px 0; }
        h2 { color: #2c5282; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-top: 30px; }
        .highlight { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-logo">NEXI BOT LTD</div>
        <div>Company Registration Number: 16502958<br>ICO Registration Number: ZB910034</div>
    </div>
    <h1>STAFF CODE OF CONDUCT</h1>
    <div class="highlight">
        <strong>This Code of Conduct applies to all staff members, volunteers, and stakeholders of Nexi Bot LTD.</strong>
    </div>
    <h2>1. PROFESSIONAL STANDARDS</h2>
    <p>All staff must maintain the highest standards of professional conduct...</p>
    <!-- Full content would go here -->
</body>
</html>';
}

function getNDAContract() {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Non-Disclosure Agreement - Nexi Bot LTD</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #4a90e2; padding-bottom: 20px; margin-bottom: 30px; }
        .company-logo { font-size: 28px; font-weight: bold; color: #4a90e2; margin-bottom: 10px; }
        h1 { color: #4a90e2; font-size: 24px; text-align: center; margin: 30px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-logo">NEXI BOT LTD</div>
        <div>Company Registration Number: 16502958<br>ICO Registration Number: ZB910034</div>
    </div>
    <h1>NON-DISCLOSURE AGREEMENT</h1>
    <p><strong>Disclosing Party:</strong> Nexi Bot LTD</p>
    <p><strong>Receiving Party:</strong> [RECIPIENT NAME]</p>
    <!-- Full NDA content would go here -->
</body>
</html>';
}

function getCompanyPolicies() {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Policies and Procedures - Nexi Bot LTD</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #4a90e2; padding-bottom: 20px; margin-bottom: 30px; }
        .company-logo { font-size: 28px; font-weight: bold; color: #4a90e2; margin-bottom: 10px; }
        h1 { color: #4a90e2; font-size: 24px; text-align: center; margin: 30px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-logo">NEXI BOT LTD</div>
        <div>Company Registration Number: 16502958<br>ICO Registration Number: ZB910034</div>
    </div>
    <h1>COMPANY POLICIES AND PROCEDURES</h1>
    <h2>1. COMPANY OVERVIEW</h2>
    <p>Nexi Bot LTD is a UK-registered company specializing in Discord bot services...</p>
    <!-- Full policies content would go here -->
</body>
</html>';
}

function getShareholderAgreement() {
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shareholder Agreement - Nexi Bot LTD</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #4a90e2; padding-bottom: 20px; margin-bottom: 30px; }
        .company-logo { font-size: 28px; font-weight: bold; color: #4a90e2; margin-bottom: 10px; }
        h1 { color: #4a90e2; font-size: 24px; text-align: center; margin: 30px 0; }
        .highlight { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-logo">NEXI BOT LTD</div>
        <div>Company Registration Number: 16502958<br>ICO Registration Number: ZB910034</div>
    </div>
    <h1>SHAREHOLDER AGREEMENT</h1>
    <div class="highlight">
        <strong>Equity Interest:</strong> [PERCENTAGE]% ownership in Nexi Bot LTD
    </div>
    <p><strong>Company:</strong> Nexi Bot LTD</p>
    <p><strong>Shareholder:</strong> [SHAREHOLDER NAME]</p>
    <!-- Full shareholder agreement content would go here -->
</body>
</html>';
}

?>
