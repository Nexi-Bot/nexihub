<?php
require_once __DIR__ . '/config/config.php';

echo "Updating production contracts with comprehensive UK legal documents...\n\n";

// Delete existing contracts
$pdo->exec("DELETE FROM contract_templates");
$pdo->exec("ALTER TABLE contract_templates AUTO_INCREMENT = 1");

// Comprehensive UK-legal contracts with Nexi branding
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
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.8; color: #1a202c; max-width: 900px; margin: 0 auto; padding: 30px; background: #f8fafc; }
        .contract-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h1 { color: #2b6cb0; text-align: center; border-bottom: 4px solid #2b6cb0; padding-bottom: 15px; font-size: 2.2em; margin-bottom: 30px; }
        h2 { color: #2c5282; margin-top: 35px; font-size: 1.4em; border-left: 4px solid #2c5282; padding-left: 15px; }
        h3 { color: #3182ce; margin-top: 25px; font-size: 1.2em; }
        .header { text-align: center; margin-bottom: 40px; }
        .nexi-brand { background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: center; }
        .section { margin-bottom: 25px; }
        .subsection { margin-left: 25px; margin-bottom: 15px; }
        .critical { background: #fed7d7; border: 2px solid #e53e3e; padding: 15px; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .important { background: #bee3f8; border: 2px solid #2b6cb0; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .warning { background: #fef5e7; border: 2px solid #dd6b20; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .signature-section { margin-top: 50px; border-top: 3px solid #2b6cb0; padding-top: 30px; }
        ul, ol { padding-left: 30px; }
        li { margin-bottom: 8px; }
        .clause-number { font-weight: bold; color: #2c5282; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #cbd5e0; padding: 12px; text-align: left; }
        th { background: #edf2f7; font-weight: bold; }
        .data-protection { background: #f0fff4; border: 2px solid #38a169; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .ip-protection { background: #faf5ff; border: 2px solid #805ad5; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="contract-container">
        <div class="nexi-brand">
            <h1 style="margin: 0; border: none; color: white;">NEXI HUB LIMITED</h1>
            <p style="margin: 10px 0 0 0; font-size: 1.1em;">Voluntary Contract of Employment</p>
        </div>

        <div class="critical">
            <strong>LEGALLY BINDING CONTRACT:</strong> This document creates binding legal obligations under English Law. Both parties must comply with all terms. Breach may result in legal action including injunctive relief and damages.
        </div>

        <div class="section">
            <h2>CONTRACTING PARTIES</h2>
            <p><strong>EMPLOYER:</strong> Nexi Hub Limited, a private limited company incorporated in England and Wales (Company No. [TO BE INSERTED]), whose registered office is at [REGISTERED OFFICE ADDRESS] ("Company", "Nexi Hub", "we", "us")</p>
            <p><strong>EMPLOYEE:</strong> The individual whose details appear in the execution section below ("Employee", "you", "Volunteer")</p>
        </div>

        <div class="section">
            <h2><span class="clause-number">1.</span> NATURE OF ENGAGEMENT</h2>
            <div class="important">
                <p><strong>1.1 Voluntary Status:</strong> This is a voluntary engagement. No salary, wages, or guaranteed compensation is payable, though the Company may at its absolute discretion provide benefits, expenses, recognition, equity participation, or future compensation.</p>
                <p><strong>1.2 Not Employment Contract:</strong> This agreement does not create an employment relationship for statutory purposes, though common law duties apply.</p>
                <p><strong>1.3 Conversion Rights:</strong> The Company reserves the right to convert this to paid employment at any time with mutual agreement.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">2.</span> ROLE AND RESPONSIBILITIES</h2>
            <div class="subsection">
                <p><strong>2.1 Position:</strong> Your role, department, and specific duties are as specified in your individual profile and may be varied by the Company.</p>
                <p><strong>2.2 Core Duties:</strong> You agree to:</p>
                <ul>
                    <li>Perform all assigned tasks diligently and professionally</li>
                    <li>Attend required meetings, training, and company events</li>
                    <li>Maintain professional standards in all interactions</li>
                    <li>Follow all company policies, procedures, and lawful instructions</li>
                    <li>Contribute positively to team objectives and company culture</li>
                    <li>Maintain accurate records of your activities and time</li>
                </ul>
                <p><strong>2.3 Availability:</strong> While voluntary, you commit to reasonable availability during agreed hours and will provide adequate notice of unavailability.</p>
                <p><strong>2.4 Performance Standards:</strong> You must maintain professional performance standards. Poor performance may result in role adjustment or termination.</p>
            </div>
        </div>

        <div class="data-protection">
            <h2><span class="clause-number">3.</span> DATA PROTECTION AND CONFIDENTIALITY</h2>
            <div class="subsection">
                <p><strong>3.1 GDPR Compliance:</strong> You must comply with UK GDPR, Data Protection Act 2018, and all applicable data protection laws.</p>
                <p><strong>3.2 Customer Data:</strong> You shall:</p>
                <ul>
                    <li>Process personal data only as instructed and for legitimate business purposes</li>
                    <li>Implement appropriate technical and organisational security measures</li>
                    <li>Report data breaches immediately (within 1 hour of discovery)</li>
                    <li>Not transfer data outside the UK without explicit authorization</li>
                    <li>Delete or return all personal data upon termination</li>
                    <li>Submit to data protection audits and training</li>
                </ul>
                <p><strong>3.3 Staff Data Protection:</strong> Employee personal data including salary information, contact details, performance records, and private communications must be kept strictly confidential.</p>
                <p><strong>3.4 Confidential Information:</strong> All non-public information is confidential, including:</p>
                <ul>
                    <li>Business strategies, financial information, customer lists</li>
                    <li>Technical specifications, algorithms, system architectures</li>
                    <li>Supplier relationships, pricing structures, contract terms</li>
                    <li>Internal processes, methodologies, and know-how</li>
                    <li>Personnel information and internal communications</li>
                    <li>Any information marked confidential or which should reasonably be considered confidential</li>
                </ul>
                <p><strong>3.5 Perpetual Confidentiality:</strong> These obligations survive termination indefinitely.</p>
            </div>
        </div>

        <div class="ip-protection">
            <h2><span class="clause-number">4.</span> INTELLECTUAL PROPERTY AND CODE PROTECTION</h2>
            <div class="subsection">
                <p><strong>4.1 Company Ownership:</strong> All intellectual property created during your engagement belongs exclusively to the Company, including:</p>
                <ul>
                    <li>Software code, scripts, algorithms, and technical documentation</li>
                    <li>System designs, architectures, and methodologies</li>
                    <li>Business processes, strategies, and operational procedures</li>
                    <li>Creative works, content, and marketing materials</li>
                    <li>Inventions, improvements, and innovations</li>
                    <li>Databases, data structures, and analytical models</li>
                </ul>
                <p><strong>4.2 Assignment of Rights:</strong> You hereby assign all present and future intellectual property rights to the Company and agree to execute any documents necessary to perfect such assignment.</p>
                <p><strong>4.3 Source Code Security:</strong> You must:</p>
                <ul>
                    <li>Keep all source code, APIs, and technical specifications strictly confidential</li>
                    <li>Use only company-approved development environments and tools</li>
                    <li>Implement secure coding practices and conduct code reviews</li>
                    <li>Not copy, distribute, or reverse engineer company code</li>
                    <li>Report any suspected intellectual property theft immediately</li>
                    <li>Return all code, documentation, and development materials upon termination</li>
                </ul>
                <p><strong>4.4 Third-Party IP:</strong> You warrant that your contributions do not infringe third-party rights and will indemnify the Company against any claims.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">5.</span> INFORMATION SECURITY AND PASSWORD MANAGEMENT</h2>
            <div class="warning">
                <p><strong>5.1 System Security:</strong> You are responsible for maintaining the security of all company systems and data:</p>
                <ul>
                    <li><strong>Password Requirements:</strong> Use strong, unique passwords (minimum 12 characters, mixed case, numbers, symbols)</li>
                    <li><strong>Multi-Factor Authentication:</strong> Enable MFA on all company accounts and systems</li>
                    <li><strong>Access Controls:</strong> Only access systems necessary for your role</li>
                    <li><strong>Session Management:</strong> Log out of all systems when finished, lock screens when away</li>
                    <li><strong>Device Security:</strong> Secure all devices with encryption, automatic locks, and current security patches</li>
                    <li><strong>Network Security:</strong> Use only secure, approved networks for company business</li>
                </ul>
                <p><strong>5.2 Prohibited Activities:</strong> You must not:</p>
                <ul>
                    <li>Share passwords, API keys, or access credentials</li>
                    <li>Install unauthorized software or browser extensions</li>
                    <li>Connect personal devices to company networks without approval</li>
                    <li>Access systems outside your authorized scope</li>
                    <li>Attempt to bypass security controls or exploit vulnerabilities</li>
                    <li>Store company data on personal devices or cloud services</li>
                </ul>
                <p><strong>5.3 Incident Response:</strong> Immediately report any security incidents, suspected breaches, or policy violations.</p>
                <p><strong>5.4 Monitoring:</strong> Company systems may be monitored for security and compliance purposes.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">6.</span> COMPLIANCE AND LEGAL OBLIGATIONS</h2>
            <div class="subsection">
                <p><strong>6.1 Legal Compliance:</strong> You must comply with all applicable laws including:</p>
                <ul>
                    <li>Data Protection Act 2018 and UK GDPR</li>
                    <li>Computer Misuse Act 1990</li>
                    <li>Copyright, Designs and Patents Act 1988</li>
                    <li>Fraud Act 2006</li>
                    <li>Bribery Act 2010</li>
                    <li>Equality Act 2010</li>
                    <li>Health and Safety at Work Act 1974</li>
                </ul>
                <p><strong>6.2 Anti-Bribery and Corruption:</strong> Zero tolerance for bribery, corruption, or facilitation payments.</p>
                <p><strong>6.3 Money Laundering:</strong> Report any suspicious financial activities immediately.</p>
                <p><strong>6.4 Export Controls:</strong> Comply with all UK export control and sanctions regulations.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">7.</span> OUTSIDE INTERESTS AND CONFLICTS</h2>
            <div class="subsection">
                <p><strong>7.1 Disclosure:</strong> Immediately disclose any actual or potential conflicts of interest.</p>
                <p><strong>7.2 Competing Activities:</strong> During engagement, you may not:</p>
                <ul>
                    <li>Work for or advise competitors or potential competitors</li>
                    <li>Develop competing products or services</li>
                    <li>Solicit company employees, customers, or suppliers for competing ventures</li>
                    <li>Use company resources for personal projects</li>
                </ul>
                <p><strong>7.3 Financial Interests:</strong> Declare any financial interests in suppliers, customers, or competitors.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">8.</span> HEALTH, SAFETY AND WELLBEING</h2>
            <div class="subsection">
                <p><strong>8.1 Health and Safety:</strong> Comply with all health and safety policies and report hazards immediately.</p>
                <p><strong>8.2 Workplace Setup:</strong> Maintain a safe working environment whether on-site or remote.</p>
                <p><strong>8.3 Mental Health:</strong> Company supports mental health and wellbeing. Resources are available through management.</p>
                <p><strong>8.4 Incident Reporting:</strong> Report all accidents, near-misses, and health concerns immediately.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">9.</span> SOCIAL MEDIA AND COMMUNICATIONS</h2>
            <div class="subsection">
                <p><strong>9.1 Professional Representation:</strong> Maintain professional standards in all communications.</p>
                <p><strong>9.2 Social Media:</strong> Do not make unauthorized statements about the company on social media.</p>
                <p><strong>9.3 Media Contacts:</strong> Refer all media inquiries to senior management.</p>
                <p><strong>9.4 External Communications:</strong> Obtain approval before representing the company publicly.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">10.</span> TERMINATION</h2>
            <div class="subsection">
                <p><strong>10.1 Termination by Notice:</strong> Either party may terminate with 30 days written notice.</p>
                <p><strong>10.2 Immediate Termination:</strong> Company may terminate immediately for:</p>
                <ul>
                    <li>Breach of confidentiality or data protection obligations</li>
                    <li>Intellectual property violations or unauthorized use of company property</li>
                    <li>Security breaches or unauthorized system access</li>
                    <li>Violation of legal or regulatory requirements</li>
                    <li>Gross misconduct, including harassment or discrimination</li>
                    <li>Competing activities or conflicts of interest</li>
                    <li>Poor performance after reasonable improvement opportunity</li>
                </ul>
                <p><strong>10.3 Return of Property:</strong> Upon termination, immediately return all company property, data, and confidential information.</p>
                <p><strong>10.4 Survival:</strong> Confidentiality, intellectual property, and non-compete obligations survive termination.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">11.</span> REMEDIES AND ENFORCEMENT</h2>
            <div class="critical">
                <p><strong>11.1 Injunctive Relief:</strong> Breach may cause irreparable harm entitling the Company to injunctive relief without proving damages.</p>
                <p><strong>11.2 Damages:</strong> You may be liable for all losses, costs, and expenses resulting from breach.</p>
                <p><strong>11.3 Legal Costs:</strong> Breaching party pays reasonable legal costs of enforcement.</p>
                <p><strong>11.4 Criminal Liability:</strong> Some breaches may constitute criminal offenses under UK law.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">12.</span> GENERAL PROVISIONS</h2>
            <div class="subsection">
                <p><strong>12.1 Entire Agreement:</strong> This agreement constitutes the entire agreement and supersedes all prior arrangements.</p>
                <p><strong>12.2 Amendments:</strong> Changes must be in writing and signed by both parties.</p>
                <p><strong>12.3 Severability:</strong> Invalid provisions do not affect the validity of remaining provisions.</p>
                <p><strong>12.4 Governing Law:</strong> This agreement is governed by English law and subject to English court jurisdiction.</p>
                <p><strong>12.5 Service:</strong> Legal notices may be served by email or registered post.</p>
            </div>
        </div>

        <div class="signature-section">
            <div class="important">
                <p><strong>ACKNOWLEDGMENT AND AGREEMENT</strong></p>
                <p>By executing this agreement, I acknowledge that I have read, understood, and agree to be bound by all terms and conditions. I understand the serious legal implications of this agreement and confirm I have had the opportunity to seek independent legal advice.</p>
            </div>
            
            <table>
                <tr>
                    <td><strong>Employee Name:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Employee Signature:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Company Representative:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Company Signature:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>_________________________________</td>
                </tr>
            </table>
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
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.8; color: #1a202c; max-width: 900px; margin: 0 auto; padding: 30px; background: #f8fafc; }
        .conduct-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h1 { color: #2b6cb0; text-align: center; border-bottom: 4px solid #2b6cb0; padding-bottom: 15px; font-size: 2.2em; margin-bottom: 30px; }
        h2 { color: #2c5282; margin-top: 35px; font-size: 1.4em; border-left: 4px solid #2c5282; padding-left: 15px; }
        h3 { color: #3182ce; margin-top: 25px; font-size: 1.2em; }
        .nexi-brand { background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: center; }
        .section { margin-bottom: 25px; }
        .subsection { margin-left: 25px; margin-bottom: 15px; }
        .critical { background: #fed7d7; border: 2px solid #e53e3e; padding: 15px; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .important { background: #bee3f8; border: 2px solid #2b6cb0; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .warning { background: #fef5e7; border: 2px solid #dd6b20; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .zero-tolerance { background: #fed7d7; border: 3px solid #c53030; padding: 20px; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        ul, ol { padding-left: 30px; }
        li { margin-bottom: 8px; }
        .clause-number { font-weight: bold; color: #2c5282; }
        .signature-section { margin-top: 50px; border-top: 3px solid #2b6cb0; padding-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #cbd5e0; padding: 12px; text-align: left; }
        th { background: #edf2f7; font-weight: bold; }
    </style>
</head>
<body>
    <div class="conduct-container">
        <div class="nexi-brand">
            <h1 style="margin: 0; border: none; color: white;">NEXI HUB LIMITED</h1>
            <p style="margin: 10px 0 0 0; font-size: 1.1em;">Staff Code of Conduct</p>
        </div>

        <div class="critical">
            <strong>MANDATORY COMPLIANCE:</strong> This Code of Conduct is legally binding on all staff, volunteers, contractors, and associates. Violations may result in disciplinary action up to and including immediate termination and potential legal action.
        </div>

        <div class="section">
            <h2><span class="clause-number">1.</span> FUNDAMENTAL PRINCIPLES</h2>
            <div class="important">
                <p>All Nexi Hub team members must uphold the highest standards of professional conduct, integrity, and ethical behavior. We are committed to:</p>
                <ul>
                    <li><strong>Integrity:</strong> Acting honestly and transparently in all business dealings</li>
                    <li><strong>Respect:</strong> Treating all individuals with dignity and professional courtesy</li>
                    <li><strong>Excellence:</strong> Delivering high-quality work and continuous improvement</li>
                    <li><strong>Accountability:</strong> Taking responsibility for actions and decisions</li>
                    <li><strong>Compliance:</strong> Following all applicable laws, regulations, and company policies</li>
                </ul>
            </div>
        </div>

        <div class="zero-tolerance">
            <h2><span class="clause-number">2.</span> ZERO TOLERANCE POLICIES</h2>
            <div class="subsection">
                <p><strong>Nexi Hub maintains absolute zero tolerance for:</strong></p>
                <ul>
                    <li><strong>Harassment and Discrimination:</strong> Any form of harassment, bullying, or discrimination based on protected characteristics</li>
                    <li><strong>Sexual Misconduct:</strong> Unwelcome sexual advances, requests for sexual favors, or other verbal/physical conduct of a sexual nature</li>
                    <li><strong>Violence and Threats:</strong> Physical violence, threats of violence, or intimidating behavior</li>
                    <li><strong>Substance Abuse:</strong> Being under the influence of alcohol, illegal drugs, or controlled substances during work</li>
                    <li><strong>Theft and Fraud:</strong> Theft of company property, data, or fraudulent activities</li>
                    <li><strong>Corruption and Bribery:</strong> Offering, accepting, or soliciting bribes or improper payments</li>
                    <li><strong>Data Breaches:</strong> Unauthorized access, use, or disclosure of confidential information</li>
                </ul>
                <p><strong>ANY VIOLATION WILL RESULT IN IMMEDIATE INVESTIGATION AND LIKELY TERMINATION</strong></p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">3.</span> PROFESSIONAL CONDUCT STANDARDS</h2>
            <div class="subsection">
                <p><strong>3.1 General Behavior:</strong></p>
                <ul>
                    <li>Maintain professional demeanor in all business interactions</li>
                    <li>Treat colleagues, clients, suppliers, and stakeholders with respect and courtesy</li>
                    <li>Use appropriate language and avoid offensive, discriminatory, or inflammatory comments</li>
                    <li>Dress appropriately for your role and any customer interactions</li>
                    <li>Be punctual and reliable in meeting commitments and deadlines</li>
                    <li>Contribute positively to team dynamics and company culture</li>
                </ul>
                <p><strong>3.2 Communication Standards:</strong></p>
                <ul>
                    <li>Communicate clearly, honestly, and professionally</li>
                    <li>Listen actively and respond constructively to feedback</li>
                    <li>Resolve conflicts through appropriate channels</li>
                    <li>Maintain confidentiality of sensitive discussions</li>
                    <li>Use company communication systems appropriately</li>
                </ul>
                <p><strong>3.3 Work Quality:</strong></p>
                <ul>
                    <li>Deliver work to the highest possible standard</li>
                    <li>Meet deadlines and commitments consistently</li>
                    <li>Seek help when needed and offer assistance to colleagues</li>
                    <li>Continuously develop skills and knowledge</li>
                    <li>Follow established processes and procedures</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">4.</span> DATA PROTECTION AND INFORMATION SECURITY</h2>
            <div class="warning">
                <p><strong>4.1 Customer Data Protection (UK GDPR Compliance):</strong></p>
                <ul>
                    <li>Process personal data only for legitimate business purposes and in accordance with data protection principles</li>
                    <li>Implement appropriate technical and organizational measures to ensure data security</li>
                    <li>Obtain proper consent before collecting or processing personal data</li>
                    <li>Respond to data subject requests (access, rectification, erasure, portability) within statutory timeframes</li>
                    <li>Report suspected data breaches immediately (within 1 hour) to the Data Protection Officer</li>
                    <li>Complete mandatory data protection training and maintain certification</li>
                    <li>Never transfer personal data outside the UK without proper safeguards</li>
                    <li>Conduct data protection impact assessments for high-risk processing</li>
                </ul>
                <p><strong>4.2 Staff Data Confidentiality:</strong></p>
                <ul>
                    <li>Employee personal information, including salary details, performance reviews, and private communications, is strictly confidential</li>
                    <li>Access staff data only when necessary for your role</li>
                    <li>Do not discuss employee personal information with unauthorized individuals</li>
                    <li>Secure all employee records and maintain accurate data processing records</li>
                </ul>
                <p><strong>4.3 Information Security:</strong></p>
                <ul>
                    <li>Protect all company confidential information from unauthorized disclosure</li>
                    <li>Use strong passwords and enable multi-factor authentication on all accounts</li>
                    <li>Keep software and systems updated with latest security patches</li>
                    <li>Report security incidents and suspicious activities immediately</li>
                    <li>Do not use personal devices or cloud services for company data without approval</li>
                    <li>Secure your workspace and lock devices when unattended</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">5.</span> INTELLECTUAL PROPERTY AND CODE PROTECTION</h2>
            <div class="subsection">
                <p><strong>5.1 Company IP Protection:</strong></p>
                <ul>
                    <li>All source code, algorithms, system designs, and technical documentation are company intellectual property</li>
                    <li>Do not copy, distribute, reverse engineer, or unauthorized use of company code</li>
                    <li>Implement secure coding practices and conduct thorough code reviews</li>
                    <li>Use only approved development tools and environments</li>
                    <li>Document code appropriately while maintaining security considerations</li>
                    <li>Report any suspected intellectual property theft or infringement</li>
                </ul>
                <p><strong>5.2 Third-Party IP Respect:</strong></p>
                <ul>
                    <li>Ensure all code contributions are original or properly licensed</li>
                    <li>Verify licensing terms for all third-party libraries and components</li>
                    <li>Obtain proper authorization before using external code or resources</li>
                    <li>Report any potential IP infringement concerns immediately</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">6.</span> FINANCIAL INTEGRITY AND ANTI-CORRUPTION</h2>
            <div class="subsection">
                <p><strong>6.1 Financial Reporting:</strong></p>
                <ul>
                    <li>Maintain accurate financial records and expense reporting</li>
                    <li>Follow proper authorization procedures for expenditures</li>
                    <li>Report any suspected financial irregularities immediately</li>
                    <li>Cooperate fully with internal and external audits</li>
                </ul>
                <p><strong>6.2 Anti-Bribery and Corruption (Bribery Act 2010):</strong></p>
                <ul>
                    <li>Never offer, accept, or solicit bribes, kickbacks, or improper payments</li>
                    <li>Avoid conflicts of interest and declare any potential conflicts</li>
                    <li>Follow proper procedures for gifts, entertainment, and hospitality</li>
                    <li>Report any requests for improper payments or corrupt practices</li>
                </ul>
                <p><strong>6.3 Money Laundering Prevention:</strong></p>
                <ul>
                    <li>Be alert to suspicious financial activities or transactions</li>
                    <li>Verify the identity and legitimacy of new business partners</li>
                    <li>Report unusual payment requests or financial arrangements</li>
                    <li>Comply with all anti-money laundering regulations</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">7.</span> HEALTH, SAFETY AND WELLBEING</h2>
            <div class="subsection">
                <p><strong>7.1 Workplace Safety:</strong></p>
                <ul>
                    <li>Follow all health and safety procedures and guidelines</li>
                    <li>Report accidents, near-misses, and safety hazards immediately</li>
                    <li>Use personal protective equipment where required</li>
                    <li>Maintain a clean and safe working environment</li>
                    <li>Participate in safety training and emergency procedures</li>
                </ul>
                <p><strong>7.2 Remote Work Safety:</strong></p>
                <ul>
                    <li>Set up a safe and ergonomic home office environment</li>
                    <li>Take regular breaks and maintain work-life balance</li>
                    <li>Ensure adequate lighting, ventilation, and equipment</li>
                    <li>Report any work-related injuries or health concerns</li>
                </ul>
                <p><strong>7.3 Mental Health and Wellbeing:</strong></p>
                <ul>
                    <li>Support colleagues\' mental health and wellbeing</li>
                    <li>Seek help when experiencing work-related stress or difficulties</li>
                    <li>Maintain appropriate work-life boundaries</li>
                    <li>Participate in wellbeing initiatives and support programs</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">8.</span> EQUAL OPPORTUNITIES AND INCLUSION</h2>
            <div class="important">
                <p><strong>8.1 Equality and Diversity:</strong></p>
                <ul>
                    <li>Treat all individuals fairly regardless of age, gender, race, religion, sexual orientation, disability, or other protected characteristics</li>
                    <li>Create an inclusive environment where everyone can thrive</li>
                    <li>Value diverse perspectives and contributions</li>
                    <li>Challenge discrimination and inappropriate behavior</li>
                    <li>Support equal opportunities in recruitment, development, and progression</li>
                </ul>
                <p><strong>8.2 Reasonable Adjustments:</strong></p>
                <ul>
                    <li>Support colleagues who require reasonable adjustments for disabilities</li>
                    <li>Ensure accessibility in all workplace practices and procedures</li>
                    <li>Provide necessary accommodations for religious or cultural requirements</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">9.</span> SOCIAL MEDIA AND EXTERNAL COMMUNICATIONS</h2>
            <div class="subsection">
                <p><strong>9.1 Social Media Guidelines:</strong></p>
                <ul>
                    <li>Maintain professional standards when referencing the company on social media</li>
                    <li>Do not share confidential company information on social platforms</li>
                    <li>Ensure personal opinions are clearly distinguished from company positions</li>
                    <li>Respect colleagues\' privacy and do not share their personal information</li>
                    <li>Report any inappropriate social media content involving the company</li>
                </ul>
                <p><strong>9.2 Media and Public Relations:</strong></p>
                <ul>
                    <li>Refer all media inquiries to senior management or designated spokesperson</li>
                    <li>Do not make public statements on behalf of the company without authorization</li>
                    <li>Ensure accuracy in any public communications about company activities</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">10.</span> CONFLICTS OF INTEREST</h2>
            <div class="subsection">
                <p><strong>10.1 Declaration Requirements:</strong></p>
                <ul>
                    <li>Disclose any actual or potential conflicts of interest immediately</li>
                    <li>Declare financial interests in suppliers, customers, or competitors</li>
                    <li>Report personal relationships that may affect business decisions</li>
                    <li>Seek approval before taking on outside employment or directorships</li>
                </ul>
                <p><strong>10.2 Managing Conflicts:</strong></p>
                <ul>
                    <li>Avoid situations where personal interests conflict with company interests</li>
                    <li>Abstain from decisions where you have a declared conflict</li>
                    <li>Follow company procedures for managing identified conflicts</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">11.</span> REPORTING AND WHISTLEBLOWING</h2>
            <div class="important">
                <p><strong>11.1 Reporting Obligations:</strong></p>
                <ul>
                    <li>Report violations of this Code of Conduct immediately</li>
                    <li>Report suspected illegal activities, fraud, or corruption</li>
                    <li>Report safety hazards and security incidents promptly</li>
                    <li>Report harassment, discrimination, or inappropriate behavior</li>
                </ul>
                <p><strong>11.2 Reporting Channels:</strong></p>
                <ul>
                    <li>Direct supervisor or line manager</li>
                    <li>Human Resources department</li>
                    <li>Senior management team</li>
                    <li>Anonymous whistleblowing hotline (where available)</li>
                    <li>External regulatory authorities (for serious breaches)</li>
                </ul>
                <p><strong>11.3 Protection Against Retaliation:</strong></p>
                <ul>
                    <li>No retaliation against individuals who report concerns in good faith</li>
                    <li>Anonymous reporting options available where possible</li>
                    <li>Investigation procedures ensure confidentiality and fairness</li>
                    <li>Support provided to whistleblowers throughout the process</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">12.</span> DISCIPLINARY PROCEDURES</h2>
            <div class="critical">
                <p><strong>12.1 Progressive Discipline:</strong></p>
                <ol>
                    <li><strong>Verbal Warning:</strong> Informal discussion and guidance for minor violations</li>
                    <li><strong>Written Warning:</strong> Formal written warning for continued or more serious violations</li>
                    <li><strong>Final Written Warning:</strong> Final opportunity to improve before termination</li>
                    <li><strong>Termination:</strong> Immediate termination for gross misconduct or repeated violations</li>
                </ol>
                <p><strong>12.2 Gross Misconduct (Immediate Termination):</strong></p>
                <ul>
                    <li>Theft, fraud, or dishonesty</li>
                    <li>Violence, threats, or harassment</li>
                    <li>Serious data protection or security breaches</li>
                    <li>Discrimination or hate speech</li>
                    <li>Substance abuse during work hours</li>
                    <li>Serious breach of confidentiality</li>
                    <li>Criminal activity affecting the company</li>
                </ul>
                <p><strong>12.3 Investigation Process:</strong></p>
                <ul>
                    <li>Fair and impartial investigation of all reported violations</li>
                    <li>Right to representation during disciplinary proceedings</li>
                    <li>Written records of all disciplinary actions</li>
                    <li>Appeals process for disciplinary decisions</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">13.</span> TRAINING AND AWARENESS</h2>
            <div class="subsection">
                <p><strong>13.1 Mandatory Training:</strong></p>
                <ul>
                    <li>Complete Code of Conduct training upon joining and annually thereafter</li>
                    <li>Attend data protection and information security training</li>
                    <li>Participate in equality and diversity awareness programs</li>
                    <li>Complete role-specific compliance training as required</li>
                </ul>
                <p><strong>13.2 Ongoing Awareness:</strong></p>
                <ul>
                    <li>Stay informed about updates to policies and procedures</li>
                    <li>Participate in compliance communications and initiatives</li>
                    <li>Seek guidance when uncertain about appropriate conduct</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">14.</span> COMPLIANCE AND ACKNOWLEDGMENT</h2>
            <div class="critical">
                <p><strong>14.1 Legal Compliance:</strong> This Code of Conduct incorporates requirements from:</p>
                <ul>
                    <li>Employment Rights Act 1996</li>
                    <li>Equality Act 2010</li>
                    <li>Data Protection Act 2018 and UK GDPR</li>
                    <li>Health and Safety at Work Act 1974</li>
                    <li>Bribery Act 2010</li>
                    <li>Computer Misuse Act 1990</li>
                    <li>Human Rights Act 1998</li>
                </ul>
                <p><strong>14.2 Updates and Amendments:</strong> This Code may be updated to reflect changes in law, regulation, or company policy. All staff will be notified of changes and required to confirm understanding.</p>
                <p><strong>14.3 Governing Law:</strong> This Code is governed by English law and regulations.</p>
            </div>
        </div>

        <div class="signature-section">
            <div class="important">
                <p><strong>EMPLOYEE ACKNOWLEDGMENT</strong></p>
                <p>I acknowledge that I have read, understood, and agree to comply with this Staff Code of Conduct. I understand that violation may result in disciplinary action up to and including termination of employment/engagement. I commit to upholding these standards and reporting any violations I observe.</p>
            </div>
            
            <table>
                <tr>
                    <td><strong>Employee Name:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Employee Signature:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Witness Name:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Witness Signature:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>_________________________________</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>',
        'is_assignable' => 1
    ]
];

// Insert the first 2 contracts
foreach ($contracts as $contract) {
    $stmt = $pdo->prepare("INSERT INTO contract_templates (name, type, content, is_assignable, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$contract['name'], $contract['type'], $contract['content'], $contract['is_assignable']]);
    echo "✓ Updated: {$contract['name']}\n";
}

echo "\nFirst 2 contracts updated with comprehensive UK legal content!\n";
?>
