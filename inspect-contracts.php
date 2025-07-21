<?php
/**
 * Inspect contract structure to understand the signature sections
 */
require_once 'config/config.php';

try {
    echo "🔍 INSPECTING CONTRACT STRUCTURES...\n\n";

    // Check Employment contract
    $stmt = $pdo->prepare("SELECT content FROM contract_templates WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $employment_content = $stmt->fetchColumn();
    
    echo "📄 EMPLOYMENT CONTRACT (ID: 1)\n";
    echo "Length: " . strlen($employment_content) . " characters\n";
    
    // Look for signature-related content
    if (strpos($employment_content, 'Signature:') !== false) {
        echo "✅ Contains 'Signature:' text\n";
    } else {
        echo "❌ No 'Signature:' text found\n";
    }
    
    if (strpos($employment_content, 'DIGITAL SIGNATURE') !== false) {
        echo "✅ Contains 'DIGITAL SIGNATURE' text\n";
    } else {
        echo "❌ No 'DIGITAL SIGNATURE' text found\n";
    }
    
    // Show the last 1000 characters to see the signature section
    echo "\nLast 1000 characters of Employment contract:\n";
    echo "----------------------------------------\n";
    echo substr($employment_content, -1000);
    echo "\n========================================\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
