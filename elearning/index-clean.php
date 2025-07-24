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

// User is logged in, get staff information
$staff = null;
if (isset($_SESSION['staff_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE id = ?");
    $stmt->execute([$_SESSION['staff_id']]);
    $staff = $stmt->fetch();
} else {
    // Try to find staff by contract user email
    $stmt = $pdo->prepare("SELECT sp.* FROM staff_profiles sp JOIN contract_users cu ON sp.id = cu.staff_id WHERE cu.id = ?");
    $stmt->execute([$_SESSION['contract_user_id']]);
    $staff = $stmt->fetch();
    if ($staff) {
        $_SESSION['staff_id'] = $staff['id'];
        $_SESSION['staff_name'] = $staff['full_name'];
    }
}

if (!$staff) {
    // No associated staff member found
    $error_message = 'No staff profile found for your account. Please contact HR.';
    include '../includes/header.php';
    ?>
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>Access Denied</h1>
                    <p>Staff profile not found</p>
                </div>
                <div class="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
                <a href="logout.php" class="btn-login">Return to Login</a>
            </div>
        </div>
    </section>
    <?php
    include '../includes/footer.php';
    exit;
}

// Get user's E-Learning progress
$stmt = $pdo->prepare("SELECT * FROM elearning_progress WHERE staff_id = ? ORDER BY module_id");
$stmt->execute([$staff['id']]);
$progress = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create progress lookup
$progress_lookup = [];
foreach ($progress as $p) {
    $progress_lookup[$p['module_id']] = $p;
}

// Module definitions
$modules = [
    1 => ['title' => 'Company Overview', 'description' => 'Learn about Nexi Hub\'s mission, values, and structure'],
    2 => ['title' => 'Code of Conduct', 'description' => 'Professional behavior and ethical guidelines'],
    3 => ['title' => 'Health & Safety', 'description' => 'Workplace safety protocols and procedures'],
    4 => ['title' => 'Data Protection', 'description' => 'GDPR compliance and data handling best practices'],
    5 => ['title' => 'Communication Guidelines', 'description' => 'Internal and external communication standards'],
    6 => ['title' => 'Technology Policies', 'description' => 'IT security, acceptable use, and digital tools'],
    7 => ['title' => 'Performance Standards', 'description' => 'Quality expectations and performance metrics']
];

// Calculate completion status
$completed_modules = 0;
$total_modules = count($modules);
foreach ($modules as $module_id => $module) {
    if (isset($progress_lookup[$module_id]) && $progress_lookup[$module_id]['completed']) {
        $completed_modules++;
    }
}

$completion_percentage = $total_modules > 0 ? round(($completed_modules / $total_modules) * 100) : 0;
$all_completed = $completed_modules === $total_modules;

// Update staff E-Learning status
$status = 'Not Started';
if ($completed_modules > 0 && $completed_modules < $total_modules) {
    $status = 'In Progress';
} elseif ($all_completed) {
    $status = 'Completed';
}

$stmt = $pdo->prepare("UPDATE staff_profiles SET elearning_status = ? WHERE id = ?");
$stmt->execute([$status, $staff['id']]);

$page_title = "E-Learning Portal";
$page_description = "Training modules and progress tracking";
include '../includes/header.php';
?>

<style>
/* E-Learning Portal Styles */
.elearning-section {
    background: var(--background-dark);
    min-height: 100vh;
    padding: 2rem 0;
}

.elearning-header {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem;
    margin-bottom: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.elearning-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.elearning-header h1 {
    color: var(--text-primary);
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-message {
    color: var(--text-secondary);
    font-size: 1.2rem;
    margin: 0 0 1rem 0;
}

.user-info {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin: 0 auto;
    max-width: 600px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    text-align: center;
}

.info-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.info-value {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1.1rem;
}

.progress-overview {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 3rem;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.progress-title {
    color: var(--text-primary);
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.completion-badge {
    padding: 0.75rem 1.5rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.completion-badge.completed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.completion-badge.in-progress {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.completion-badge.not-started {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.progress-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.progress-bar-container {
    background: var(--background-dark);
    border-radius: 8px;
    height: 12px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.progress-bar {
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    height: 100%;
    transition: width 0.3s ease;
}

.progress-text {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
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

.module-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transform: scaleX(0);
    transition: all 0.3s ease;
}

.module-card:hover::before {
    transform: scaleX(1);
}

.module-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px var(--shadow-medium);
    border-color: var(--primary-color);
}

.module-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.module-number {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 1rem;
}

.module-title {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.module-description {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.5;
}

.module-content {
    padding: 1.5rem;
}

.module-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.module-status.completed {
    color: #10b981;
}

.module-status.not-completed {
    color: #ef4444;
}

.module-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    box-shadow: 0 4px 12px rgba(230, 79, 33, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(230, 79, 33, 0.4);
}

.btn-success {
    background: #10b981;
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.certificate-section {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-top: 3rem;
    text-align: center;
}

.certificate-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.certificate-title {
    color: var(--text-primary);
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
}

.certificate-description {
    color: var(--text-secondary);
    margin: 0 0 2rem 0;
    line-height: 1.6;
}

.navigation-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    padding: 1rem 0;
}

.user-welcome {
    color: var(--text-primary);
    font-weight: 600;
}

.nav-links {
    display: flex;
    gap: 1rem;
}

.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: var(--primary-color);
    background: rgba(230, 79, 33, 0.1);
}

@media (max-width: 768px) {
    .elearning-header h1 {
        font-size: 2rem;
    }
    
    .modules-grid {
        grid-template-columns: 1fr;
    }
    
    .progress-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .user-info {
        grid-template-columns: 1fr;
        text-align: left;
    }
    
    .navigation-bar {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .nav-links {
        justify-content: center;
    }
}
</style>

<section class="elearning-section">
    <div class="container">
        <div class="navigation-bar">
            <div class="user-welcome">
                Welcome, <?php echo htmlspecialchars($staff['full_name']); ?>
            </div>
            <div class="nav-links">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <div class="elearning-header">
            <h1>E-Learning Portal</h1>
            <p class="welcome-message">Complete your training modules and track your progress</p>
            
            <div class="user-info">
                <div class="info-item">
                    <div class="info-label">Staff ID</div>
                    <div class="info-value"><?php echo htmlspecialchars($staff['staff_id']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value"><?php echo htmlspecialchars($staff['department'] ?: 'Not Set'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Progress Status</div>
                    <div class="info-value"><?php echo htmlspecialchars($status); ?></div>
                </div>
            </div>
        </div>

        <div class="progress-overview">
            <div class="progress-header">
                <h2 class="progress-title">Training Progress</h2>
                <span class="completion-badge <?php echo strtolower(str_replace(' ', '-', $status)); ?>">
                    <?php echo htmlspecialchars($status); ?>
                </span>
            </div>

            <div class="progress-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $completed_modules; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_modules - $completed_modules; ?></div>
                    <div class="stat-label">Remaining</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $completion_percentage; ?>%</div>
                    <div class="stat-label">Progress</div>
                </div>
            </div>

            <div class="progress-bar-container">
                <div class="progress-bar" style="width: <?php echo $completion_percentage; ?>%"></div>
            </div>
            <div class="progress-text">
                <?php echo $completed_modules; ?> of <?php echo $total_modules; ?> modules completed
            </div>
        </div>

        <div class="modules-grid">
            <?php foreach ($modules as $module_id => $module): ?>
                <?php 
                $is_completed = isset($progress_lookup[$module_id]) && $progress_lookup[$module_id]['completed'];
                $completed_at = $is_completed ? $progress_lookup[$module_id]['completed_at'] : null;
                ?>
                <div class="module-card">
                    <div class="module-header">
                        <div class="module-number"><?php echo $module_id; ?></div>
                        <h3 class="module-title"><?php echo htmlspecialchars($module['title']); ?></h3>
                        <p class="module-description"><?php echo htmlspecialchars($module['description']); ?></p>
                    </div>
                    
                    <div class="module-content">
                        <div class="module-status <?php echo $is_completed ? 'completed' : 'not-completed'; ?>">
                            <i class="fas fa-<?php echo $is_completed ? 'check-circle' : 'clock'; ?>"></i>
                            <?php if ($is_completed): ?>
                                Completed on <?php echo date('M j, Y', strtotime($completed_at)); ?>
                            <?php else: ?>
                                Not Completed
                            <?php endif; ?>
                        </div>

                        <div class="module-actions">
                            <?php if ($is_completed): ?>
                                <a href="module.php?id=<?php echo $module_id; ?>" class="btn btn-success">
                                    <i class="fas fa-eye"></i> Review
                                </a>
                            <?php else: ?>
                                <a href="module.php?id=<?php echo $module_id; ?>" class="btn btn-primary">
                                    <i class="fas fa-play"></i> Start Module
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($all_completed): ?>
            <div class="certificate-section">
                <div class="certificate-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="certificate-title">Congratulations!</h3>
                <p class="certificate-description">
                    You have successfully completed all training modules. 
                    You can now download your completion certificate.
                </p>
                <a href="certificate.php" class="btn btn-success" target="_blank">
                    <i class="fas fa-download"></i> Download Certificate
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
