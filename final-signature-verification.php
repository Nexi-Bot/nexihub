<?php
/**
 * Final verification script to ensure all contracts have digital signatures only
 * and no physical signature boxes remain
 */
require_once 'config/config.php';

try {
    $stmt = $pdo->query("SELECT id, name, content FROM contract_templates WHERE is_assignable = 1 ORDER BY id");
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "🔍 FINAL SIGNATURE VERIFICATION REPORT\n";
    echo "=====================================\n\n";
    
    $issues_found = 0;
    
    foreach ($contracts as $contract) {
        echo "📄 " . $contract['name'] . " (ID: " . $contract['id'] . ")\n";
        echo str_repeat('-', 50) . "\n";
        
        // Check for physical signature boxes (bad)
        $signature_boxes = [
            'Signature: ____________________',
            'Signature: ________________________________',
            'Name: _________________________',
            'Date: _________________________',
            'Date: _____________________________________'
        ];
        
        $has_signature_boxes = false;
        foreach ($signature_boxes as $box) {
            if (strpos($contract['content'], $box) !== false) {
                $has_signature_boxes = true;
                echo "❌ FOUND PHYSICAL SIGNATURE BOX: '$box'\n";
                $issues_found++;
            }
        }
        
        // Check for digital signature process (good)
        $digital_elements = [
            'DIGITAL SIGNATURE PROCESS',
            'HR Portal\'s digital signature system',
            'Digital signatures have full legal validity',
            'BY CLICKING "SIGN AGREEMENT" IN THE HR PORTAL'
        ];
        
        $has_digital_process = true;
        foreach ($digital_elements as $element) {
            if (stripos($contract['content'], $element) === false) {
                $has_digital_process = false;
                echo "❌ MISSING DIGITAL ELEMENT: '$element'\n";
                $issues_found++;
            }
        }
        
        // Check for company details
        $company_details = [
            'NEXI BOT LTD',
            '16502958',
            'ZB910034',
            '80A Ruskin Avenue, Welling, London, DA16 3QQ'
        ];
        
        $has_company_details = true;
        foreach ($company_details as $detail) {
            if (strpos($contract['content'], $detail) === false) {
                $has_company_details = false;
                echo "❌ MISSING COMPANY DETAIL: '$detail'\n";
                $issues_found++;
            }
        }
        
        // Check for minor protections
        $minor_protections = [
            'FOR YOUNG WORKERS',
            'parent/guardian',
            'digital consent process'
        ];
        
        $has_minor_protections = true;
        foreach ($minor_protections as $protection) {
            if (stripos($contract['content'], $protection) === false) {
                $has_minor_protections = false;
                echo "❌ MISSING MINOR PROTECTION: '$protection'\n";
                $issues_found++;
            }
        }
        
        // Summary for this contract
        if (!$has_signature_boxes && $has_digital_process && $has_company_details && $has_minor_protections) {
            echo "✅ CONTRACT IS FULLY COMPLIANT\n";
        } else {
            echo "⚠️  CONTRACT HAS ISSUES\n";
        }
        
        echo "\n";
    }
    
    echo "📊 FINAL SUMMARY\n";
    echo "================\n";
    echo "Total Contracts: " . count($contracts) . "\n";
    echo "Issues Found: $issues_found\n";
    
    if ($issues_found === 0) {
        echo "🎉 ALL CONTRACTS ARE FULLY COMPLIANT!\n";
        echo "✅ Digital signatures only\n";
        echo "✅ No physical signature boxes\n";
        echo "✅ Company details present\n";
        echo "✅ Minor protections included\n";
        echo "✅ HR Portal integration complete\n";
    } else {
        echo "❌ ISSUES NEED TO BE RESOLVED\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
