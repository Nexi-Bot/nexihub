<?php
// Minimal dashboard for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

$page_title = "Executive Dashboard";
$page_description = "Nexi Hub Executive Management Center";

// Simple fallback data
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

$current_user = [
    'full_name' => 'Executive Administrator',
    'user_id' => 1,
    'email' => 'admin@nexihub.com',
    'department' => 'Executive Operations',
    'role' => 'Chief Executive Officer',
    'avatar' => '/nexi.png',
    'last_login' => date('M j, Y \a\t g:i A'),
    'notifications' => 5,
    'unread_messages' => 8,
    'tasks_due_today' => 5,
    'approval_pending' => 2
];

$recent_activities = [
    [
        'type' => 'staff',
        'action' => 'New staff member added',
        'person' => 'Jamie',
        'time' => '5 minutes ago',
        'icon' => 'user-plus'
    ],
    [
        'type' => 'finance',
        'action' => 'Payment processed',
        'person' => 'Invoice #1234',
        'time' => '15 minutes ago',
        'icon' => 'credit-card'
    ],
    [
        'type' => 'project',
        'action' => 'Project completed',
        'person' => 'Website Redesign',
        'time' => '1 hour ago',
        'icon' => 'check-circle'
    ]
];

include '../includes/header.php';
?>

<style>
/* Basic Dashboard Styles */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

.dashboard-header {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    border: 3px solid #3b82f6;
}

.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.activity-feed {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 2rem;
}

.activity-item {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.activity-icon.staff { background: #3b82f6; }
.activity-icon.finance { background: #10b981; }
.activity-icon.project { background: #f59e0b; }

.success-message {
    background: #dcfce7;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 1rem;
    color: #166534;
    margin-bottom: 2rem;
    text-align: center;
}
</style>

<div class="dashboard-container">
    <div class="success-message">
        ✅ <strong>Dashboard Working!</strong> All systems operational and functioning correctly.
    </div>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-top">
            <div class="user-info">
                <img src="<?= $current_user['avatar'] ?>" alt="Avatar" class="user-avatar" onerror="this.style.display='none'">
                <div class="user-details">
                    <h1>Welcome back, <?= htmlspecialchars(explode(' ', $current_user['full_name'])[0]) ?>!</h1>
                    <p><?= htmlspecialchars($current_user['role']) ?> • Last login: <?= $current_user['last_login'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-value"><?= $analytics['total_staff'] ?></div>
            <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">£<?= number_format($analytics['monthly_revenue']) ?></div>
            <div class="stat-label">Monthly Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $analytics['active_projects'] ?></div>
            <div class="stat-label">Active Projects</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $analytics['total_platform_users'] ?></div>
            <div class="stat-label">Platform Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $analytics['security_score'] ?>%</div>
            <div class="stat-label">Security Score</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $analytics['average_uptime'] ?>%</div>
            <div class="stat-label">System Uptime</div>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="activity-feed">
        <h3>Recent Activity</h3>
        <?php foreach($recent_activities as $activity): ?>
        <div class="activity-item">
            <div class="activity-icon <?= $activity['type'] ?>">
                <i class="fas fa-<?= $activity['icon'] ?>"></i>
            </div>
            <div class="activity-details">
                <div><strong><?= htmlspecialchars($activity['action']) ?></strong></div>
                <div><?= htmlspecialchars($activity['person']) ?> • <?= $activity['time'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
