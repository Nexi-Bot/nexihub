<?php
// Database Configuration
define('DB_HOST', '65.21.61.192');
define('DB_PORT', '3306');
define('DB_USER', 'u25473_Y8CkMsMHyp');
define('DB_PASS', 'rlALotgMWdSy^8flYbx0PYS@');
define('DB_NAME', 's25473_NexiBotDatabase');

// Stripe Configuration
define('STRIPE_PUBLISHABLE_KEY', 'pk_live_51RgSvsHxd4KTYsDdodmX55cZkcaGwzXGgARw7yvfH4d8iZhUKUiKT7MGHyboIsnoAZkmsSovqrpJh2ajldqcc7te00gdwtNGiB');
define('STRIPE_SECRET_KEY', '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');

// Discord OAuth Configuration
define('DISCORD_CLIENT_ID', '1394054979811282955'); // You'll need to provide this
define('DISCORD_CLIENT_SECRET', 'Oy5sgCK4IF4gb1GQpbMovUgtImc04AaN'); // You'll need to provide this

// Site Configuration
$is_local = (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost:8000' || $_SERVER['HTTP_HOST'] === '127.0.0.1:8000'));
define('SITE_URL', $is_local ? 'http://localhost:8000' : 'https://nexihub.uk');
define('SITE_NAME', 'Nexi Hub');
define('IS_LOCAL_DEV', $is_local);
$discord_redirect = $is_local ? 'http://localhost:8000/staff/discord-callback' : 'https://nexihub.uk/staff/discord-callback';
define('DISCORD_REDIRECT_URI', $discord_redirect);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', $is_local ? 0 : 1); // Only require HTTPS in production
ini_set('session.use_only_cookies', 1);
ini_set('session.gc_maxlifetime', 300); // 5 minutes

if (!session_id()) {
    session_start();
}

// Check for session timeout (5 minutes of inactivity)
function checkSessionTimeout() {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
        // Session timed out, destroy it
        session_unset();
        session_destroy();
        session_start();
        return false;
    }
    $_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time
    return true;
}

// Database Connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Helper Functions
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_ARGON2ID);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function redirectTo($url) {
    // If it's a relative URL starting with /, prepend the site URL
    if (strpos($url, '/') === 0) {
        $url = SITE_URL . $url;
    }
    header("Location: " . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['staff_id']) && isset($_SESSION['discord_verified']) && isset($_SESSION['email_verified']) && isset($_SESSION['two_fa_verified']);
}

function requireAuth() {
    if (!checkSessionTimeout() || !isLoggedIn()) {
        redirectTo('/staff/login');
    }
}

function requirePartialAuth() {
    if (!checkSessionTimeout() || !isset($_SESSION['staff_id'])) {
        redirectTo('/staff/login');
    }
}
?>
