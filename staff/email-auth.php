<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('/staff/login');
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$error = '';

try {
    if (!$email || !$password) {
        throw new Exception('Please provide both email and password');
    }

    // Validate email domain
    if (!str_ends_with($email, '@nexihub.uk')) {
        throw new Exception('Only @nexihub.uk email addresses are allowed');
    }

    // Check if user exists and verify password
    $stmt = $pdo->prepare("SELECT id, email, password_hash, discord_id, two_fa_secret, two_fa_enabled FROM staff WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $staff = $stmt->fetch();

    if (!$staff || !verifyPassword($password, $staff['password_hash'])) {
        throw new Exception('Invalid email or password');
    }

    // Update Discord ID if not set but user has Discord verified
    if (!$staff['discord_id'] && isset($_SESSION['discord_id'])) {
        $updateStmt = $pdo->prepare("UPDATE staff SET discord_id = ?, discord_username = ?, discord_discriminator = ?, discord_avatar = ? WHERE id = ?");
        $updateStmt->execute([
            $_SESSION['discord_id'],
            $_SESSION['discord_username'] ?? '',
            $_SESSION['discord_discriminator'] ?? '',
            $_SESSION['discord_avatar'] ?? '',
            $staff['id']
        ]);
    }

    // Store staff information in session
    $_SESSION['staff_id'] = $staff['id'];
    $_SESSION['staff_email'] = $staff['email'];
    $_SESSION['email_verified'] = true;

    // Check if 2FA is enabled
    if (!$staff['two_fa_enabled']) {
        // Generate new 2FA secret
        require_once '../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
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
