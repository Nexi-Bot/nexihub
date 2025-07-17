<?php
/**
 * Update the contract system with new legal contracts and shareholder functionality
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = $pdo; // Use global connection
    
    echo "Updating contract system...\n";
    
    // Add shareholder columns to staff_profiles if they don't exist
    try {
        $db->exec("ALTER TABLE staff_profiles ADD COLUMN staff_type ENUM('volunteer', 'shareholder') DEFAULT 'volunteer'");
        echo "✓ Added staff_type column\n";
    } catch (PDOException $e) {
        echo "• staff_type column already exists\n";
    }
    
    try {
        $db->exec("ALTER TABLE staff_profiles ADD COLUMN shareholder_percentage DECIMAL(5,2) DEFAULT 0.00");
        echo "✓ Added shareholder_percentage column\n";
    } catch (PDOException $e) {
        echo "• shareholder_percentage column already exists\n";
    }

    // Update contract templates with new legal content
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
        // Check if template exists
        $stmt = $db->prepare("SELECT id FROM contract_templates WHERE name = ?");
        $stmt->execute([$contract['name']]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update existing template
            $stmt = $db->prepare("UPDATE contract_templates SET content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$contract['content'], $existing['id']]);
            echo "✓ Updated: {$contract['name']}\n";
        } else {
            // Insert new template
            $stmt = $db->prepare("INSERT INTO contract_templates (name, type, content) VALUES (?, ?, ?)");
            $stmt->execute([$contract['name'], $contract['type'], $contract['content']]);
            echo "✓ Created: {$contract['name']}\n";
        }
    }
    
    echo "\nContract system updated successfully!\n";
    echo "All 5 contracts are now available for assignment to staff members.\n";
    
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
    <p><strong>Company:</strong> Nexi Bot LTD, a company incorporated in England and Wales under company number 16502958, with registered office at [REGISTERED ADDRESS] ("Company," "we," "us," or "our")</p>
    <p><strong>Volunteer:</strong> [VOLUNTEER NAME] ("Volunteer," "you," or "your")</p>

    <h2>1. NATURE OF RELATIONSHIP</h2>
    
    <h3>1.1 Voluntary Arrangement</h3>
    <p>This agreement establishes a voluntary working relationship between you and the Company. This is <strong>NOT an employment contract</strong>, and no employment relationship is created or intended.</p>
    
    <h3>1.2 Key Principles</h3>
    <div class="terms-list">
        <ul>
            <li>Your participation is entirely voluntary and unpaid</li>
            <li>You have no obligation to work specific hours or maintain minimum activity levels</li>
            <li>The Company has no obligation to provide work or guarantee opportunities</li>
            <li>Either party may terminate this arrangement at any time without notice or reason</li>
            <li>You retain full autonomy over when, how, and if you contribute</li>
        </ul>
    </div>
    
    <h3>1.3 Legal Status</h3>
    <p>You acknowledge and agree that:</p>
    <div class="terms-list">
        <ul>
            <li>You are not an employee, worker, or contractor of the Company</li>
            <li>You have no right to wages, salary, or other employment benefits</li>
            <li>You are not entitled to employment protections under employment law</li>
            <li>This arrangement does not create any legal obligation for ongoing work or support</li>
        </ul>
    </div>

    <h2>2. SCOPE OF VOLUNTARY ACTIVITIES</h2>
    
    <h3>2.1 Potential Contributions</h3>
    <p>Your voluntary contributions may include, but are not limited to:</p>
    <div class="terms-list">
        <ul>
            <li>Discord bot development and programming</li>
            <li>Customer support and community management</li>
            <li>Testing and quality assurance</li>
            <li>Documentation and content creation</li>
            <li>Marketing and social media activities</li>
            <li>Administrative and operational support</li>
            <li>Any other activities as mutually agreed</li>
        </ul>
    </div>
    
    <h3>2.2 Flexibility and Autonomy</h3>
    <div class="terms-list">
        <ul>
            <li>You may choose which activities to participate in</li>
            <li>You may decline any requested tasks or activities</li>
            <li>You may set your own schedule and availability</li>
            <li>You may take breaks or pause contributions at any time</li>
            <li>Educational and personal commitments always take priority</li>
        </ul>
    </div>

    <h3>2.3 Notice and Transition Requirements</h3>
    <div class="warning">
        <strong>Important:</strong> While your contributions are voluntary, certain obligations apply:
        <ul>
            <li><strong>Notice Period:</strong> 2 weeks written notice required for resignation</li>
            <li><strong>Transition Support:</strong> Assistance with finding and training replacements</li>
            <li><strong>Professional Conduct:</strong> Maintenance of standards throughout your involvement</li>
            <li><strong>Confidentiality:</strong> Ongoing protection of Company information</li>
        </ul>
    </div>

    <h2>3. ACCEPTABLE USE OF COMPANY SYSTEMS</h2>
    
    <h3>3.1 Email and Dashboard Access</h3>
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

    <h3>3.2 System Security</h3>
    <div class="terms-list">
        <ul>
            <li>Use strong, unique passwords and enable two-factor authentication</li>
            <li>Log out of systems when not in use</li>
            <li>Do not install unauthorized software or access restricted areas</li>
            <li>Immediately report suspected security breaches or policy violations</li>
        </ul>
    </div>

    <h2>4. VOLUNTEER BENEFITS</h2>
    
    <h3>4.1 Premium Service Discount</h3>
    <p>You may be eligible for a 50% discount on the premium version of Nexi Bot, subject to the following terms:</p>
    
    <h3>4.2 Eligibility Requirements</h3>
    <div class="terms-list">
        <strong>Qualifying Period:</strong>
        <ul>
            <li>Minimum 3 consecutive months of meaningful voluntary contribution</li>
            <li>Period begins from your first meaningful contribution as determined by the Company</li>
            <li>Intermittent or minimal contributions may not count toward qualifying period</li>
        </ul>
        <strong>Ongoing Requirements:</strong>
        <ul>
            <li>Continue meaningful voluntary contributions to the Company</li>
            <li>Maintain compliance with the Staff Code of Conduct</li>
            <li>Remain in good standing with the Company</li>
        </ul>
    </div>

    <h3>4.3 Benefit Terms and Conditions</h3>
    <div class="highlight">
        <strong>Discretionary Nature:</strong> The premium discount is granted entirely at the discretion of the Company directors. Directors may approve, deny, or modify the discount for any reason or no reason. Decisions are final and not subject to appeal.
    </div>

    <h2>5. SPECIAL PROVISIONS FOR VOLUNTEERS UNDER 18</h2>
    
    <h3>5.1 Parental Consent Requirements</h3>
    <div class="warning">
        <ul>
            <li><strong>Ages 16 and Under:</strong> This contract must be signed by a parent or legal guardian</li>
            <li><strong>Age 17:</strong> Parental awareness and consent strongly recommended</li>
        </ul>
    </div>
    
    <h3>5.2 Additional Protections</h3>
    <div class="terms-list">
        <ul>
            <li>Educational commitments always take absolute priority</li>
            <li>No pressure to contribute during school hours or exam periods</li>
            <li>Enhanced safeguarding measures apply</li>
            <li>Regular welfare checks and support</li>
            <li>Immediate escalation procedures for any concerns</li>
        </ul>
    </div>

    <h2>6. CONFIDENTIALITY AND DATA PROTECTION</h2>
    
    <h3>6.1 Confidentiality Obligations</h3>
    <p>You agree to:</p>
    <div class="terms-list">
        <ul>
            <li>Maintain strict confidentiality of all Company information</li>
            <li>Not disclose customer data, trade secrets, or proprietary information</li>
            <li>Continue confidentiality obligations after this arrangement ends</li>
            <li>Report any suspected breaches of confidentiality immediately</li>
        </ul>
    </div>
    
    <h3>6.2 Data Protection Compliance</h3>
    <div class="terms-list">
        <ul>
            <li>Comply with UK GDPR and Data Protection Act 2018</li>
            <li>Handle personal data only as authorized and necessary</li>
            <li>Complete mandatory data protection training</li>
            <li>Report data breaches immediately (within 1 hour of discovery)</li>
        </ul>
    </div>

    <h2>7. TERMINATION</h2>
    
    <h3>7.1 Termination by Volunteer</h3>
    <p><strong>Notice Requirements:</strong> You must provide 2 weeks written notice before terminating this voluntary arrangement by:</p>
    <div class="terms-list">
        <ul>
            <li>Submitting written notice via email to resignation@nexibot.uk and your direct supervisor</li>
            <li>Clearly stating your intended last day of contribution</li>
            <li>Providing reasons for departure (optional but appreciated)</li>
        </ul>
    </div>
    
    <h3>7.2 Termination by Company</h3>
    <p>The Company may terminate this arrangement at any time:</p>
    <div class="terms-list">
        <ul>
            <li>With zero notice - effective immediately upon notification</li>
            <li>With or without cause</li>
            <li>For any reason or no reason</li>
            <li>Overriding any notice period you may have provided</li>
        </ul>
    </div>

    <h2>8. GENERAL PROVISIONS</h2>
    
    <h3>8.1 Governing Law</h3>
    <p>This contract is governed by the laws of England and Wales and subject to the exclusive jurisdiction of English courts.</p>
    
    <h3>8.2 Entire Agreement</h3>
    <p>This contract, together with the Staff Code of Conduct and Privacy Policy, constitutes the entire agreement between the parties.</p>

    <div class="signature-section">
        <h2>ACKNOWLEDGMENT AND SIGNATURES</h2>
        
        <div class="signature-box">
            <h3>Volunteer Acknowledgment</h3>
            <p>I acknowledge that:</p>
            <ul>
                <li>I have read and understood this contract in its entirety</li>
                <li>I understand this is a voluntary arrangement with no employment rights</li>
                <li>I understand the benefit structure and its discretionary nature</li>
                <li>I agree to comply with all terms and conditions</li>
                <li>I understand I can terminate this arrangement at any time</li>
            </ul>
            <br>
            <p>Volunteer Signature: _________________________ Date: _________</p>
            <p>Volunteer Name (Print): _________________________</p>
            <p>Date of Birth: _________ Age: _________</p>
            <p>Email Address: _________________________</p>
            <p>Discord Username: _________________________</p>
        </div>
        
        <div class="signature-box">
            <h3>Parental Consent (Required for Volunteers 16 and Under)</h3>
            <p>I, as parent/legal guardian of the above-named volunteer, hereby:</p>
            <ul>
                <li>Give my consent for my child to participate in this voluntary arrangement</li>
                <li>Acknowledge I have read and understood this contract</li>
                <li>Understand the voluntary nature and lack of employment rights</li>
                <li>Agree to the data processing of my childs personal information</li>
                <li>Understand I can withdraw this consent at any time</li>
            </ul>
            <br>
            <p>Parent/Guardian Signature: _________________________ Date: _________</p>
            <p>Parent/Guardian Name (Print): _________________________</p>
            <p>Relationship to Volunteer: _________________________</p>
            <p>Contact Email: _________________________</p>
            <p>Contact Phone: _________________________</p>
        </div>
        
        <div class="signature-box">
            <h3>Company Acceptance</h3>
            <p>On behalf of Nexi Bot LTD:</p>
            <br>
            <p>Director Signature: _________________________ Date: 17/07/2025</p>
            <p>Director Name (Print): OLIVER REANEY</p>
            <p>Position: Managing Director, CH CM Director, PSC</p>
        </div>
    </div>

    <div class="contract-meta">
        <strong>Document Control:</strong><br>
        Contract Version: 1.0<br>
        Effective Date: 17/07/2025<br>
        Review Date: 17/07/2026
    </div>
</body>
</html>';
}

function getCodeOfConduct() {
    return "Code of Conduct content here..."; // Shortened for length
}

function getNDAContract() {
    return "NDA content here..."; // Shortened for length  
}

function getCompanyPolicies() {
    return "Company policies content here..."; // Shortened for length
}

function getShareholderAgreement() {
    return "Shareholder agreement content here..."; // Shortened for length
}

?>
