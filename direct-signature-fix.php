<?php
/**
 * Direct signature section replacement for problematic contracts
 */
require_once 'config/config.php';

try {
    echo "🔧 DIRECT SIGNATURE REPLACEMENT...\n\n";

    // Get Employment contract and check its structure
    $stmt = $pdo->prepare("SELECT content FROM contract_templates WHERE id = 1");
    $stmt->execute();
    $employment_content = $stmt->fetchColumn();
    
    // Find signature section
    $start_pos = strpos($employment_content, '<div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">');
    if ($start_pos !== false) {
        echo "📄 Found signature section in Employment contract at position: $start_pos\n";
        
        // Show what's there
        $snippet = substr($employment_content, $start_pos, 500);
        echo "Current content snippet:\n";
        echo substr($snippet, 0, 200) . "...\n\n";
        
        // Find the end of the signature section
        $end_pos = strrpos($employment_content, '</div>');
        if ($end_pos !== false) {
            // Replace everything from the signature section to the end
            $before_signature = substr($employment_content, 0, $start_pos);
            
            $new_signature_section = '<div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
        <h2>🖊️ DIGITAL SIGNATURE PROCESS</h2>
        <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; border: 2px solid #007bff;">
            <h3>How to Complete This Contract:</h3>
            <ol style="font-size: 16px; line-height: 1.8;">
                <li><strong>Review:</strong> Read this entire Employment Contract carefully</li>
                <li><strong>Digital Signature:</strong> Use the HR Portal\'s digital signature system to sign</li>
                <li><strong>Timestamp:</strong> Your signature will be automatically timestamped</li>
                <li><strong>Legal Validity:</strong> Digital signatures have full legal validity under UK Electronic Signature Regulations</li>
            </ol>
            
            <div style="background: #fff; padding: 15px; border-left: 4px solid #007bff; margin-top: 15px;">
                <strong>⚖️ LEGAL CONFIRMATION:</strong><br>
                By clicking "Sign Agreement" in the HR Portal, you confirm that:
                <ul>
                    <li>You have read and understood this Employment Contract</li>
                    <li>You agree to be legally bound by all terms and conditions</li>
                    <li>You accept the role, responsibilities, and compensation outlined</li>
                    <li>If under 16, your parent/guardian has provided consent via the HR Portal</li>
                </ul>
            </div>
        </div>
        
        <div class="minor-provision" style="margin-top: 20px;">
            <strong>📋 FOR YOUNG WORKERS (Under 16):</strong><br>
            Your parent/guardian must also complete the digital consent process in the HR Portal before your signature is valid.
        </div>
    </div>

    <p style="font-size: 12px; margin-top: 20px;"><strong>Legal Framework:</strong> Employment Rights Act 1996, Equality Act 2010, Working Time Regulations 1998, Health and Safety at Work Act 1974, Data Protection Act 2018. Document: EMP-UK-2024-v2.0</p>
</body>
</html>';
            
            $new_employment_content = $before_signature . $new_signature_section;
            
            $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 1");
            $stmt->execute([$new_employment_content]);
            echo "✅ Employment contract signature section replaced\n\n";
        }
    } else {
        echo "❌ Could not find signature section in Employment contract\n\n";
    }
    
    // Do the same for Code of Conduct (ID: 2)
    $stmt = $pdo->prepare("SELECT content FROM contract_templates WHERE id = 2");
    $stmt->execute();
    $conduct_content = $stmt->fetchColumn();
    
    $start_pos = strpos($conduct_content, '<div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">');
    if ($start_pos !== false) {
        echo "📄 Found signature section in Code of Conduct contract\n";
        
        $before_signature = substr($conduct_content, 0, $start_pos);
        
        $new_conduct_signature = '<div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
        <h2>🖊️ DIGITAL SIGNATURE PROCESS</h2>
        <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; border: 2px solid #007bff;">
            <h3>How to Complete This Agreement:</h3>
            <ol style="font-size: 16px; line-height: 1.8;">
                <li><strong>Review:</strong> Read this entire Code of Conduct carefully</li>
                <li><strong>Digital Signature:</strong> Use the HR Portal\'s digital signature system to sign</li>
                <li><strong>Timestamp:</strong> Your signature will be automatically timestamped</li>
                <li><strong>Legal Validity:</strong> Digital signatures have full legal validity under UK Electronic Signature Regulations</li>
            </ol>
            
            <div style="background: #fff; padding: 15px; border-left: 4px solid #007bff; margin-top: 15px;">
                <strong>⚖️ LEGAL CONFIRMATION:</strong><br>
                By clicking "Sign Agreement" in the HR Portal, you confirm that:
                <ul>
                    <li>You have read and understood this Code of Conduct</li>
                    <li>You agree to be legally bound by all behavioral standards</li>
                    <li>You will uphold professional conduct at all times</li>
                    <li>If under 16, your parent/guardian has provided consent via the HR Portal</li>
                </ul>
            </div>
        </div>
        
        <div class="minor-provision" style="margin-top: 20px;">
            <strong>📋 FOR YOUNG WORKERS (Under 16):</strong><br>
            Your parent/guardian must also complete the digital consent process in the HR Portal before your signature is valid.
        </div>
    </div>

    <p style="font-size: 12px; margin-top: 20px;"><strong>Legal Framework:</strong> Employment Rights Act 1996, Equality Act 2010, Data Protection Act 2018, Computer Misuse Act 1990, Public Interest Disclosure Act 1998. Document: COC-UK-2024-v2.0</p>
</body>
</html>';
        
        $new_conduct_content = $before_signature . $new_conduct_signature;
        
        $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 2");
        $stmt->execute([$new_conduct_content]);
        echo "✅ Code of Conduct contract signature section replaced\n\n";
    } else {
        echo "❌ Could not find signature section in Code of Conduct contract\n\n";
    }
    
    // Fix NDA (ID: 3) content as well
    $stmt = $pdo->prepare("SELECT content FROM contract_templates WHERE id = 3");
    $stmt->execute();
    $nda_content = $stmt->fetchColumn();
    
    $start_pos = strpos($nda_content, '<div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">');
    if ($start_pos !== false) {
        echo "📄 Found signature section in NDA contract\n";
        
        $before_signature = substr($nda_content, 0, $start_pos);
        
        $new_nda_signature = '<div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
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
        
        <div class="minor-provision" style="margin-top: 20px;">
            <strong>📋 FOR YOUNG WORKERS (Under 16):</strong><br>
            Your parent/guardian must also complete the digital consent process in the HR Portal before your signature is valid.
        </div>
    </div>

    <p style="font-size: 12px; margin-top: 20px;"><strong>Legal Framework:</strong> Data Protection Act 2018, UK GDPR, Copyright Designs & Patents Act 1988, Computer Misuse Act 1990, Employment Rights Act 1996. Document: NDA-UK-2024-v2.0</p>
</body>
</html>';
        
        $new_nda_content = $before_signature . $new_nda_signature;
        
        $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 3");
        $stmt->execute([$new_nda_content]);
        echo "✅ NDA contract signature section replaced\n\n";
    } else {
        echo "❌ Could not find signature section in NDA contract\n\n";
    }

    echo "🎉 DIRECT REPLACEMENT COMPLETE!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
