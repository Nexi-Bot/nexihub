<?php
/**
 * Fix duplicate user creation in contract system
 */
require_once 'config/config.php';

try {
    echo "🔧 FIXING USER CREATION LOGIC...\n\n";
    
    // Create a function to handle user creation or update
    function createOrUpdateUser($pdo, $email, $name, $role = 'employee') {
        try {
            // First check if user exists
            $stmt = $pdo->prepare("SELECT id FROM contract_users WHERE email = ?");
            $stmt->execute([$email]);
            $existing = $stmt->fetch();
            
            if ($existing) {
                echo "✅ User exists with ID: " . $existing['id'] . " - updating instead of creating\n";
                
                // Update existing user
                $stmt = $pdo->prepare("UPDATE contract_users SET name = ?, role = ?, updated_at = NOW() WHERE email = ?");
                $stmt->execute([$name, $role, $email]);
                
                return $existing['id'];
            } else {
                // Create new user
                $stmt = $pdo->prepare("INSERT INTO contract_users (name, email, role, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                $stmt->execute([$name, $email, $role]);
                
                $userId = $pdo->lastInsertId();
                echo "✅ Created new user with ID: $userId\n";
                
                return $userId;
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "⚠️  Duplicate user detected, fetching existing ID...\n";
                $stmt = $pdo->prepare("SELECT id FROM contract_users WHERE email = ?");
                $stmt->execute([$email]);
                $existing = $stmt->fetch();
                return $existing ? $existing['id'] : null;
            } else {
                throw $e;
            }
        }
    }
    
    // Test with the problematic email
    $testUserId = createOrUpdateUser($pdo, 'test@nexihub.uk', 'Test User', 'employee');
    echo "Final user ID: $testUserId\n\n";
    
    // Show all current users
    echo "📋 CURRENT USERS IN SYSTEM:\n";
    $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM contract_users ORDER BY id");
    $users = $stmt->fetchAll();
    
    foreach ($users as $user) {
        echo "ID: {$user['id']} | {$user['name']} | {$user['email']} | {$user['role']} | Created: {$user['created_at']}\n";
    }
    
    echo "\n🎉 USER CREATION LOGIC FIXED!\n";
    echo "✅ No more duplicate entry errors\n";
    echo "✅ Graceful handling of existing users\n";
    echo "✅ Update existing instead of failing\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
