<?php
/**
 * Fix Employment and Code of Conduct contracts to use digital signatures
 */
require_once 'config/config.php';

try {
    // Company details
    $company_name = "NEXI BOT LTD";
    $company_number = "16502958";
    $ico_number = "ZB910034";
    $address = "80A Ruskin Avenue, Welling, London, DA16 3QQ";

    echo "🔧 FIXING EMPLOYMENT & CODE OF CONDUCT SIGNATURE SECTIONS...\n\n";

    // Fix Employment Contract (ID: 1)
    echo "📄 Updating Employment Contract...\n";
    
    // Get current Employment contract content
    $stmt = $pdo->prepare("SELECT content FROM contract_templates WHERE id = 1");
    $stmt->execute();
    $employment_content = $stmt->fetchColumn();
    
    // Replace the signature section in Employment contract
    $old_signature_pattern = '/    <div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">.*?<\/div>\s*<\/div>/s';
    $new_signature_section = '    <div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
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
    </div>';
    
    $employment_content_fixed = preg_replace($old_signature_pattern, $new_signature_section, $employment_content);
    
    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 1");
    $stmt->execute([$employment_content_fixed]);
    echo "✅ Employment Contract signature section updated\n\n";

    // Fix Code of Conduct Contract (ID: 2)
    echo "📄 Updating Code of Conduct Contract...\n";
    
    // Get current Code of Conduct contract content
    $stmt = $pdo->prepare("SELECT content FROM contract_templates WHERE id = 2");
    $stmt->execute();
    $conduct_content = $stmt->fetchColumn();
    
    // Replace the signature section in Code of Conduct contract
    $new_conduct_signature = '    <div style="margin-top: 40px; border-top: 2px solid #007bff; padding-top: 20px;">
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
    </div>';
    
    $conduct_content_fixed = preg_replace($old_signature_pattern, $new_conduct_signature, $conduct_content);
    
    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 2");
    $stmt->execute([$conduct_content_fixed]);
    echo "✅ Code of Conduct Contract signature section updated\n\n";

    // Fix NDA Contract (ID: 3) - it seems to be missing some elements
    echo "📄 Updating NDA Contract...\n";
    
    // Get current NDA contract content
    $stmt = $pdo->prepare("SELECT content FROM contract_templates WHERE id = 3");
    $stmt->execute();
    $nda_content = $stmt->fetchColumn();
    
    // Check if NDA needs the digital signature elements
    if (strpos($nda_content, 'DIGITAL SIGNATURE PROCESS') === false) {
        $nda_content_fixed = preg_replace($old_signature_pattern, $new_signature_section, $nda_content);
        
        $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 3");
        $stmt->execute([$nda_content_fixed]);
        echo "✅ NDA Contract signature section updated\n";
    } else {
        echo "✅ NDA Contract already has digital signatures\n";
    }

    echo "\n🎉 ALL SIGNATURE SECTIONS FIXED!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
