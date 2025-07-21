<?php
require_once 'config/config.php';

try {
    // Insert Company Policies Agreement
    $policies_content = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexi Company Policies & Procedures Agreement</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #007bff; padding-bottom: 20px; }
        .logo { font-size: 28px; font-weight: bold; color: #007bff; margin-bottom: 10px; }
        .subtitle { font-size: 18px; color: #666; }
        .section { margin: 25px 0; }
        .section h3 { color: #007bff; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        .important { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; }
        .minor-provision { background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0; }
        .warning { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
        .signature-section { margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px; }
        .signature-box { border: 1px solid #ccc; padding: 15px; margin: 10px 0; background: #f9f9f9; }
        ul li { margin: 8px 0; }
        .legal-notice { font-size: 12px; color: #666; margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; }
        .policy-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .policy-item { background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">NEXI</div>
        <div class="subtitle">Company Policies & Procedures Agreement</div>
        <p><strong>Comprehensive Workplace Standards & Compliance Framework</strong></p>
    </div>

    <div class="important">
        <strong>MANDATORY COMPLIANCE:</strong> All employees must read, understand, and comply with these policies. Violations may result in disciplinary action up to and including termination. If you are under 16, a parent or guardian must also acknowledge these policies.
    </div>

    <div class="minor-provision">
        <h4>Special Protections for Young Workers (Under 16)</h4>
        <p>Additional safeguards and protections apply:</p>
        <ul>
            <li>Parent/guardian must review and acknowledge all policies</li>
            <li>Enhanced supervision and mentoring provided</li>
            <li>Modified work schedules and break requirements</li>
            <li>Age-appropriate task assignments and responsibility levels</li>
            <li>Regular welfare checks and progress reviews</li>
            <li>Access to additional support and counseling resources</li>
        </ul>
    </div>

    <div class="section">
        <h3>1. Workplace Health & Safety</h3>
        <div class="policy-grid">
            <div class="policy-item">
                <h4>Physical Safety</h4>
                <ul>
                    <li>Report all hazards immediately</li>
                    <li>Use protective equipment when required</li>
                    <li>Follow emergency procedures</li>
                    <li>Maintain clean, organized workspace</li>
                    <li>Report accidents/injuries promptly</li>
                </ul>
            </div>
            <div class="policy-item">
                <h4>Digital Safety</h4>
                <ul>
                    <li>Use strong, unique passwords</li>
                    <li>Enable two-factor authentication</li>
                    <li>Report suspicious emails/activities</li>
                    <li>Keep software updated</li>
                    <li>Follow IT security protocols</li>
                </ul>
            </div>
        </div>
        
        <div class="warning">
            <strong>Zero Tolerance:</strong> Any behavior that endangers health or safety will result in immediate disciplinary action.
        </div>
    </div>

    <div class="section">
        <h3>2. Anti-Discrimination & Equality</h3>
        <p><strong>Nexi is committed to providing an inclusive, discrimination-free workplace.</strong></p>
        
        <p><strong>Protected Characteristics (Equality Act 2010):</strong></p>
        <ul>
            <li>Age, disability, gender reassignment, marriage/civil partnership</li>
            <li>Pregnancy/maternity, race, religion/belief, sex, sexual orientation</li>
        </ul>
        
        <p><strong>Prohibited Conduct:</strong></p>
        <ul>
            <li>Direct or indirect discrimination based on protected characteristics</li>
            <li>Harassment, bullying, or victimization</li>
            <li>Offensive jokes, comments, or imagery</li>
            <li>Creating hostile or intimidating environments</li>
            <li>Retaliation against those who report discrimination</li>
        </ul>
        
        <p><strong>Positive Duties:</strong></p>
        <ul>
            <li>Treat all colleagues with respect and dignity</li>
            <li>Challenge inappropriate behavior when safe to do so</li>
            <li>Support diversity and inclusion initiatives</li>
            <li>Report discrimination or harassment promptly</li>
        </ul>
    </div>

    <div class="section">
        <h3>3. Professional Conduct Standards</h3>
        
        <div class="policy-grid">
            <div class="policy-item">
                <h4>Communication</h4>
                <ul>
                    <li>Use professional language</li>
                    <li>Respect cultural differences</li>
                    <li>Listen actively and empathetically</li>
                    <li>Provide constructive feedback</li>
                    <li>Maintain confidentiality</li>
                </ul>
            </div>
            <div class="policy-item">
                <h4>Teamwork</h4>
                <ul>
                    <li>Collaborate effectively</li>
                    <li>Share knowledge and resources</li>
                    <li>Meet commitments and deadlines</li>
                    <li>Support team goals</li>
                    <li>Resolve conflicts constructively</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>4. Data Protection & Privacy (UK GDPR)</h3>
        <p><strong>As data processors, all employees must:</strong></p>
        <ul>
            <li>Process personal data only for authorized purposes</li>
            <li>Implement appropriate security measures</li>
            <li>Report data breaches within 1 hour</li>
            <li>Respect data subject rights (access, rectification, erasure)</li>
            <li>Not transfer data outside UK/EEA without authorization</li>
            <li>Maintain accurate records of processing</li>
            <li>Delete data when no longer needed</li>
            <li>Provide privacy information to data subjects</li>
        </ul>
        
        <div class="warning">
            <strong>Breach Consequences:</strong> Data protection violations can result in regulatory fines up to £17.5M or 4% of turnover, plus criminal penalties.
        </div>
    </div>

    <div class="section">
        <h3>5. IT & Technology Usage</h3>
        
        <p><strong>Acceptable Use:</strong></p>
        <ul>
            <li>Use company resources for business purposes</li>
            <li>Maintain professional online presence</li>
            <li>Respect intellectual property rights</li>
            <li>Follow software licensing terms</li>
            <li>Report technical issues promptly</li>
        </ul>
        
        <p><strong>Prohibited Activities:</strong></p>
        <ul>
            <li>Accessing inappropriate or illegal content</li>
            <li>Installing unauthorized software</li>
            <li>Sharing access credentials</li>
            <li>Using company systems for personal business</li>
            <li>Downloading copyrighted material</li>
            <li>Attempting to bypass security controls</li>
        </ul>
    </div>

    <div class="section">
        <h3>6. Social Media & External Communications</h3>
        <p><strong>Guidelines for online presence:</strong></p>
        <ul>
            <li>Do not speak on behalf of Nexi without authorization</li>
            <li>Respect customer and colleague privacy</li>
            <li>Avoid posting confidential business information</li>
            <li>Maintain professional standards in all posts</li>
            <li>Disclose Nexi affiliation when relevant</li>
            <li>Report concerning online behavior</li>
        </ul>
    </div>

    <div class="section">
        <h3>7. Conflict of Interest</h3>
        <p><strong>You must disclose any situations that could create conflicts:</strong></p>
        <ul>
            <li>Financial interests in competitors or suppliers</li>
            <li>Personal relationships with customers or vendors</li>
            <li>Outside employment or consulting arrangements</li>
            <li>Gifts or favors from business contacts</li>
            <li>Use of company resources for personal benefit</li>
        </ul>
    </div>

    <div class="section">
        <h3>8. Attendance & Punctuality</h3>
        <p><strong>Professional Standards:</strong></p>
        <ul>
            <li>Arrive on time for work and meetings</li>
            <li>Provide advance notice of absences when possible</li>
            <li>Follow proper procedures for requesting time off</li>
            <li>Make up missed work promptly</li>
            <li>Communicate delays or scheduling conflicts</li>
        </ul>
        
        <div class="minor-provision">
            <h4>Young Worker Schedules</h4>
            <p>Special provisions for employees under 16:</p>
            <ul>
                <li>Maximum 8 hours per day, 40 hours per week during holidays</li>
                <li>Maximum 12 hours per week during school term</li>
                <li>No work before 7 AM or after 7 PM</li>
                <li>30-minute break for shifts over 4.5 hours</li>
                <li>2 consecutive rest days per week</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h3>9. Substance Abuse Policy</h3>
        <div class="warning">
            <strong>Zero Tolerance Policy:</strong> Nexi maintains a drug and alcohol-free workplace.
        </div>
        
        <p><strong>Prohibited:</strong></p>
        <ul>
            <li>Possession, use, or distribution of illegal substances</li>
            <li>Reporting to work under the influence of alcohol or drugs</li>
            <li>Consuming alcohol during work hours (except authorized events)</li>
            <li>Prescription drug misuse that impairs work performance</li>
        </ul>
        
        <p><strong>Support Available:</strong></p>
        <ul>
            <li>Employee assistance programs</li>
            <li>Confidential counseling services</li>
            <li>Treatment program referrals</li>
            <li>Return-to-work support</li>
        </ul>
    </div>

    <div class="section">
        <h3>10. Reporting & Whistleblowing</h3>
        <p><strong>Protected Disclosures:</strong> You are protected when reporting:</p>
        <ul>
            <li>Criminal offenses or legal violations</li>
            <li>Health and safety dangers</li>
            <li>Environmental damage</li>
            <li>Miscarriage of justice</li>
            <li>Fraud or financial misconduct</li>
            <li>Covering up any of the above</li>
        </ul>
        
        <p><strong>Reporting Channels:</strong></p>
        <ul>
            <li>Direct supervisor or management</li>
            <li>HR department</li>
            <li>Anonymous reporting system</li>
            <li>External regulators when appropriate</li>
        </ul>
        
        <div class="important">
            <strong>No Retaliation:</strong> Nexi prohibits retaliation against anyone making good faith reports of misconduct.
        </div>
    </div>

    <div class="section">
        <h3>11. Disciplinary Procedures</h3>
        <p><strong>Progressive Discipline Process:</strong></p>
        <ul>
            <li><strong>Verbal Warning:</strong> First minor violation, documented</li>
            <li><strong>Written Warning:</strong> Continued or more serious violations</li>
            <li><strong>Final Written Warning:</strong> Serious violations or repeated offenses</li>
            <li><strong>Termination:</strong> Gross misconduct or failure to improve</li>
        </ul>
        
        <p><strong>Gross Misconduct (Immediate Termination):</strong></p>
        <ul>
            <li>Theft, fraud, or dishonesty</li>
            <li>Violence, threats, or serious harassment</li>
            <li>Serious data breaches or security violations</li>
            <li>Criminal activity affecting work</li>
            <li>Serious insubordination</li>
        </ul>
    </div>

    <div class="section">
        <h3>12. Policy Updates & Training</h3>
        <p><strong>Ongoing Obligations:</strong></p>
        <ul>
            <li>Attend mandatory training sessions</li>
            <li>Review policy updates promptly</li>
            <li>Ask questions when policies are unclear</li>
            <li>Stay current with regulatory changes</li>
            <li>Participate in policy development feedback</li>
        </ul>
    </div>

    <div class="signature-section">
        <h3>Acknowledgment & Agreement</h3>
        
        <div class="important">
            <p>By signing below, I acknowledge that I have read, understood, and agree to comply with all Nexi company policies and procedures. I understand that violations may result in disciplinary action up to and including termination.</p>
        </div>
        
        <div class="signature-box">
            <p><strong>Employee Signature:</strong></p>
            <p>Name: _________________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
            <p>Age: __________________________________</p>
        </div>

        <div class="signature-box" style="background: #e8f5e8;">
            <p><strong>Parent/Guardian Acknowledgment (Required if employee is under 16):</strong></p>
            <p>I acknowledge that I have reviewed these policies and understand the expectations for my child\'s employment with Nexi.</p>
            <p>Name: _________________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
            <p>Relationship to Employee: ______________</p>
        </div>

        <div class="signature-box">
            <p><strong>Nexi Representative:</strong></p>
            <p>Name: _________________________________</p>
            <p>Title: ________________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
        </div>
    </div>

    <div class="legal-notice">
        <p><strong>Legal Framework:</strong> These policies comply with UK employment law including the Employment Rights Act 1996, Equality Act 2010, Health and Safety at Work Act 1974, Data Protection Act 2018, and related regulations. Policies are reviewed annually and updated as needed to maintain legal compliance.</p>
        
        <p><strong>Document Version:</strong> POLICIES-UK-2024-v1.0 | <strong>Effective Date:</strong> Upon signature | <strong>Next Review:</strong> Annual</p>
    </div>
</body>
</html>';

    $stmt = $pdo->prepare('INSERT INTO contract_templates (name, type, content, is_assignable, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
    $result = $stmt->execute([
        'Company Policies & Procedures Agreement',
        'policies',
        $policies_content,
        1
    ]);
    
    if ($result) {
        echo "Successfully inserted Company Policies contract.\n";
    } else {
        echo "Failed to insert Company Policies contract.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
