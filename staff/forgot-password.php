<?php
require_once __DIR__ . '/../config/config.php';

$page_title = "Forgot Password";
$page_description = "Reset your Nexi Hub staff account password";

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    
    try {
        if (!$email || !str_ends_with($email, '@nexihub.uk')) {
            throw new Exception('Please enter a valid @nexihub.uk email address');
        }

        // Check if staff member exists
        $stmt = $pdo->prepare("SELECT id FROM staff WHERE email = ? AND is_active = 1");
        $stmt->execute([$email]);
        $staff = $stmt->fetch();

        if ($staff) {
            // Generate reset token
            $token = generateSecureToken(32);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Delete any existing tokens for this user
            $deleteStmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE staff_id = ?");
            $deleteStmt->execute([$staff['id']]);

            // Insert new token
            $insertStmt = $pdo->prepare("
                INSERT INTO password_reset_tokens (staff_id, token, expires_at) 
                VALUES (?, ?, ?)
            ");
            $insertStmt->execute([$staff['id'], $token, $expiresAt]);

            // In a real application, you would send an email here
            // For now, we'll just log the reset link
            $resetLink = SITE_URL . "/staff/reset-password?token=" . $token;
            error_log("Password reset link for {$email}: {$resetLink}");
        }

        // Always show success message for security
        $message = 'If an account with that email exists, a password reset link has been sent.';
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include __DIR__ . '/../includes/header.php';
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

.success-message {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22C55E;
    padding: 0.875rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
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
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="/nexi.png" alt="Nexi Hub" class="auth-logo">
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your @nexihub.uk email address</p>
        </div>

        <?php if ($message): ?>
            <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="your.name@nexihub.uk" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <button type="submit" class="auth-btn">Send Reset Link</button>
        </form>

        <div class="auth-links">
            <a href="/staff/login">← Back to Login</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
