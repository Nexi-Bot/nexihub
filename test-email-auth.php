<?php
/**
 * Test script to verify email authentication step works correctly
 */

require_once __DIR__ . '/config/config.php';

echo "Testing email authentication step...\n";

try {
    // Simulate the exact query from email-auth.php
    $email = 'ollie.r@nexihub.uk';
    $stmt = $pdo->prepare("SELECT id, email, name, discord_id, two_fa_secret, two_fa_enabled, password_hash FROM staff WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $staff = $stmt->fetch();
    
    if ($staff) {
        echo "✓ Staff member found:\n";
        echo "  ID: " . $staff['id'] . "\n";
        echo "  Email: " . $staff['email'] . "\n";
        echo "  Name: " . $staff['name'] . "\n";
        echo "  Discord ID: " . $staff['discord_id'] . "\n";
        echo "  2FA Enabled: " . ($staff['two_fa_enabled'] ? 'Yes' : 'No') . "\n";
        
        // Test password verification
        $password = 'Geronimo2018!';
        if (isset($staff['password_hash'])) {
            if (password_verify($password, $staff['password_hash'])) {
                echo "✓ Password verification passed\n";
            } else {
                echo "✗ Password verification failed\n";
            }
        } else {
            echo "⚠ No password hash found in database\n";
        }
    } else {
        echo "✗ Staff member not found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "\nEmail authentication test complete.\n";
?>
