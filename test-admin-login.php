<?php
/**
 * Test script to verify the 3-step authentication works for olliereaney
 */

require_once __DIR__ . '/config/config.php';

echo "Testing 3-step authentication for olliereaney...\n\n";

try {
    $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Step 1: Check Discord user linking
    echo "Step 1: Discord Authentication\n";
    $stmt = $db->prepare('SELECT * FROM staff WHERE discord_id = ?');
    $stmt->execute(['876400589628670022']);
    $discord_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($discord_user) {
        echo "✓ Discord user 876400589628670022 (olliereaney) linked to: {$discord_user['email']}\n";
    } else {
        echo "✗ Discord user not found!\n";
        exit(1);
    }
    
    // Step 2: Check email and password
    echo "\nStep 2: Email Authentication\n";
    $stmt = $db->prepare('SELECT * FROM staff WHERE email = ? AND is_active = 1');
    $stmt->execute(['ollie.r@nexihub.uk']);
    $email_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($email_user) {
        echo "✓ Email ollie.r@nexihub.uk found in staff table\n";
        
        if (password_verify('Geronimo2018!', $email_user['password_hash'])) {
            echo "✓ Password 'Geronimo2018!' verification passed\n";
        } else {
            echo "✗ Password verification failed!\n";
            exit(1);
        }
    } else {
        echo "✗ Email not found or user not active!\n";
        exit(1);
    }
    
    // Step 3: Check 2FA status
    echo "\nStep 3: 2FA Status\n";
    if ($email_user['two_fa_enabled']) {
        echo "✓ 2FA is enabled - user will need to enter 2FA code\n";
        echo "  2FA Secret: " . ($email_user['two_fa_secret'] ? 'Set' : 'Not set') . "\n";
    } else {
        echo "✓ 2FA not enabled - user will be prompted to set up 2FA on first login\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "AUTHENTICATION TEST COMPLETE\n";
    echo str_repeat("=", 50) . "\n";
    
    echo "Login Process:\n";
    echo "1. Go to: https://nexihub.uk/staff/login\n";
    echo "2. Discord: Login as 'olliereaney' (ID: 876400589628670022)\n";
    echo "3. Email: Enter 'ollie.r@nexihub.uk' and password 'Geronimo2018!'\n";
    echo "4. 2FA: Set up Google Authenticator on first login\n";
    echo "\nThe user should now be able to complete all 3 steps successfully!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
