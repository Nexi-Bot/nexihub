<?php
require_once __DIR__ . '/../config/config.php';

// Handle login
if ($_POST['action'] ?? '' === 'login') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $db = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]);
            } else {
                $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            
            $stmt = $db->prepare("SELECT * FROM contract_users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['contract_user_id'] = $user['id'];
                $_SESSION['contract_user_email'] = $user['email'];
                $_SESSION['contract_user_role'] = $user['role'];
                $_SESSION['contract_staff_id'] = $user['staff_id'];
                
                // Check if password reset is required
                if ($user['needs_password_reset']) {
                    $_SESSION['needs_password_reset'] = true;
                    header('Location: ./password-reset.php');
                    exit;
                }
                
                header('Location: ./dashboard.php');
                exit;
            } else {
                $error = "Invalid email or password";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter both email and password";
    }
}

$page_title = "Nexi HR Portal - Login";
$page_description = "Staff Contract Signing Portal";
include __DIR__ . '/../includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Nexi HR Portal</h1>
            <p class="hero-subtitle">Digital Contract Signing</p>
            <p class="hero-description">
                Secure portal for staff to view and digitally sign employment contracts, NDAs, and company policies.
            </p>
        </div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="login-wrapper">
            <div class="login-container">
                <div class="login-header">
                    <div class="login-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/>
                        </svg>
                    </div>
                    <h2>Staff Login</h2>
                    <p>Access your contract dashboard</p>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,2L13.09,8.26L22,9L13.09,9.74L12,16L10.91,9.74L2,9L10.91,8.26L12,2Z"/>
                        </svg>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="login-form">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                        Sign In to Nexi HR Portal
                    </button>
                </form>
                
                <div class="login-support">
                    <div class="support-info">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A1,1 0 0,1 11,16A1,1 0 0,1 12,15A1,1 0 0,1 13,16A1,1 0 0,1 12,17M12,7A3,3 0 0,1 15,10C15,11.31 14.17,12.42 13.06,12.81C12.67,12.95 12.5,13.34 12.5,13.75V14H11.5V13.75C11.5,12.9 12.1,12.23 12.94,12.06C13.63,11.92 14,11.27 14,10.5C14,9.67 13.33,9 12.5,9S11,9.67 11,10.5H10A2.5,2.5 0 0,1 12.5,8A2.5,2.5 0 0,1 15,10.5Z"/>
                        </svg>
                        <div>
                            <h4>Need Help?</h4>
                            <p>Contact HR at <a href="mailto:hr@nexihub.uk">hr@nexihub.uk</a> for login assistance</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="info-panel">
                <h3>What you can do in the Nexi HR Portal:</h3>
                <div class="feature-list">
                    <div class="feature-item">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                        <div>
                            <h4>View Assigned Contracts</h4>
                            <p>Access all employment contracts, NDAs, and policies assigned to you</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14.6,16.6L19.2,12L14.6,7.4L13.2,8.8L15.67,11.25H5V12.75H15.67L13.2,15.2L14.6,16.6M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4C12.76,4 13.5,4.11 14.2,4.31L15.77,2.74C14.61,2.26 13.34,2 12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22C13.34,22 14.61,21.74 15.77,21.26L14.2,19.69C13.5,19.89 12.76,20 12,20Z"/>
                        </svg>
                        <div>
                            <h4>Digital Signature</h4>
                            <p>Sign contracts electronically with our secure signature pad</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A1,1 0 0,1 11,16A1,1 0 0,1 12,15A1,1 0 0,1 13,16A1,1 0 0,1 12,17M12,7A3,3 0 0,1 15,10C15,11.31 14.17,12.42 13.06,12.81C12.67,12.95 12.5,13.34 12.5,13.75V14H11.5V13.75C11.5,12.9 12.1,12.23 12.94,12.06C13.63,11.92 14,11.27 14,10.5C14,9.67 13.33,9 12.5,9S11,9.67 11,10.5H10A2.5,2.5 0 0,1 12.5,8A2.5,2.5 0 0,1 15,10.5Z"/>
                        </svg>
                        <div>
                            <h4>Track Status</h4>
                            <p>Monitor the signing status of all your contracts in one place</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.login-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
    max-width: 1000px;
    margin: 0 auto;
}

.login-container {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    padding: 3rem;
    box-shadow: 0 20px 40px var(--shadow-light);
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.login-icon svg {
    width: 32px;
    height: 32px;
}

.login-header h2 {
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    font-size: 1.8rem;
    font-weight: 700;
}

.login-header p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 1.1rem;
}

.login-form {
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.form-group input {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 1rem;
    background: var(--background-dark);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(230, 79, 33, 0.1);
}

.btn {
    width: 100%;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(230, 79, 33, 0.3);
}

.btn svg {
    width: 20px;
    height: 20px;
}

.error-message {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
}

.error-message svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.login-support {
    border-top: 1px solid var(--border-color);
    padding-top: 1.5rem;
}

.support-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--background-dark);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.support-info svg {
    width: 24px;
    height: 24px;
    color: var(--primary-color);
    flex-shrink: 0;
}

.support-info h4 {
    margin: 0 0 0.25rem 0;
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 600;
}

.support-info p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.support-info a {
    color: var(--primary-color);
    text-decoration: none;
}

.support-info a:hover {
    text-decoration: underline;
}

.info-panel {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    padding: 3rem;
}

.info-panel h3 {
    color: var(--text-primary);
    margin: 0 0 2rem 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.feature-list {
    display: grid;
    gap: 1.5rem;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    border-color: var(--primary-color);
    box-shadow: 0 8px 16px var(--shadow-light);
}

.feature-item svg {
    width: 24px;
    height: 24px;
    color: var(--primary-color);
    flex-shrink: 0;
    margin-top: 0.25rem;
}

.feature-item h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.feature-item p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .login-wrapper {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .login-container,
    .info-panel {
        padding: 2rem;
    }
    
    .login-header h2 {
        font-size: 1.5rem;
    }
    
    .info-panel h3 {
        font-size: 1.3rem;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
