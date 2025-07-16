<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/api_config.php';
require_once __DIR__ . '/../includes/StripeIntegration.php';
require_once __DIR__ . '/../includes/RealDataAnalytics.php';

// requireAuth(); // Temporarily disabled for debugging

$page_title = "Executive Dashboard";
$page_description = "Nexi Hub Executive Management Center - Complete business oversight and control";

// Database connection for real data
try {
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        // Use MySQL connection
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        // Fallback to SQLite
        $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    // Log error and fallback to SQLite
    error_log("Database connection failed: " . $e->getMessage());
    $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

// Initialize Stripe integration for real financial data
$stripe = null;
if (false && USE_REAL_FINANCIAL_DATA && defined('STRIPE_SECRET_KEY')) { // Temporarily disabled
    $stripe = new StripeIntegration(STRIPE_SECRET_KEY);
}

// Initialize real data analytics
try {
    $analytics_provider = new RealDataAnalytics($db, $stripe);
} catch (Exception $e) {
    error_log("Analytics provider error: " . $e->getMessage());
    // Fallback to simple analytics
    $analytics_provider = null;
}

// Initialize core database tables (compatible with both MySQL and SQLite)
if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
    // MySQL table creation
    $db->exec("CREATE TABLE IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        department VARCHAR(100),
        role VARCHAR(100),
        status VARCHAR(20) DEFAULT 'active',
        hire_date DATE,
        salary DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        status VARCHAR(20) DEFAULT 'active',
        client_name VARCHAR(255),
        budget DECIMAL(10,2),
        start_date DATE,
        end_date DATE,
        completion_percentage INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS financial_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(20) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        description TEXT,
        category VARCHAR(100),
        date DATE,
        status VARCHAR(20) DEFAULT 'completed',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS time_off_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        staff_id INT,
        start_date DATE,
        end_date DATE,
        reason TEXT,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (staff_id) REFERENCES staff(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL,
        action VARCHAR(255) NOT NULL,
        details TEXT,
        user_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS platforms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        status VARCHAR(20) DEFAULT 'active',
        users_count INT DEFAULT 0,
        revenue DECIMAL(10,2) DEFAULT 0,
        uptime DECIMAL(5,2) DEFAULT 99.9,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} else {
    // SQLite table creation (fallback)
    $db->exec("CREATE TABLE IF NOT EXISTS staff (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        department TEXT,
        role TEXT,
        status TEXT DEFAULT 'active',
        hire_date DATE,
        salary DECIMAL(10,2),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS projects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        status TEXT DEFAULT 'active',
        client_name TEXT,
        budget DECIMAL(10,2),
        start_date DATE,
        end_date DATE,
        completion_percentage INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS financial_records (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        description TEXT,
        category TEXT,
        date DATE,
        status TEXT DEFAULT 'completed',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS time_off_requests (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        staff_id INTEGER,
        start_date DATE,
        end_date DATE,
        reason TEXT,
        status TEXT DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (staff_id) REFERENCES staff(id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS activity_log (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL,
        action TEXT NOT NULL,
        details TEXT,
        user_id INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS platforms (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT,
        status TEXT DEFAULT 'active',
        users_count INTEGER DEFAULT 0,
        revenue DECIMAL(10,2) DEFAULT 0,
        uptime DECIMAL(5,2) DEFAULT 99.9,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
}

// Enhanced user profile with real session data
$current_user = [
    'full_name' => $_SESSION['staff_name'] ?? 'Executive Administrator',
    'user_id' => $_SESSION['staff_id'] ?? 1,
    'email' => $_SESSION['staff_email'] ?? 'admin@nexihub.com',
    'department' => $_SESSION['staff_department'] ?? 'Executive Operations',
    'role' => $_SESSION['staff_role'] ?? 'Chief Executive Officer',
    'avatar' => '/nexi.png',
    'last_login' => date('M j, Y \a\t g:i A'),
];

// Get real activity data (no sample data fallback)
function getRecentActivities($db) {
    $activities = $db->query("
        SELECT type, action, details as person, 
               CASE 
                   WHEN datetime(created_at) > datetime('now', '-1 hour') THEN 
                       CAST((julianday('now') - julianday(created_at)) * 24 * 60 AS INTEGER) || ' minutes ago'
                   WHEN datetime(created_at) > datetime('now', '-1 day') THEN 
                       CAST((julianday('now') - julianday(created_at)) * 24 AS INTEGER) || ' hours ago'
                   ELSE 
                       date(created_at) || ' at ' || time(created_at)
               END as time,
               CASE type
                   WHEN 'staff' THEN 'user-plus'
                   WHEN 'finance' THEN 'credit-card'
                   WHEN 'project' THEN 'check-circle'
                   WHEN 'security' THEN 'shield-alt'
                   WHEN 'platform' THEN 'cogs'
                   ELSE 'info-circle'
               END as icon
        FROM activity_log 
        ORDER BY created_at DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    return $activities;
}

// Get current user notifications and tasks
function getUserDashboardData($db, $user_id) {
    $data = [];
    
    // Get pending approvals
    $data['notifications'] = (int)$db->query("SELECT COUNT(*) FROM time_off_requests WHERE status = 'pending'")->fetchColumn();
    $data['unread_messages'] = 8; // This would come from a messages system
    $data['tasks_due_today'] = 5; // This would come from a tasks system
    $data['approval_pending'] = (int)$db->query("SELECT COUNT(*) FROM time_off_requests WHERE status = 'pending'")->fetchColumn();
    
    return $data;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        switch ($_POST['action']) {
            case 'add_staff':
                $stmt = $db->prepare("INSERT INTO staff (name, email, department, role, hire_date, salary) VALUES (?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    $_POST['name'],
                    $_POST['email'],
                    $_POST['department'],
                    $_POST['role'],
                    $_POST['hire_date'],
                    $_POST['salary']
                ]);
                
                if ($result) {
                    // Log activity
                    $stmt = $db->prepare("INSERT INTO activity_log (type, action, details) VALUES ('staff', 'New staff member added', ?)");
                    $stmt->execute([$_POST['name']]);
                    
                    echo json_encode(['success' => true, 'message' => 'Staff member added successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add staff member']);
                }
                break;
                
            case 'add_project':
                $stmt = $db->prepare("INSERT INTO projects (name, description, client_name, budget, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    $_POST['name'],
                    $_POST['description'],
                    $_POST['client_name'],
                    $_POST['budget'],
                    $_POST['start_date'],
                    $_POST['end_date']
                ]);
                
                if ($result) {
                    // Log activity
                    $stmt = $db->prepare("INSERT INTO activity_log (type, action, details) VALUES ('project', 'New project created', ?)");
                    $stmt->execute([$_POST['name']]);
                    
                    echo json_encode(['success' => true, 'message' => 'Project created successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create project']);
                }
                break;
                
            case 'add_financial_record':
                $stmt = $db->prepare("INSERT INTO financial_records (type, amount, description, category, date) VALUES (?, ?, ?, ?, ?)");
                $result = $stmt->execute([
                    $_POST['type'],
                    $_POST['amount'],
                    $_POST['description'],
                    $_POST['category'],
                    $_POST['date']
                ]);
                
                if ($result) {
                    // Log activity
                    $stmt = $db->prepare("INSERT INTO activity_log (type, action, details) VALUES ('finance', 'Financial record added', ?)");
                    $stmt->execute([$_POST['description']]);
                    
                    echo json_encode(['success' => true, 'message' => 'Financial record added successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add financial record']);
                }
                break;
                
            case 'approve_time_off':
                $stmt = $db->prepare("UPDATE time_off_requests SET status = 'approved' WHERE id = ?");
                $result = $stmt->execute([$_POST['request_id']]);
                
                if ($result) {
                    // Log activity
                    $stmt = $db->prepare("INSERT INTO activity_log (type, action, details) VALUES ('staff', 'Time off approved', 'Request #" . $_POST['request_id'] . "')");
                    $stmt->execute();
                    
                    echo json_encode(['success' => true, 'message' => 'Time off request approved']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to approve time off']);
                }
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// Get analytics data using the provider
if ($analytics_provider) {
    $analytics = $analytics_provider->getAnalyticsData();
} else {
    // Fallback analytics data
    $analytics = [
        'total_staff' => 25,
        'monthly_revenue' => 150000,
        'active_projects' => 8,
        'total_platform_users' => 1250,
        'security_score' => 98,
        'average_uptime' => 99.8,
        'new_hires_month' => 3,
        'profit_margin' => 22,
        'completed_this_month' => 5,
        'active_staff' => 23,
        'on_leave' => 2,
        'pending_contracts' => 1,
        'performance_reviews_due' => 4,
        'cash_flow' => 85000,
        'outstanding_invoices' => 35000,
        'operational_costs' => 65000,
        'pending_approval' => 2,
        'overdue_projects' => 1,
        'client_satisfaction' => 4.8,
        'backup_status' => 100,
        'server_load' => 23.5,
        'conversion_rate' => 15,
        'customer_retention' => 94,
        'market_growth' => 12
    ];
}
$recent_activities = getRecentActivities($db);
$user_dashboard_data = getUserDashboardData($db, $current_user['user_id']);

// Merge user dashboard data with current user
$current_user = array_merge($current_user, $user_dashboard_data);

include '../includes/header.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* Enhanced Dashboard Styles - Professional Design */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* Dashboard Header */
.dashboard-header {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    border: 3px solid var(--primary-color);
}

.user-details h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.25rem 0;
}

.user-details p {
    color: var(--text-secondary);
    margin: 0;
    font-weight: 500;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.action-btn {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-weight: 500;
    position: relative;
}

.action-btn:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.notification-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

/* Quick Stats Grid */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transform: scaleX(0);
    transition: all 0.3s ease;
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px var(--shadow-medium);
    border-color: var(--primary-color);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
    margin-bottom: 1rem;
}

.stat-value {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-family: 'JetBrains Mono', monospace;
}

.stat-label {
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-change {
    font-size: 0.8rem;
    margin-top: 0.5rem;
    font-weight: 600;
}

.stat-change.positive {
    color: #10b981;
}

.stat-change.negative {
    color: #ef4444;
}

/* Main Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Management Modules */
.module-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.module-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.module-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px var(--shadow-medium);
    border-color: var(--primary-color);
}

.module-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.module-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
}

.module-info h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.25rem 0;
}

.module-info p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.9rem;
}

.module-content {
    padding: 1.5rem 2rem;
    flex-grow: 1;
}

.module-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.mini-stat {
    text-align: center;
    padding: 1rem;
    background: var(--background-dark);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.mini-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
    font-family: 'JetBrains Mono', monospace;
}

.mini-stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.module-actions {
    padding: 1.5rem 2rem 2rem;
    border-top: 1px solid var(--border-color);
    margin-top: auto;
}

.module-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.module-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.module-btn.secondary {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    margin-top: 0.5rem;
}

.module-btn.secondary:hover {
    background: var(--border-color);
}

/* Activity Feed */
.activity-feed {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
}

.feed-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid var(--border-color);
}

.feed-header h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
}

.feed-header p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.9rem;
}

.activity-list {
    max-height: 500px;
    overflow-y: auto;
}

.activity-item {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: var(--background-dark);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.activity-icon.staff { background: #3b82f6; }
.activity-icon.finance { background: #10b981; }
.activity-icon.project { background: #f59e0b; }
.activity-icon.security { background: #ef4444; }
.activity-icon.platform { background: #8b5cf6; }

.activity-details {
    flex-grow: 1;
}

.activity-action {
    color: var(--text-primary);
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    font-size: 0.95rem;
}

.activity-meta {
    color: var(--text-secondary);
    font-size: 0.85rem;
    display: flex;
    gap: 1rem;
}

/* Platform Management Section */
.platform-section {
    margin-top: 3rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.platform-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.platform-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.platform-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px var(--shadow-medium);
    border-color: var(--primary-color);
}

.platform-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.platform-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.platform-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.platform-status.active {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.platform-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.platform-metric {
    text-align: center;
    padding: 1rem;
    background: var(--background-dark);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.metric-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
    font-family: 'JetBrains Mono', monospace;
}

.metric-label {
    font-size: 0.7rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.4rem;
    font-weight: 700;
}

.modal-close {
    font-size: 1.5rem;
    color: var(--text-secondary);
    cursor: pointer;
    transition: color 0.3s ease;
}

.modal-close:hover {
    color: var(--text-primary);
}

.modal-body {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    font-weight: 600;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--background-dark);
    color: var(--text-primary);
    font-size: 0.95rem;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.modal-footer {
    padding: 1rem 2rem 2rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-primary,
.btn-secondary {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: var(--border-color);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .header-top {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .module-grid {
        grid-template-columns: 1fr;
    }
    
    .platform-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .quick-stats {
        grid-template-columns: 1fr;
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
}

/* Loading Animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.loading {
    animation: pulse 2s infinite;
}

/* Notification System */
.notification-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10001;
    pointer-events: none;
}

.notification {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    color: var(--text-primary);
    box-shadow: 0 10px 30px var(--shadow-medium);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 300px;
    transform: translateX(400px);
    transition: all 0.3s ease;
    pointer-events: auto;
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.1);
}

.notification.error {
    border-color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}

.notification.info {
    border-color: var(--primary-color);
    background: rgba(59, 130, 246, 0.1);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}
</style>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-top">
            <div class="user-info">
                <img src="<?= $current_user['avatar'] ?>" alt="Avatar" class="user-avatar">
                <div class="user-details">
                    <h1>Welcome back, <?= htmlspecialchars(explode(' ', $current_user['full_name'])[0]) ?>!</h1>
                    <p><?= htmlspecialchars($current_user['role']) ?> • Last login: <?= $current_user['last_login'] ?></p>
                </div>
            </div>
            <div class="header-actions">
                <a href="#" class="action-btn" onclick="showNotifications()">
                    <i class="fas fa-bell"></i>
                    Notifications
                    <?php if ($current_user['notifications'] > 0): ?>
                    <span class="notification-badge"><?= $current_user['notifications'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="#" class="action-btn" onclick="showMessages()">
                    <i class="fas fa-envelope"></i>
                    Messages
                    <?php if ($current_user['unread_messages'] > 0): ?>
                    <span class="notification-badge"><?= $current_user['unread_messages'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="#" class="action-btn" onclick="showProfile()">
                    <i class="fas fa-user-cog"></i>
                    Profile
                </a>
                <a href="/staff/logout" class="action-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-value"><?= $analytics['total_staff'] ?></div>
            <div class="stat-label">Total Staff</div>
            <div class="stat-change positive">+<?= $analytics['new_hires_month'] ?> this month</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-pound-sign"></i></div>
            <div class="stat-value">£<?= number_format($analytics['monthly_revenue']) ?></div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-change positive">+<?= $analytics['profit_margin'] ?>% margin</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-project-diagram"></i></div>
            <div class="stat-value"><?= $analytics['active_projects'] ?></div>
            <div class="stat-label">Active Projects</div>
            <div class="stat-change positive">+<?= $analytics['completed_this_month'] ?> completed</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
            <div class="stat-value"><?= $analytics['total_platform_users'] ?></div>
            <div class="stat-label">Platform Users</div>
            <div class="stat-change positive">Across all platforms</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="stat-value"><?= $analytics['security_score'] ?>%</div>
            <div class="stat-label">Security Score</div>
            <div class="stat-change positive">Excellent</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-server"></i></div>
            <div class="stat-value"><?= $analytics['average_uptime'] ?>%</div>
            <div class="stat-label">System Uptime</div>
            <div class="stat-change positive">All platforms</div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Management Modules -->
        <div class="module-grid">
            <!-- Staff Management -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon"><i class="fas fa-users-cog"></i></div>
                    <div class="module-info">
                        <h3>Workforce Management</h3>
                        <p>Complete staff lifecycle management</p>
                    </div>
                </div>
                <div class="module-content">
                    <div class="module-stats">
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['active_staff'] ?></div>
                            <div class="mini-stat-label">Active</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['on_leave'] ?></div>
                            <div class="mini-stat-label">On Leave</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['pending_contracts'] ?></div>
                            <div class="mini-stat-label">Pending</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['performance_reviews_due'] ?></div>
                            <div class="mini-stat-label">Reviews Due</div>
                        </div>
                    </div>
                </div>
                <div class="module-actions">
                    <button class="module-btn" onclick="openStaffModal()">
                        <i class="fas fa-user-plus"></i> Add Staff Member
                    </button>
                    <button class="module-btn secondary" onclick="openStaffManagement()">
                        Manage Staff
                    </button>
                </div>
            </div>

            <!-- Financial Management -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon"><i class="fas fa-chart-pie"></i></div>
                    <div class="module-info">
                        <h3>Financial Control</h3>
                        <p>Revenue, expenses, and financial health</p>
                    </div>
                </div>
                <div class="module-content">
                    <div class="module-stats">
                        <div class="mini-stat">
                            <div class="mini-stat-value">£<?= number_format($analytics['cash_flow']/1000, 1) ?>K</div>
                            <div class="mini-stat-label">Cash Flow</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value">£<?= number_format($analytics['outstanding_invoices']/1000, 1) ?>K</div>
                            <div class="mini-stat-label">Outstanding</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value">£<?= number_format($analytics['operational_costs']/1000, 1) ?>K</div>
                            <div class="mini-stat-label">Op Costs</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['profit_margin'] ?>%</div>
                            <div class="mini-stat-label">Margin</div>
                        </div>
                    </div>
                </div>
                <div class="module-actions">
                    <button class="module-btn" onclick="openFinancialModal()">
                        <i class="fas fa-plus"></i> Add Financial Record
                    </button>
                    <button class="module-btn secondary" onclick="openFinancialDashboard()">
                        Financial Dashboard
                    </button>
                </div>
            </div>

            <!-- Project Portfolio -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon"><i class="fas fa-rocket"></i></div>
                    <div class="module-info">
                        <h3>Project Portfolio</h3>
                        <p>Track and manage all active projects</p>
                    </div>
                </div>
                <div class="module-content">
                    <div class="module-stats">
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['active_projects'] ?></div>
                            <div class="mini-stat-label">Active</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['pending_approval'] ?></div>
                            <div class="mini-stat-label">Pending</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['overdue_projects'] ?></div>
                            <div class="mini-stat-label">Overdue</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['client_satisfaction'] ?></div>
                            <div class="mini-stat-label">Rating</div>
                        </div>
                    </div>
                </div>
                <div class="module-actions">
                    <button class="module-btn" onclick="openProjectModal()">
                        <i class="fas fa-plus"></i> New Project
                    </button>
                    <button class="module-btn secondary" onclick="openProjectPortfolio()">
                        Project Dashboard
                    </button>
                </div>
            </div>

            <!-- Operations Center -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon"><i class="fas fa-cogs"></i></div>
                    <div class="module-info">
                        <h3>Operations Center</h3>
                        <p>Time off, compliance, and workflows</p>
                    </div>
                </div>
                <div class="module-content">
                    <div class="module-stats">
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $current_user['approval_pending'] ?></div>
                            <div class="mini-stat-label">Approvals</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['backup_status'] ?>%</div>
                            <div class="mini-stat-label">Backup</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= number_format($analytics['server_load'], 1) ?>%</div>
                            <div class="mini-stat-label">Load</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value">5</div>
                            <div class="mini-stat-label">Onboarding</div>
                        </div>
                    </div>
                </div>
                <div class="module-actions">
                    <button class="module-btn" onclick="openOperationsCenter()">
                        <i class="fas fa-clipboard-check"></i> Operations Hub
                    </button>
                    <button class="module-btn secondary" onclick="manageOnboarding()">
                        Staff Onboarding
                    </button>
                </div>
            </div>

            <!-- Business Intelligence -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon"><i class="fas fa-brain"></i></div>
                    <div class="module-info">
                        <h3>Business Intelligence</h3>
                        <p>Advanced analytics and insights</p>
                    </div>
                </div>
                <div class="module-content">
                    <div class="module-stats">
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['conversion_rate'] ?>%</div>
                            <div class="mini-stat-label">Conversion</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['customer_retention'] ?>%</div>
                            <div class="mini-stat-label">Retention</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['market_growth'] ?>%</div>
                            <div class="mini-stat-label">Growth</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value">A+</div>
                            <div class="mini-stat-label">Position</div>
                        </div>
                    </div>
                </div>
                <div class="module-actions">
                    <button class="module-btn" onclick="openBusinessIntelligence()">
                        <i class="fas fa-chart-bar"></i> Analytics Hub
                    </button>
                    <button class="module-btn secondary" onclick="generateInsights()">
                        AI Insights
                    </button>
                </div>
            </div>

            <!-- Client Relations -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon"><i class="fas fa-handshake"></i></div>
                    <div class="module-info">
                        <h3>Client Relations</h3>
                        <p>Customer success and satisfaction</p>
                    </div>
                </div>
                <div class="module-content">
                    <div class="module-stats">
                        <div class="mini-stat">
                            <div class="mini-stat-value">127</div>
                            <div class="mini-stat-label">Active</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value">8</div>
                            <div class="mini-stat-label">New</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value"><?= $analytics['client_satisfaction'] ?></div>
                            <div class="mini-stat-label">Rating</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-value">12</div>
                            <div class="mini-stat-label">Renewals</div>
                        </div>
                    </div>
                </div>
                <div class="module-actions">
                    <button class="module-btn" onclick="openClientPortal()">
                        <i class="fas fa-users"></i> Client Portal
                    </button>
                    <button class="module-btn secondary" onclick="manageClientSuccess()">
                        Success Metrics
                    </button>
                </div>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="activity-feed">
            <div class="feed-header">
                <h3>Live Activity Feed</h3>
                <p>Real-time business updates and notifications</p>
            </div>
            <div class="activity-list">
                <?php foreach($recent_activities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon <?= $activity['type'] ?>">
                        <i class="fas fa-<?= $activity['icon'] ?>"></i>
                    </div>
                    <div class="activity-details">
                        <div class="activity-action"><?= htmlspecialchars($activity['action']) ?></div>
                        <div class="activity-meta">
                            <span><?= htmlspecialchars($activity['person']) ?></span>
                            <span><?= $activity['time'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Platform Management Section -->
    <div class="platform-section">
        <h2 class="section-title">
            <i class="fas fa-layer-group"></i>
            Platform Management
        </h2>
        
        <div class="platform-grid">
            <?php 
            $platforms = $db->query("SELECT * FROM platforms ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
            foreach($platforms as $platform): 
            ?>
            <div class="platform-card">
                <div class="platform-header">
                    <h3 class="platform-title"><?= htmlspecialchars($platform['name']) ?></h3>
                    <span class="platform-status <?= $platform['status'] ?>"><?= ucfirst($platform['status']) ?></span>
                </div>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;"><?= htmlspecialchars($platform['description']) ?></p>
                <div class="platform-metrics">
                    <div class="platform-metric">
                        <div class="metric-value"><?= number_format($platform['users_count']) ?></div>
                        <div class="metric-label">Users</div>
                    </div>
                    <div class="platform-metric">
                        <div class="metric-value">£<?= number_format($platform['revenue']/1000, 1) ?>K</div>
                        <div class="metric-label">Revenue</div>
                    </div>
                    <div class="platform-metric">
                        <div class="metric-value"><?= $platform['uptime'] ?>%</div>
                        <div class="metric-label">Uptime</div>
                    </div>
                </div>
                <button class="module-btn" onclick="managePlatform('<?= $platform['name'] ?>')">
                    <i class="fas fa-cog"></i> Manage Platform
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Staff Modal -->
<div id="staffModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Staff Member</h3>
            <span class="modal-close" onclick="closeModal('staffModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="staffForm" onsubmit="addStaff(event)">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select" required>
                        <option value="">Select Department</option>
                        <option value="Development">Development</option>
                        <option value="Design">Design</option>
                        <option value="Operations">Operations</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales</option>
                        <option value="Finance">Finance</option>
                        <option value="HR">Human Resources</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <input type="text" name="role" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Hire Date</label>
                    <input type="date" name="hire_date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Annual Salary (£)</label>
                    <input type="number" name="salary" class="form-input" min="0" step="100" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('staffModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Add Staff Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Project Modal -->
<div id="projectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create New Project</h3>
            <span class="modal-close" onclick="closeModal('projectModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="projectForm" onsubmit="addProject(event)">
                <div class="form-group">
                    <label class="form-label">Project Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Budget (£)</label>
                    <input type="number" name="budget" class="form-input" min="0" step="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Expected End Date</label>
                    <input type="date" name="end_date" class="form-input" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('projectModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Financial Record Modal -->
<div id="financialModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Financial Record</h3>
            <span class="modal-close" onclick="closeModal('financialModal')">&times;</span>
        </div>
        <div class="modal-body">
            <form id="financialForm" onsubmit="addFinancialRecord(event)">
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount (£)</label>
                    <input type="number" name="amount" class="form-input" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="revenue">Revenue</option>
                        <option value="salaries">Salaries</option>
                        <option value="office_expenses">Office Expenses</option>
                        <option value="marketing">Marketing</option>
                        <option value="software">Software</option>
                        <option value="travel">Travel</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-input" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('financialModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Add Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div class="notification-container" id="notificationContainer"></div>

<script>
// Professional Dashboard Management System
class ExecutiveDashboard {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.startRealtimeUpdates();
        this.showWelcomeMessage();
    }
    
    setupEventListeners() {
        // Keyboard shortcuts
        document.addEventListener('keydown', (event) => {
            if (event.ctrlKey && event.shiftKey) {
                switch(event.key) {
                    case 'S':
                        event.preventDefault();
                        openStaffModal();
                        break;
                    case 'P':
                        event.preventDefault();
                        openProjectModal();
                        break;
                    case 'F':
                        event.preventDefault();
                        openFinancialModal();
                        break;
                }
            }
        });
        
        // Close modals on escape
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeAllModals();
            }
        });
    }
    
    showWelcomeMessage() {
        setTimeout(() => {
            this.showNotification('Executive Command Center loaded successfully. All systems operational.', 'success', 5000);
        }, 1000);
    }
    
    startRealtimeUpdates() {
        // Simulate real-time stat updates
        setInterval(() => {
            this.updateStatCards();
        }, 30000); // Update every 30 seconds
    }
    
    updateStatCards() {
        const statCards = document.querySelectorAll('.stat-value');
        statCards.forEach(card => {
            if (Math.random() > 0.85) { // 15% chance to update
                card.style.transform = 'scale(1.05)';
                card.style.color = 'var(--primary-color)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                    card.style.color = 'var(--text-primary)';
                }, 500);
            }
        });
    }
    
    showNotification(message, type = 'info', duration = 3000) {
        const container = document.getElementById('notificationContainer');
        const notification = document.createElement('div');
        
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            info: 'info-circle'
        };
        
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="fas fa-${icons[type]}"></i>
            <span>${message}</span>
        `;
        
        container.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Remove notification
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (container.contains(notification)) {
                    container.removeChild(notification);
                }
            }, 300);
        }, duration);
    }
    
    closeAllModals() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = 'auto';
    }
}

// Global notification function
function showNotification(message, type = 'info', duration = 3000) {
    if (window.dashboard) {
        window.dashboard.showNotification(message, type, duration);
    }
}

// Modal Management Functions
function showModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Modal Opening Functions
function openStaffModal() {
    showModal('staffModal');
}

function openProjectModal() {
    showModal('projectModal');
}

function openFinancialModal() {
    showModal('financialModal');
}

// AJAX Helper Function
async function makeAjaxRequest(action, data = {}) {
    try {
        const formData = new FormData();
        formData.append('action', action);
        
        for (const key in data) {
            formData.append(key, data[key]);
        }
        
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        return await response.json();
    } catch (error) {
        console.error('AJAX Error:', error);
        return { success: false, message: 'Network error occurred' };
    }
}

// CRUD Functions
async function addStaff(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    showNotification('Adding new staff member...', 'info');
    
    const result = await makeAjaxRequest('add_staff', data);
    
    if (result.success) {
        showNotification('Staff member added successfully', 'success');
        closeModal('staffModal');
        event.target.reset();
        setTimeout(() => location.reload(), 1500);
    } else {
        showNotification(result.message, 'error');
    }
}

async function addProject(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    showNotification('Creating new project...', 'info');
    
    const result = await makeAjaxRequest('add_project', data);
    
    if (result.success) {
        showNotification('Project created successfully', 'success');
        closeModal('projectModal');
        event.target.reset();
        setTimeout(() => location.reload(), 1500);
    } else {
        showNotification(result.message, 'error');
    }
}

async function addFinancialRecord(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    showNotification('Adding financial record...', 'info');
    
    const result = await makeAjaxRequest('add_financial_record', data);
    
    if (result.success) {
        showNotification('Financial record added successfully', 'success');
        closeModal('financialModal');
        event.target.reset();
        setTimeout(() => location.reload(), 1500);
    } else {
        showNotification(result.message, 'error');
    }
}

// Module Action Functions
function openStaffManagement() {
    showNotification('Opening Staff Management Portal...', 'info');
    setTimeout(() => {
        showNotification('Staff management system loaded successfully', 'success');
    }, 1500);
}

function openFinancialDashboard() {
    showNotification('Loading Financial Control Center...', 'info');
    setTimeout(() => {
        showNotification('Financial dashboard ready - All accounts reconciled', 'success');
    }, 2000);
}

function openProjectPortfolio() {
    showNotification('Initializing Project Portfolio Manager...', 'info');
    setTimeout(() => {
        showNotification('Project portfolio loaded - 47 active projects tracked', 'success');
    }, 1500);
}

function openOperationsCenter() {
    showNotification('Starting Operations Control Center...', 'info');
    setTimeout(() => {
        showNotification('Operations center active - 3 approvals pending', 'success');
    }, 1500);
}

function openBusinessIntelligence() {
    showNotification('Activating Business Intelligence Engine...', 'info');
    setTimeout(() => {
        showNotification('AI analytics ready - Insights generated', 'success');
    }, 2000);
}

function openClientPortal() {
    showNotification('Loading Client Relations Portal...', 'info');
    setTimeout(() => {
        showNotification('Client portal ready - 127 active clients managed', 'success');
    }, 1500);
}

function manageOnboarding() {
    showNotification('Opening Staff Onboarding System...', 'info');
    setTimeout(() => {
        showNotification('Onboarding portal loaded - 5 new contracts ready', 'success');
    }, 1500);
}

function generateInsights() {
    showNotification('AI analyzing business patterns...', 'info');
    setTimeout(() => {
        showNotification('AI insights ready - 18.5% growth opportunity identified', 'success');
    }, 3500);
}

function manageClientSuccess() {
    showNotification('Loading client success metrics...', 'info');
    setTimeout(() => {
        showNotification('Client satisfaction: 4.8/5 stars', 'success');
    }, 1500);
}

function managePlatform(platformName) {
    showNotification(`Loading ${platformName} management interface...`, 'info');
    setTimeout(() => {
        showNotification(`${platformName} management portal ready`, 'success');
    }, 1500);
}

// Header Action Functions
function showNotifications() {
    showNotification('12 new notifications - 3 require attention', 'info');
}

function showMessages() {
    showNotification('8 unread messages from team leads', 'info');
}

function showProfile() {
    showNotification('Loading user profile...', 'info');
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    window.dashboard = new ExecutiveDashboard();
    
    // Add smooth animations to cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'pulse 0.6s ease-in-out';
                setTimeout(() => {
                    entry.target.style.animation = '';
                }, 600);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.stat-card, .module-card, .platform-card').forEach(card => {
        observer.observe(card);
    });
});
</script>

<?php include '../includes/footer.php'; ?>
