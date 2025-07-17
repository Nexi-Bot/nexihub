<?php
require_once __DIR__ . '/config/config.php';

echo "<h2>Fixing Contract Content Formatting</h2>";

try {
    // Database connection
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

    // Clean and professional contract templates
    $contracts = [
        [
            'name' => 'Employment Agreement',
            'type' => 'employment',
            'content' => 'EMPLOYMENT AGREEMENT

This Employment Agreement ("Agreement") is entered into between Nexi Bot LTD, a company incorporated under the laws of England and Wales (Company Registration: 16502958, ICO Registration: ZB910034) ("Company"), and the undersigned employee ("Employee").

ARTICLE 1: EMPLOYMENT TERMS

1.1 Position and Duties
The Employee agrees to serve the Company in the capacity set forth in the employee profile. The Employee shall perform such duties and responsibilities as may be assigned by the Company from time to time, consistent with the Employee\'s position.

1.2 Employment Relationship
This Agreement establishes an employment relationship between the parties. The Employee acknowledges that employment with the Company is at-will and may be terminated by either party at any time, with or without cause, and with or without notice.

1.3 Compensation
The Employee\'s compensation shall be as set forth in the Company\'s compensation policies and may be modified from time to time at the Company\'s discretion.

ARTICLE 2: CONFIDENTIALITY AND PROPRIETARY INFORMATION

2.1 Confidential Information
Employee acknowledges that during employment, Employee may have access to confidential and proprietary information of the Company. Employee agrees to maintain the confidentiality of such information and not to disclose it to any third party without prior written consent of the Company.

2.2 Return of Property
Upon termination of employment, Employee agrees to return all Company property, including but not limited to documents, equipment, and confidential information.

ARTICLE 3: GENERAL PROVISIONS

3.1 Governing Law
This Agreement shall be governed by and construed in accordance with the laws of England and Wales.

3.2 Entire Agreement
This Agreement constitutes the entire agreement between the parties and supersedes all prior negotiations, representations, or agreements relating to the subject matter hereof.

3.3 Amendments
This Agreement may only be amended in writing, signed by both parties.

By signing below, the parties acknowledge that they have read, understood, and agree to be bound by the terms of this Agreement.'
        ],
        [
            'name' => 'Non-Disclosure Agreement (NDA)',
            'type' => 'nda',
            'content' => 'NON-DISCLOSURE AGREEMENT

This Non-Disclosure Agreement ("Agreement") is entered into between Nexi Bot LTD, a company incorporated under the laws of England and Wales (Company Registration: 16502958, ICO Registration: ZB910034) ("Disclosing Party"), and the undersigned ("Receiving Party").

ARTICLE 1: DEFINITION OF CONFIDENTIAL INFORMATION

1.1 Confidential Information includes, but is not limited to:
• Technical data, trade secrets, know-how, research, product plans, products, services, customers, customer lists, markets, software, developments, inventions, processes, formulas, technology, designs, drawings, engineering, hardware configuration information, marketing, finances, or other business information.
• Any information that is marked, designated, or otherwise identified as confidential or proprietary.
• Any information that would reasonably be considered confidential under the circumstances.

ARTICLE 2: OBLIGATIONS OF RECEIVING PARTY

2.1 Non-Disclosure
Receiving Party agrees to hold all Confidential Information in strict confidence and not to disclose such information to any third parties without the prior written consent of the Disclosing Party.

2.2 Use Restrictions
Receiving Party agrees to use Confidential Information solely for the purpose of evaluating potential business relationships with the Disclosing Party and not for any other purpose.

2.3 Protection Measures
Receiving Party agrees to take reasonable measures to protect the confidentiality of the Confidential Information, including but not limited to implementing appropriate physical, technical, and administrative safeguards.

ARTICLE 3: DURATION AND TERMINATION

3.1 Term
This Agreement shall remain in effect for a period of five (5) years from the date of execution, unless terminated earlier by mutual written consent of the parties.

3.2 Return of Information
Upon termination of this Agreement, Receiving Party shall promptly return or destroy all materials containing Confidential Information.

ARTICLE 4: GENERAL PROVISIONS

4.1 Governing Law
This Agreement shall be governed by and construed in accordance with the laws of England and Wales.

4.2 Remedies
Receiving Party acknowledges that any breach of this Agreement may cause irreparable harm to the Disclosing Party and that monetary damages may be inadequate. Therefore, the Disclosing Party shall be entitled to seek equitable relief, including injunction and specific performance.

By signing below, the parties acknowledge that they have read, understood, and agree to be bound by the terms of this Agreement.'
        ],
        [
            'name' => 'Code of Conduct',
            'type' => 'conduct',
            'content' => 'NEXI BOT LTD CODE OF CONDUCT

INTRODUCTION

This Code of Conduct establishes the ethical and professional standards expected of all employees, contractors, and representatives of Nexi Bot LTD. These standards reflect our commitment to integrity, respect, and excellence in all business activities.

SECTION 1: CORE VALUES AND PRINCIPLES

1.1 Integrity and Honesty
All employees must conduct themselves with the highest level of integrity and honesty in all business dealings, both internal and external.

1.2 Respect and Dignity
We are committed to maintaining a workplace that is free from discrimination, harassment, and retaliation. All individuals must be treated with respect and dignity.

1.3 Professional Excellence
Employees are expected to perform their duties with competence, diligence, and in the best interests of the Company and its stakeholders.

SECTION 2: WORKPLACE CONDUCT

2.1 Professional Behavior
• Maintain professional demeanor in all business interactions
• Communicate respectfully and constructively with colleagues, clients, and partners
• Dress appropriately for the workplace and business meetings
• Arrive punctually and maintain regular attendance

2.2 Confidentiality and Privacy
• Protect confidential information of the Company, clients, and colleagues
• Respect privacy rights of all individuals
• Use Company resources and information systems appropriately
• Report suspected data breaches or security incidents immediately

2.3 Conflict of Interest
• Avoid situations where personal interests conflict with Company interests
• Disclose potential conflicts of interest to management
• Refrain from accepting inappropriate gifts or benefits from third parties
• Maintain independence in business decision-making

SECTION 3: COMPLIANCE AND LEGAL OBLIGATIONS

3.1 Legal Compliance
All employees must comply with applicable laws, regulations, and Company policies in their jurisdiction and areas of responsibility.

3.2 Anti-Corruption and Bribery
The Company prohibits all forms of corruption, bribery, and improper payments in connection with business activities.

3.3 Financial Integrity
• Maintain accurate and complete financial records
• Report financial irregularities or suspected fraud
• Comply with all accounting standards and procedures
• Protect Company assets from misuse or theft

SECTION 4: TECHNOLOGY AND INFORMATION SECURITY

4.1 Information Systems
• Use Company technology resources for legitimate business purposes
• Maintain strong passwords and follow cybersecurity protocols
• Report suspected security breaches immediately
• Respect intellectual property rights

4.2 Social Media and Communications
• Exercise good judgment when representing the Company online
• Respect confidentiality obligations in all communications
• Avoid posting content that could damage the Company\'s reputation
• Distinguish personal opinions from Company positions

SECTION 5: REPORTING AND ENFORCEMENT

5.1 Reporting Violations
Employees are encouraged to report suspected violations of this Code through appropriate channels, including direct supervisors or the HR department.

5.2 Investigation Process
All reports will be investigated promptly and thoroughly while maintaining confidentiality to the extent possible.

5.3 Non-Retaliation
The Company prohibits retaliation against any individual who reports suspected violations in good faith.

5.4 Disciplinary Action
Violations of this Code may result in disciplinary action, up to and including termination of employment.

ACKNOWLEDGMENT

By signing this document, I acknowledge that I have read, understood, and agree to comply with this Code of Conduct. I understand that violations may result in disciplinary action, including termination.

This Code of Conduct is effective as of the date of signature and remains in effect throughout the duration of my employment or engagement with Nexi Bot LTD.'
        ],
        [
            'name' => 'Company Policies Agreement',
            'type' => 'policies',
            'content' => 'COMPANY POLICIES AGREEMENT

This Agreement acknowledges that the Employee has received, read, and agrees to comply with all Company policies and procedures as set forth in the Employee Handbook and other policy documents.

ARTICLE 1: POLICY COMPLIANCE

1.1 General Policies
Employee agrees to comply with all Company policies, including but not limited to:
• Attendance and punctuality policies
• Dress code and professional appearance standards
• Technology use and cybersecurity policies
• Health and safety protocols
• Equal opportunity and anti-discrimination policies

1.2 Workplace Policies
• Respectful workplace behavior
• Anti-harassment and anti-bullying policies
• Substance abuse policies
• Conflict resolution procedures
• Performance management standards

ARTICLE 2: COMMUNICATION AND UPDATES

2.1 Policy Updates
The Company reserves the right to modify, update, or supplement its policies at any time. Employees will be notified of significant policy changes and are responsible for staying current with all applicable policies.

2.2 Questions and Clarifications
Employees are encouraged to seek clarification on any policy matters through their supervisor or the Human Resources department.

ARTICLE 3: COMPLIANCE MONITORING

3.1 Adherence
Employee performance and conduct will be evaluated based on compliance with Company policies and standards.

3.2 Training
The Company may provide training on policies and procedures, and Employee agrees to participate in such training as required.

ARTICLE 4: ACKNOWLEDGMENT

By signing this Agreement, Employee acknowledges:
• Receipt of the Employee Handbook and all applicable policy documents
• Understanding of the policies and procedures contained therein
• Agreement to comply with all current and future Company policies
• Understanding that policy violations may result in disciplinary action

This Agreement supplements and does not replace any other employment agreements or documents.'
        ]
    ];

    // Update each contract with clean, professional content
    $stmt = $db->prepare("UPDATE contract_templates SET content = ? WHERE type = ?");
    
    foreach ($contracts as $contract) {
        $stmt->execute([$contract['content'], $contract['type']]);
        echo "<p>✅ Updated: " . $contract['name'] . "</p>";
    }
    
    // Also update the names to be more professional
    $updates = [
        ['name' => 'Employment Agreement', 'type' => 'employment'],
        ['name' => 'Non-Disclosure Agreement (NDA)', 'type' => 'nda'],
        ['name' => 'Code of Conduct', 'type' => 'conduct'],
        ['name' => 'Company Policies Agreement', 'type' => 'policies']
    ];
    
    $stmt = $db->prepare("UPDATE contract_templates SET name = ? WHERE type = ?");
    foreach ($updates as $update) {
        $stmt->execute([$update['name'], $update['type']]);
    }
    
    // Clean up any duplicate or malformed entries
    $db->exec("DELETE FROM contract_templates WHERE name LIKE '%Voluntary%' OR name LIKE '%Shareholder%'");
    
    echo "<p><strong>✅ Contract templates updated with clean, professional formatting!</strong></p>";
    echo "<p><a href='contracts/login-test.php'>→ Test the updated contracts</a></p>";
    
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}
?>
