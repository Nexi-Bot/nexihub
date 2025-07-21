<?php
/**
 * Check table structure and fix user handling
 */
require_once 'config/config.php';

try {
    echo "🔍 CHECKING TABLE STRUCTURE...\n\n";
    
    // Check contract_users table structure
    $stmt = $pdo->query("DESCRIBE contract_users");
    $columns = $stmt->fetchAll();
    
    echo "📋 contract_users table columns:\n";
    foreach ($columns as $col) {
        echo "- {$col['Field']} ({$col['Type']}) - {$col['Null']} - {$col['Key']}\n";
    }
    
    echo "\n📊 Current users:\n";
    $stmt = $pdo->query("SELECT * FROM contract_users LIMIT 5");
    $users = $stmt->fetchAll();
    
    foreach ($users as $user) {
        echo "ID: {$user['id']} | Email: {$user['email']}\n";
        // Show all columns
        foreach ($user as $key => $value) {
            if (!is_numeric($key)) {
                echo "  $key: $value\n";
            }
        }
        echo "\n";
    }
    
    // Simple fix for duplicate issue
    echo "🔧 SIMPLE FIX: Update where email exists\n";
    $email = 'test@nexihub.uk';
    
    $stmt = $pdo->prepare("SELECT id FROM contract_users WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo "✅ User $email already exists with ID: {$existing['id']}\n";
        echo "✅ No need to create duplicate\n";
    } else {
        echo "❌ User $email not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
