<?php
require_once __DIR__ . '/config/config.php';

echo "Completing Company Policies and creating Shareholder Agreement...\n\n";

// Complete the Company Policies contract content (continuing from where we left off)
$complete_policies_content = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Policies and Procedures Manual</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.7; color: #1a202c; max-width: 1000px; margin: 0 auto; padding: 30px; background: #f8fafc; }
        .policies-container { background: white; padding: 50px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h1 { color: #2b6cb0; text-align: center; border-bottom: 4px solid #2b6cb0; padding-bottom: 20px; font-size: 2.3em; margin-bottom: 40px; }
        h2 { color: #2c5282; margin-top: 40px; font-size: 1.5em; border-left: 5px solid #2c5282; padding-left: 20px; }
        h3 { color: #3182ce; margin-top: 30px; font-size: 1.3em; border-bottom: 2px solid #bee3f8; padding-bottom: 8px; }
        h4 { color: #4a5568; margin-top: 25px; font-size: 1.1em; }
        .nexi-brand { background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 40px; text-align: center; }
        .section { margin-bottom: 30px; }
        .subsection { margin-left: 25px; margin-bottom: 20px; }
        .policy-box { background: #f7fafc; border: 2px solid #cbd5e0; padding: 20px; border-radius: 8px; margin: 15px 0; }
        .critical { background: #fed7d7; border: 3px solid #c53030; padding: 20px; border-radius: 8px; margin: 25px 0; font-weight: bold; }
        .important { background: #bee3f8; border: 2px solid #2b6cb0; padding: 18px; border-radius: 8px; margin: 20px 0; }
        .warning { background: #fef5e7; border: 2px solid #dd6b20; padding: 18px; border-radius: 8px; margin: 20px 0; }
        .legal-requirement { background: #c6f6d5; border: 2px solid #38a169; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .gdpr-section { background: #e6fffa; border: 3px solid #0694a2; padding: 25px; border-radius: 10px; margin: 25px 0; }
        ul, ol { padding-left: 35px; }
        li { margin-bottom: 10px; }
        .clause-number { font-weight: bold; color: #2c5282; }
        .signature-section { margin-top: 60px; border-top: 4px solid #2b6cb0; padding-top: 40px; }
        table { width: 100%; border-collapse: collapse; margin: 25px 0; }
        th, td { border: 1px solid #cbd5e0; padding: 15px; text-align: left; }
        th { background: #edf2f7; font-weight: bold; }
        .procedure-step { background: #faf5ff; border-left: 4px solid #805ad5; padding: 15px; margin: 10px 0; }
        .zero-tolerance { background: #fed7d7; border: 4px solid #c53030; padding: 25px; border-radius: 10px; margin: 25px 0; text-align: center; }
    </style>
</head>
<body>
    <div class="policies-container">
        <div class="nexi-brand">
            <h1 style="margin: 0; border: none; color: white;">NEXI HUB LIMITED</h1>
            <p style="margin: 15px 0 0 0; font-size: 1.2em;">Comprehensive Company Policies & Procedures Manual</p>
            <p style="margin: 10px 0 0 0; font-size: 1em;">Effective Date: [DATE] | Version: [VERSION]</p>
        </div>

        <div class="critical">
            <strong>MANDATORY COMPLIANCE NOTICE:</strong> All policies in this manual are legally binding and mandatory for all staff, contractors, volunteers, and associates. Violation may result in disciplinary action up to and including immediate termination, legal action, and criminal prosecution where applicable under UK law.
        </div>

        <div class="section">
            <h2><span class="clause-number">6.</span> FINANCIAL CONTROLS AND ANTI-CORRUPTION</h2>
            
            <div class="legal-requirement">
                <h3>6.1 Anti-Bribery and Corruption (Bribery Act 2010)</h3>
                <div class="zero-tolerance">
                    <h4>ZERO TOLERANCE FOR BRIBERY AND CORRUPTION</h4>
                    <p>Any form of bribery or corruption will result in immediate termination and criminal prosecution.</p>
                </div>

                <h4>Prohibited Activities:</h4>
                <ul>
                    <li>Offering, promising, or giving bribes to any person</li>
                    <li>Requesting, agreeing to receive, or accepting bribes</li>
                    <li>Bribing foreign public officials</li>
                    <li>Facilitating or enabling bribery by third parties</li>
                    <li>Offering or accepting facilitation payments</li>
                    <li>Providing excessive gifts, hospitality, or entertainment</li>
                </ul>

                <h4>Gifts and Hospitality:</h4>
                <ul>
                    <li>Must be reasonable, proportionate, and infrequent</li>
                    <li>Must not be cash or cash equivalents</li>
                    <li>Must not be offered or received to influence business decisions</li>
                    <li>Must be transparently given and properly recorded</li>
                    <li>Must comply with recipient organization\'s policies</li>
                    <li>Gifts over £50 value must be declared to management</li>
                    <li>Entertainment must be proportionate and include business discussion</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>6.2 Financial Management and Controls</h3>
                <h4>Expense Management:</h4>
                <ul>
                    <li>All business expenses must be legitimate, reasonable, and properly documented</li>
                    <li>Expense claims require original receipts and business justification</li>
                    <li>Approval required before incurring significant expenses</li>
                    <li>Personal expenses must not be claimed as business expenses</li>
                    <li>Expense policies apply equally to all staff levels</li>
                    <li>Regular audits of expense claims and corporate credit card usage</li>
                </ul>

                <h4>Financial Reporting:</h4>
                <ul>
                    <li>All financial records must be accurate, complete, and timely</li>
                    <li>No false, misleading, or incomplete entries in financial records</li>
                    <li>All transactions must be properly authorized and documented</li>
                    <li>Segregation of duties in financial processes</li>
                    <li>Regular reconciliation of accounts and financial statements</li>
                    <li>Cooperation with internal and external auditors</li>
                </ul>

                <h4>Money Laundering Prevention:</h4>
                <ul>
                    <li>Know Your Customer (KYC) procedures for all business relationships</li>
                    <li>Enhanced due diligence for high-risk customers or transactions</li>
                    <li>Suspicious Activity Reporting to relevant authorities</li>
                    <li>Regular training on money laundering indicators</li>
                    <li>Record keeping and transaction monitoring</li>
                    <li>Compliance with sanctions and export control regulations</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">7.</span> INTELLECTUAL PROPERTY AND CONFIDENTIALITY</h2>
            
            <div class="critical">
                <h3>7.1 Company Intellectual Property Protection</h3>
                <h4>Ownership:</h4>
                <ul>
                    <li>All work product created during employment belongs to the company</li>
                    <li>Source code, algorithms, system designs are company property</li>
                    <li>Business processes, methodologies, and know-how are proprietary</li>
                    <li>Customer lists, supplier relationships, and business intelligence are confidential</li>
                    <li>Marketing materials, branding, and creative works are company assets</li>
                </ul>

                <h4>Protection Measures:</h4>
                <ul>
                    <li>Secure development environments and access controls</li>
                    <li>Code review processes and version control systems</li>
                    <li>Confidentiality markings on sensitive documents and systems</li>
                    <li>Regular IP audits and protection strategy reviews</li>
                    <li>Employee training on IP rights and obligations</li>
                    <li>Legal protection through patents, trademarks, and copyrights where appropriate</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>7.2 Trade Secrets and Confidential Information</h3>
                <h4>Classification Levels:</h4>
                <ul>
                    <li><strong>Public:</strong> Information approved for public release</li>
                    <li><strong>Internal:</strong> Information for internal use only</li>
                    <li><strong>Confidential:</strong> Sensitive information requiring protection</li>
                    <li><strong>Strictly Confidential:</strong> Highly sensitive information with restricted access</li>
                    <li><strong>Top Secret:</strong> Critical information requiring highest level protection</li>
                </ul>

                <h4>Handling Requirements:</h4>
                <ul>
                    <li>Clear labeling and marking of confidential information</li>
                    <li>Need-to-know access principles</li>
                    <li>Secure storage and transmission protocols</li>
                    <li>Regular review and reclassification of information</li>
                    <li>Secure disposal of confidential materials</li>
                    <li>Third-party confidentiality agreements where required</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">8.</span> COMMUNICATIONS AND SOCIAL MEDIA</h2>
            
            <div class="policy-box">
                <h3>8.1 External Communications</h3>
                <h4>Media Relations:</h4>
                <ul>
                    <li>All media inquiries must be referred to designated spokesperson</li>
                    <li>No employee may speak to media on behalf of company without authorization</li>
                    <li>Press releases and public statements require senior management approval</li>
                    <li>Crisis communication protocols for negative publicity or incidents</li>
                    <li>Consistent messaging and brand representation</li>
                </ul>

                <h4>Social Media Guidelines:</h4>
                <ul>
                    <li>Personal social media accounts must not represent company positions</li>
                    <li>Clear disclaimer when discussing work-related topics on personal accounts</li>
                    <li>No sharing of confidential company information on social platforms</li>
                    <li>Professional conduct standards apply to all online interactions</li>
                    <li>Respect for colleagues\' privacy and reputation online</li>
                    <li>Immediate reporting of negative or damaging content about the company</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>8.2 Internal Communications</h3>
                <h4>Communication Standards:</h4>
                <ul>
                    <li>Professional, respectful, and constructive communication</li>
                    <li>Clear, concise, and accurate information sharing</li>
                    <li>Appropriate use of communication channels (email, messaging, video calls)</li>
                    <li>Timely responses to business communications</li>
                    <li>Confidentiality of sensitive internal communications</li>
                </ul>

                <h4>Meeting and Documentation:</h4>
                <ul>
                    <li>Professional conduct in all meetings and conference calls</li>
                    <li>Accurate meeting minutes and action item tracking</li>
                    <li>Appropriate sharing of meeting recordings and documents</li>
                    <li>Inclusive participation and respect for diverse viewpoints</li>
                    <li>Secure platforms for confidential discussions</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">9.</span> DISCIPLINARY AND GRIEVANCE PROCEDURES</h2>
            
            <div class="procedure-step">
                <h3>9.1 Disciplinary Procedures</h3>
                <h4>Progressive Discipline Process:</h4>
                <ol>
                    <li><strong>Informal Discussion:</strong> Verbal guidance and coaching for minor issues</li>
                    <li><strong>First Written Warning:</strong> Formal written warning for continued or more serious issues</li>
                    <li><strong>Final Written Warning:</strong> Clear indication that further misconduct may result in dismissal</li>
                    <li><strong>Dismissal:</strong> Termination of employment for continued misconduct or gross misconduct</li>
                </ol>

                <h4>Gross Misconduct (Summary Dismissal):</h4>
                <ul>
                    <li>Theft, fraud, or dishonesty</li>
                    <li>Physical violence, bullying, or harassment</li>
                    <li>Serious breaches of health and safety</li>
                    <li>Discrimination or hate speech</li>
                    <li>Serious data protection or confidentiality breaches</li>
                    <li>Criminal activity affecting the company or role</li>
                    <li>Serious breach of trust and confidence</li>
                    <li>Substance abuse affecting work performance</li>
                </ul>

                <h4>Investigation and Hearing Process:</h4>
                <ul>
                    <li>Prompt and thorough investigation of all allegations</li>
                    <li>Suspension on full pay during investigation where appropriate</li>
                    <li>Right to be accompanied by colleague or trade union representative</li>
                    <li>Written notification of allegations and hearing arrangements</li>
                    <li>Opportunity to present case and call witnesses</li>
                    <li>Written decision with clear reasoning and appeal rights</li>
                </ul>
            </div>

            <div class="procedure-step">
                <h3>9.2 Grievance Procedures</h3>
                <h4>Informal Resolution:</h4>
                <ul>
                    <li>Encourage direct discussion with line manager where appropriate</li>
                    <li>Support and mediation available for informal resolution</li>
                    <li>Documentation of informal resolution attempts</li>
                    <li>Escalation to formal process if informal resolution unsuccessful</li>
                </ul>

                <h4>Formal Grievance Process:</h4>
                <ol>
                    <li><strong>Written Grievance:</strong> Detailed written statement of complaint</li>
                    <li><strong>Investigation:</strong> Thorough investigation by appropriate manager</li>
                    <li><strong>Grievance Hearing:</strong> Opportunity to present case with representation</li>
                    <li><strong>Decision:</strong> Written decision with reasoning and proposed resolution</li>
                    <li><strong>Appeal:</strong> Right of appeal to more senior manager</li>
                    <li><strong>Final Decision:</strong> Written confirmation of final outcome</li>
                </ol>

                <h4>Support and Protection:</h4>
                <ul>
                    <li>Protection from victimization or retaliation</li>
                    <li>Confidentiality maintained throughout process</li>
                    <li>Access to counseling and support services</li>
                    <li>Reasonable adjustments where appropriate</li>
                    <li>Right to be accompanied throughout formal process</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">10.</span> BUSINESS CONTINUITY AND CRISIS MANAGEMENT</h2>
            
            <div class="policy-box">
                <h3>10.1 Business Continuity Planning</h3>
                <h4>Risk Assessment:</h4>
                <ul>
                    <li>Regular evaluation of potential business disruptions</li>
                    <li>Assessment of critical business functions and dependencies</li>
                    <li>Identification of key personnel and skills</li>
                    <li>Analysis of IT systems and data backup requirements</li>
                    <li>Evaluation of supplier and third-party dependencies</li>
                </ul>

                <h4>Continuity Plans:</h4>
                <ul>
                    <li>Documented procedures for maintaining operations during disruptions</li>
                    <li>Alternative work arrangements and remote working capabilities</li>
                    <li>Communication protocols for staff, customers, and stakeholders</li>
                    <li>Data backup and recovery procedures</li>
                    <li>Emergency contact lists and escalation procedures</li>
                    <li>Regular testing and updating of continuity plans</li>
                </ul>
            </div>

            <div class="critical">
                <h3>10.2 Crisis Management</h3>
                <h4>Crisis Response Team:</h4>
                <ul>
                    <li>Designated crisis management team with defined roles</li>
                    <li>Clear decision-making authority and communication channels</li>
                    <li>Regular training and simulation exercises</li>
                    <li>24/7 contact arrangements for key personnel</li>
                </ul>

                <h4>Response Procedures:</h4>
                <ol>
                    <li><strong>Assessment:</strong> Rapid evaluation of situation and potential impact</li>
                    <li><strong>Activation:</strong> Activation of crisis management team and procedures</li>
                    <li><strong>Communication:</strong> Internal and external communication strategies</li>
                    <li><strong>Response:</strong> Implementation of response measures and resource allocation</li>
                    <li><strong>Recovery:</strong> Steps to restore normal operations</li>
                    <li><strong>Review:</strong> Post-crisis evaluation and lessons learned</li>
                </ol>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">11.</span> ENVIRONMENTAL AND SUSTAINABILITY</h2>
            
            <div class="policy-box">
                <h3>11.1 Environmental Responsibility</h3>
                <h4>Waste Reduction:</h4>
                <ul>
                    <li>Minimize waste generation through efficient processes</li>
                    <li>Proper segregation and disposal of different waste types</li>
                    <li>Recycling programs for paper, electronics, and other materials</li>
                    <li>Reduction of single-use items and packaging</li>
                    <li>Paperless processes and digital document management</li>
                </ul>

                <h4>Energy Efficiency:</h4>
                <ul>
                    <li>Energy-efficient equipment and lighting</li>
                    <li>Automatic power management for computers and devices</li>
                    <li>Optimization of heating, cooling, and ventilation systems</li>
                    <li>Promotion of remote working to reduce commuting</li>
                    <li>Consideration of environmental impact in procurement decisions</li>
                </ul>

                <h4>Sustainable Practices:</h4>
                <ul>
                    <li>Sustainable sourcing and procurement policies</li>
                    <li>Consideration of environmental impact in business decisions</li>
                    <li>Support for public transportation and cycle-to-work schemes</li>
                    <li>Environmental awareness training for all staff</li>
                    <li>Regular review and reporting on environmental performance</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">12.</span> TRAINING AND PROFESSIONAL DEVELOPMENT</h2>
            
            <div class="policy-box">
                <h3>12.1 Mandatory Training</h3>
                <h4>Compliance Training:</h4>
                <ul>
                    <li>Data protection and GDPR training (annual refresh required)</li>
                    <li>Health and safety training including fire safety and first aid</li>
                    <li>Information security and cybersecurity awareness</li>
                    <li>Anti-bribery and corruption training</li>
                    <li>Equality, diversity, and inclusion training</li>
                    <li>Code of conduct and company policies training</li>
                </ul>

                <h4>Role-Specific Training:</h4>
                <ul>
                    <li>Technical skills development and certification</li>
                    <li>Customer service and communication skills</li>
                    <li>Management and leadership development</li>
                    <li>Specialized compliance training for specific roles</li>
                    <li>Industry-specific knowledge and qualifications</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>12.2 Professional Development</h3>
                <h4>Development Opportunities:</h4>
                <ul>
                    <li>Individual development plans and career pathways</li>
                    <li>Internal training programs and workshops</li>
                    <li>External courses, conferences, and seminars</li>
                    <li>Mentoring and coaching programs</li>
                    <li>Cross-functional projects and secondments</li>
                    <li>Professional qualification and certification support</li>
                </ul>

                <h4>Performance Management:</h4>
                <ul>
                    <li>Regular performance reviews and feedback sessions</li>
                    <li>Clear objective setting and progress monitoring</li>
                    <li>Recognition and reward programs</li>
                    <li>Support for underperformance through training and development</li>
                    <li>Career advancement opportunities based on merit</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">13.</span> COMPLIANCE AND LEGAL REQUIREMENTS</h2>
            
            <div class="legal-requirement">
                <h3>13.1 Regulatory Compliance</h3>
                <h4>Key Legislation:</h4>
                <ul>
                    <li><strong>Employment Rights Act 1996:</strong> Employment contracts, working time, and dismissal procedures</li>
                    <li><strong>Equality Act 2010:</strong> Anti-discrimination and equal opportunities</li>
                    <li><strong>Data Protection Act 2018 & UK GDPR:</strong> Personal data protection and privacy</li>
                    <li><strong>Health and Safety at Work Act 1974:</strong> Workplace health and safety</li>
                    <li><strong>Companies Act 2006:</strong> Corporate governance and director duties</li>
                    <li><strong>Bribery Act 2010:</strong> Anti-corruption and bribery prevention</li>
                    <li><strong>Computer Misuse Act 1990:</strong> Cybersecurity and system protection</li>
                    <li><strong>Human Rights Act 1998:</strong> Fundamental rights and freedoms</li>
                </ul>

                <h4>Compliance Monitoring:</h4>
                <ul>
                    <li>Regular compliance audits and assessments</li>
                    <li>Legal update monitoring and policy reviews</li>
                    <li>Staff training on legal requirements and changes</li>
                    <li>Incident reporting and regulatory notifications</li>
                    <li>Record keeping and documentation requirements</li>
                    <li>External legal advice and support where required</li>
                </ul>
            </div>

            <div class="critical">
                <h3>13.2 Policy Updates and Communication</h3>
                <h4>Review and Update Process:</h4>
                <ul>
                    <li>Annual review of all policies and procedures</li>
                    <li>Updates following legal or regulatory changes</li>
                    <li>Stakeholder consultation on significant policy changes</li>
                    <li>Version control and change management</li>
                    <li>Communication of changes to all affected staff</li>
                </ul>

                <h4>Training and Awareness:</h4>
                <ul>
                    <li>Regular policy awareness sessions and updates</li>
                    <li>Online policy library with current versions</li>
                    <li>Manager briefings on policy implementation</li>
                    <li>New starter induction including all key policies</li>
                    <li>Regular reminders and compliance communications</li>
                </ul>
            </div>
        </div>

        <div class="signature-section">
            <div class="critical">
                <p><strong>EMPLOYEE ACKNOWLEDGMENT AND AGREEMENT</strong></p>
                <p>I acknowledge that I have received, read, and understood all company policies and procedures contained in this manual. I agree to comply with all policies and understand that:</p>
                <ul>
                    <li>These policies are legally binding and enforceable</li>
                    <li>Violation may result in disciplinary action up to and including termination</li>
                    <li>Policies may be updated and I will be notified of changes</li>
                    <li>I have a responsibility to seek clarification if I do not understand any policy</li>
                    <li>I must report any violations or concerns through appropriate channels</li>
                </ul>
                <p><strong>I commit to upholding these standards and contributing to a compliant, ethical, and professional workplace.</strong></p>
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
                    <td><strong>Department:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Manager/Witness:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>_________________________________</td>
                </tr>
            </table>

            <div class="important" style="margin-top: 30px;">
                <p><strong>For HR Use Only:</strong></p>
                <p>Policy acknowledgment received and filed: ___________</p>
                <p>Employee training record updated: ___________</p>
                <p>HR Representative: _______________________</p>
            </div>
        </div>
    </div>
</body>
</html>';

// Update Company Policies with complete content
$stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 4");
$stmt->execute([$complete_policies_content]);
echo "✓ Completed: Company Policies (ID: 4) - Full comprehensive manual\n";

echo "\nCreating final Shareholder Agreement...\n";
?>
