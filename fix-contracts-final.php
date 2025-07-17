<?php
require_once 'config/config.php';

try {
    $db = new PDO('sqlite:' . __DIR__ . '/database/nexihub.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Fixing contract templates table...\n";
    
    // Add is_assignable column if it doesn't exist
    try {
        $db->exec("ALTER TABLE contract_templates ADD COLUMN is_assignable INTEGER DEFAULT 1");
        echo "Added is_assignable column\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'duplicate column name') === false) {
            echo "Note: is_assignable column may already exist or error: " . $e->getMessage() . "\n";
        }
    }
    
    // Clear all existing contracts
    $db->exec("DELETE FROM contract_templates");
    echo "Cleared existing contracts\n";
    
    // Insert the 5 required UK-legal contracts
    $contracts = [
        [
            'name' => 'Voluntary Contract of Employment',
            'type' => 'employment',
            'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntary Contract of Employment</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin: 0; }
        .section { margin-bottom: 25px; }
        .section h2 { color: #34495e; border-left: 4px solid #3498db; padding-left: 15px; }
        .highlight { background-color: #ecf0f1; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .signature-section { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature-box { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>VOLUNTARY CONTRACT OF EMPLOYMENT</h1>
        <p><strong>NexiHub Limited</strong></p>
    </div>

    <div class="section">
        <h2>1. Parties</h2>
        <p>This agreement is between NexiHub Limited (the "Company") and [Employee Name] (the "Employee").</p>
    </div>

    <div class="section">
        <h2>2. Employment Terms</h2>
        <div class="highlight">
            <p><strong>Position:</strong> [Job Title]</p>
            <p><strong>Start Date:</strong> [Start Date]</p>
            <p><strong>Employment Type:</strong> Voluntary</p>
        </div>
    </div>

    <div class="section">
        <h2>3. Duties and Responsibilities</h2>
        <p>The Employee agrees to perform duties as assigned by the Company in accordance with UK employment law and company policies.</p>
    </div>

    <div class="section">
        <h2>4. Confidentiality</h2>
        <p>The Employee agrees to maintain strict confidentiality regarding all company information and intellectual property.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Employee Signature</p>
            <p>Date: ___________</p>
        </div>
        <div class="signature-box">
            <p>Company Representative</p>
            <p>Date: ___________</p>
        </div>
    </div>
</body>
</html>',
            'is_assignable' => 1
        ],
        [
            'name' => 'Staff Code of Conduct',
            'type' => 'conduct',
            'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Code of Conduct</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #e74c3c; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #e74c3c; margin: 0; }
        .section { margin-bottom: 25px; }
        .section h2 { color: #c0392b; border-left: 4px solid #e74c3c; padding-left: 15px; }
        .rule { background-color: #fdf2f2; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 3px solid #e74c3c; }
        .signature-section { margin-top: 40px; text-align: center; }
        .signature-box { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 10px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>STAFF CODE OF CONDUCT</h1>
        <p><strong>NexiHub Limited</strong></p>
    </div>

    <div class="section">
        <h2>1. Professional Standards</h2>
        <div class="rule">
            <p>All staff must maintain the highest standards of professional conduct in accordance with UK employment regulations.</p>
        </div>
    </div>

    <div class="section">
        <h2>2. Workplace Behavior</h2>
        <div class="rule">
            <p>Respectful communication and collaboration with colleagues, clients, and stakeholders is mandatory.</p>
        </div>
    </div>

    <div class="section">
        <h2>3. Compliance</h2>
        <div class="rule">
            <p>All staff must comply with UK law, company policies, and industry regulations.</p>
        </div>
    </div>

    <div class="section">
        <h2>4. Consequences</h2>
        <p>Violation of this code may result in disciplinary action up to and including termination of employment.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Employee Acknowledgment</p>
            <p>Date: ___________</p>
        </div>
    </div>
</body>
</html>',
            'is_assignable' => 1
        ],
        [
            'name' => 'Non-Disclosure Agreement (NDA)',
            'type' => 'nda',
            'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Disclosure Agreement</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #9b59b6; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #9b59b6; margin: 0; }
        .section { margin-bottom: 25px; }
        .section h2 { color: #8e44ad; border-left: 4px solid #9b59b6; padding-left: 15px; }
        .confidential { background-color: #f4f1f8; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #9b59b6; }
        .signature-section { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature-box { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NON-DISCLOSURE AGREEMENT</h1>
        <p><strong>NexiHub Limited</strong></p>
    </div>

    <div class="section">
        <h2>1. Confidential Information</h2>
        <div class="confidential">
            <p>This agreement covers all proprietary information, trade secrets, client data, and business processes of NexiHub Limited.</p>
        </div>
    </div>

    <div class="section">
        <h2>2. Obligations</h2>
        <p>The undersigned agrees to maintain strict confidentiality and not disclose any confidential information to third parties.</p>
    </div>

    <div class="section">
        <h2>3. Duration</h2>
        <p>This agreement remains in effect during employment and for a period of 2 years after termination.</p>
    </div>

    <div class="section">
        <h2>4. Legal Compliance</h2>
        <p>This agreement is governed by UK law and complies with the Data Protection Act 2018 and GDPR.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Employee Signature</p>
            <p>Date: ___________</p>
        </div>
        <div class="signature-box">
            <p>Company Representative</p>
            <p>Date: ___________</p>
        </div>
    </div>
</body>
</html>',
            'is_assignable' => 1
        ],
        [
            'name' => 'Company Policies',
            'type' => 'policies',
            'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Policies</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #f39c12; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #f39c12; margin: 0; }
        .section { margin-bottom: 25px; }
        .section h2 { color: #e67e22; border-left: 4px solid #f39c12; padding-left: 15px; }
        .policy { background-color: #fef9e7; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 3px solid #f39c12; }
        .signature-section { margin-top: 40px; text-align: center; }
        .signature-box { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 10px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>COMPANY POLICIES</h1>
        <p><strong>NexiHub Limited</strong></p>
    </div>

    <div class="section">
        <h2>1. Equal Opportunities</h2>
        <div class="policy">
            <p>NexiHub Limited is committed to equal opportunities for all employees regardless of age, gender, race, religion, or disability.</p>
        </div>
    </div>

    <div class="section">
        <h2>2. Health and Safety</h2>
        <div class="policy">
            <p>All staff must comply with health and safety regulations as required by UK law and company procedures.</p>
        </div>
    </div>

    <div class="section">
        <h2>3. Data Protection</h2>
        <div class="policy">
            <p>All personal and client data must be handled in accordance with GDPR and the Data Protection Act 2018.</p>
        </div>
    </div>

    <div class="section">
        <h2>4. Disciplinary Procedures</h2>
        <div class="policy">
            <p>The company follows ACAS guidelines for all disciplinary and grievance procedures.</p>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Employee Acknowledgment</p>
            <p>Date: ___________</p>
        </div>
    </div>
</body>
</html>',
            'is_assignable' => 1
        ],
        [
            'name' => 'Shareholder Agreement',
            'type' => 'shareholder',
            'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shareholder Agreement</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #27ae60; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #27ae60; margin: 0; }
        .section { margin-bottom: 25px; }
        .section h2 { color: #229954; border-left: 4px solid #27ae60; padding-left: 15px; }
        .shareholding { background-color: #eafaf1; padding: 15px; border-radius: 5px; margin: 10px 0; border: 1px solid #27ae60; }
        .signature-section { margin-top: 40px; display: flex; justify-content: space-between; }
        .signature-box { border-top: 1px solid #333; width: 200px; text-align: center; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SHAREHOLDER AGREEMENT</h1>
        <p><strong>NexiHub Limited</strong></p>
    </div>

    <div class="section">
        <h2>1. Share Allocation</h2>
        <div class="shareholding">
            <p><strong>Shareholder:</strong> [Name]</p>
            <p><strong>Share Percentage:</strong> [Percentage]%</p>
            <p><strong>Share Class:</strong> Ordinary Shares</p>
        </div>
    </div>

    <div class="section">
        <h2>2. Rights and Obligations</h2>
        <p>This agreement outlines the rights, obligations, and responsibilities of shareholders in NexiHub Limited.</p>
    </div>

    <div class="section">
        <h2>3. Governance</h2>
        <p>Shareholders agree to participate in company governance in accordance with the Companies Act 2006.</p>
    </div>

    <div class="section">
        <h2>4. Transfer Restrictions</h2>
        <p>Any transfer of shares must be approved by the board of directors and comply with UK company law.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Shareholder Signature</p>
            <p>Date: ___________</p>
        </div>
        <div class="signature-box">
            <p>Company Representative</p>
            <p>Date: ___________</p>
        </div>
    </div>
</body>
</html>',
            'is_assignable' => 1
        ]
    ];
    
    foreach ($contracts as $contract) {
        $stmt = $db->prepare("INSERT INTO contract_templates (name, type, content, is_assignable) VALUES (?, ?, ?, ?)");
        $stmt->execute([$contract['name'], $contract['type'], $contract['content'], $contract['is_assignable']]);
        echo "Added contract: " . $contract['name'] . "\n";
    }
    
    echo "\nContract templates updated successfully!\n";
    
    // Verify the results
    echo "\nFinal verification:\n";
    echo "==================\n";
    $stmt = $db->query('SELECT id, name, is_assignable FROM contract_templates ORDER BY id');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("ID: %d | Name: %s | Assignable: %s\n", 
            $row['id'], 
            $row['name'], 
            $row['is_assignable'] ? 'Yes' : 'No'
        );
    }
    
    $count = $db->query('SELECT COUNT(*) FROM contract_templates WHERE is_assignable = 1')->fetchColumn();
    echo "\nTotal assignable contracts: " . $count . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
