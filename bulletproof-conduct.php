<?php
require_once 'config/config.php';

echo "🔨 Bulletproofing Code of Conduct Contract...\n";

$company_number = "16502958";
$ico_number = "ZB910034";
$company_name = "NEXI BOT LTD";
$address = "80A Ruskin Avenue, Welling, London, DA16 3QQ";

try {
    $conduct_bulletproof = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Code of Conduct - ' . $company_name . '</title>
    <style>
        body { font-family: "Times New Roman", serif; line-height: 1.6; color: #333; max-width: 900px; margin: 0 auto; padding: 20px; }
        h1 { color: #1a365d; text-align: center; border-bottom: 3px solid #1a365d; padding-bottom: 10px; }
        h2 { color: #2d4a87; margin-top: 30px; }
        h3 { color: #4a5568; margin-top: 25px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { background: #f7fafc; padding: 15px; border-left: 4px solid #2d4a87; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .conduct-box { background: #f0fff4; border: 1px solid #68d391; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .violation-box { background: #fed7d7; border: 1px solid #fc8181; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .important { background: #fef5e7; border: 1px solid #f6ad55; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .minor-protection { background: #d4edda; border: 1px solid #28a745; padding: 15px; border-radius: 5px; margin: 15px 0; }
        ol li { margin-bottom: 10px; }
        ul li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>STAFF CODE OF CONDUCT</h1>
        <div class="company-info">
            <strong>' . $company_name . '</strong><br>
            Company Registration Number: ' . $company_number . '<br>
            ICO Registration Number: ' . $ico_number . '<br>
            Registered Office: ' . $address . '<br>
            Comprehensive Professional Standards and Behavioral Guidelines<br>
            Governed by: Employment Rights Act 1996, Equality Act 2010, Health and Safety at Work Act 1974
        </div>
    </div>

    <div class="violation-box">
        <strong>MANDATORY COMPLIANCE:</strong> This Code of Conduct is contractually binding under the Employment Rights Act 1996 and Equality Act 2010. Violations may result in disciplinary action up to and including termination, legal action, and regulatory reporting. If you are under 16, a parent or guardian must also acknowledge this code.
    </div>

    <div class="minor-protection">
        <h3>🛡️ ENHANCED PROTECTIONS FOR YOUNG WORKERS (Ages 13-17)</h3>
        <p><strong>Additional safeguards under UK employment law for minors:</strong></p>
        <ul>
            <li>✅ <strong>Parental Review Required:</strong> Parent/guardian must review and approve all conduct expectations for under-16s</li>
            <li>✅ <strong>Age-Appropriate Standards:</strong> Conduct expectations adjusted for developmental stage and maturity</li>
            <li>✅ <strong>Enhanced Supervision:</strong> Additional mentoring and guidance provided</li>
            <li>✅ <strong>Welfare Priority:</strong> Employee wellbeing takes precedence in all disciplinary matters</li>
            <li>✅ <strong>Educational Support:</strong> Conduct training provided with age-appropriate materials</li>
            <li>✅ <strong>Child Protection Compliance:</strong> All interactions comply with UK child protection legislation</li>
        </ul>
    </div>

    <div class="section">
        <h2>1. EQUALITY ACT 2010 COMPLIANCE</h2>
        <div class="conduct-box">
            <h3>Protected Characteristics - Zero Tolerance for Discrimination:</h3>
            <ul>
                <li><strong>Age, Disability, Gender Reassignment:</strong> No discrimination, harassment, or victimization</li>
                <li><strong>Marriage/Civil Partnership, Pregnancy/Maternity:</strong> Full protection and support</li>
                <li><strong>Race, Religion/Belief:</strong> Respect for all cultural and religious diversity</li>
                <li><strong>Sex, Sexual Orientation:</strong> Inclusive environment for all gender identities and orientations</li>
            </ul>
            
            <h3>Positive Duties:</h3>
            <ul>
                <li>Advance equality of opportunity between different groups</li>
                <li>Foster good relations between people with different protected characteristics</li>
                <li>Eliminate discrimination, harassment, and victimization</li>
                <li>Make reasonable adjustments for disabled colleagues</li>
            </ul>
        </div>
        
        <div class="violation-box">
            <h3>Prohibited Under Equality Act 2010:</h3>
            <ul>
                <li>Direct or indirect discrimination based on protected characteristics</li>
                <li>Harassment creating intimidating, hostile, or offensive environments</li>
                <li>Victimization of those who report discrimination or support complainants</li>
                <li>Failure to make reasonable adjustments for disabilities</li>
                <li>Discriminatory jokes, comments, imagery, or behavior</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>2. HEALTH AND SAFETY AT WORK ACT 1974</h2>
        <div class="important">
            <h3>Employer Duties (Section 2):</h3>
            <p>' . $company_name . ' provides safe working conditions, proper training, and comprehensive risk assessments.</p>
            
            <h3>Employee Duties (Section 7-8):</h3>
            <ul>
                <li><strong>General Duty:</strong> Take reasonable care for health and safety of yourself and others</li>
                <li><strong>Cooperation:</strong> Cooperate with employer on health and safety matters</li>
                <li><strong>Equipment Use:</strong> Use protective equipment and follow safety procedures</li>
                <li><strong>Reporting:</strong> Report hazards, accidents, near-misses, and dangerous occurrences immediately</li>
                <li><strong>Training:</strong> Participate in mandatory health and safety training</li>
                <li><strong>No Interference:</strong> Do not intentionally damage or misuse safety equipment</li>
            </ul>
            
            <h3>Young Workers - Enhanced Safety Requirements:</h3>
            <ul>
                <li>Additional supervision and restricted access to hazardous areas</li>
                <li>Age-appropriate risk assessments and safety training</li>
                <li>Regular welfare checks and safety monitoring</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>3. DATA PROTECTION ACT 2018 & UK GDPR</h2>
        <div class="violation-box">
            <p><strong>Data Controller:</strong> ' . $company_name . ' (ICO Registration: ' . $ico_number . ')</p>
            <p><strong>Mandatory Data Protection Principles:</strong></p>
            <ul>
                <li><strong>Lawfulness:</strong> Process personal data only for legitimate, authorized purposes</li>
                <li><strong>Fairness & Transparency:</strong> Inform data subjects how their data is used</li>
                <li><strong>Purpose Limitation:</strong> Use data only for specified, legitimate purposes</li>
                <li><strong>Data Minimization:</strong> Collect and process only necessary personal data</li>
                <li><strong>Accuracy:</strong> Keep personal data accurate and up to date</li>
                <li><strong>Storage Limitation:</strong> Retain data only as long as necessary</li>
                <li><strong>Security:</strong> Implement appropriate technical and organizational measures</li>
                <li><strong>Accountability:</strong> Demonstrate compliance with data protection principles</li>
            </ul>
            
            <h3>Breach Reporting:</h3>
            <p><strong>Personal data breaches must be reported within 1 hour to the Data Protection Officer.</strong></p>
            <p><strong>Criminal Penalties:</strong> Unlawful processing can result in fines up to £17.5M or 4% of turnover, plus criminal prosecution under section 170-173 DPA 2018.</p>
        </div>
    </div>

    <div class="section">
        <h2>4. INTELLECTUAL PROPERTY PROTECTION</h2>
        <div class="conduct-box">
            <p><strong>Copyright, Designs and Patents Act 1988:</strong> All intellectual property created belongs to ' . $company_name . '</p>
            <p><strong>Trade Secrets & Confidential Information:</strong></p>
            <ul>
                <li>Software code, algorithms, and technical innovations</li>
                <li>Business processes, client data, and commercial strategies</li>
                <li>Financial information and competitive intelligence</li>
                <li>Any confidential information marked as proprietary</li>
            </ul>
            <p><strong>Prohibited:</strong> Copying, sharing, or using company IP for personal gain or competitors.</p>
        </div>
    </div>

    <div class="section">
        <h2>5. DISCIPLINARY PROCEDURES (ACAS CODE)</h2>
        <div class="important">
            <p><strong>ACAS Code of Practice Compliance:</strong> All disciplinary matters follow statutory ACAS guidelines.</p>
            
            <h3>Progressive Disciplinary Process:</h3>
            <ol>
                <li><strong>Investigation:</strong> Fair, thorough investigation of alleged misconduct</li>
                <li><strong>Written Notice:</strong> Advance notice of disciplinary meeting</li>
                <li><strong>Meeting:</strong> Right to be accompanied by trade union representative or colleague</li>
                <li><strong>Decision:</strong> Written decision with clear reasons</li>
                <li><strong>Appeal:</strong> Right of appeal to senior management</li>
            </ol>
            
            <h3>Disciplinary Sanctions:</h3>
            <ul>
                <li><strong>Verbal Warning:</strong> First minor violation</li>
                <li><strong>Written Warning:</strong> More serious or repeated violations</li>
                <li><strong>Final Written Warning:</strong> Serious misconduct</li>
                <li><strong>Dismissal:</strong> Gross misconduct or persistent violations</li>
            </ul>
            
            <h3>Gross Misconduct (Summary Dismissal):</h3>
            <ul>
                <li>Theft, fraud, dishonesty, or criminal activity</li>
                <li>Serious discrimination, harassment, or bullying</li>
                <li>Serious health and safety breaches endangering others</li>
                <li>Serious data protection violations or security breaches</li>
                <li>Violence, threats, or serious insubordination</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>6. WHISTLEBLOWING PROTECTION (PUBLIC INTEREST DISCLOSURE ACT 1998)</h2>
        <div class="conduct-box">
            <h3>Protected Disclosures - You are legally protected when reporting:</h3>
            <ul>
                <li><strong>Criminal Offenses:</strong> Any criminal activity or legal violations</li>
                <li><strong>Health & Safety:</strong> Dangers to health and safety of individuals</li>
                <li><strong>Environmental Damage:</strong> Damage or likely damage to the environment</li>
                <li><strong>Miscarriage of Justice:</strong> That a miscarriage of justice has occurred</li>
                <li><strong>Regulatory Breaches:</strong> Failure to comply with legal obligations</li>
                <li><strong>Cover-ups:</strong> Concealment of any of the above matters</li>
            </ul>
            
            <h3>No Retaliation Policy:</h3>
            <p>' . $company_name . ' prohibits retaliation against employees making protected disclosures in good faith. Retaliation is itself gross misconduct.</p>
            
            <h3>Reporting Channels:</h3>
            <ul>
                <li>Line manager or senior management</li>
                <li>Human Resources department</li>
                <li>External regulators (ICO, HSE, etc.) when appropriate</li>
                <li>Legal advisors or prescribed persons under PIDA 1998</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>7. EMPLOYMENT RIGHTS ACT 1996 - STATUTORY PROTECTIONS</h2>
        <div class="important">
            <h3>Your Statutory Employment Rights:</h3>
            <ul>
                <li><strong>Written Statement:</strong> Right to written statement of employment terms</li>
                <li><strong>Minimum Notice:</strong> Statutory minimum notice periods</li>
                <li><strong>Unfair Dismissal:</strong> Protection against unfair dismissal (after qualifying period)</li>
                <li><strong>Redundancy Pay:</strong> Statutory redundancy entitlements where applicable</li>
                <li><strong>Time Off:</strong> Rights to time off for various statutory purposes</li>
                <li><strong>Flexible Working:</strong> Right to request flexible working arrangements</li>
            </ul>
            
            <h3>Termination Procedures:</h3>
            <p>All terminations follow statutory procedures with proper notice, consultation, and appeals processes.</p>
        </div>
    </div>

    <div class="section">
        <h2>8. TRAINING AND AWARENESS</h2>
        <div class="conduct-box">
            <h3>Mandatory Training Requirements:</h3>
            <ul>
                <li><strong>Equality & Diversity:</strong> Annual training on Equality Act 2010 compliance</li>
                <li><strong>Data Protection:</strong> UK GDPR and DPA 2018 training</li>
                <li><strong>Health & Safety:</strong> Workplace safety and emergency procedures</li>
                <li><strong>Code of Conduct:</strong> Regular updates and refresher training</li>
            </ul>
            
            <h3>Young Workers - Enhanced Training:</h3>
            <ul>
                <li>Age-appropriate training materials and delivery methods</li>
                <li>Additional support and mentoring programs</li>
                <li>Regular competency assessments and feedback</li>
            </ul>
        </div>
    </div>

    <div style="margin-top: 40px; border-top: 2px solid #2d4a87; padding-top: 20px;">
        <h2>ACKNOWLEDGMENT AND AGREEMENT</h2>
        
        <div class="important">
            <p>By signing below, I acknowledge that I have read, understood, and agree to comply with this Code of Conduct. I understand my legal obligations under UK employment law and the consequences of violations.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
            <div style="border: 1px solid #ddd; padding: 15px;">
                <strong>EMPLOYEE:</strong><br><br>
                Name: _________________________<br>
                Age: __________________________<br>
                Signature: ____________________<br>
                Date: _________________________
            </div>
            <div style="border: 1px solid #ddd; padding: 15px;">
                <strong>' . $company_name . ':</strong><br><br>
                Representative: ________________<br>
                Title: ________________________<br>
                Signature: ____________________<br>
                Date: _________________________
            </div>
        </div>
        
        <div class="minor-protection" style="margin-top: 20px;">
            <strong>PARENTAL ACKNOWLEDGMENT (Required for employees under 16):</strong><br>
            <div style="border: 2px solid #28a745; padding: 15px; margin: 10px 0;">
                I have reviewed this Code of Conduct and understand the behavioral expectations for my child\'s employment.<br><br>
                Parent/Guardian: ___________________________<br>
                Signature: ________________________________<br>
                Date: _____________________________________
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 15px;">
        <p><strong>Legal Framework:</strong> This Code complies with Employment Rights Act 1996, Equality Act 2010, Health and Safety at Work Act 1974, Data Protection Act 2018, Public Interest Disclosure Act 1998, and related UK employment legislation.</p>
        <p><strong>Document Reference:</strong> COC-UK-2024-v2.0 | <strong>Company:</strong> ' . $company_name . ' | <strong>ICO Reg:</strong> ' . $ico_number . '</p>
    </div>
</body>
</html>';

    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 2");
    $stmt->execute([$conduct_bulletproof]);
    echo "✅ Code of Conduct bulletproofed\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
