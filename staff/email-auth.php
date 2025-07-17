<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('/staff/login');
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$error = '';

try {
    if (!$email) {
        throw new Exception('Please provide your email address');
    }

    // Check if Discord authentication was completed first
    if (!isset($_SESSION['discord_verified']) || !$_SESSION['discord_verified']) {
        throw new Exception('Discord authentication required first');
    }

    // Validate email domain
    if (!str_ends_with($email, '@nexihub.uk')) {
        throw new Exception('Only @nexihub.uk email addresses are allowed');
    }

    // Check if user exists and is active
    $stmt = $pdo->prepare("SELECT id, email, name, discord_id, two_fa_secret, two_fa_enabled FROM staff WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $staff = $stmt->fetch();

    if (!$staff) {
        throw new Exception('Email address not found in staff directory');
    }

    // For development/testing, allow any Discord user to link to ollie.r@nexihub.uk
    // In production, you'd want stricter validation
    if ($email === 'ollie.r@nexihub.uk') {
        // Auto-link Discord account if not already linked
        if (!$staff['discord_id'] && isset($_SESSION['discord_id'])) {
            $updateStmt = $pdo->prepare("UPDATE staff SET discord_id = ? WHERE id = ?");
            $updateStmt->execute([$_SESSION['discord_id'], $staff['id']]);
        }
    } else {
        // For other users, ensure Discord ID matches
        if ($staff['discord_id'] && $staff['discord_id'] !== $_SESSION['discord_id']) {
            throw new Exception('This email is linked to a different Discord account');
        }
    }

    // Store staff information in session
    $_SESSION['staff_id'] = $staff['id'];
    $_SESSION['staff_email'] = $staff['email'];
    $_SESSION['staff_name'] = $staff['name'];
    $_SESSION['email_verified'] = true;

    error_log("Email verification successful for: $email (Staff ID: {$staff['id']})");

    redirectTo('/staff/login');

} catch (Exception $e) {
    error_log("Email auth error: " . $e->getMessage());
    $_SESSION['auth_error'] = $e->getMessage();
    redirectTo('/staff/login');
}
?>
        require_once __DIR__ . '/../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        
        $_SESSION['temp_2fa_secret'] = $secret;
        $_SESSION['setup_2fa'] = true;
    }

    // Update last login
    $updateLogin = $pdo->prepare("UPDATE staff SET last_login = NOW() WHERE id = ?");
    $updateLogin->execute([$staff['id']]);

    redirectTo('/staff/login');

} catch (Exception $e) {
    error_log("Email auth error: " . $e->getMessage());
    $_SESSION['auth_error'] = $e->getMessage();
    redirectTo('/staff/login');
}
?>
