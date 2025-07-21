<?php
require_once __DIR__ . '/config/config.php';

echo "Updating remaining 3 contracts with comprehensive UK legal content...\n\n";

$contracts = [
    [
        'id' => 3,
        'name' => 'Non-Disclosure Agreement (NDA)',
        'type' => 'nda',
        'content' => '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Disclosure Agreement</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; line-height: 1.8; color: #1a202c; max-width: 900px; margin: 0 auto; padding: 30px; background: #f8fafc; }
        .nda-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h1 { color: #2b6cb0; text-align: center; border-bottom: 4px solid #2b6cb0; padding-bottom: 15px; font-size: 2.2em; margin-bottom: 30px; }
        h2 { color: #2c5282; margin-top: 35px; font-size: 1.4em; border-left: 4px solid #2c5282; padding-left: 15px; }
        h3 { color: #3182ce; margin-top: 25px; font-size: 1.2em; }
        .nexi-brand { background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; text-align: center; }
        .section { margin-bottom: 25px; }
        .subsection { margin-left: 25px; margin-bottom: 15px; }
        .critical { background: #fed7d7; border: 3px solid #c53030; padding: 20px; border-radius: 8px; margin: 20px 0; font-weight: bold; }
        .important { background: #bee3f8; border: 2px solid #2b6cb0; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .warning { background: #fef5e7; border: 2px solid #dd6b20; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .confidential-info { background: #edf2f7; border: 2px solid #4a5568; padding: 20px; border-radius: 8px; margin: 20px 0; }
        ul, ol { padding-left: 30px; }
        li { margin-bottom: 8px; }
        .clause-number { font-weight: bold; color: #2c5282; }
        .signature-section { margin-top: 50px; border-top: 3px solid #2b6cb0; padding-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #cbd5e0; padding: 12px; text-align: left; }
        th { background: #edf2f7; font-weight: bold; }
        .perpetual { background: #c6f6d5; border: 2px solid #38a169; padding: 15px; border-radius: 8px; margin: 20px 0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="nda-container">
        <div class="nexi-brand">
            <h1 style="margin: 0; border: none; color: white;">NEXI HUB LIMITED</h1>
            <p style="margin: 10px 0 0 0; font-size: 1.1em;">Comprehensive Non-Disclosure Agreement</p>
        </div>

        <div class="critical">
            <strong>LEGALLY BINDING CONFIDENTIALITY AGREEMENT:</strong> This agreement creates perpetual legal obligations regarding confidential information. Breach may result in immediate legal action including injunctive relief, damages, and criminal prosecution under UK law.
        </div>

        <div class="section">
            <h2>PARTIES AND EFFECTIVE DATE</h2>
            <p><strong>DISCLOSING PARTY:</strong> Nexi Hub Limited, a private limited company incorporated in England and Wales (Company No. [TO BE INSERTED]), whose registered office is at [REGISTERED OFFICE ADDRESS] ("Company", "Nexi Hub", "Disclosing Party")</p>
            <p><strong>RECEIVING PARTY:</strong> The individual whose details appear in the execution section below ("Recipient", "Receiving Party", "you")</p>
            <p><strong>EFFECTIVE DATE:</strong> The date of execution by both parties</p>
        </div>

        <div class="confidential-info">
            <h2><span class="clause-number">1.</span> DEFINITION OF CONFIDENTIAL INFORMATION</h2>
            <div class="subsection">
                <p><strong>1.1 Confidential Information</strong> means ALL non-public information disclosed by or on behalf of Nexi Hub, whether orally, in writing, electronically, visually, or in any other form, including but not limited to:</p>
                
                <p><strong>Technical Information:</strong></p>
                <ul>
                    <li>Source code, object code, algorithms, software architecture, and system designs</li>
                    <li>APIs, data models, database schemas, and technical specifications</li>
                    <li>Development methodologies, coding standards, and technical documentation</li>
                    <li>Server configurations, security protocols, and system vulnerabilities</li>
                    <li>Artificial intelligence models, machine learning algorithms, and training data</li>
                    <li>Performance metrics, benchmarks, and optimization techniques</li>
                    <li>Research and development projects, prototypes, and experimental features</li>
                    <li>Integration methods, third-party relationships, and technical partnerships</li>
                </ul>

                <p><strong>Business Information:</strong></p>
                <ul>
                    <li>Customer lists, contact information, preferences, and interaction history</li>
                    <li>Supplier lists, vendor relationships, and procurement strategies</li>
                    <li>Financial information including revenue, costs, pricing models, and forecasts</li>
                    <li>Business strategies, market analysis, and competitive intelligence</li>
                    <li>Marketing plans, campaign strategies, and customer acquisition methods</li>
                    <li>Sales processes, conversion rates, and performance metrics</li>
                    <li>Partnership agreements, joint venture terms, and collaboration strategies</li>
                    <li>Merger and acquisition discussions, due diligence materials</li>
                </ul>

                <p><strong>Operational Information:</strong></p>
                <ul>
                    <li>Internal processes, procedures, and operational methodologies</li>
                    <li>Organizational structure, reporting relationships, and management strategies</li>
                    <li>Employee information including personal data, salary details, and performance records</li>
                    <li>Training materials, internal communications, and policy documents</li>
                    <li>Quality assurance procedures, testing protocols, and compliance frameworks</li>
                    <li>Risk management strategies, incident response plans, and security procedures</li>
                    <li>Intellectual property strategies, patent applications, and trademark portfolios</li>
                </ul>

                <p><strong>Personal and Customer Data:</strong></p>
                <ul>
                    <li>All personal data as defined by UK GDPR and Data Protection Act 2018</li>
                    <li>Customer personal information, preferences, and behavioral data</li>
                    <li>Employee personal data, contact details, and sensitive personal information</li>
                    <li>Biometric data, identification information, and authentication credentials</li>
                    <li>Health information, special category data, and protected characteristics</li>
                    <li>Financial data, payment information, and transaction records</li>
                    <li>Communication records, correspondence, and interaction logs</li>
                </ul>

                <p><strong>1.2 Broad Interpretation:</strong> Confidential Information includes information that:</p>
                <ul>
                    <li>Is marked or designated as confidential, proprietary, or restricted</li>
                    <li>Would reasonably be considered confidential by a prudent business person</li>
                    <li>Relates to current, future, or abandoned projects and initiatives</li>
                    <li>Concerns relationships with third parties including customers, suppliers, and partners</li>
                    <li>Has been learned through observation, discussion, or involvement in company activities</li>
                    <li>Comprises compilations, analyses, or derivatives of underlying confidential information</li>
                </ul>

                <p><strong>1.3 Form and Medium:</strong> Confidential Information includes information in any form including written documents, electronic files, oral communications, visual observations, physical samples, prototypes, demonstrations, presentations, meetings, training sessions, and any other medium or format.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">2.</span> CONFIDENTIALITY OBLIGATIONS</h2>
            <div class="subsection">
                <p><strong>2.1 Non-Disclosure:</strong> You agree to:</p>
                <ul>
                    <li>Hold all Confidential Information in strict confidence using the same degree of care you use for your own confidential information, but in no event less than reasonable care</li>
                    <li>Not disclose Confidential Information to any third party without prior written consent from Nexi Hub</li>
                    <li>Not discuss Confidential Information in public places or where it may be overheard</li>
                    <li>Not post, transmit, or share Confidential Information on social media, forums, or public platforms</li>
                    <li>Limit access to Confidential Information to those who have a legitimate need to know and who are bound by similar confidentiality obligations</li>
                </ul>

                <p><strong>2.2 Non-Use:</strong> You agree to:</p>
                <ul>
                    <li>Use Confidential Information solely for the purpose of your engagement with Nexi Hub</li>
                    <li>Not use Confidential Information for personal benefit or the benefit of any third party</li>
                    <li>Not reverse engineer, decompile, or attempt to derive the source code of any software or technology</li>
                    <li>Not create derivative works based on Confidential Information without express written permission</li>
                    <li>Not use Confidential Information to compete with Nexi Hub or develop competing products or services</li>
                </ul>

                <p><strong>2.3 Protection Measures:</strong> You must implement appropriate safeguards including:</p>
                <ul>
                    <li>Physical security measures to protect documents and materials</li>
                    <li>Technical security measures including encryption, access controls, and secure storage</li>
                    <li>Administrative measures including confidentiality training and access logs</li>
                    <li>Network security including secure communications and VPN usage</li>
                    <li>Device security including password protection, encryption, and remote wipe capabilities</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">3.</span> SPECIFIC DATA PROTECTION OBLIGATIONS</h2>
            <div class="warning">
                <p><strong>3.1 UK GDPR Compliance:</strong> When handling personal data as part of Confidential Information, you must:</p>
                <ul>
                    <li>Process personal data only in accordance with UK GDPR and Data Protection Act 2018</li>
                    <li>Implement appropriate technical and organizational measures to ensure data security</li>
                    <li>Not process personal data for purposes incompatible with the original purpose</li>
                    <li>Ensure personal data is accurate, up-to-date, and processed lawfully</li>
                    <li>Not retain personal data longer than necessary for the specified purposes</li>
                    <li>Implement data protection by design and by default principles</li>
                    <li>Report any suspected personal data breaches within 1 hour of discovery</li>
                    <li>Cooperate fully with data protection impact assessments</li>
                </ul>

                <p><strong>3.2 Cross-Border Data Transfers:</strong></p>
                <ul>
                    <li>Do not transfer personal data outside the UK without explicit written authorization</li>
                    <li>Ensure appropriate safeguards are in place for any authorized international transfers</li>
                    <li>Comply with adequacy decisions and standard contractual clauses where applicable</li>
                    <li>Obtain necessary approvals from data protection authorities where required</li>
                </ul>

                <p><strong>3.3 Data Subject Rights:</strong></p>
                <ul>
                    <li>Facilitate the company\'s ability to respond to data subject access requests</li>
                    <li>Support rectification, erasure, and portability requests as directed</li>
                    <li>Assist with objections to processing and withdrawal of consent</li>
                    <li>Maintain accurate records to support data subject rights fulfillment</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">4.</span> EXCEPTIONS TO CONFIDENTIALITY</h2>
            <div class="subsection">
                <p><strong>4.1 Limited Exceptions:</strong> The obligations herein do not apply to information that:</p>
                <ul>
                    <li>Is publicly available through no breach of this agreement by you</li>
                    <li>Was rightfully known to you prior to disclosure and not subject to any confidentiality obligation</li>
                    <li>Is rightfully received from a third party without restriction and without breach of any confidentiality obligation</li>
                    <li>Is independently developed by you without use of or reference to Confidential Information</li>
                    <li>Is required to be disclosed by law, court order, or regulatory requirement (with prior notice to Nexi Hub where legally permitted)</li>
                </ul>

                <p><strong>4.2 Burden of Proof:</strong> You bear the burden of proving that any information falls within these limited exceptions through clear and convincing documentary evidence.</p>

                <p><strong>4.3 Legal Disclosure:</strong> If compelled to disclose Confidential Information by legal process:</p>
                <ul>
                    <li>Provide immediate notice to Nexi Hub (unless legally prohibited)</li>
                    <li>Cooperate with Nexi Hub\'s efforts to obtain protective orders or limitations</li>
                    <li>Disclose only the minimum information required by the legal process</li>
                    <li>Use best efforts to maintain confidentiality of the disclosed information</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">5.</span> RETURN AND DESTRUCTION OF CONFIDENTIAL INFORMATION</h2>
            <div class="subsection">
                <p><strong>5.1 Immediate Return:</strong> Upon termination of your relationship with Nexi Hub or upon request, you must immediately:</p>
                <ul>
                    <li>Return all documents, materials, and tangible items containing Confidential Information</li>
                    <li>Delete all electronic copies from computers, mobile devices, cloud storage, and backup systems</li>
                    <li>Destroy all notes, memoranda, and derivative works containing Confidential Information</li>
                    <li>Clear browser caches, temporary files, and recycle bins of any Confidential Information</li>
                    <li>Provide written certification of compliance with these return and destruction obligations</li>
                </ul>

                <p><strong>5.2 Retained Copies:</strong> You may retain copies only if:</p>
                <ul>
                    <li>Required by law or legal hold obligations</li>
                    <li>Created automatically by computer systems (such as backup tapes) that cannot reasonably be retrieved</li>
                    <li>Necessary for compliance with regulatory requirements</li>
                </ul>
                <p>Any retained copies remain subject to all confidentiality obligations under this agreement.</p>

                <p><strong>5.3 Verification:</strong> Nexi Hub reserves the right to verify compliance with return and destruction obligations through reasonable inspection or audit procedures.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">6.</span> INTELLECTUAL PROPERTY PROTECTION</h2>
            <div class="subsection">
                <p><strong>6.1 No License:</strong> This agreement does not grant you any license or rights to Nexi Hub\'s intellectual property, including patents, trademarks, copyrights, or trade secrets.</p>

                <p><strong>6.2 Ownership:</strong> All Confidential Information remains the exclusive property of Nexi Hub, including any improvements, modifications, or derivative works.</p>

                <p><strong>6.3 Third-Party Rights:</strong> You acknowledge that Confidential Information may include third-party intellectual property licensed to Nexi Hub, and you agree to respect all such third-party rights.</p>

                <p><strong>6.4 Patent Rights:</strong> Disclosure of Confidential Information does not constitute a waiver of any patent rights or other intellectual property rights of Nexi Hub.</p>
            </div>
        </div>

        <div class="perpetual">
            <h2><span class="clause-number">7.</span> DURATION AND SURVIVAL</h2>
            <div class="subsection">
                <p><strong>7.1 Perpetual Obligations:</strong> Your confidentiality obligations under this agreement are PERPETUAL and survive indefinitely, continuing after:</p>
                <ul>
                    <li>Termination of your employment or engagement with Nexi Hub</li>
                    <li>Completion of any specific project or assignment</li>
                    <li>Any change in your relationship with Nexi Hub</li>
                    <li>Dissolution or change in ownership of Nexi Hub</li>
                </ul>

                <p><strong>7.2 No Expiration:</strong> Unlike typical contractual obligations, confidentiality obligations DO NOT EXPIRE and remain in effect for as long as the information retains its confidential nature.</p>

                <p><strong>7.3 Successors and Assigns:</strong> These obligations bind your heirs, successors, and assigns.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">8.</span> REMEDIES AND ENFORCEMENT</h2>
            <div class="critical">
                <p><strong>8.1 Irreparable Harm:</strong> You acknowledge that breach of this agreement would cause irreparable harm to Nexi Hub that cannot be adequately compensated by monetary damages.</p>

                <p><strong>8.2 Injunctive Relief:</strong> Nexi Hub is entitled to seek immediate injunctive relief, specific performance, and other equitable remedies without posting bond and without proving monetary damages.</p>

                <p><strong>8.3 Monetary Damages:</strong> You may be liable for all direct, indirect, consequential, and punitive damages resulting from breach, including:</p>
                <ul>
                    <li>Lost profits and business opportunities</li>
                    <li>Costs of remedial measures and security enhancements</li>
                    <li>Legal fees and investigation costs</li>
                    <li>Regulatory fines and penalties</li>
                    <li>Reputational damage and loss of competitive advantage</li>
                </ul>

                <p><strong>8.4 Criminal Liability:</strong> Certain breaches may constitute criminal offenses under:</p>
                <ul>
                    <li>Computer Misuse Act 1990</li>
                    <li>Data Protection Act 2018</li>
                    <li>Copyright, Designs and Patents Act 1988</li>
                    <li>Fraud Act 2006</li>
                    <li>Trade Secrets Directive (when implemented)</li>
                </ul>

                <p><strong>8.5 Cumulative Remedies:</strong> All remedies are cumulative and not exclusive. Pursuit of one remedy does not preclude others.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">9.</span> ADDITIONAL SECURITY OBLIGATIONS</h2>
            <div class="subsection">
                <p><strong>9.1 Physical Security:</strong></p>
                <ul>
                    <li>Store confidential documents in locked containers when not in use</li>
                    <li>Prevent unauthorized viewing of screens or documents</li>
                    <li>Use privacy screens in public or shared spaces</li>
                    <li>Secure disposal of confidential materials through shredding or equivalent destruction</li>
                    <li>Report any loss, theft, or unauthorized access immediately</li>
                </ul>

                <p><strong>9.2 Digital Security:</strong></p>
                <ul>
                    <li>Use strong authentication methods including multi-factor authentication</li>
                    <li>Encrypt confidential information stored on portable devices</li>
                    <li>Implement endpoint protection and anti-malware software</li>
                    <li>Use secure communication channels for transmitting confidential information</li>
                    <li>Regularly update software and security patches</li>
                    <li>Log out of systems and lock devices when unattended</li>
                </ul>

                <p><strong>9.3 Communication Security:</strong></p>
                <ul>
                    <li>Use encrypted communication channels for sensitive discussions</li>
                    <li>Verify recipient identity before sharing confidential information</li>
                    <li>Avoid discussing confidential matters in public or unsecured locations</li>
                    <li>Use secure file sharing services approved by Nexi Hub</li>
                    <li>Include appropriate confidentiality notices in communications</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">10.</span> INCIDENT REPORTING AND BREACH NOTIFICATION</h2>
            <div class="warning">
                <p><strong>10.1 Immediate Notification:</strong> You must report any actual or suspected breach immediately (within 1 hour) including:</p>
                <ul>
                    <li>Unauthorized disclosure or access to confidential information</li>
                    <li>Loss, theft, or compromise of devices or materials containing confidential information</li>
                    <li>Suspected cyber attacks or security incidents</li>
                    <li>Accidental disclosure or mishandling of confidential information</li>
                    <li>Requests from third parties for access to confidential information</li>
                    <li>Any circumstances that may compromise confidential information</li>
                </ul>

                <p><strong>10.2 Incident Response:</strong> Upon discovering a breach, you must:</p>
                <ul>
                    <li>Take immediate steps to contain and minimize the breach</li>
                    <li>Preserve evidence related to the breach</li>
                    <li>Cooperate fully with Nexi Hub\'s incident response procedures</li>
                    <li>Provide detailed written reports as requested</li>
                    <li>Assist in remedial measures and damage assessment</li>
                    <li>Implement additional security measures as directed</li>
                </ul>

                <p><strong>10.3 Continuing Obligations:</strong> Reporting a breach does not relieve you of ongoing confidentiality obligations or potential liability.</p>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">11.</span> GENERAL PROVISIONS</h2>
            <div class="subsection">
                <p><strong>11.1 Entire Agreement:</strong> This agreement constitutes the entire agreement regarding confidentiality and supersedes all prior understandings or agreements on this subject.</p>

                <p><strong>11.2 Amendments:</strong> This agreement may only be modified by written instrument signed by both parties.</p>

                <p><strong>11.3 Severability:</strong> If any provision is deemed invalid or unenforceable, the remaining provisions remain in full force and effect.</p>

                <p><strong>11.4 Governing Law and Jurisdiction:</strong> This agreement is governed by English law and subject to the exclusive jurisdiction of English courts.</p>

                <p><strong>11.5 Assignment:</strong> You may not assign or transfer your obligations without prior written consent. Nexi Hub may assign this agreement without restriction.</p>

                <p><strong>11.6 Waiver:</strong> No waiver of any provision is effective unless in writing. Waiver of one breach does not waive future breaches.</p>

                <p><strong>11.7 Notice:</strong> All notices must be in writing and delivered by email, registered post, or personal delivery to the addresses specified in this agreement.</p>

                <p><strong>11.8 Interpretation:</strong> This agreement shall be interpreted to maximize protection of Nexi Hub\'s confidential information while remaining legally enforceable.</p>
            </div>
        </div>

        <div class="signature-section">
            <div class="critical">
                <p><strong>ACKNOWLEDGMENT AND AGREEMENT</strong></p>
                <p>By executing this agreement, I acknowledge that I have read, understood, and agree to be bound by all terms and conditions. I understand the serious legal implications of this agreement, including potential criminal liability for certain breaches. I have had the opportunity to seek independent legal advice and I enter into this agreement voluntarily.</p>
                <p><strong>I UNDERSTAND THAT THESE CONFIDENTIALITY OBLIGATIONS ARE PERPETUAL AND SURVIVE INDEFINITELY.</strong></p>
            </div>
            
            <table>
                <tr>
                    <td><strong>Receiving Party Name:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Receiving Party Signature:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td><strong>Nexi Hub Representative:</strong></td>
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
</html>'
    ]
];

// Update the NDA contract
foreach ($contracts as $contract) {
    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = ?");
    $stmt->execute([$contract['content'], $contract['id']]);
    echo "✓ Updated: {$contract['name']} (ID: {$contract['id']})\n";
}

echo "\nNDA contract updated with comprehensive UK legal content!\n";
echo "Continuing with Company Policies...\n";

// Continue with Company Policies contract (ID 4)
$company_policies_contract = [
    'id' => 4,
    'name' => 'Company Policies',
    'type' => 'policies', 
    'content' => '<!DOCTYPE html>
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
            <h2>TABLE OF CONTENTS</h2>
            <div class="policy-box">
                <ol>
                    <li>Employment and Equal Opportunities</li>
                    <li>Data Protection and Privacy (UK GDPR)</li>
                    <li>Information Technology and Cybersecurity</li>
                    <li>Health, Safety and Wellbeing</li>
                    <li>Anti-Harassment and Discrimination</li>
                    <li>Financial Controls and Anti-Corruption</li>
                    <li>Intellectual Property and Confidentiality</li>
                    <li>Communications and Social Media</li>
                    <li>Disciplinary and Grievance Procedures</li>
                    <li>Business Continuity and Crisis Management</li>
                    <li>Environmental and Sustainability</li>
                    <li>Training and Professional Development</li>
                    <li>Compliance and Legal Requirements</li>
                </ol>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">1.</span> EMPLOYMENT AND EQUAL OPPORTUNITIES</h2>
            
            <div class="legal-requirement">
                <h3>1.1 Equal Opportunities Policy (Equality Act 2010)</h3>
                <p><strong>Commitment:</strong> Nexi Hub is committed to providing equal opportunities in employment regardless of:</p>
                <ul>
                    <li>Age, disability, gender reassignment, marriage and civil partnership</li>
                    <li>Pregnancy and maternity, race, religion or belief</li>
                    <li>Sex, sexual orientation, or any other protected characteristic</li>
                    <li>Trade union membership, political affiliation, or social background</li>
                </ul>
                
                <h4>Recruitment and Selection:</h4>
                <ul>
                    <li>Job requirements based solely on role necessities</li>
                    <li>Consistent, fair, and transparent selection processes</li>
                    <li>Reasonable adjustments for disabled candidates</li>
                    <li>Objective assessment criteria applied equally to all candidates</li>
                    <li>Interview panels trained in equality and diversity principles</li>
                </ul>

                <h4>Career Development:</h4>
                <ul>
                    <li>Equal access to training, development, and promotion opportunities</li>
                    <li>Performance management based on objective criteria</li>
                    <li>Support for career progression regardless of background</li>
                    <li>Mentoring and development programs available to all</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>1.2 Flexible Working Arrangements</h3>
                <p><strong>Remote Working:</strong></p>
                <ul>
                    <li>All employees have the right to request flexible working arrangements</li>
                    <li>Requests will be considered fairly and only refused for valid business reasons</li>
                    <li>Home working arrangements must meet health and safety requirements</li>
                    <li>Equipment and technology support provided for remote workers</li>
                    <li>Regular reviews of flexible working arrangements</li>
                </ul>

                <p><strong>Working Time Compliance (Working Time Regulations 1998):</strong></p>
                <ul>
                    <li>Maximum 48-hour average working week (with opt-out where applicable)</li>
                    <li>Minimum 20 minutes break for 6+ hour shifts</li>
                    <li>11 hours minimum daily rest period</li>
                    <li>24 hours minimum weekly rest period</li>
                    <li>5.6 weeks minimum annual leave entitlement</li>
                </ul>
            </div>
        </div>

        <div class="gdpr-section">
            <h2><span class="clause-number">2.</span> DATA PROTECTION AND PRIVACY (UK GDPR)</h2>
            
            <div class="critical">
                <h3>2.1 Data Protection Principles</h3>
                <p>All staff must comply with the Data Protection Act 2018 and UK GDPR when processing personal data:</p>
                
                <h4>Fundamental Principles:</h4>
                <ol>
                    <li><strong>Lawfulness, Fairness, Transparency:</strong> Process data lawfully, fairly, and transparently</li>
                    <li><strong>Purpose Limitation:</strong> Collect data for specified, explicit, legitimate purposes only</li>
                    <li><strong>Data Minimisation:</strong> Process only data that is adequate, relevant, and necessary</li>
                    <li><strong>Accuracy:</strong> Keep personal data accurate and up-to-date</li>
                    <li><strong>Storage Limitation:</strong> Retain data only as long as necessary</li>
                    <li><strong>Integrity and Confidentiality:</strong> Ensure appropriate security measures</li>
                    <li><strong>Accountability:</strong> Demonstrate compliance with all principles</li>
                </ol>
            </div>

            <div class="policy-box">
                <h3>2.2 Customer Data Protection</h3>
                <h4>Processing Requirements:</h4>
                <ul>
                    <li>Obtain valid legal basis before processing (consent, contract, legitimate interest, etc.)</li>
                    <li>Provide clear privacy notices explaining data use</li>
                    <li>Implement data protection by design and by default</li>
                    <li>Conduct Data Protection Impact Assessments for high-risk processing</li>
                    <li>Maintain detailed records of processing activities</li>
                    <li>Implement appropriate technical and organizational measures</li>
                </ul>

                <h4>Data Subject Rights:</h4>
                <ul>
                    <li><strong>Right of Access:</strong> Provide data copies within 1 month</li>
                    <li><strong>Right to Rectification:</strong> Correct inaccurate data promptly</li>
                    <li><strong>Right to Erasure:</strong> Delete data when legally required</li>
                    <li><strong>Right to Restrict Processing:</strong> Limit processing in certain circumstances</li>
                    <li><strong>Right to Data Portability:</strong> Provide data in machine-readable format</li>
                    <li><strong>Right to Object:</strong> Stop processing for direct marketing or legitimate interests</li>
                    <li><strong>Rights related to Automated Decision-Making:</strong> Review automated decisions</li>
                </ul>

                <h4>International Transfers:</h4>
                <ul>
                    <li>No transfers outside UK without adequate protection</li>
                    <li>Use Standard Contractual Clauses where necessary</li>
                    <li>Verify adequacy decisions for destination countries</li>
                    <li>Obtain explicit consent for transfers where required</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>2.3 Employee Data Protection</h3>
                <h4>Staff Personal Data:</h4>
                <ul>
                    <li>Process employee data only for employment, legal, and legitimate business purposes</li>
                    <li>Maintain confidentiality of all employee personal information</li>
                    <li>Secure storage and access controls for HR records</li>
                    <li>Regular review and deletion of unnecessary employee data</li>
                    <li>Transparent policies on employee monitoring and surveillance</li>
                </ul>

                <h4>Special Category Data:</h4>
                <ul>
                    <li>Extra protection for health data, criminal records, biometric data</li>
                    <li>Explicit consent or specific legal basis required</li>
                    <li>Enhanced security measures and access restrictions</li>
                    <li>Regular audits of special category data processing</li>
                </ul>
            </div>

            <div class="critical">
                <h3>2.4 Data Breach Response</h3>
                <div class="procedure-step">
                    <h4>Immediate Response (Within 1 Hour):</h4>
                    <ol>
                        <li>Contain the breach and assess ongoing risk</li>
                        <li>Notify the Data Protection Officer and senior management</li>
                        <li>Document all known facts about the breach</li>
                        <li>Preserve evidence and log all response actions</li>
                        <li>Begin assessment of likely consequences and risks</li>
                    </ol>
                </div>

                <div class="procedure-step">
                    <h4>Within 72 Hours:</h4>
                    <ol>
                        <li>Notify ICO if breach likely to result in risk to individuals</li>
                        <li>Provide required breach notification information</li>
                        <li>Continue investigation and implement remedial measures</li>
                        <li>Assess need for individual notifications</li>
                        <li>Review and update security measures</li>
                    </ol>
                </div>

                <div class="procedure-step">
                    <h4>Ongoing Requirements:</h4>
                    <ul>
                        <li>Maintain detailed breach register</li>
                        <li>Notify affected individuals if high risk to rights and freedoms</li>
                        <li>Cooperate with ICO investigation</li>
                        <li>Implement lessons learned and prevent recurrence</li>
                        <li>Regular breach response training and testing</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">3.</span> INFORMATION TECHNOLOGY AND CYBERSECURITY</h2>
            
            <div class="warning">
                <h3>3.1 Acceptable Use Policy</h3>
                <h4>Permitted Use:</h4>
                <ul>
                    <li>Business-related activities and authorized personal use</li>
                    <li>Professional development and learning activities</li>
                    <li>Communication necessary for work performance</li>
                    <li>Approved software and applications only</li>
                </ul>

                <h4>Prohibited Activities:</h4>
                <ul>
                    <li>Accessing, downloading, or distributing illegal or inappropriate content</li>
                    <li>Installing unauthorized software or browser extensions</li>
                    <li>Attempting to bypass security controls or access restrictions</li>
                    <li>Sharing login credentials or system access</li>
                    <li>Using company resources for personal commercial activities</li>
                    <li>Connecting personal devices to company networks without approval</li>
                    <li>Downloading or streaming non-work-related content during business hours</li>
                    <li>Sending bulk unsolicited emails or spam</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>3.2 Information Security Controls</h3>
                <h4>Authentication and Access:</h4>
                <ul>
                    <li><strong>Password Requirements:</strong> Minimum 12 characters, mixed case, numbers, symbols</li>
                    <li><strong>Multi-Factor Authentication:</strong> Required on all business systems and accounts</li>
                    <li><strong>Access Reviews:</strong> Quarterly review of user access rights and permissions</li>
                    <li><strong>Privileged Access:</strong> Additional controls for administrative and sensitive access</li>
                    <li><strong>Account Monitoring:</strong> Automated monitoring for suspicious account activity</li>
                </ul>

                <h4>Device Security:</h4>
                <ul>
                    <li>All devices must have current operating system and security patches</li>
                    <li>Approved antivirus/anti-malware software required</li>
                    <li>Device encryption mandatory for laptops and mobile devices</li>
                    <li>Automatic screen locks after 10 minutes of inactivity</li>
                    <li>Remote wipe capability for company-owned mobile devices</li>
                    <li>Regular security assessments and vulnerability scanning</li>
                </ul>

                <h4>Network Security:</h4>
                <ul>
                    <li>Use only approved and secured network connections</li>
                    <li>VPN required for remote access to company systems</li>
                    <li>Guest network access restricted and monitored</li>
                    <li>Regular network security assessments and penetration testing</li>
                    <li>Intrusion detection and prevention systems</li>
                </ul>
            </div>

            <div class="critical">
                <h3>3.3 Cyber Incident Response</h3>
                <h4>Incident Types:</h4>
                <ul>
                    <li>Malware infections and ransomware attacks</li>
                    <li>Phishing and social engineering attempts</li>
                    <li>Unauthorized access or data breaches</li>
                    <li>System compromises and security vulnerabilities</li>
                    <li>Denial of service attacks</li>
                    <li>Insider threats and suspicious user activity</li>
                </ul>

                <h4>Response Procedures:</h4>
                <ol>
                    <li><strong>Immediate:</strong> Isolate affected systems, preserve evidence</li>
                    <li><strong>Within 1 Hour:</strong> Notify IT security team and management</li>
                    <li><strong>Assessment:</strong> Determine scope, impact, and required response</li>
                    <li><strong>Containment:</strong> Implement containment measures to prevent spread</li>
                    <li><strong>Recovery:</strong> Restore systems from clean backups where necessary</li>
                    <li><strong>Lessons Learned:</strong> Post-incident review and improvement plans</li>
                </ol>
            </div>
        </div>

        <div class="section">
            <h2><span class="clause-number">4.</span> HEALTH, SAFETY AND WELLBEING</h2>
            
            <div class="legal-requirement">
                <h3>4.1 Health and Safety at Work (Health and Safety at Work Act 1974)</h3>
                <h4>Employer Duties:</h4>
                <ul>
                    <li>Provide safe working environment and equipment</li>
                    <li>Ensure safe handling, storage, and transport of substances</li>
                    <li>Provide adequate training, instruction, and supervision</li>
                    <li>Maintain safe access and egress routes</li>
                    <li>Provide necessary personal protective equipment</li>
                </ul>

                <h4>Employee Responsibilities:</h4>
                <ul>
                    <li>Take reasonable care for own health and safety</li>
                    <li>Consider effects of actions on colleagues and others</li>
                    <li>Cooperate with employer on health and safety matters</li>
                    <li>Use equipment and protective equipment properly</li>
                    <li>Report hazards, accidents, and near-misses immediately</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>4.2 Workplace Safety Procedures</h3>
                <h4>Risk Assessment:</h4>
                <ul>
                    <li>Regular workplace risk assessments conducted</li>
                    <li>Specific assessments for new equipment, processes, or locations</li>
                    <li>Employee involvement in risk identification and control measures</li>
                    <li>Documentation and review of all risk assessments</li>
                    <li>Implementation of hierarchy of controls (elimination, substitution, engineering, administrative, PPE)</li>
                </ul>

                <h4>Incident Reporting:</h4>
                <ul>
                    <li>All accidents, injuries, and near-misses must be reported immediately</li>
                    <li>RIDDOR reportable incidents notified to HSE within required timeframes</li>
                    <li>Investigation of all incidents to identify root causes</li>
                    <li>Implementation of corrective actions to prevent recurrence</li>
                    <li>Maintenance of accident records and statistics</li>
                </ul>

                <h4>Emergency Procedures:</h4>
                <ul>
                    <li>Clear evacuation procedures and assembly points</li>
                    <li>Trained fire wardens and first aiders</li>
                    <li>Regular fire drills and emergency procedure testing</li>
                    <li>Emergency contact information readily available</li>
                    <li>Business continuity plans for various emergency scenarios</li>
                </ul>
            </div>

            <div class="policy-box">
                <h3>4.3 Mental Health and Wellbeing</h3>
                <h4>Support Framework:</h4>
                <ul>
                    <li>Recognition that mental health is equally important as physical health</li>
                    <li>Open culture encouraging discussion of mental health concerns</li>
                    <li>Training for managers on identifying and supporting mental health issues</li>
                    <li>Access to Employee Assistance Programs and counseling services</li>
                    <li>Reasonable adjustments for mental health conditions</li>
                    <li>Stress risk assessments and workload management</li>
                </ul>

                <h4>Work-Life Balance:</h4>
                <ul>
                    <li>Respect for personal time and boundaries</li>
                    <li>Flexible working arrangements where possible</li>
                    <li>Encouragement to take full annual leave entitlement</li>
                    <li>Right to disconnect policies for out-of-hours communications</li>
                    <li>Workload monitoring and redistribution when necessary</li>
                </ul>
            </div>
        </div>

        <div class="zero-tolerance">
            <h2><span class="clause-number">5.</span> ANTI-HARASSMENT AND DISCRIMINATION</h2>
            <h3>ZERO TOLERANCE POLICY</h3>
            <p><strong>Nexi Hub has absolute zero tolerance for harassment, discrimination, bullying, or victimization in any form.</strong></p>
        </div>

        <div class="critical">
            <h3>5.1 Prohibited Conduct</h3>
            <h4>Harassment:</h4>
            <ul>
                <li>Unwanted conduct related to protected characteristics</li>
                <li>Conduct that violates dignity or creates intimidating, hostile, degrading, humiliating, or offensive environment</li>
                <li>Sexual harassment including unwelcome sexual advances, requests for sexual favors, or other verbal/physical conduct of sexual nature</li>
                <li>Cyberbullying, online harassment, or inappropriate use of social media</li>
            </ul>

            <h4>Discrimination:</h4>
            <ul>
                <li>Direct discrimination based on protected characteristics</li>
                <li>Indirect discrimination through apparently neutral practices with discriminatory effect</li>
                <li>Discrimination by association or perception</li>
                <li>Failure to make reasonable adjustments for disabled employees</li>
            </ul>

            <h4>Bullying:</h4>
            <ul>
                <li>Offensive, intimidating, malicious, insulting, or humiliating behavior</li>
                <li>Abuse of power or position to undermine, humiliate, or injure</li>
                <li>Persistent criticism, exclusion from team activities, or unreasonable work demands</li>
                <li>Spreading malicious rumors or making false allegations</li>
            </ul>
        </div>

        <div class="procedure-step">
            <h3>5.2 Reporting and Investigation Procedures</h3>
            <h4>Reporting Options:</h4>
            <ul>
                <li>Direct supervisor or line manager</li>
                <li>Human Resources department</li>
                <li>Senior management team member</li>
                <li>Anonymous reporting mechanisms where available</li>
                <li>External agencies (ACAS, Equality and Human Rights Commission)</li>
            </ul>

            <h4>Investigation Process:</h4>
            <ol>
                <li><strong>Initial Assessment:</strong> Immediate evaluation of complaint and interim measures</li>
                <li><strong>Investigation:</strong> Thorough, impartial investigation by trained investigators</li>
                <li><strong>Interviews:</strong> All relevant parties interviewed with right to representation</li>
                <li><strong>Evidence Review:</strong> Comprehensive review of all available evidence</li>
                <li><strong>Decision:</strong> Written decision with clear reasoning and any disciplinary action</li>
                <li><strong>Appeal:</strong> Right of appeal for both complainant and respondent</li>
                <li><strong>Monitoring:</strong> Ongoing monitoring to ensure no retaliation</li>
            </ol>

            <h4>Support During Process:</h4>
            <ul>
                <li>Confidentiality maintained to greatest extent possible</li>
                <li>Support for complainants throughout the process</li>
                <li>Protection from retaliation or victimization</li>
                <li>Access to counseling and Employee Assistance Programs</li>
                <li>Reasonable adjustments and interim measures where appropriate</li>
            </ul>
        </div>'
];

// Update the Company Policies contract
$stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = ?");
$stmt->execute([$company_policies_contract['content'], $company_policies_contract['id']]);
echo "✓ Updated: {$company_policies_contract['name']} (ID: {$company_policies_contract['id']})\n";

echo "\nCompany Policies contract updated (Part 1)! Continuing with remaining sections...\n";
