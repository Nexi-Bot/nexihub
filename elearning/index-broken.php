<?php
require_once '../config/config.php';

// Handle login
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please enter both email and password.';
    } else {
        // Check contract_users table (HR portal login)
        $stmt = $pdo->prepare("SELECT * FROM contract_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session for contract user
            $_SESSION['contract_user_id'] = $user['id'];
            $_SESSION['contract_user_email'] = $user['email'];
            $_SESSION['contract_user_role'] = $user['role'];
            
            // Get associated staff member if exists
            if ($user['staff_id']) {
                $staff_stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE id = ?");
                $staff_stmt->execute([$user['staff_id']]);
                $staff = $staff_stmt->fetch();
                if ($staff) {
                    $_SESSION['staff_id'] = $staff['id'];
                    $_SESSION['staff_name'] = $staff['full_name'];
                }
            }
            
            // Redirect to prevent form resubmission
            header('Location: index.php');
            exit;
        } else {
            $error_message = 'Invalid email or password.';
        }
    }
}

// Check if user is logged in via contract user session
if (!isset($_SESSION['contract_user_id'])) {
    // Not logged in, show login form
    $page_title = "E-Learning Portal - Login";
    $page_description = "Access your training modules and track your progress";
    include '../includes/header.php';
    ?>
    
    <style>
    .login-section {
        background: var(--background-dark);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .login-container {
        background: var(--background-light);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 3rem;
        width: 100%;
        max-width: 450px;
        text-align: center;
        box-shadow: 0 20px 40px var(--shadow-medium);
        position: relative;
        overflow: hidden;
    }

    .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .login-header {
        margin-bottom: 2rem;
    }

    .login-header h1 {
        color: var(--text-primary);
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .login-header p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 1.1rem;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        text-align: left;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: var(--background-dark);
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(230, 79, 33, 0.1);
        background: var(--background-light);
    }

    .btn-login {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(230, 79, 33, 0.3);
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(230, 79, 33, 0.4);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
        font-weight: 500;
    }

    .portal-info {
        margin-top: 2rem;
        padding: 1.5rem;
        background: var(--background-dark);
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .portal-info h3 {
        color: var(--primary-color);
        margin: 0 0 1rem 0;
        font-size: 1.2rem;
    }

    .portal-info p {
        color: var(--text-secondary);
        margin: 0.5rem 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .login-container {
            margin: 1rem;
            padding: 2rem;
        }
        
        .login-header h1 {
            font-size: 1.5rem;
        }
    }
    </style>

    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>E-Learning Portal</h1>
                    <p>Access your training modules and track progress</p>
                </div>

                <?php if ($error_message): ?>
                    <div class="alert">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" name="login" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <div class="portal-info">
                    <h3>Portal Information</h3>
                    <p><strong>Access:</strong> Use the same login credentials as the HR Portal</p>
                    <p><strong>Training:</strong> Complete 7 comprehensive modules</p>
                    <p><strong>Certificate:</strong> Download your completion certificate</p>
                </div>
            </div>
        </div>
    </section>

    <?php
    include '../includes/footer.php';
    exit;
}
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        text-align: left;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: var(--background-dark);
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(230, 79, 33, 0.1);
    }

    .btn-login {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(230, 79, 33, 0.3);
    }

    .error-message {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid rgba(239, 68, 68, 0.2);
        margin-bottom: 1rem;
    }

    .login-footer {
        margin-top: 2rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .login-footer a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }
    </style>

    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>E-Learning Portal</h1>
                    <p>Please sign in to access your training modules</p>
                </div>

                <?php
                $error = '';
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $email = $_POST['email'] ?? '';
                    $password = $_POST['password'] ?? '';

                    if ($email && $password) {
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM contract_users WHERE email = ?");
                            $stmt->execute([$email]);
                            $user = $stmt->fetch();

                            if ($user && password_verify($password, $user['password_hash'])) {
                                $_SESSION['contract_user_id'] = $user['id'];
                                $_SESSION['contract_user_email'] = $user['email'];
                                $_SESSION['contract_staff_id'] = $user['staff_id'];
                                
                                header('Location: /elearning/');
                                exit;
                            } else {
                                $error = "Invalid email or password";
                            }
                        } catch (PDOException $e) {
                            $error = "Database error occurred";
                        }
                    } else {
                        $error = "Please enter both email and password";
                    }
                }

                if ($error): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                               placeholder="your.email@nexihub.uk">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <div class="login-footer">
                    <p>Need help? Contact <a href="mailto:hr@nexihub.uk">hr@nexihub.uk</a></p>
                </div>
            </div>
        </div>
    </section>

    <?php
    include '../includes/footer.php';
    exit;
}

// User is logged in, get staff information
$stmt = $pdo->prepare("SELECT sp.* FROM staff_profiles sp 
                       JOIN contract_users cu ON sp.id = cu.staff_id 
                       WHERE cu.id = ?");
$stmt->execute([$_SESSION['contract_user_id']]);
$staff = $stmt->fetch();

if (!$staff) {
    session_destroy();
    header('Location: /elearning/');
    exit;
}

// Get user's E-Learning progress
$stmt = $pdo->prepare("SELECT * FROM elearning_progress WHERE staff_id = ?");
$stmt->execute([$staff['id']]);
$progress_records = $stmt->fetchAll();

// Create progress array
$completed_modules = [];
foreach ($progress_records as $record) {
    $completed_modules[$record['module_id']] = $record;
}

// Module configuration
$modules = [
    1 => [
        'title' => 'Welcome to Nexi',
        'description' => 'Learn about our company mission, vision, and core values',
        'duration' => '15 min'
    ],
    2 => [
        'title' => 'Company Values & Culture',
        'description' => 'Understanding our workplace culture and professional standards',
        'duration' => '20 min'
    ],
    3 => [
        'title' => 'Communication Guidelines',
        'description' => 'Effective communication practices and professional etiquette',
        'duration' => '15 min'
    ],
    4 => [
        'title' => 'Data Protection & Security',
        'description' => 'Essential security practices and data protection policies',
        'duration' => '25 min'
    ],
    5 => [
        'title' => 'Final Assessment',
        'description' => 'Comprehensive assessment covering all training modules',
        'duration' => '30 min'
    ]
];

$total_modules = count($modules);
$completed_count = count($completed_modules);
$progress_percent = $total_modules > 0 ? round(($completed_count / $total_modules) * 100) : 0;
$is_completed = $completed_count >= $total_modules;

$page_title = "E-Learning Portal";
$page_description = "Complete your training modules and track your progress";
include '../includes/header.php';
?>

<style>
.elearning-section {
    background: var(--background-dark);
    min-height: 100vh;
    padding: 2rem 0;
}

.dashboard-header {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.dashboard-header h1 {
    color: var(--text-primary);
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.dashboard-header p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 1.1rem;
}

.progress-overview {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.progress-header h2 {
    color: var(--text-primary);
    margin: 0;
    font-size: 1.5rem;
}

.progress-percentage {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.progress-bar-container {
    background: var(--border-color);
    border-radius: 20px;
    height: 12px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.progress-bar-fill {
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    height: 100%;
    border-radius: 20px;
    transition: width 0.5s ease;
}

.progress-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    color: var(--text-secondary);
}

.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.module-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 0;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.module-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px var(--shadow-medium);
    border-color: var(--primary-color);
}

.module-card.completed {
    border-color: #10b981;
}

.module-card.completed::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #10b981;
}

.module-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--background-dark);
}

.module-title {
    color: var(--text-primary);
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.module-duration {
    color: var(--text-secondary);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.module-content {
    padding: 1.5rem;
}

.module-description {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.module-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(230, 79, 33, 0.3);
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--border-color);
    color: var(--text-secondary);
}

.btn-secondary:hover {
    background: var(--text-secondary);
    color: var(--background-light);
}

.completion-banner {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    margin-bottom: 2rem;
}

.completion-banner h2 {
    margin: 0 0 1rem 0;
    font-size: 2rem;
}

.completion-banner p {
    margin: 0 0 1.5rem 0;
    opacity: 0.9;
}

.certificate-btn {
    background: white;
    color: #10b981;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.certificate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.user-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
}

.user-info h3 {
    color: var(--text-primary);
    margin: 0;
    font-size: 1.2rem;
}

.user-info p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.9rem;
}

.logout-btn {
    background: var(--background-dark);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background: var(--border-color);
    color: var(--text-primary);
}

@media (max-width: 768px) {
    .dashboard-header h1 {
        font-size: 2rem;
    }
    
    .modules-grid {
        grid-template-columns: 1fr;
    }
    
    .progress-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .user-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
}
</style>

<section class="elearning-section">
    <div class="container">
        <div class="user-header">
            <div class="user-info">
                <h3>Welcome, <?php echo htmlspecialchars($staff['preferred_name'] ?? $staff['full_name']); ?>!</h3>
                <p><?php echo htmlspecialchars($staff['job_title'] ?? 'Staff Member'); ?> • <?php echo htmlspecialchars($staff['department'] ?? 'Nexi Hub'); ?></p>
            </div>
            <a href="/elearning/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <div class="dashboard-header">
            <h1>E-Learning Portal</h1>
            <p>Complete your training modules to enhance your knowledge and skills</p>
        </div>

        <?php if ($is_completed): ?>
            <div class="completion-banner">
                <h2>🎉 Congratulations!</h2>
                <p>You have successfully completed all training modules. Download your certificate below.</p>
                <a href="/elearning/certificate.php" class="certificate-btn" target="_blank">
                    <i class="fas fa-certificate"></i> Download Certificate
                </a>
            </div>
        <?php endif; ?>

        <div class="progress-overview">
            <div class="progress-header">
                <h2>Your Progress</h2>
                <div class="progress-percentage"><?php echo $progress_percent; ?>%</div>
            </div>
            
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: <?php echo $progress_percent; ?>%"></div>
            </div>
            
            <div class="progress-stats">
                <div>Completed: <?php echo $completed_count; ?>/<?php echo $total_modules; ?> modules</div>
                <div>Status: <?php echo $staff['elearning_status'] ?? 'Not Started'; ?></div>
                <?php if ($is_completed): ?>
                    <div>Completed: <?php echo date('M j, Y'); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="modules-grid">
            <?php foreach ($modules as $module_id => $module): ?>
                <?php 
                $is_module_completed = isset($completed_modules[$module_id]);
                $can_access = $module_id == 1 || isset($completed_modules[$module_id - 1]);
                ?>
                <div class="module-card <?php echo $is_module_completed ? 'completed' : ''; ?>">
                    <div class="module-header">
                        <h3 class="module-title">
                            Module <?php echo $module_id; ?>: <?php echo htmlspecialchars($module['title']); ?>
                            <?php if ($is_module_completed): ?>
                                <i class="fas fa-check-circle" style="color: #10b981; margin-left: 0.5rem;"></i>
                            <?php endif; ?>
                        </h3>
                        <div class="module-duration">
                            <i class="fas fa-clock"></i>
                            <?php echo $module['duration']; ?>
                        </div>
                    </div>
                    
                    <div class="module-content">
                        <p class="module-description"><?php echo htmlspecialchars($module['description']); ?></p>
                        
                        <div class="module-actions">
                            <?php if ($is_module_completed): ?>
                                <a href="/elearning/module.php?id=<?php echo $module_id; ?>" class="btn btn-success">
                                    <i class="fas fa-check"></i> Completed
                                </a>
                                <a href="/elearning/module.php?id=<?php echo $module_id; ?>" class="btn btn-secondary">
                                    <i class="fas fa-eye"></i> Review
                                </a>
                            <?php elseif ($can_access): ?>
                                <a href="/elearning/module.php?id=<?php echo $module_id; ?>" class="btn btn-primary">
                                    <i class="fas fa-play"></i> Start Module
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-lock"></i> Locked
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
// Auto-refresh progress if needed
setInterval(function() {
    // Check for any updates without reloading the page
    fetch('/elearning/check-progress.php')
        .then(response => response.json())
        .then(data => {
            if (data.updated) {
                location.reload();
            }
        })
        .catch(error => console.log('Progress check failed'));
}, 30000); // Check every 30 seconds
</script>

<?php include '../includes/footer.php'; ?>
