<?php
require_once '../config/config.php';

$page_title = "Staff Login";
$page_description = "Secure staff login portal for Nexi Hub team members";

// Check if already logged in
if (isLoggedIn()) {
    redirectTo('/staff/dashboard');
}

$error = $_SESSION['auth_error'] ?? '';
unset($_SESSION['auth_error']);

$step = 'discord'; // discord, email, two_fa

// Check current authentication step
if (isset($_SESSION['staff_id'])) {
    if (!isset($_SESSION['email_verified']) || !$_SESSION['email_verified']) {
        $step = 'email';
    } elseif (!isset($_SESSION['two_fa_verified']) || !$_SESSION['two_fa_verified']) {
        $step = 'two_fa';
    }
}

include '../includes/header.php';
?>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--background-dark) 0%, var(--background-light) 100%);
    padding: 2rem;
}

.auth-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

.auth-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-logo {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    margin: 0 auto 1rem;
}

.auth-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.auth-subtitle {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.auth-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
    position: relative;
}

.auth-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--border-color);
    transform: translateY(-50%);
    z-index: 1;
}

.auth-step {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--background-dark);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.9rem;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

.auth-step.completed {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.auth-step.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    box-shadow: 0 0 20px rgba(230, 79, 33, 0.4);
}

.discord-section,
.email-section,
.two-fa-section {
    display: none;
}

.discord-section.active,
.email-section.active,
.two-fa-section.active {
    display: block;
}

.discord-info {
    display: flex;
    align-items: center;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.discord-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 1rem;
}

.discord-details h4 {
    color: var(--text-primary);
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
}

.discord-details p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.85rem;
}

.discord-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 1rem;
    background: #5865F2;
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.discord-btn:hover {
    background: #4752C4;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(88, 101, 242, 0.3);
}

.discord-btn svg {
    width: 24px;
    height: 24px;
    margin-right: 0.75rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    font-weight: 500;
    font-size: 0.9rem;
}

.form-group input {
    width: 100%;
    padding: 0.875rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(230, 79, 33, 0.1);
}

.auth-btn {
    width: 100%;
    padding: 1rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.auth-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(230, 79, 33, 0.3);
}

.auth-btn:disabled {
    background: var(--border-color);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.error-message {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #EF4444;
    padding: 0.875rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.auth-links {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.auth-links a {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.auth-links a:hover {
    color: var(--secondary-color);
}

.qr-code-container {
    text-align: center;
    margin: 1.5rem 0;
}

.qr-code {
    background: white;
    padding: 1rem;
    border-radius: 12px;
    display: inline-block;
    margin-bottom: 1rem;
}

.two-fa-setup {
    background: rgba(230, 79, 33, 0.05);
    border: 1px solid rgba(230, 79, 33, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.two-fa-setup h4 {
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
}

.two-fa-setup ol {
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.6;
    margin: 0 0 1rem 1.2rem;
}

.secret-key {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem;
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: var(--primary-color);
    word-break: break-all;
    margin-top: 0.5rem;
}
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="/nexi.png" alt="Nexi Hub" class="auth-logo">
            <h1 class="auth-title">Staff Portal</h1>
            <p class="auth-subtitle">Secure multi-factor authentication</p>
        </div>

        <div class="auth-steps">
            <div class="auth-step <?php echo $step === 'discord' ? 'active' : (isset($_SESSION['discord_verified']) ? 'completed' : ''); ?>">1</div>
            <div class="auth-step <?php echo $step === 'email' ? 'active' : (isset($_SESSION['email_verified']) && $_SESSION['email_verified'] ? 'completed' : ''); ?>">2</div>
            <div class="auth-step <?php echo $step === 'two_fa' ? 'active' : (isset($_SESSION['two_fa_verified']) && $_SESSION['two_fa_verified'] ? 'completed' : ''); ?>">3</div>
        </div>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Discord Authentication Step -->
        <div class="discord-section <?php echo $step === 'discord' ? 'active' : ''; ?>">
            <?php if (isset($_SESSION['discord_verified']) && $_SESSION['discord_verified']): ?>
                <div class="discord-info">
                    <img src="<?php echo htmlspecialchars($_SESSION['discord_avatar'] ?? '/assets/default-avatar.png'); ?>" alt="Discord Avatar" class="discord-avatar">
                    <div class="discord-details">
                        <h4><?php echo htmlspecialchars($_SESSION['discord_username'] ?? 'Unknown User'); ?></h4>
                        <p>Discord verification complete</p>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                </script>
            <?php else: ?>
                <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem;">Step 1: Discord Verification</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.9rem;">
                    Connect with your Discord account to verify your identity as a Nexi Hub team member.
                </p>
                <a href="/staff/discord-auth" class="discord-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419-.0190 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1568 2.4189Z"/>
                    </svg>
                    Continue with Discord
                </a>
            <?php endif; ?>
        </div>

        <!-- Email Authentication Step -->
        <div class="email-section <?php echo $step === 'email' ? 'active' : ''; ?>">
            <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem;">Step 2: Email Verification</h3>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.9rem;">
                Sign in with your @nexihub.uk email address and password.
            </p>

            <form method="POST" action="/staff/email-auth">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="your.name@nexihub.uk" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="auth-btn">Verify Email & Password</button>
            </form>

            <div class="auth-links">
                <a href="/staff/forgot-password">Forgot your password?</a>
            </div>
        </div>

        <!-- Two-Factor Authentication Step -->
        <div class="two-fa-section <?php echo $step === 'two_fa' ? 'active' : ''; ?>">
            <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.2rem;">Step 3: Two-Factor Authentication</h3>
            
            <?php if (isset($_SESSION['setup_2fa']) && $_SESSION['setup_2fa']): ?>
                <div class="two-fa-setup">
                    <h4>Set up your authenticator app</h4>
                    <ol>
                        <li>Download Google Authenticator or Microsoft Authenticator</li>
                        <li>Scan the QR code below or enter the secret key manually</li>
                        <li>Enter the 6-digit code from your authenticator app</li>
                    </ol>
                </div>

                <div class="qr-code-container">
                    <div class="qr-code">
                        <div id="qrcode"></div>
                    </div>
                    <div class="secret-key">
                        Secret Key: <span id="secret-key"><?php echo $_SESSION['temp_2fa_secret'] ?? ''; ?></span>
                    </div>
                </div>
            <?php else: ?>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.9rem;">
                    Enter the 6-digit code from your authenticator app.
                </p>
            <?php endif; ?>

            <form method="POST" action="/staff/two-fa-auth">
                <div class="form-group">
                    <label for="two_fa_code">Authentication Code</label>
                    <input type="text" id="two_fa_code" name="two_fa_code" placeholder="123456" maxlength="6" pattern="[0-9]{6}" required>
                </div>
                <button type="submit" class="auth-btn">Verify & Complete Login</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['setup_2fa']) && $_SESSION['setup_2fa']): ?>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const secretKey = document.getElementById('secret-key').textContent.trim();
    const qrData = `otpauth://totp/Nexi%20Hub:<?php echo urlencode($_SESSION['staff_email'] ?? ''); ?>?secret=${secretKey}&issuer=Nexi%20Hub`;
    
    QRCode.toCanvas(document.getElementById('qrcode'), qrData, {
        width: 200,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#ffffff'
        }
    });
});
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
