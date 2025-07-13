<?php
require_once __DIR__ . '/../config/config.php';
require_once '../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('/staff/login');
}

requirePartialAuth();

$code = preg_replace('/\D/', '', $_POST['two_fa_code'] ?? '');
$error = '';

try {
    if (strlen($code) !== 6) {
        throw new Exception('Please enter a valid 6-digit code');
    }

    $ga = new PHPGangsta_GoogleAuthenticator();

    // Check if setting up 2FA for the first time
    if (isset($_SESSION['setup_2fa']) && $_SESSION['setup_2fa']) {
        $secret = $_SESSION['temp_2fa_secret'];
        
        if (!$ga->verifyCode($secret, $code, 2)) {
            throw new Exception('Invalid authentication code. Please try again.');
        }

        // Save 2FA secret to database
        $stmt = $pdo->prepare("UPDATE staff SET two_fa_secret = ?, two_fa_enabled = 1 WHERE id = ?");
        $stmt->execute([$secret, $_SESSION['staff_id']]);

        unset($_SESSION['temp_2fa_secret']);
        unset($_SESSION['setup_2fa']);
        
    } else {
        // Verify existing 2FA
        $stmt = $pdo->prepare("SELECT two_fa_secret FROM staff WHERE id = ? AND two_fa_enabled = 1");
        $stmt->execute([$_SESSION['staff_id']]);
        $staff = $stmt->fetch();

        if (!$staff || !$staff['two_fa_secret']) {
            throw new Exception('Two-factor authentication not properly configured');
        }

        if (!$ga->verifyCode($staff['two_fa_secret'], $code, 2)) {
            throw new Exception('Invalid authentication code. Please try again.');
        }
    }

    // Complete authentication
    $_SESSION['two_fa_verified'] = true;
    
    // Generate session token
    $sessionToken = generateSecureToken(32);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    $stmt = $pdo->prepare("
        INSERT INTO staff_sessions (staff_id, session_token, discord_verified, email_verified, two_fa_verified, ip_address, user_agent, expires_at) 
        VALUES (?, ?, 1, 1, 1, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['staff_id'],
        $sessionToken,
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? '',
        $expiresAt
    ]);

    $_SESSION['session_token'] = $sessionToken;

    redirectTo('/staff/dashboard');

} catch (Exception $e) {
    error_log("2FA auth error: " . $e->getMessage());
    $_SESSION['auth_error'] = $e->getMessage();
    redirectTo('/staff/login');
}
?>
