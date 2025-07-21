<?php
require_once 'config/config.php';

echo "Adding company registration details to all contracts...\n\n";

try {
    // Company details to add
    $company_number = "16502958"; // Real UK company number
    $ico_number = "ZB910034"; // ICO registration number
    $company_name = "NEXI BOT LTD";
    $address = "80A Ruskin Avenue, Welling, London, DA16 3QQ";
    
    // Get all contracts
    $stmt = $pdo->query("SELECT id, name, content FROM contract_templates ORDER BY id");
    $contracts = $stmt->fetchAll();
    
    foreach ($contracts as $contract) {
        echo "Updating contract: {$contract['name']}\n";
        
        $content = $contract['content'];
        
        // Replace placeholder company info with real details
        $content = str_replace(
            'Company Registration Number: [TO BE INSERTED]',
            "Company Registration Number: {$company_number}",
            $content
        );
        
        $content = str_replace(
            'Registered Office: [TO BE INSERTED]',
            "Registered Office: {$address}",
            $content
        );
        
        // Update company name references
        $content = str_replace(
            'NEXI HUB LIMITED',
            "{$company_name}",
            $content
        );
        
        $content = str_replace(
            'Nexi Hub Limited',
            "{$company_name}",
            $content
        );
        
        // Add ICO registration where data protection is mentioned
        if (strpos($content, 'Data Protection') !== false || strpos($content, 'GDPR') !== false) {
            // Add ICO number after company info
            $content = str_replace(
                'Email: admin@nexihub.co.uk<br>',
                "Email: admin@nexihub.co.uk<br>\n            ICO Registration Number: {$ico_number}<br>",
                $content
            );
        }
        
        // Update the contract in database
        $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = ?");
        $result = $stmt->execute([$content, $contract['id']]);
        
        if ($result) {
            echo "✓ Updated successfully\n";
        } else {
            echo "✗ Failed to update\n";
        }
    }
    
    echo "\nCompany details update completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
