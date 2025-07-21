<?php
require_once 'config/config.php';

echo "🔨 Bulletproofing remaining contracts (NDA, Policies, Shareholder)...\n";

$company_number = "16502958";
$ico_number = "ZB910034";
$company_name = "NEXI BOT LTD";
$address = "80A Ruskin Avenue, Welling, London, DA16 3QQ";

try {
    // Bulletproof NDA Contract
    $nda_bulletproof = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive NDA - ' . $company_name . '</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #007bff; padding-bottom: 20px; }
        .company-info { background: #f7fafc; padding: 15px; border-left: 4px solid #007bff; margin-bottom: 20px; }
        .section { margin: 25px 0; }
        .important { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }
        .minor-provision { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0; }
        .critical { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>COMPREHENSIVE CONFIDENTIALITY & NON-DISCLOSURE AGREEMENT</h1>
        <div class="company-info">
            <strong>' . $company_name . '</strong><br>
            Company Registration Number: ' . $company_number . '<br>
            ICO Registration Number: ' . $ico_number . '<br>
            Registered Office: ' . $address . '<br>
            Legal Framework: Data Protection Act 2018, UK GDPR, Trade Secrets Regulations 2018
        </div>
    </div>

    <div class="critical">
        <strong>LEGAL OBLIGATIONS:</strong> This agreement creates binding legal obligations under UK GDPR, Data Protection Act 2018, and confidentiality law. Violations may result in criminal prosecution, regulatory fines up to £17.5M, and civil liability. If you are under 16, parental/guardian consent is required.
    </div>

    <div class="minor-provision">
        <h3>🛡️ PROTECTIONS FOR YOUNG WORKERS (Under 16)</h3>
        <ul>
            <li>✅ <strong>Parental Consent Required:</strong> Parent/guardian must co-sign this agreement</li>
            <li>✅ <strong>Age-Appropriate Access:</strong> Confidential information access limited to suitable materials</li>
            <li>✅ <strong>Enhanced Supervision:</strong> Additional oversight of information handling</li>
            <li>✅ <strong>Child Protection Compliance:</strong> Information access complies with UK child protection law</li>
            <li>✅ <strong>Educational Safeguards:</strong> Training on confidentiality appropriate for age</li>
        </ul>
    </div>

    <h2>1. DATA PROTECTION ACT 2018 & UK GDPR COMPLIANCE</h2>
    <div class="important">
        <p><strong>Data Controller:</strong> ' . $company_name . ' (ICO Registration: ' . $ico_number . ')</p>
        <p><strong>Your Legal Obligations as Data Processor:</strong></p>
        <ul>
            <li>Process personal data only on documented instructions</li>
            <li>Implement appropriate technical and organizational security measures</li>
            <li>Report personal data breaches within 1 hour of discovery</li>
            <li>Assist with data subject requests (access, rectification, erasure)</li>
            <li>Not transfer personal data outside UK/EEA without authorization</li>
            <li>Delete or return personal data upon employment termination</li>
        </ul>
        <p><strong>Criminal Penalties:</strong> Unlawful processing under sections 170-173 DPA 2018 can result in unlimited fines and imprisonment.</p>
    </div>

    <h2>2. INTELLECTUAL PROPERTY PROTECTION</h2>
    <p><strong>Copyright, Designs and Patents Act 1988:</strong> All IP belongs to ' . $company_name . '</p>
    <p><strong>Trade Secrets (Enforcement, etc.) Regulations 2018:</strong> Trade secrets receive statutory protection</p>
    <p><strong>Confidential Information includes:</strong></p>
    <ul>
        <li>Software code, algorithms, technical specifications</li>
        <li>Customer data, client lists, business relationships</li>
        <li>Financial information, pricing, commercial strategies</li>
        <li>Trade secrets, know-how, proprietary processes</li>
    </ul>

    <h2>3. COMPUTER MISUSE ACT 1990</h2>
    <p><strong>Criminal Offenses:</strong> Unauthorized access to computer systems is a criminal offense</p>
    <p><strong>Penalties:</strong> Up to 10 years imprisonment and unlimited fines</p>

    <h2>4. EMPLOYMENT RIGHTS ACT 1996</h2>
    <p><strong>Confidentiality as Contractual Term:</strong> Breach may result in summary dismissal</p>
    <p><strong>Post-Employment Obligations:</strong> Confidentiality survives termination indefinitely</p>

    <h2>5. PUBLIC INTEREST DISCLOSURE ACT 1998</h2>
    <p><strong>Whistleblowing Exception:</strong> This agreement does not prevent reporting:</p>
    <ul>
        <li>Criminal activities to appropriate authorities</li>
        <li>Regulatory violations to prescribed persons</li>
        <li>Health and safety dangers to relevant bodies</li>
    </ul>

    <div class="important" style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
        <h2>📝 SIGNING INSTRUCTIONS</h2>
        <p><strong>Digital Signing via HR Portal:</strong> This contract must be signed electronically through the company HR portal system. Physical signatures are not required or accepted.</p>
        <ul>
            <li>✅ <strong>Employee:</strong> Sign electronically via your HR portal account</li>
            <li>✅ <strong>Company Representative:</strong> Authorized signatory will countersign digitally</li>
            <li>✅ <strong>Parental Consent (Under 16):</strong> Parent/guardian will receive separate HR portal access for consent</li>
            <li>✅ <strong>Legal Validity:</strong> Electronic signatures have full legal effect under Electronic Communications Act 2000</li>
        </ul>
        <div style="text-align: center; font-weight: bold; color: #007bff; margin-top: 15px;">
            Please proceed to the HR Portal to complete the signing process.
        </div>
    </div>

    <p style="font-size: 12px; margin-top: 20px;"><strong>Legal Framework:</strong> Data Protection Act 2018, UK GDPR, Copyright Designs & Patents Act 1988, Computer Misuse Act 1990, Employment Rights Act 1996. Document: NDA-UK-2024-v2.0</p>
</body>
</html>';

    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 3");
    $stmt->execute([$nda_bulletproof]);
    echo "✅ NDA Contract bulletproofed\n";

    // Bulletproof Policies Contract
    $policies_bulletproof = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Policies - ' . $company_name . '</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #007bff; padding-bottom: 20px; }
        .company-info { background: #f7fafc; padding: 15px; border-left: 4px solid #007bff; margin-bottom: 20px; }
        .important { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }
        .minor-provision { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0; }
        .warning { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>COMPREHENSIVE COMPANY POLICIES & PROCEDURES</h1>
        <div class="company-info">
            <strong>' . $company_name . '</strong><br>
            Company Registration: ' . $company_number . ' | ICO Registration: ' . $ico_number . '<br>
            Address: ' . $address . '<br>
            Legal Compliance: Employment Rights Act 1996, Equality Act 2010, Health and Safety at Work Act 1974, Data Protection Act 2018
        </div>
    </div>

    <div class="warning">
        <strong>STATUTORY COMPLIANCE:</strong> These policies ensure compliance with UK employment law including Employment Rights Act 1996, Equality Act 2010, Working Time Regulations 1998, and Health and Safety at Work Act 1974. Non-compliance may result in disciplinary action and legal consequences.
    </div>

    <div class="minor-provision">
        <h3>🛡️ YOUNG WORKER PROTECTIONS (Ages 13-17)</h3>
        <ul>
            <li>✅ <strong>Enhanced Supervision:</strong> Additional oversight and mentoring for under-16s</li>
            <li>✅ <strong>Working Time Limits:</strong> Strict adherence to young worker time restrictions</li>
            <li>✅ <strong>Age-Appropriate Tasks:</strong> Suitable work assignments and safety measures</li>
            <li>✅ <strong>Parental Involvement:</strong> Regular communication with parents/guardians</li>
            <li>✅ <strong>Educational Priority:</strong> Work must not interfere with education</li>
        </ul>
    </div>

    <h2>1. EQUALITY ACT 2010 - ANTI-DISCRIMINATION</h2>
    <div class="important">
        <p><strong>Protected Characteristics:</strong> Age, disability, gender reassignment, marriage/civil partnership, pregnancy/maternity, race, religion/belief, sex, sexual orientation</p>
        <p><strong>Prohibited Conduct:</strong> Direct/indirect discrimination, harassment, victimization</p>
        <p><strong>Reasonable Adjustments:</strong> Required for disabled employees</p>
    </div>

    <h2>2. HEALTH AND SAFETY AT WORK ACT 1974</h2>
    <div class="important">
        <p><strong>Employer Duties:</strong> Provide safe workplace, training, supervision</p>
        <p><strong>Employee Duties:</strong> Take reasonable care, cooperate on safety, report hazards</p>
        <p><strong>Risk Assessment:</strong> Regular assessment and control of workplace risks</p>
        <p><strong>Young Workers:</strong> Enhanced safety measures and restricted activities</p>
    </div>

    <h2>3. DATA PROTECTION ACT 2018 & UK GDPR</h2>
    <div class="warning">
        <p><strong>Data Controller:</strong> ' . $company_name . ' (ICO: ' . $ico_number . ')</p>
        <p><strong>Processing Requirements:</strong> Lawful basis, fairness, transparency, purpose limitation</p>
        <p><strong>Data Subject Rights:</strong> Access, rectification, erasure, portability, objection</p>
        <p><strong>Breach Reporting:</strong> Report within 72 hours to ICO</p>
        <p><strong>Penalties:</strong> Up to £17.5M or 4% turnover, plus criminal prosecution</p>
    </div>

    <h2>4. EMPLOYMENT RIGHTS ACT 1996</h2>
    <div class="important">
        <p><strong>Written Statements:</strong> Right to written terms and conditions</p>
        <p><strong>Notice Periods:</strong> Statutory minimum notice requirements</p>
        <p><strong>Unfair Dismissal:</strong> Protection against unfair dismissal</p>
        <p><strong>Flexible Working:</strong> Right to request flexible arrangements</p>
    </div>

    <h2>5. WORKING TIME REGULATIONS 1998</h2>
    <div class="important">
        <p><strong>Maximum Hours:</strong> 48 hours per week (averaged over 17 weeks)</p>
        <p><strong>Rest Periods:</strong> 11 hours daily, 24 hours weekly</p>
        <p><strong>Young Workers:</strong> Maximum 8 hours/day, 40 hours/week, no night work</p>
        <p><strong>Breaks:</strong> 20 minutes for 6+ hour shifts (30 minutes for young workers over 4.5 hours)</p>
    </div>

    <h2>6. DISCIPLINARY PROCEDURES (ACAS CODE)</h2>
    <div class="important">
        <p><strong>ACAS Compliance:</strong> All procedures follow ACAS Code of Practice</p>
        <p><strong>Progressive Discipline:</strong> Verbal warning → Written warning → Final warning → Dismissal</p>
        <p><strong>Gross Misconduct:</strong> Summary dismissal for serious breaches</p>
        <p><strong>Right to Appeal:</strong> Appeal process for all disciplinary decisions</p>
    </div>

    <h2>7. PUBLIC INTEREST DISCLOSURE ACT 1998</h2>
    <div class="important">
        <p><strong>Whistleblowing Protection:</strong> Protected disclosures of wrongdoing</p>
        <p><strong>No Retaliation:</strong> Protection against detriment for good faith reporting</p>
        <p><strong>Qualifying Disclosures:</strong> Criminal offenses, health/safety dangers, regulatory breaches</p>
    </div>

    <div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
        <h2>🖊️ DIGITAL SIGNATURE PROCESS</h2>
        <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; border: 2px solid #007bff;">
            <h3>How to Complete This Agreement:</h3>
            <ol style="font-size: 16px; line-height: 1.8;">
                <li><strong>Review:</strong> Read these Company Policies carefully</li>
                <li><strong>Digital Signature:</strong> Use the HR Portal\'s digital signature system to sign</li>
                <li><strong>Timestamp:</strong> Your signature will be automatically timestamped</li>
                <li><strong>Legal Validity:</strong> Digital signatures have full legal validity under UK Electronic Signature Regulations</li>
            </ol>
            
            <div style="background: #fff; padding: 15px; border-left: 4px solid #007bff; margin-top: 15px;">
                <strong>⚖️ LEGAL CONFIRMATION:</strong><br>
                By clicking "Sign Agreement" in the HR Portal, you confirm that:
                <ul>
                    <li>You have read and understood these Company Policies</li>
                    <li>You agree to be legally bound by all terms</li>
                    <li>You will comply with all policies and procedures</li>
                    <li>If under 16, your parent/guardian has provided consent via the HR Portal</li>
                </ul>
            </div>
        </div>
        
        <div class="minor-provision" style="margin-top: 20px;">
            <strong>📋 FOR YOUNG WORKERS (Under 16):</strong><br>
            Your parent/guardian must also complete the digital consent process in the HR Portal before your signature is valid.
        </div>
    </div>

    <p style="font-size: 12px; margin-top: 20px;"><strong>Legal Compliance:</strong> Employment Rights Act 1996, Equality Act 2010, Health and Safety at Work Act 1974, Data Protection Act 2018, Working Time Regulations 1998, Public Interest Disclosure Act 1998. Document: POL-UK-2024-v2.0</p>
</body>
</html>';

    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 4");
    $stmt->execute([$policies_bulletproof]);
    echo "✅ Policies Contract bulletproofed\n";

    // Bulletproof Shareholder Agreement
    $shareholder_bulletproof = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Share Participation - ' . $company_name . '</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #007bff; padding-bottom: 20px; }
        .company-info { background: #f7fafc; padding: 15px; border-left: 4px solid #007bff; margin-bottom: 20px; }
        .important { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }
        .minor-provision { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0; }
        .warning { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
        .financial-table { border-collapse: collapse; width: 100%; margin: 15px 0; }
        .financial-table th, .financial-table td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>EMPLOYEE SHARE PARTICIPATION AGREEMENT</h1>
        <div class="company-info">
            <strong>' . $company_name . '</strong><br>
            Company Registration: ' . $company_number . ' | ICO Registration: ' . $ico_number . '<br>
            Registered Office: ' . $address . '<br>
            Legal Framework: Companies Act 2006, Employment Rights Act 1996, Financial Services and Markets Act 2000
        </div>
    </div>

    <div class="warning">
        <strong>INVESTMENT RISK NOTICE:</strong> This agreement involves financial investments with potential risks. Under Financial Services and Markets Act 2000, you should seek independent financial advice. If you are under 18, parental/guardian consent is required and additional consumer protections apply.
    </div>

    <div class="minor-provision">
        <h3>🛡️ YOUNG PERSON PROTECTIONS (Under 18)</h3>
        <ul>
            <li>✅ <strong>Parental Consent Required:</strong> Parent/guardian must approve all equity arrangements</li>
            <li>✅ <strong>Consumer Credit Act Protection:</strong> Enhanced consumer protection for minors</li>
            <li>✅ <strong>Financial Education:</strong> Mandatory financial literacy training</li>
            <li>✅ <strong>Independent Legal Advice:</strong> Strongly recommended before signing</li>
            <li>✅ <strong>Simplified Terms:</strong> Age-appropriate explanations provided</li>
            <li>✅ <strong>Enhanced Disclosure:</strong> Additional reporting and monitoring</li>
        </ul>
    </div>

    <h2>1. COMPANIES ACT 2006 COMPLIANCE</h2>
    <div class="important">
        <p><strong>Company:</strong> ' . $company_name . ' (Registration: ' . $company_number . ')</p>
        <p><strong>Share Class:</strong> Ordinary shares with rights as per Articles of Association</p>
        <p><strong>Legal Requirements:</strong></p>
        <ul>
            <li>Proper share register maintenance under s113 Companies Act 2006</li>
            <li>Statutory returns filed with Companies House</li>
            <li>Board resolutions for all share issuances</li>
            <li>Compliance with company\'s Articles of Association</li>
        </ul>
    </div>

    <h2>2. EMPLOYMENT RIGHTS ACT 1996</h2>
    <div class="important">
        <p><strong>Employment Link:</strong> Share participation linked to employment status</p>
        <p><strong>Termination Effects:</strong> Impact of employment termination on share rights</p>
        <p><strong>Statutory Protection:</strong> Employment rights not affected by share ownership</p>
    </div>

    <h2>3. FINANCIAL SERVICES REGULATION</h2>
    <div class="warning">
        <p><strong>Investment Risk:</strong> Share values may fluctuate and you may lose money</p>
        <p><strong>No Financial Advice:</strong> Company does not provide investment advice</p>
        <p><strong>Regulatory Compliance:</strong> All issuances comply with FSMA 2000</p>
        <p><strong>Anti-Money Laundering:</strong> Identity verification required</p>
    </div>

    <h2>4. VESTING SCHEDULE</h2>
    <table class="financial-table">
        <tr><th>Period</th><th>Vested %</th><th>Conditions</th></tr>
        <tr><td>0-12 months</td><td>0%</td><td>Cliff period</td></tr>
        <tr><td>12 months</td><td>25%</td><td>Continuous employment</td></tr>
        <tr><td>13-48 months</td><td>2.08% monthly</td><td>Ongoing employment</td></tr>
    </table>

    <h2>5. TAX OBLIGATIONS</h2>
    <div class="important">
        <p><strong>Income Tax:</strong> May apply on exercise or vesting</p>
        <p><strong>Capital Gains Tax:</strong> May apply on disposal</p>
        <p><strong>National Insurance:</strong> May be due on certain events</p>
        <p><strong>Professional Advice:</strong> Tax advice strongly recommended</p>
    </div>

    <h2>6. DATA PROTECTION</h2>
    <div class="important">
        <p><strong>Shareholder Records:</strong> Personal data processed under UK GDPR</p>
        <p><strong>ICO Registration:</strong> ' . $ico_number . '</p>
        <p><strong>Data Rights:</strong> Standard data subject rights apply</p>
    </div>

    <div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
        <h2>🖊️ DIGITAL SIGNATURE PROCESS</h2>
        <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; border: 2px solid #007bff;">
            <h3>How to Complete This Agreement:</h3>
            <ol style="font-size: 16px; line-height: 1.8;">
                <li><strong>Review:</strong> Read this entire NDA agreement carefully</li>
                <li><strong>Digital Signature:</strong> Use the HR Portal\'s digital signature system to sign</li>
                <li><strong>Timestamp:</strong> Your signature will be automatically timestamped</li>
                <li><strong>Legal Validity:</strong> Digital signatures have full legal validity under UK Electronic Signature Regulations</li>
            </ol>
            
            <div style="background: #fff; padding: 15px; border-left: 4px solid #007bff; margin-top: 15px;">
                <strong>⚖️ LEGAL CONFIRMATION:</strong><br>
                By clicking "Sign Agreement" in the HR Portal, you confirm that:
                <ul>
                    <li>You have read and understood this Confidentiality Agreement</li>
                    <li>You agree to be legally bound by all terms</li>
                    <li>You understand the consequences of breach</li>
                    <li>If under 16, your parent/guardian has provided consent via the HR Portal</li>
                </ul>
            </div>
        </div>
        
        <div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
            <h2>🖊️ DIGITAL SIGNATURE PROCESS</h2>
            <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; border: 2px solid #007bff;">
                <h3>How to Complete This Agreement:</h3>
                <ol style="font-size: 16px; line-height: 1.8;">
                    <li><strong>Review:</strong> Read this entire Shareholder Agreement carefully</li>
                    <li><strong>Digital Signature:</strong> Use the HR Portal\'s digital signature system to sign</li>
                    <li><strong>Timestamp:</strong> Your signature will be automatically timestamped</li>
                    <li><strong>Legal Validity:</strong> Digital signatures have full legal validity under UK Electronic Signature Regulations</li>
                </ol>
                
                <div style="background: #fff; padding: 15px; border-left: 4px solid #007bff; margin-top: 15px;">
                    <strong>⚖️ LEGAL CONFIRMATION:</strong><br>
                    By clicking "Sign Agreement" in the HR Portal, you confirm that:
                    <ul>
                        <li>You have read and understood this Shareholder Agreement</li>
                        <li>You agree to be legally bound by all terms</li>
                        <li>You understand the investment risks and tax implications</li>
                        <li>If under 18, your parent/guardian has provided consent via the HR Portal</li>
                    </ul>
                </div>
            </div>
            
            <div class="minor-provision" style="margin-top: 20px;">
                <strong>📋 FOR YOUNG WORKERS (Under 18):</strong><br>
                Your parent/guardian must also complete the digital consent process in the HR Portal before your signature is valid.
            </div>
            
            <div style="background: #f0f8ff; border: 1px solid #007bff; padding: 15px; margin-top: 10px;">
                <strong>BOARD RESOLUTION CONFIRMATION:</strong><br>
                This share grant will be approved by Board Resolution and recorded by the Company Secretary upon completion of the digital signing process.
            </div>
        </div>
    </div>

    <p style="font-size: 12px; margin-top: 20px;"><strong>Legal Framework:</strong> Companies Act 2006, Employment Rights Act 1996, Financial Services and Markets Act 2000, Consumer Credit Act 1974, Data Protection Act 2018. Document: SHA-UK-2024-v2.0</p>
</body>
</html>';

    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 5");
    $stmt->execute([$shareholder_bulletproof]);
    echo "✅ Shareholder Agreement bulletproofed\n";

    echo "\n🎉 ALL CONTRACTS BULLETPROOFED SUCCESSFULLY!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
