<?php
require_once __DIR__ . '/config/config.php';

// Connect to MySQL database
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL database successfully.\n\n";
    
    // Find Oliver by email
    $stmt = $db->prepare("SELECT id, discord_username, email FROM staff WHERE email = ?");
    $stmt->execute(['ollie.r@nexihub.uk']);
    $oliver = $stmt->fetch();
    
    if (!$oliver) {
        echo "ERROR: Oliver not found in staff table with email ollie.r@nexihub.uk\n";
        exit(1);
    }
    
    $oliver_id = $oliver['id'];
    echo "Found Oliver: ID={$oliver['id']}, Name={$oliver['discord_username']}, Email={$oliver['email']}\n";
    
    // Get the NDA and Shareholder Agreement templates
    $stmt = $db->prepare("SELECT id, name FROM contract_templates WHERE name IN ('Confidentiality & Non-Disclosure Agreement', 'Comprehensive Profit-Sharing Agreement')");
    $stmt->execute();
    $templates = $stmt->fetchAll();
    
    echo "\nFound contract templates:\n";
    foreach ($templates as $template) {
        echo "ID: {$template['id']}, Name: {$template['name']}\n";
    }
    
    if (count($templates) < 2) {
        echo "\nERROR: Missing required contract templates.\n";
        exit(1);
    }
    
    // Delete any existing contracts for Oliver
    $stmt = $db->prepare("DELETE FROM staff_contracts WHERE staff_id = ?");
    $stmt->execute([$oliver_id]);
    echo "\nDeleted any existing contracts for Oliver.\n";
    
    // Create unsigned contracts for Oliver
    foreach ($templates as $template) {
        $stmt = $db->prepare("INSERT INTO staff_contracts (staff_id, template_id, is_signed, created_at) VALUES (?, ?, 0, NOW())");
        $stmt->execute([$oliver_id, $template['id']]);
        echo "Created unsigned contract: {$template['name']}\n";
    }
    
    // Verify the contracts
    $stmt = $db->prepare("
        SELECT c.id, c.staff_id, c.template_id, c.is_signed, c.signed_at, t.name as template_name 
        FROM staff_contracts c 
        JOIN contract_templates t ON c.template_id = t.id 
        WHERE c.staff_id = ? 
        ORDER BY c.id
    ");
    $stmt->execute([$oliver_id]);
    $contracts = $stmt->fetchAll();
    
    echo "\nFinal contract status for Oliver (staff_id=$oliver_id):\n";
    foreach ($contracts as $contract) {
        $signed_status = $contract['is_signed'] ? 'SIGNED' : 'UNSIGNED';
        echo "ID: {$contract['id']}, Template: {$contract['template_name']}, Status: $signed_status, Date: {$contract['signed_at']}\n";
    }
    
    echo "\n✅ Oliver's contracts are now set up as UNSIGNED in MySQL database.\n";
    echo "✅ Use staff_id: $oliver_id when setting session variables.\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
