<?php
require_once '../config/config.php';

// Create time_off_requests table if it doesn't exist
$db = $pdo;
$is_mysql = ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql');
$int_primary = $is_mysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';

$createTimeOffTableSQL = "
CREATE TABLE IF NOT EXISTS time_off_requests (
    id $int_primary,
    staff_id " . ($is_mysql ? 'INT' : 'INTEGER') . " NOT NULL,
    request_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days " . ($is_mysql ? 'INT' : 'INTEGER') . " NOT NULL,
    reason TEXT,
    notes TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    reviewed_by " . ($is_mysql ? 'INT' : 'INTEGER') . ",
    reviewed_at DATETIME,
    review_notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP" . ($is_mysql ? " ON UPDATE CURRENT_TIMESTAMP" : "") . "
)";

$createAuditLogSQL = "
CREATE TABLE IF NOT EXISTS time_off_audit_log (
    id $int_primary,
    request_id " . ($is_mysql ? 'INT' : 'INTEGER') . " NOT NULL,
    staff_id " . ($is_mysql ? 'INT' : 'INTEGER') . " NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_status VARCHAR(20),
    new_status VARCHAR(20),
    notes TEXT,
    created_by " . ($is_mysql ? 'INT' : 'INTEGER') . ",
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

try {
    $db->exec($createTimeOffTableSQL);
    $db->exec($createAuditLogSQL);
} catch (PDOException $e) {
    error_log("Error creating time off tables: " . $e->getMessage());
}

// Handle login
$error_message = '';
$success_message = '';

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

// Handle time off request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    if (isset($_SESSION['staff_id'])) {
        $request_type = $_POST['request_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $reason = $_POST['reason'];
        $notes = $_POST['notes'] ?? '';
        
        // Calculate total days
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end->add(new DateInterval('P1D')); // Include end date
        $total_days = $start->diff($end)->days;
        
        // Insert request
        $stmt = $pdo->prepare("
            INSERT INTO time_off_requests (staff_id, request_type, start_date, end_date, total_days, reason, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$_SESSION['staff_id'], $request_type, $start_date, $end_date, $total_days, $reason, $notes])) {
            $request_id = $pdo->lastInsertId();
            
            // Add audit log
            $audit_stmt = $pdo->prepare("
                INSERT INTO time_off_audit_log (request_id, staff_id, action, new_status, notes, created_by)
                VALUES (?, ?, 'created', 'pending', 'Request submitted', ?)
            ");
            $audit_stmt->execute([$request_id, $_SESSION['staff_id'], $_SESSION['staff_id']]);
            
            // Send email notifications
            $staff_stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE id = ?");
            $staff_stmt->execute([$_SESSION['staff_id']]);
            $staff = $staff_stmt->fetch();
            
            sendTimeOffEmail($staff, $request_type, $start_date, $end_date, $total_days, $reason, 'submitted');
            
            $success_message = 'Time off request submitted successfully!';
        } else {
            $error_message = 'Failed to submit request. Please try again.';
        }
    }
}

// Check if user is logged in via contract user session
if (!isset($_SESSION['contract_user_id'])) {
    // Not logged in, show login form
    $page_title = "Time Off Portal - Login";
    $page_description = "Request and manage your time off";
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

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-danger {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
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
                    <h1>Time Off Portal</h1>
                    <p>Request and manage your time off</p>
                </div>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
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
                    <p><strong>Features:</strong> Submit time off requests, track status, and view history</p>
                    <p><strong>Notifications:</strong> Receive email updates on request status changes</p>
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
                <div class="alert alert-danger">
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

// Get user's time off requests
$stmt = $pdo->prepare("SELECT * FROM time_off_requests WHERE staff_id = ? ORDER BY created_at DESC");
$stmt->execute([$staff['id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate used time off for current year
$current_year = date('Y');
$stmt = $pdo->prepare("
    SELECT SUM(total_days) as used_days 
    FROM time_off_requests 
    WHERE staff_id = ? AND status = 'approved' AND YEAR(start_date) = ?
");
$stmt->execute([$staff['id'], $current_year]);
$used_days = $stmt->fetchColumn() ?: 0;

$remaining_days = max(0, $staff['time_off_balance'] - $used_days);

$page_title = "Time Off Portal";
$page_description = "Manage your time off requests";
include '../includes/header.php';

// Email function
function sendTimeOffEmail($staff, $request_type, $start_date, $end_date, $total_days, $reason, $action, $notes = '') {
    $to_staff = $staff['nexi_email'];
    $to_hr = 'hr@nexihub.uk';
    
    $subject = "Time Off Request " . ucfirst($action) . " - " . $staff['full_name'];
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: #e64f21; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .details { background: #f9f9f9; padding: 15px; border-left: 4px solid #e64f21; margin: 15px 0; }
            .footer { text-align: center; padding: 10px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>Nexi Hub - Time Off Request " . ucfirst($action) . "</h2>
        </div>
        <div class='content'>
            <h3>Request Details</h3>
            <div class='details'>
                <p><strong>Staff Member:</strong> " . htmlspecialchars($staff['full_name']) . " (" . htmlspecialchars($staff['staff_id']) . ")</p>
                <p><strong>Department:</strong> " . htmlspecialchars($staff['department']) . "</p>
                <p><strong>Request Type:</strong> " . htmlspecialchars($request_type) . "</p>
                <p><strong>Dates:</strong> " . date('M j, Y', strtotime($start_date)) . " to " . date('M j, Y', strtotime($end_date)) . "</p>
                <p><strong>Total Days:</strong> " . $total_days . "</p>
                <p><strong>Reason:</strong> " . htmlspecialchars($reason) . "</p>
                " . ($notes ? "<p><strong>Notes:</strong> " . htmlspecialchars($notes) . "</p>" : "") . "
                <p><strong>Status:</strong> " . ucfirst($action) . "</p>
                <p><strong>Date:</strong> " . date('M j, Y g:i A') . "</p>
            </div>
        </div>
        <div class='footer'>
            <p>This is an automated message from the Nexi Hub Time Off Portal.</p>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Nexi Hub <noreply@nexihub.uk>" . "\r\n";
    
    // Send to staff
    if ($to_staff) {
        @mail($to_staff, $subject, $message, $headers);
    }
    
    // Send to HR
    @mail($to_hr, $subject, $message, $headers);
}
?>

<style>
/* Time Off Portal Styles */
.timeoff-section {
    background: var(--background-dark);
    min-height: 100vh;
    padding: 2rem 0;
}

.timeoff-header {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem;
    margin-bottom: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.timeoff-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.timeoff-header h1 {
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
    max-width: 800px;
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

.balance-item .info-value {
    color: var(--primary-color);
    font-size: 1.3rem;
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

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.request-form-section {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
}

.section-title {
    color: var(--text-primary);
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
}

.form-group {
    margin-bottom: 1.5rem;
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
    border-radius: 8px;
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
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.btn {
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    box-shadow: 0 4px 12px rgba(230, 79, 33, 0.3);
    width: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(230, 79, 33, 0.4);
}

.requests-history {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
}

.requests-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--background-dark);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px var(--shadow-light);
}

.requests-table thead {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.requests-table th,
.requests-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.requests-table th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.requests-table tbody tr {
    transition: all 0.3s ease;
}

.requests-table tbody tr:hover {
    background: var(--background-light);
}

.requests-table tbody tr:last-child td {
    border-bottom: none;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.pending {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-badge.approved {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-badge.denied {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 8px;
    font-weight: 500;
}

.alert-success {
    background-color: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.alert-danger {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
    color: var(--primary-color);
}

.empty-state h3 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .timeoff-header h1 {
        font-size: 2rem;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .user-info {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .navigation-bar {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .nav-links {
        justify-content: center;
    }
    
    .requests-table {
        font-size: 0.8rem;
    }
    
    .requests-table th,
    .requests-table td {
        padding: 0.5rem;
    }
}
</style>

<section class="timeoff-section">
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

        <div class="timeoff-header">
            <h1>Time Off Portal</h1>
            <p class="welcome-message">Submit and manage your time off requests</p>
            
            <div class="user-info">
                <div class="info-item">
                    <div class="info-label">Staff ID</div>
                    <div class="info-value"><?php echo htmlspecialchars($staff['staff_id']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value"><?php echo htmlspecialchars($staff['department'] ?: 'Not Set'); ?></div>
                </div>
                <div class="info-item balance-item">
                    <div class="info-label">Annual Allowance</div>
                    <div class="info-value"><?php echo $staff['time_off_balance']; ?> days</div>
                </div>
                <div class="info-item balance-item">
                    <div class="info-label">Remaining Days</div>
                    <div class="info-value"><?php echo $remaining_days; ?> days</div>
                </div>
            </div>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <div class="request-form-section">
                <h2 class="section-title">Submit New Request</h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="request_type" class="form-label">Request Type</label>
                        <select id="request_type" name="request_type" class="form-control" required>
                            <option value="">Select request type...</option>
                            <option value="annual_leave">Annual Leave</option>
                            <option value="sick_leave">Sick Leave</option>
                            <option value="personal_leave">Personal Leave</option>
                            <option value="emergency_leave">Emergency Leave</option>
                            <option value="study_leave">Study Leave</option>
                            <option value="maternity_paternity">Maternity/Paternity Leave</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea id="reason" name="reason" class="form-control" rows="3" placeholder="Brief description of the reason for time off" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any additional information or special requirements"></textarea>
                    </div>

                    <button type="submit" name="submit_request" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </form>
            </div>

            <div class="requests-history">
                <h2 class="section-title">Request History</h2>
                
                <?php if (!empty($requests)): ?>
                    <div style="overflow-x: auto;">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?php echo ucfirst(str_replace('_', ' ', $request['request_type'])); ?></td>
                                        <td>
                                            <?php 
                                            echo date('M j', strtotime($request['start_date'])); 
                                            if ($request['start_date'] !== $request['end_date']) {
                                                echo ' - ' . date('M j, Y', strtotime($request['end_date']));
                                            } else {
                                                echo ', ' . date('Y', strtotime($request['start_date']));
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $request['total_days']; ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $request['status']; ?>">
                                                <i class="fas fa-<?php 
                                                    echo $request['status'] === 'approved' ? 'check-circle' : 
                                                        ($request['status'] === 'denied' ? 'times-circle' : 'clock'); 
                                                ?>"></i>
                                                <?php echo ucfirst($request['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($request['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>No Requests Yet</h3>
                        <p>You haven't submitted any time off requests. Use the form to submit your first request.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
// Auto-calculate end date and validate
document.getElementById('start_date').addEventListener('change', function() {
    const endDateInput = document.getElementById('end_date');
    endDateInput.min = this.value;
    if (endDateInput.value && endDateInput.value < this.value) {
        endDateInput.value = this.value;
    }
});

// Auto-set end date to start date if not set
document.getElementById('end_date').addEventListener('focus', function() {
    const startDate = document.getElementById('start_date').value;
    if (startDate && !this.value) {
        this.value = startDate;
    }
});
</script>

<?php include '../includes/footer.php'; ?>
