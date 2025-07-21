<?php
require_once __DIR__ . '/config/config.php';

echo "Cleaning production MySQL contract templates...\n\n";

echo "Current contracts: " . $pdo->query("SELECT COUNT(*) FROM contract_templates")->fetchColumn() . "\n";

// Delete all existing contract templates
echo "Deleting all existing contract templates...\n";
$pdo->exec("DELETE FROM contract_templates");
echo "Deleted.\n";

// Reset AUTO_INCREMENT
$pdo->exec("ALTER TABLE contract_templates AUTO_INCREMENT = 1");
echo "Reset AUTO_INCREMENT.\n";

// Insert the 5 correct contract templates with proper UK-legal content
echo "Inserting 5 UK-legal contract templates...\n";

$contracts = [
    [
        'name' => 'Voluntary Contract of Employment',
        'type' => 'employment',
        'content' => '<!DOCTYPE html>
<html><head><title>Voluntary Contract of Employment</title></head><body>
<h1>VOLUNTARY CONTRACT OF EMPLOYMENT</h1>
<h2>Nexi Hub Ltd</h2>

<p><strong>This Voluntary Contract of Employment</strong> is entered into between Nexi Hub Ltd (Company Registration Number: [TO_BE_INSERTED]) and the individual specified in the execution section below.</p>

<h3>1. POSITION AND DUTIES</h3>
<p>1.1. The Employee agrees to serve the Company as a voluntary contributor in the capacity specified in their individual profile.</p>
<p>1.2. The Employee will undertake such duties and responsibilities as may reasonably be assigned by the Company.</p>
<p>1.3. This is a voluntary position with no guaranteed compensation, though the Company may at its discretion provide benefits, recognition, or future compensation.</p>

<h3>2. TERM</h3>
<p>2.1. This agreement shall commence on the date specified and continue until terminated by either party with 30 days written notice.</p>

<h3>3. CONFIDENTIALITY</h3>
<p>3.1. The Employee acknowledges that they may have access to confidential information and agrees to maintain strict confidentiality.</p>

<h3>4. INTELLECTUAL PROPERTY</h3>
<p>4.1. Any work product, innovations, or intellectual property created during the course of engagement shall belong to the Company.</p>

<h3>5. TERMINATION</h3>
<p>5.1. Either party may terminate this agreement with 30 days written notice.</p>
<p>5.2. The Company may terminate immediately for cause including breach of confidentiality or misconduct.</p>

<h3>6. GOVERNING LAW</h3>
<p>6.1. This agreement shall be governed by and construed in accordance with the laws of England and Wales.</p>

<p><em>By executing this document, both parties acknowledge they have read, understood, and agree to be bound by its terms.</em></p>
</body></html>',
        'is_assignable' => 1
    ],
    [
        'name' => 'Staff Code of Conduct',
        'type' => 'conduct',
        'content' => '<!DOCTYPE html>
<html><head><title>Staff Code of Conduct</title></head><body>
<h1>STAFF CODE OF CONDUCT</h1>
<h2>Nexi Hub Ltd</h2>

<h3>1. PROFESSIONAL CONDUCT</h3>
<p>1.1. All staff members must maintain the highest standards of professional conduct.</p>
<p>1.2. Treat all colleagues, clients, and stakeholders with respect and dignity.</p>
<p>1.3. Act with integrity and honesty in all business dealings.</p>

<h3>2. COMMUNICATION STANDARDS</h3>
<p>2.1. Maintain professional communication in all company channels.</p>
<p>2.2. Respect confidentiality of sensitive information.</p>
<p>2.3. Report concerns or conflicts promptly to management.</p>

<h3>3. ANTI-HARASSMENT AND DISCRIMINATION</h3>
<p>3.1. Nexi Hub maintains zero tolerance for harassment or discrimination.</p>
<p>3.2. All forms of bullying, intimidation, or inappropriate behavior are prohibited.</p>
<p>3.3. Report any incidents immediately to management.</p>

<h3>4. DATA PROTECTION AND PRIVACY</h3>
<p>4.1. Comply with all applicable data protection laws including UK GDPR.</p>
<p>4.2. Handle personal data responsibly and in accordance with company policies.</p>
<p>4.3. Maintain security of all systems and information.</p>

<h3>5. CONFLICTS OF INTEREST</h3>
<p>5.1. Declare any potential conflicts of interest.</p>
<p>5.2. Avoid activities that may compromise professional judgment.</p>

<h3>6. COMPLIANCE</h3>
<p>6.1. Violations of this Code may result in disciplinary action up to and including termination.</p>
<p>6.2. All staff must complete regular training on these standards.</p>

<p><strong>This Code of Conduct is binding on all staff members and must be adhered to at all times.</strong></p>
</body></html>',
        'is_assignable' => 1
    ],
    [
        'name' => 'Non-Disclosure Agreement (NDA)',
        'type' => 'nda',
        'content' => '<!DOCTYPE html>
<html><head><title>Non-Disclosure Agreement</title></head><body>
<h1>NON-DISCLOSURE AGREEMENT</h1>
<h2>Nexi Hub Ltd</h2>

<p>This Non-Disclosure Agreement ("Agreement") is entered into between Nexi Hub Ltd ("Company") and the individual executing this document ("Recipient").</p>

<h3>1. DEFINITION OF CONFIDENTIAL INFORMATION</h3>
<p>1.1. "Confidential Information" includes all non-public information relating to the Company\'s business, including but not limited to:</p>
<ul>
<li>Technical data, trade secrets, know-how, research, product plans</li>
<li>Customer lists, supplier information, business strategies</li>
<li>Financial information, pricing, marketing strategies</li>
<li>Software code, algorithms, system designs</li>
<li>Personnel information and internal communications</li>
</ul>

<h3>2. OBLIGATIONS OF RECIPIENT</h3>
<p>2.1. Hold all Confidential Information in strict confidence.</p>
<p>2.2. Not disclose Confidential Information to any third parties without prior written consent.</p>
<p>2.3. Use Confidential Information solely for the purpose of their engagement with the Company.</p>
<p>2.4. Take reasonable precautions to protect the confidentiality of all information.</p>

<h3>3. EXCLUSIONS</h3>
<p>3.1. This Agreement does not apply to information that:</p>
<ul>
<li>Is or becomes publicly available through no breach by Recipient</li>
<li>Is lawfully received from third parties without breach</li>
<li>Is required to be disclosed by law or court order</li>
</ul>

<h3>4. DURATION</h3>
<p>4.1. This Agreement remains in effect indefinitely, surviving termination of any employment or engagement.</p>

<h3>5. REMEDIES</h3>
<p>5.1. Breach of this Agreement may cause irreparable harm, entitling Company to injunctive relief.</p>
<p>5.2. Company may seek all available legal remedies for breach.</p>

<h3>6. GOVERNING LAW</h3>
<p>6.1. This Agreement is governed by the laws of England and Wales.</p>

<p><strong>By signing below, Recipient acknowledges understanding and agreement to these terms.</strong></p>
</body></html>',
        'is_assignable' => 1
    ],
    [
        'name' => 'Company Policies',
        'type' => 'policies',
        'content' => '<!DOCTYPE html>
<html><head><title>Company Policies</title></head><body>
<h1>COMPANY POLICIES AND PROCEDURES</h1>
<h2>Nexi Hub Ltd</h2>

<h3>1. GENERAL EMPLOYMENT POLICIES</h3>
<p>1.1. <strong>Equal Opportunities:</strong> We are committed to providing equal opportunities regardless of age, gender, race, religion, sexual orientation, or disability.</p>
<p>1.2. <strong>Health and Safety:</strong> All staff must comply with health and safety regulations and report hazards immediately.</p>

<h3>2. WORKING ARRANGEMENTS</h3>
<p>2.1. <strong>Remote Work:</strong> Remote working arrangements must be approved by management.</p>
<p>2.2. <strong>Working Hours:</strong> Core business hours and availability requirements as specified in individual agreements.</p>
<p>2.3. <strong>Time Off:</strong> Annual leave and sick leave policies as per individual agreements.</p>

<h3>3. IT AND COMMUNICATIONS POLICY</h3>
<p>3.1. <strong>Acceptable Use:</strong> Company systems must be used responsibly and for business purposes.</p>
<p>3.2. <strong>Security:</strong> Maintain strong passwords, report security incidents immediately.</p>
<p>3.3. <strong>Data Protection:</strong> Comply with UK GDPR and company data handling procedures.</p>

<h3>4. SOCIAL MEDIA AND COMMUNICATIONS</h3>
<p>4.1. Professional representation of the company in all communications.</p>
<p>4.2. Obtain approval before making public statements on behalf of the company.</p>

<h3>5. DISCIPLINARY PROCEDURES</h3>
<p>5.1. Progressive discipline process: verbal warning, written warning, final warning, termination.</p>
<p>5.2. Gross misconduct may result in immediate termination.</p>

<h3>6. GRIEVANCE PROCEDURES</h3>
<p>6.1. Staff may raise concerns with their manager or senior management.</p>
<p>6.2. Formal grievance process available for unresolved issues.</p>

<h3>7. TRAINING AND DEVELOPMENT</h3>
<p>7.1. Mandatory training requirements including compliance and safety training.</p>
<p>7.2. Professional development opportunities as available.</p>

<h3>8. EXPENSES AND BENEFITS</h3>
<p>8.1. Expense reimbursement procedures for approved business expenses.</p>
<p>8.2. Benefits eligibility as specified in individual agreements.</p>

<p><strong>These policies are binding on all staff and may be updated from time to time. Staff will be notified of any changes.</strong></p>
</body></html>',
        'is_assignable' => 1
    ],
    [
        'name' => 'Shareholder Agreement',
        'type' => 'shareholder',
        'content' => '<!DOCTYPE html>
<html><head><title>Shareholder Agreement</title></head><body>
<h1>SHAREHOLDER AGREEMENT</h1>
<h2>Nexi Hub Ltd</h2>

<p>This Shareholder Agreement ("Agreement") governs the relationship between Nexi Hub Ltd ("Company") and the individual specified below ("Shareholder").</p>

<h3>1. SHARE ALLOCATION</h3>
<p>1.1. The Shareholder\'s percentage ownership will be as specified in their individual profile.</p>
<p>1.2. Share allocation is subject to vesting schedules and performance criteria.</p>
<p>1.3. Shares are subject to the Company\'s Articles of Association.</p>

<h3>2. SHAREHOLDER RIGHTS AND OBLIGATIONS</h3>
<p>2.1. <strong>Voting Rights:</strong> Shareholders may vote on matters as specified in the Articles of Association.</p>
<p>2.2. <strong>Information Rights:</strong> Regular updates on company performance and major decisions.</p>
<p>2.3. <strong>Confidentiality:</strong> All shareholder information must be kept confidential.</p>

<h3>3. TRANSFER RESTRICTIONS</h3>
<p>3.1. Shares cannot be transferred without Board approval.</p>
<p>3.2. Company has right of first refusal on any proposed transfers.</p>
<p>3.3. Transfers must comply with applicable securities laws.</p>

<h3>4. VESTING AND CLIFF PROVISIONS</h3>
<p>4.1. Shares may be subject to vesting schedules based on continued service.</p>
<p>4.2. Cliff vesting periods may apply before any shares vest.</p>
<p>4.3. Acceleration provisions may apply in certain circumstances.</p>

<h3>5. DRAG-ALONG AND TAG-ALONG RIGHTS</h3>
<p>5.1. Majority shareholders may require minority participation in certain sales.</p>
<p>5.2. Minority shareholders have rights to participate in certain sales.</p>

<h3>6. ANTI-DILUTION PROTECTIONS</h3>
<p>6.1. Certain shareholders may have anti-dilution rights in future funding rounds.</p>

<h3>7. GOVERNANCE</h3>
<p>7.1. Board composition and voting procedures as specified in Articles.</p>
<p>7.2. Major decisions may require shareholder approval.</p>

<h3>8. TERMINATION OF SHAREHOLDING</h3>
<p>8.1. Company may repurchase shares upon termination of employment/engagement.</p>
<p>8.2. Valuation procedures for share repurchases.</p>

<h3>9. GOVERNING LAW</h3>
<p>9.1. This Agreement is governed by the laws of England and Wales.</p>

<p><strong>This Agreement supplements and does not replace the Company\'s Articles of Association. By signing, Shareholder agrees to all terms and conditions.</strong></p>
</body></html>',
        'is_assignable' => 1
    ]
];

foreach ($contracts as $contract) {
    $stmt = $pdo->prepare("INSERT INTO contract_templates (name, type, content, is_assignable, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$contract['name'], $contract['type'], $contract['content'], $contract['is_assignable']]);
    echo "✓ Inserted: {$contract['name']}\n";
}

echo "\nFinal count: " . $pdo->query("SELECT COUNT(*) FROM contract_templates")->fetchColumn() . " contracts\n";
echo "Assignable contracts: " . $pdo->query("SELECT COUNT(*) FROM contract_templates WHERE is_assignable = 1")->fetchColumn() . "\n";

echo "\nProduction MySQL database cleaned and populated with 5 UK-legal contracts!\n";
?>
