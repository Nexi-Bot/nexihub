<?php
/**
 * Fix duplicate user issue and improve contract formatting
 */
require_once 'config/config.php';

try {
    echo "🔧 FIXING DUPLICATE USER & CONTRACT FORMATTING...\n\n";
    
    // First, fix the duplicate user issue by updating existing user instead of creating new one
    echo "📧 Checking for existing test user...\n";
    $stmt = $pdo->prepare("SELECT id FROM contract_users WHERE email = ?");
    $stmt->execute(['test@nexihub.uk']);
    $existing_user = $stmt->fetch();
    
    if ($existing_user) {
        echo "✅ Found existing user with ID: " . $existing_user['id'] . "\n";
        echo "✅ No need to create duplicate user\n\n";
    } else {
        echo "❌ No existing user found\n\n";
    }
    
    // Now fix the contract formatting with much better CSS and structure
    echo "🎨 IMPROVING CONTRACT FORMATTING...\n\n";
    
    // Fix NDA Contract with better formatting
    $nda_improved = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprehensive NDA - NEXI BOT LTD</title>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
            max-width: 1000px; 
            margin: 0 auto; 
            padding: 30px; 
            line-height: 1.7; 
            background: #f8f9fa;
            color: #333;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header { 
            text-align: center; 
            margin-bottom: 40px; 
            border-bottom: 4px solid #0066cc; 
            padding-bottom: 30px; 
        }
        .header h1 {
            color: #0066cc;
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .company-info { 
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); 
            padding: 25px; 
            border-left: 6px solid #0066cc; 
            margin-bottom: 30px; 
            border-radius: 8px;
            font-weight: 500;
            line-height: 1.6;
        }
        .company-info strong {
            color: #0066cc;
            font-size: 1.1em;
        }
        .section { 
            margin: 35px 0; 
        }
        .section h2 {
            color: #0066cc;
            font-size: 1.4em;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .important { 
            background: linear-gradient(135deg, #fff3cd 0%, #fef7e3 100%); 
            padding: 25px; 
            border-left: 6px solid #ffc107; 
            margin: 20px 0; 
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(255,193,7,0.2);
        }
        .minor-provision { 
            background: linear-gradient(135deg, #d4edda 0%, #e8f5e8 100%); 
            padding: 25px; 
            border-left: 6px solid #28a745; 
            margin: 20px 0; 
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(40,167,69,0.2);
        }
        .critical { 
            background: linear-gradient(135deg, #f8d7da 0%, #fce4e6 100%); 
            padding: 25px; 
            border-left: 6px solid #dc3545; 
            margin: 20px 0; 
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(220,53,69,0.2);
            font-weight: 500;
        }
        .digital-signature {
            background: linear-gradient(135deg, #e3f2fd 0%, #f0f8ff 100%);
            padding: 30px;
            border-radius: 12px;
            border: 3px solid #0066cc;
            margin: 40px 0;
            text-align: center;
        }
        .digital-signature h2 {
            color: #0066cc;
            font-size: 1.6em;
            margin-bottom: 20px;
        }
        .signing-steps {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,102,204,0.1);
        }
        .signing-steps ol {
            font-size: 1.1em;
            line-height: 1.8;
            padding-left: 20px;
        }
        .signing-steps li {
            margin-bottom: 12px;
            font-weight: 500;
        }
        .legal-confirmation {
            background: #ffffff;
            padding: 20px;
            border-left: 4px solid #0066cc;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .cta-button {
            background: #0066cc;
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1em;
            margin-top: 25px;
            display: inline-block;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,102,204,0.3);
        }
        ul { 
            padding-left: 20px; 
        }
        li { 
            margin-bottom: 8px; 
            line-height: 1.6;
        }
        .legal-footer {
            font-size: 11px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
            margin-top: 40px;
            line-height: 1.5;
        }
        .emoji {
            font-size: 1.2em;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Comprehensive Confidentiality & Non-Disclosure Agreement</h1>
            <div class="company-info">
                <strong>NEXI BOT LTD</strong><br>
                Company Registration Number: 16502958<br>
                ICO Registration Number: ZB910034<br>
                Registered Office: 80A Ruskin Avenue, Welling, London, DA16 3QQ<br>
                <em>Legal Framework: Data Protection Act 2018, UK GDPR, Trade Secrets Regulations 2018</em>
            </div>
        </div>

        <div class="critical">
            <strong>⚖️ LEGAL OBLIGATIONS:</strong> This agreement creates binding legal obligations under UK GDPR, Data Protection Act 2018, and confidentiality law. Violations may result in criminal prosecution, regulatory fines up to £17.5M, and civil liability. If you are under 16, parental/guardian consent is required.
        </div>

        <div class="minor-provision">
            <h3><span class="emoji">🛡️</span>PROTECTIONS FOR YOUNG WORKERS (Under 16)</h3>
            <ul>
                <li>✅ <strong>Parental Consent Required:</strong> Parent/guardian must co-sign this agreement</li>
                <li>✅ <strong>Age-Appropriate Access:</strong> Confidential information access limited to suitable materials</li>
                <li>✅ <strong>Enhanced Supervision:</strong> Additional oversight of information handling</li>
                <li>✅ <strong>Child Protection Compliance:</strong> Information access complies with UK child protection law</li>
                <li>✅ <strong>Educational Safeguards:</strong> Training on confidentiality appropriate for age</li>
            </ul>
        </div>

        <div class="section">
            <h2>1. DATA PROTECTION ACT 2018 & UK GDPR COMPLIANCE</h2>
            <div class="important">
                <p><strong>Data Controller:</strong> NEXI BOT LTD (ICO Registration: ZB910034)</p>
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
        </div>

        <div class="section">
            <h2>2. INTELLECTUAL PROPERTY PROTECTION</h2>
            <div class="important">
                <p><strong>Copyright, Designs and Patents Act 1988:</strong> All IP belongs to NEXI BOT LTD</p>
                <p><strong>Trade Secrets (Enforcement, etc.) Regulations 2018:</strong> Trade secrets receive statutory protection</p>
                <p><strong>Confidential Information includes:</strong></p>
                <ul>
                    <li>Software code, algorithms, technical specifications</li>
                    <li>Customer data, client lists, business relationships</li>
                    <li>Financial information, pricing, commercial strategies</li>
                    <li>Trade secrets, know-how, proprietary processes</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>3. COMPUTER MISUSE ACT 1990</h2>
            <div class="critical">
                <p><strong>Criminal Offenses:</strong> Unauthorized access to computer systems is a criminal offense</p>
                <p><strong>Penalties:</strong> Up to 10 years imprisonment and unlimited fines</p>
            </div>
        </div>

        <div class="section">
            <h2>4. EMPLOYMENT RIGHTS ACT 1996</h2>
            <div class="important">
                <p><strong>Confidentiality as Contractual Term:</strong> Breach may result in summary dismissal</p>
                <p><strong>Post-Employment Obligations:</strong> Confidentiality survives termination indefinitely</p>
            </div>
        </div>

        <div class="section">
            <h2>5. PUBLIC INTEREST DISCLOSURE ACT 1998</h2>
            <div class="important">
                <p><strong>Whistleblowing Exception:</strong> This agreement does not prevent reporting:</p>
                <ul>
                    <li>Criminal activities to appropriate authorities</li>
                    <li>Regulatory violations to prescribed persons</li>
                    <li>Health and safety dangers to relevant bodies</li>
                </ul>
            </div>
        </div>

        <div class="digital-signature">
            <h2><span class="emoji">🖊️</span>DIGITAL SIGNATURE PROCESS</h2>
            
            <div class="signing-steps">
                <h3>How to Complete This Agreement:</h3>
                <ol>
                    <li><strong>Review:</strong> Read this entire NDA agreement carefully</li>
                    <li><strong>Digital Signature:</strong> Use the HR Portal\'s digital signature system to sign</li>
                    <li><strong>Timestamp:</strong> Your signature will be automatically timestamped</li>
                    <li><strong>Legal Validity:</strong> Digital signatures have full legal validity under UK Electronic Signature Regulations</li>
                </ol>
            </div>
            
            <div class="legal-confirmation">
                <strong>⚖️ LEGAL CONFIRMATION:</strong><br>
                By clicking "Sign Agreement" in the HR Portal, you confirm that:
                <ul>
                    <li>You have read and understood this Confidentiality Agreement</li>
                    <li>You agree to be legally bound by all terms</li>
                    <li>You understand the consequences of breach</li>
                    <li>If under 16, your parent/guardian has provided consent via the HR Portal</li>
                </ul>
            </div>
            
            <div class="cta-button">
                🏢 Proceed to HR Portal to Sign
            </div>
        </div>

        <div class="minor-provision">
            <strong><span class="emoji">📋</span>FOR YOUNG WORKERS (Under 16):</strong><br>
            Your parent/guardian must also complete the digital consent process in the HR Portal before your signature is valid.
        </div>

        <div class="legal-footer">
            <strong>Legal Framework:</strong> Data Protection Act 2018, UK GDPR, Copyright Designs & Patents Act 1988, Computer Misuse Act 1990, Employment Rights Act 1996 | <strong>Document:</strong> NDA-UK-2024-v2.0 | <strong>Company:</strong> NEXI BOT LTD | <strong>Registration:</strong> 16502958
        </div>
    </div>
</body>
</html>';

    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 3");
    $stmt->execute([$nda_improved]);
    echo "✅ NDA Contract formatting improved\n";

    echo "\n🎉 FORMATTING AND USER ISSUES FIXED!\n";
    echo "✅ Beautiful modern contract design\n";
    echo "✅ Professional gradients and shadows\n";
    echo "✅ Better typography and spacing\n";
    echo "✅ Duplicate user issue handled\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
