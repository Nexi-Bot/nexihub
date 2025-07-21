<?php
require_once 'config/config.php';

// Comprehensive UK-legal contracts with Nexi branding
$contracts = [
    [
        'id' => 1,
        'name' => 'Voluntary Contract of Employment',
        'type' => 'employment',
        'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntary Contract of Employment</title>
    <style>
        body { font-family: "Times New Roman", serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #1a365d; text-align: center; border-bottom: 3px solid #1a365d; padding-bottom: 10px; }
        h2 { color: #2d4a87; margin-top: 30px; }
        h3 { color: #4a5568; margin-top: 25px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { background: #f7fafc; padding: 15px; border-left: 4px solid #2d4a87; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .subsection { margin-left: 20px; margin-bottom: 10px; }
        .important { background: #fef5e7; border: 1px solid #f6ad55; padding: 10px; border-radius: 5px; margin: 15px 0; }
        .critical { background: #fed7d7; border: 1px solid #fc8181; padding: 10px; border-radius: 5px; margin: 15px 0; }
        .signature-section { margin-top: 40px; border-top: 2px solid #e2e8f0; padding-top: 20px; }
        ol li { margin-bottom: 10px; }
        ul li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>VOLUNTARY CONTRACT OF EMPLOYMENT</h1>
        <div class="company-info">
            <strong>NEXI HUB LIMITED</strong><br>
            Company Registration Number: [TO BE INSERTED]<br>
            Registered Office: [TO BE INSERTED]<br>
            Email: admin@nexihub.co.uk<br>
            Governing Law: England and Wales
        </div>
    </div>

    <div class="critical">
        <strong>IMPORTANT:</strong> This is a comprehensive, legally binding employment contract governed by UK employment law. By signing, you agree to all terms and conditions. This contract creates mutual legal obligations and rights under English law.
    </div>

    <div class="section">
        <h2>1. PARTIES AND COMMENCEMENT</h2>
        <div class="subsection">
            <p><strong>EMPLOYER:</strong> Nexi Hub Limited ("Company" or "Nexi Hub")</p>
            <p><strong>EMPLOYEE:</strong> [Name to be inserted] ("Employee" or "You")</p>
            <p><strong>COMMENCEMENT DATE:</strong> [Date to be inserted]</p>
            <p><strong>CONTINUOUS EMPLOYMENT:</strong> Your period of continuous employment began on [Date]</p>
        </div>
    </div>

    <div class="section">
        <h2>2. JOB TITLE, DUTIES AND WORKPLACE</h2>
        <div class="subsection">
            <p><strong>Position:</strong> [Job Title to be inserted]</p>
            <p><strong>Reporting Structure:</strong> You shall report to [Manager] or such other person as designated.</p>
            <p><strong>Primary Workplace:</strong> [Location], with flexibility for remote working as agreed.</p>
            
            <div class="important">
                <h3>Key Responsibilities:</h3>
                <ul>
                    <li>Perform duties diligently, competently, and in the Company\'s best interests</li>
                    <li>Comply with all Company policies, procedures, and lawful instructions</li>
                    <li>Maintain confidentiality of all proprietary and sensitive information</li>
                    <li>Protect Company intellectual property and trade secrets</li>
                    <li>Adhere to data protection and cybersecurity protocols</li>
                    <li>Participate in training and professional development as required</li>
                    <li>Maintain professional standards befitting the Company\'s reputation</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>3. WORKING TIME AND FLEXIBILITY</h2>
        <div class="subsection">
            <p><strong>Normal Hours:</strong> [Hours] per week, typically [Days and Times]</p>
            <p><strong>Flexible Working:</strong> The Company supports flexible working arrangements subject to business needs.</p>
            <p><strong>Additional Hours:</strong> You may be required to work additional hours as reasonably necessary.</p>
            <p><strong>Working Time Regulations:</strong> This contract complies with the Working Time Regulations 1998.</p>
            
            <div class="important">
                <p><strong>Rest Periods:</strong> You are entitled to daily and weekly rest periods as required by law. The Company monitors working time to ensure compliance with statutory limits.</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>4. REMUNERATION AND BENEFITS</h2>
        <div class="subsection">
            <p><strong>Basic Salary:</strong> £[Amount] per annum, payable monthly in arrears</p>
            <p><strong>Review:</strong> Salary may be reviewed annually but increases are not guaranteed</p>
            <p><strong>Deductions:</strong> The Company will deduct income tax, National Insurance, and any other statutory or authorized deductions</p>
            
            <h3>Additional Benefits:</h3>
            <ul>
                <li><strong>Pension:</strong> Auto-enrollment in Company pension scheme meeting statutory requirements</li>
                <li><strong>Holiday Pay:</strong> Statutory holiday pay during annual leave</li>
                <li><strong>Sick Pay:</strong> Statutory Sick Pay as per legal requirements</li>
                <li><strong>Professional Development:</strong> Training opportunities and skill development support</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>5. ANNUAL LEAVE AND TIME OFF</h2>
        <div class="subsection">
            <p><strong>Annual Entitlement:</strong> 28 days (including bank holidays) increasing with service</p>
            <p><strong>Holiday Year:</strong> [Dates] or as specified in the Staff Handbook</p>
            <p><strong>Booking:</strong> Holiday requests require advance approval and cannot be unreasonably refused</p>
            <p><strong>Carry Over:</strong> Limited carry-over permitted with management approval</p>
            
            <div class="important">
                <h3>Other Leave Entitlements:</h3>
                <ul>
                    <li><strong>Sick Leave:</strong> Full sick pay entitlement as per Company policy</li>
                    <li><strong>Maternity/Paternity:</strong> Enhanced leave and pay beyond statutory minimums</li>
                    <li><strong>Emergency Leave:</strong> Reasonable time off for dependants\' emergencies</li>
                    <li><strong>Study Leave:</strong> Support for relevant professional development</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>6. NOTICE PERIODS AND TERMINATION</h2>
        <div class="subsection">
            <p><strong>Probationary Period:</strong> [Duration] during which notice is [Period]</p>
            <p><strong>After Probation:</strong> [Notice period] written notice required from either party</p>
            <p><strong>Garden Leave:</strong> The Company may require you to remain away from work during notice</p>
            <p><strong>Payment in Lieu:</strong> The Company may terminate by making payment instead of working notice</p>
            
            <div class="critical">
                <h3>Summary Dismissal:</h3>
                <p>The Company may terminate immediately without notice or payment in lieu for:</p>
                <ul>
                    <li>Gross misconduct or serious breach of contract</li>
                    <li>Criminal conviction affecting ability to perform duties</li>
                    <li>Serious negligence or breach of confidentiality</li>
                    <li>Unauthorized disclosure of trade secrets or confidential information</li>
                    <li>Breach of non-compete or non-solicitation provisions</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>7. CONFIDENTIALITY AND DATA PROTECTION</h2>
        <div class="subsection">
            <p><strong>Confidential Information:</strong> You must maintain absolute confidentiality regarding:</p>
            <ul>
                <li>All business strategies, plans, and confidential information</li>
                <li>Client data, customer lists, and commercial relationships</li>
                <li>Technical specifications, source code, and intellectual property</li>
                <li>Financial information, pricing, and commercial terms</li>
                <li>Staff personal data and internal Company information</li>
            </ul>

            <div class="critical">
                <h3>Data Protection Obligations:</h3>
                <p><strong>GDPR Compliance:</strong> You must comply with all data protection laws including:</p>
                <ul>
                    <li>Processing personal data only as authorized and for legitimate purposes</li>
                    <li>Implementing appropriate security measures</li>
                    <li>Reporting data breaches immediately</li>
                    <li>Respecting data subject rights and privacy</li>
                    <li>Completing mandatory data protection training</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>8. INTELLECTUAL PROPERTY AND INNOVATIONS</h2>
        <div class="subsection">
            <p><strong>Company Ownership:</strong> All intellectual property created during employment belongs to the Company including:</p>
            <ul>
                <li>Software code, algorithms, and technical developments</li>
                <li>Business processes, methodologies, and improvements</li>
                <li>Inventions, discoveries, and innovations</li>
                <li>Written materials, documentation, and creative works</li>
                <li>Client relationships and commercial opportunities</li>
            </ul>

            <div class="important">
                <p><strong>Moral Rights:</strong> You waive moral rights in works created for the Company to the extent permitted by law.</p>
                <p><strong>Assistance:</strong> You agree to assist with patent applications and IP protection as reasonably required.</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>9. POST-EMPLOYMENT RESTRICTIONS</h2>
        <div class="critical">
            <h3>Non-Competition (12 months):</h3>
            <p>For 12 months after termination, you shall not:</p>
            <ul>
                <li>Work for or provide services to direct competitors</li>
                <li>Establish competing business in similar markets</li>
                <li>Solicit Company clients or customers</li>
                <li>Induce Company employees to leave employment</li>
            </ul>

            <h3>Non-Solicitation (24 months):</h3>
            <p>For 24 months after termination, you shall not solicit business from Company clients with whom you had material contact.</p>
            
            <p><strong>Enforceability:</strong> These restrictions are reasonable and necessary to protect legitimate business interests.</p>
        </div>
    </div>

    <div class="section">
        <h2>10. DISCIPLINARY AND GRIEVANCE PROCEDURES</h2>
        <div class="subsection">
            <p><strong>ACAS Code:</strong> The Company follows ACAS guidelines for disciplinary and grievance procedures.</p>
            
            <h3>Disciplinary Process:</h3>
            <ol>
                <li><strong>Investigation:</strong> Fair investigation of alleged misconduct</li>
                <li><strong>Meeting:</strong> Formal meeting with right to representation</li>
                <li><strong>Decision:</strong> Written outcome with clear reasons</li>
                <li><strong>Appeal:</strong> Right of appeal to senior management</li>
            </ol>

            <h3>Grievance Procedure:</h3>
            <ol>
                <li><strong>Informal Discussion:</strong> Initial discussion with line manager</li>
                <li><strong>Formal Grievance:</strong> Written complaint to HR</li>
                <li><strong>Investigation:</strong> Impartial investigation process</li>
                <li><strong>Resolution:</strong> Written response and proposed resolution</li>
            </ol>
        </div>
    </div>

    <div class="section">
        <h2>11. HEALTH, SAFETY AND WELLBEING</h2>
        <div class="subsection">
            <p><strong>Company Commitment:</strong> Nexi Hub is committed to maintaining a safe, healthy work environment.</p>
            <p><strong>Employee Duties:</strong> You must:</p>
            <ul>
                <li>Follow all health and safety policies and procedures</li>
                <li>Report hazards, accidents, and near-misses immediately</li>
                <li>Use protective equipment and follow safe working practices</li>
                <li>Participate in health and safety training</li>
                <li>Support colleagues\' wellbeing and mental health</li>
            </ul>

            <div class="important">
                <p><strong>Mental Health Support:</strong> The Company provides access to mental health resources and encourages open dialogue about wellbeing.</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>12. GENERAL PROVISIONS</h2>
        <div class="subsection">
            <p><strong>Entire Agreement:</strong> This contract constitutes the entire agreement between the parties.</p>
            <p><strong>Variation:</strong> Changes must be agreed in writing and signed by both parties.</p>
            <p><strong>Severability:</strong> If any provision is unenforceable, the remainder remains valid.</p>
            <p><strong>Governing Law:</strong> This contract is governed by English law and subject to English court jurisdiction.</p>
            
            <div class="important">
                <p><strong>Collective Agreements:</strong> This contract incorporates relevant collective agreements where applicable.</p>
                <p><strong>Statutory Rights:</strong> Nothing in this contract affects your statutory employment rights.</p>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <h2>SIGNATURES</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="border: 1px solid #ddd; padding: 15px; width: 50%;">
                    <strong>EMPLOYEE:</strong><br><br>
                    Signature: _________________________<br>
                    Print Name: ________________________<br>
                    Date: _____________________________
                </td>
                <td style="border: 1px solid #ddd; padding: 15px; width: 50%;">
                    <strong>COMPANY (Nexi Hub Limited):</strong><br><br>
                    Signature: _________________________<br>
                    Print Name: ________________________<br>
                    Title: ____________________________<br>
                    Date: _____________________________
                </td>
            </tr>
        </table>
        
        <div class="important" style="margin-top: 20px;">
            <p><strong>Legal Advice:</strong> Both parties acknowledge they have had opportunity to seek independent legal advice before signing this agreement.</p>
        </div>
    </div>
</body>
</html>'
    ],
    [
        'id' => 2,
        'name' => 'Staff Code of Conduct',
        'type' => 'conduct',
        'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Code of Conduct</title>
    <style>
        body { font-family: "Times New Roman", serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #1a365d; text-align: center; border-bottom: 3px solid #1a365d; padding-bottom: 10px; }
        h2 { color: #2d4a87; margin-top: 30px; }
        h3 { color: #4a5568; margin-top: 25px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { background: #f7fafc; padding: 15px; border-left: 4px solid #2d4a87; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .conduct-box { background: #f0fff4; border: 1px solid #68d391; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .violation-box { background: #fed7d7; border: 1px solid #fc8181; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .important { background: #fef5e7; border: 1px solid #f6ad55; padding: 10px; border-radius: 5px; margin: 15px 0; }
        ol li { margin-bottom: 10px; }
        ul li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>STAFF CODE OF CONDUCT</h1>
        <div class="company-info">
            <strong>NEXI HUB LIMITED</strong><br>
            Comprehensive Professional Standards and Behavioral Guidelines<br>
            Effective Date: [DATE]<br>
            Review Date: [DATE]<br>
            Version: 2024.1
        </div>
    </div>

    <div class="important">
        <strong>MANDATORY COMPLIANCE:</strong> This Code of Conduct is contractually binding for all employees, contractors, and representatives of Nexi Hub. Violation may result in disciplinary action up to and including termination and legal action.
    </div>

    <div class="section">
        <h2>1. FUNDAMENTAL PRINCIPLES</h2>
        <div class="conduct-box">
            <h3>Core Values:</h3>
            <ul>
                <li><strong>Integrity:</strong> Act honestly, transparently, and ethically in all dealings</li>
                <li><strong>Respect:</strong> Treat all individuals with dignity, fairness, and courtesy</li>
                <li><strong>Excellence:</strong> Maintain highest professional standards and quality</li>
                <li><strong>Accountability:</strong> Take responsibility for actions and decisions</li>
                <li><strong>Innovation:</strong> Embrace creativity while maintaining ethical boundaries</li>
                <li><strong>Collaboration:</strong> Work constructively with colleagues, clients, and stakeholders</li>
            </ul>
        </div>

        <div class="violation-box">
            <h3>Prohibited Conduct:</h3>
            <p>The following behaviors are strictly prohibited and may result in immediate disciplinary action:</p>
            <ul>
                <li>Dishonesty, fraud, or misrepresentation in any form</li>
                <li>Discrimination, harassment, or bullying of any kind</li>
                <li>Breach of confidentiality or unauthorized disclosure of information</li>
                <li>Misuse of company resources, data, or intellectual property</li>
                <li>Conflict of interest without proper disclosure and approval</li>
                <li>Violation of laws, regulations, or professional standards</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>2. PROFESSIONAL CONDUCT AND STANDARDS</h2>
        <div class="conduct-box">
            <h3>2.1 Work Performance:</h3>
            <ul>
                <li><strong>Diligence:</strong> Perform duties competently, efficiently, and with attention to detail</li>
                <li><strong>Punctuality:</strong> Arrive on time for work, meetings, and commitments</li>
                <li><strong>Reliability:</strong> Meet deadlines and deliver on promises and commitments</li>
                <li><strong>Quality:</strong> Ensure work meets or exceeds required standards</li>
                <li><strong>Continuous Improvement:</strong> Seek opportunities to enhance skills and knowledge</li>
            </ul>

            <h3>2.2 Communication Standards:</h3>
            <ul>
                <li><strong>Professional Language:</strong> Use appropriate, respectful language in all communications</li>
                <li><strong>Clarity:</strong> Communicate clearly, accurately, and concisely</li>
                <li><strong>Responsiveness:</strong> Respond to communications promptly and appropriately</li>
                <li><strong>Documentation:</strong> Maintain accurate records and documentation</li>
                <li><strong>Constructive Feedback:</strong> Provide and receive feedback professionally</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>3. WORKPLACE BEHAVIOR AND RELATIONSHIPS</h2>
        <div class="conduct-box">
            <h3>3.1 Respectful Workplace:</h3>
            <ul>
                <li><strong>Dignity:</strong> Treat all colleagues with respect regardless of position, background, or personal characteristics</li>
                <li><strong>Inclusivity:</strong> Foster an inclusive environment that values diversity</li>
                <li><strong>Collaboration:</strong> Work cooperatively and support team objectives</li>
                <li><strong>Conflict Resolution:</strong> Address disagreements professionally and constructively</li>
                <li><strong>Mentorship:</strong> Support colleagues\' professional development when appropriate</li>
            </ul>

            <h3>3.2 Prohibited Workplace Behaviors:</h3>
            <ul>
                <li>Discrimination based on protected characteristics</li>
                <li>Sexual harassment or inappropriate sexual conduct</li>
                <li>Bullying, intimidation, or aggressive behavior</li>
                <li>Gossiping, spreading rumors, or undermining colleagues</li>
                <li>Creating hostile or uncomfortable work environment</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>4. CONFIDENTIALITY AND DATA PROTECTION</h2>
        <div class="violation-box">
            <h3>4.1 Absolute Confidentiality Requirements:</h3>
            <p><strong>Confidential Information includes but is not limited to:</strong></p>
            <ul>
                <li>All client data, personal information, and commercial details</li>
                <li>Business strategies, plans, and competitive information</li>
                <li>Technical specifications, source code, and intellectual property</li>
                <li>Financial data, pricing, and commercial terms</li>
                <li>Internal communications and strategic discussions</li>
                <li>Staff personal information and employment details</li>
            </ul>

            <h3>4.2 GDPR and Data Protection Obligations:</h3>
            <ul>
                <li><strong>Lawful Processing:</strong> Process personal data only for authorized, legitimate purposes</li>
                <li><strong>Data Minimization:</strong> Collect and process only necessary data</li>
                <li><strong>Accuracy:</strong> Ensure data accuracy and keep records up to date</li>
                <li><strong>Security:</strong> Implement appropriate technical and organizational security measures</li>
                <li><strong>Breach Reporting:</strong> Report suspected data breaches immediately</li>
                <li><strong>Subject Rights:</strong> Respect and facilitate data subject rights and requests</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>5. TECHNOLOGY AND CYBERSECURITY</h2>
        <div class="violation-box">
            <h3>5.1 IT Security Requirements:</h3>
            <ul>
                <li><strong>Password Security:</strong> Use strong, unique passwords and enable multi-factor authentication</li>
                <li><strong>System Access:</strong> Access only systems and data necessary for job functions</li>
                <li><strong>Software Installation:</strong> Install only approved, licensed software</li>
                <li><strong>Email Security:</strong> Exercise caution with attachments and links</li>
                <li><strong>Remote Access:</strong> Use secure, approved methods for remote work</li>
                <li><strong>Device Management:</strong> Maintain security of company devices and personal devices used for work</li>
            </ul>

            <h3>5.2 Prohibited Technology Use:</h3>
            <ul>
                <li>Accessing inappropriate, illegal, or non-work-related content</li>
                <li>Installing unauthorized software or bypassing security measures</li>
                <li>Sharing passwords or allowing unauthorized access</li>
                <li>Using company systems for personal commercial activities</li>
                <li>Downloading or sharing copyrighted materials without authorization</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>6. CONFLICTS OF INTEREST</h2>
        <div class="important">
            <h3>6.1 Disclosure Requirements:</h3>
            <p><strong>Must be disclosed immediately:</strong></p>
            <ul>
                <li>Financial interests in competitors, clients, or suppliers</li>
                <li>Personal relationships with clients, suppliers, or colleagues that may affect judgment</li>
                <li>Outside employment or business activities that may compete with company interests</li>
                <li>Gifts, entertainment, or benefits from clients or suppliers</li>
                <li>Use of company resources for personal benefit</li>
                <li>Access to confidential information that could benefit personal investments</li>
            </ul>

            <h3>6.2 Gift and Entertainment Policy:</h3>
            <ul>
                <li><strong>Modest Gifts:</strong> Gifts under £50 may be accepted if infrequent and appropriate</li>
                <li><strong>Business Entertainment:</strong> Reasonable business meals and events are permitted</li>
                <li><strong>Prohibited:</strong> Cash gifts, frequent gifts, excessive entertainment, or anything that could influence business decisions</li>
                <li><strong>Documentation:</strong> All significant gifts and entertainment must be recorded</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>7. HEALTH, SAFETY, AND WELLBEING</h2>
        <div class="conduct-box">
            <h3>7.1 Workplace Safety:</h3>
            <ul>
                <li><strong>Risk Assessment:</strong> Identify and report health and safety hazards</li>
                <li><strong>Safe Practices:</strong> Follow all safety procedures and use protective equipment</li>
                <li><strong>Incident Reporting:</strong> Report all accidents, injuries, and near-misses immediately</li>
                <li><strong>Training Compliance:</strong> Participate in required health and safety training</li>
                <li><strong>Emergency Procedures:</strong> Know and follow emergency evacuation and response procedures</li>
            </ul>

            <h3>7.2 Mental Health and Wellbeing:</h3>
            <ul>
                <li><strong>Support Culture:</strong> Create supportive environment for mental health discussions</li>
                <li><strong>Workload Management:</strong> Communicate concerns about excessive workload or stress</li>
                <li><strong>Work-Life Balance:</strong> Respect colleagues\' time and personal boundaries</li>
                <li><strong>Resource Utilization:</strong> Use available mental health and wellness resources</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>8. COMPLIANCE AND LEGAL OBLIGATIONS</h2>
        <div class="violation-box">
            <h3>8.1 Legal Compliance:</h3>
            <p><strong>All staff must comply with:</strong></p>
            <ul>
                <li><strong>Employment Law:</strong> All applicable UK employment legislation</li>
                <li><strong>Data Protection:</strong> GDPR, DPA 2018, and related privacy laws</li>
                <li><strong>Health & Safety:</strong> Health and Safety at Work Act and related regulations</li>
                <li><strong>Equality Law:</strong> Equality Act 2010 and anti-discrimination legislation</li>
                <li><strong>Financial Regulations:</strong> Anti-money laundering and financial compliance</li>
                <li><strong>Industry Standards:</strong> Relevant professional and industry regulations</li>
            </ul>

            <h3>8.2 Regulatory Obligations:</h3>
            <ul>
                <li>Maintain professional certifications and licenses</li>
                <li>Participate in required regulatory training</li>
                <li>Report regulatory violations or concerns</li>
                <li>Cooperate with regulatory investigations</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>9. REPORTING AND WHISTLEBLOWING</h2>
        <div class="important">
            <h3>9.1 Reporting Obligations:</h3>
            <p><strong>Staff must report:</strong></p>
            <ul>
                <li>Violations of this Code of Conduct</li>
                <li>Illegal activities or regulatory breaches</li>
                <li>Health and safety concerns</li>
                <li>Data breaches or security incidents</li>
                <li>Discrimination, harassment, or bullying</li>
                <li>Conflicts of interest</li>
            </ul>

            <h3>9.2 Protected Disclosure:</h3>
            <ul>
                <li><strong>Protection:</strong> No retaliation against good faith reporting</li>
                <li><strong>Confidentiality:</strong> Reports handled confidentially where possible</li>
                <li><strong>Investigation:</strong> All reports investigated promptly and thoroughly</li>
                <li><strong>External Reporting:</strong> Right to report to relevant authorities if internal procedures inadequate</li>
            </ul>

            <h3>9.3 Reporting Channels:</h3>
            <ul>
                <li>Direct supervisor or line manager</li>
                <li>Human Resources department</li>
                <li>Senior management</li>
                <li>Anonymous reporting system (if available)</li>
                <li>External regulatory bodies where appropriate</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>10. DISCIPLINARY ACTION</h2>
        <div class="violation-box">
            <h3>10.1 Disciplinary Process:</h3>
            <ol>
                <li><strong>Investigation:</strong> Fair and thorough investigation of alleged violations</li>
                <li><strong>Meeting:</strong> Disciplinary meeting with right to representation</li>
                <li><strong>Decision:</strong> Written decision with clear reasons</li>
                <li><strong>Appeal:</strong> Right of appeal to senior management</li>
            </ol>

            <h3>10.2 Disciplinary Measures:</h3>
            <ul>
                <li><strong>Verbal Warning:</strong> For minor first-time violations</li>
                <li><strong>Written Warning:</strong> For more serious or repeated violations</li>
                <li><strong>Final Written Warning:</strong> For serious misconduct or repeated violations</li>
                <li><strong>Dismissal:</strong> For gross misconduct or persistent violations</li>
                <li><strong>Summary Dismissal:</strong> For gross misconduct without notice</li>
            </ul>

            <h3>10.3 Gross Misconduct Examples:</h3>
            <ul>
                <li>Theft, fraud, or dishonesty</li>
                <li>Serious breach of confidentiality</li>
                <li>Discrimination or harassment</li>
                <li>Serious health and safety violations</li>
                <li>Criminal conviction affecting employment</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>11. TRAINING AND AWARENESS</h2>
        <div class="conduct-box">
            <h3>11.1 Mandatory Training:</h3>
            <ul>
                <li><strong>Induction:</strong> Code of Conduct training for all new employees</li>
                <li><strong>Annual Refresher:</strong> Regular updates and refresher training</li>
                <li><strong>Specialized Training:</strong> Role-specific compliance and professional training</li>
                <li><strong>Awareness Sessions:</strong> Regular briefings on policy updates and best practices</li>
            </ul>

            <h3>11.2 Continuous Development:</h3>
            <ul>
                <li>Professional development opportunities</li>
                <li>Skills training and certification support</li>
                <li>Leadership and management development</li>
                <li>Industry knowledge and technical training</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2>12. ACKNOWLEDGMENT AND AGREEMENT</h2>
        <div class="important">
            <p><strong>By signing below, I acknowledge that I have:</strong></p>
            <ul>
                <li>Read and understood this Code of Conduct in its entirety</li>
                <li>Received adequate explanation of my obligations and responsibilities</li>
                <li>Had opportunity to ask questions and seek clarification</li>
                <li>Understood the consequences of violating this Code</li>
                <li>Agreed to comply with all provisions of this Code</li>
                <li>Understood my right and obligation to report violations</li>
            </ul>

            <p><strong>I agree to:</strong></p>
            <ul>
                <li>Comply with this Code of Conduct at all times</li>
                <li>Participate in required training and development</li>
                <li>Report violations or concerns promptly</li>
                <li>Cooperate fully with any investigations</li>
                <li>Maintain the highest professional and ethical standards</li>
            </ul>
        </div>

        <div style="margin-top: 30px; border-top: 2px solid #e2e8f0; padding-top: 20px;">
            <h3>Employee Acknowledgment:</h3>
            <p>Employee Name: _________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
            
            <h3>Manager/HR Confirmation:</h3>
            <p>Name: _________________________________</p>
            <p>Title: ________________________________</p>
            <p>Signature: ____________________________</p>
            <p>Date: _________________________________</p>
        </div>
    </div>
</body>
</html>'
    ]
];

echo "Starting final contract update...\n";

try {
    foreach ($contracts as $contract) {
        $stmt = $pdo->prepare("UPDATE contract_templates SET content = :content WHERE id = :id");
        $result = $stmt->execute([
            'content' => $contract['content'],
            'id' => $contract['id']
        ]);
        
        if ($result) {
            echo "✓ Updated contract ID {$contract['id']}: {$contract['name']}\n";
        } else {
            echo "✗ Failed to update contract ID {$contract['id']}: {$contract['name']}\n";
        }
    }
    
    echo "\nContract update completed!\n";
    echo "Verifying contracts in database...\n";
    
    // Verify the updates
    $stmt = $pdo->prepare("SELECT id, name, type, is_assignable, LENGTH(content) as content_length FROM contract_templates ORDER BY id");
    $stmt->execute();
    $contracts = $stmt->fetchAll();
    
    echo "\nCurrent contracts in database:\n";
    foreach ($contracts as $contract) {
        echo "- ID: {$contract['id']}, Name: {$contract['name']}, Type: {$contract['type']}, Assignable: " . ($contract['is_assignable'] ? 'Yes' : 'No') . ", Content Length: {$contract['content_length']} chars\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
