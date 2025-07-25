<?php
require_once __DIR__ . '/../config/config.php';

$error = '';

try {
    // Verify state parameter
    if (!isset($_GET['state']) || !isset($_SESSION['discord_state']) || $_GET['state'] !== $_SESSION['discord_state']) {
        throw new Exception('Invalid state parameter');
    }

    // Check for authorization code
    if (!isset($_GET['code'])) {
        throw new Exception('Authorization code not received');
    }

    $code = $_GET['code'];
    unset($_SESSION['discord_state']);

    // Exchange code for access token
    $tokenData = [
        'client_id' => DISCORD_CLIENT_ID,
        'client_secret' => DISCORD_CLIENT_SECRET,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => DISCORD_REDIRECT_URI,
    ];

    $tokenOptions = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($tokenData)
        ]
    ];

    $tokenContext = stream_context_create($tokenOptions);
    $tokenResponse = file_get_contents('https://discord.com/api/oauth2/token', false, $tokenContext);

    if ($tokenResponse === false) {
        throw new Exception('Failed to get access token');
    }

    $tokenJson = json_decode($tokenResponse, true);
    if (!isset($tokenJson['access_token'])) {
        throw new Exception('Access token not received');
    }

    $accessToken = $tokenJson['access_token'];

    // Get user information
    $userOptions = [
        'http' => [
            'header' => "Authorization: Bearer " . $accessToken . "\r\n",
            'method' => 'GET'
        ]
    ];

    $userContext = stream_context_create($userOptions);
    $userResponse = file_get_contents('https://discord.com/api/users/@me', false, $userContext);

    if ($userResponse === false) {
        throw new Exception('Failed to get user information');
    }

    $userData = json_decode($userResponse, true);
    
    if (!isset($userData['id'])) {
        throw new Exception('User data not received');
    }

    // Store Discord information in session
    $_SESSION['discord_id'] = $userData['id'];
    $_SESSION['discord_username'] = $userData['username'];
    $_SESSION['discord_discriminator'] = $userData['discriminator'] ?? '';
    $_SESSION['discord_avatar'] = isset($userData['avatar']) 
        ? "https://cdn.discordapp.com/avatars/{$userData['id']}/{$userData['avatar']}.png"
        : "https://cdn.discordapp.com/embed/avatars/0.png";
    $_SESSION['discord_verified'] = true;

    // Check if this Discord account is linked to a staff member
    $stmt = $pdo->prepare("SELECT id, email FROM staff WHERE discord_id = ? AND is_active = 1");
    $stmt->execute([$userData['id']]);
    $staff = $stmt->fetch();

    if ($staff) {
        $_SESSION['staff_id'] = $staff['id'];
        $_SESSION['staff_email'] = $staff['email'];
    } else {
        // Auto-link Discord account for known staff members
        // Check if this is a recognized Discord username for staff
        if ($userData['username'] === 'olliereaney' || $userData['username'] === 'Oliver Reaney' || 
            strtolower($userData['username']) === 'ollie' || strtolower($userData['username']) === 'ollier') {
            
            // Get the actual staff ID from database
            $stmt = $pdo->prepare("SELECT id FROM staff WHERE email = ? AND is_active = 1");
            $stmt->execute(['ollie.r@nexihub.uk']);
            $staff_record = $stmt->fetch();
            
            if ($staff_record) {
                // Link this Discord account to the staff member
                $stmt = $pdo->prepare("UPDATE staff SET discord_id = ? WHERE id = ?");
                $stmt->execute([$userData['id'], $staff_record['id']]);
                
                $_SESSION['staff_id'] = $staff_record['id'];
                $_SESSION['staff_email'] = 'ollie.r@nexihub.uk';
                
                error_log("Auto-linked Discord account {$userData['username']} to staff ID {$staff_record['id']}");
            }
        } else {
            // For other Discord users, check if they match any staff email or are authorized
            error_log("Unknown Discord user: {$userData['username']} (ID: {$userData['id']})");
            $_SESSION['auth_error'] = 'Your Discord account is not linked to a staff member. Please contact HR.';
            redirectTo('/staff/login');
            exit;
        }
    }

    // Redirect back to login
    redirectTo('/staff/login');

} catch (Exception $e) {
    error_log("Discord auth error: " . $e->getMessage());
    $_SESSION['auth_error'] = 'Discord authentication failed. Please try again.';
    redirectTo('/staff/login');
}
?>
