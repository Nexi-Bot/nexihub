<?php
require_once '../config/config.php';

// Generate state token for security
$state = generateSecureToken(16);
$_SESSION['discord_state'] = $state;

// Discord OAuth URL
$discordAuthUrl = 'https://discord.com/api/oauth2/authorize?' . http_build_query([
    'client_id' => DISCORD_CLIENT_ID,
    'redirect_uri' => DISCORD_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'identify',
    'state' => $state
]);

// Redirect to Discord
redirectTo($discordAuthUrl);
?>
