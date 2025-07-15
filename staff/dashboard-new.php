<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "Staff Dashboard";
$page_description = "Comprehensive HR Management System";

// Check if user needs to complete onboarding
$currentUser = $_SESSION['user'] ?? null;
$needsPasswordReset = !($currentUser['password_reset'] ?? false);
$needs2FA = !($currentUser['two_fa_enabled'] ?? false);
$needsOnboarding = !($currentUser['onboarding_completed'] ?? false);

// Sample staff data with comprehensive HR information
$staffMembers = [
    [
        'id' => 1,
        'staff_id' => 'NEXI001',
        'manager_id' => null,
        'manager_name' => null,
        'full_name' => 'Oliver Reaney',
        'job_title' => 'Chief Executive Officer & Founder',
        'department' => 'Executive Leadership',
        'preferred_name' => 'Ollie',
        'nexi_email' => 'ollie.r@nexihub.uk',
        'private_email' => 'oliver.reaney@gmail.com',
        'phone_number' => '+44 7700 900123',
        'discord_username' => 'olliereaney',
        'discord_id' => '123456789012345678',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1995-03-15',
        'last_login' => '2024-01-15 16:30:00',
        'two_fa_enabled' => true,
        'date_joined' => '2020-01-01',
        'elearning_completed' => true,
        'onboarding_completed' => true,
        'upcoming_time_off' => [],
        'requested_time_off' => [],
        'parent_contact' => null,
        'payroll_info' => ['type' => 'Shareholder', 'rate' => 'Equity'],
        'password_reset' => true,
        'account_status' => 'active',
        'internal_notes' => 'Company founder and primary decision maker',
        'contract_signed' => true,
        'nda_signed' => true,
        'policies_signed' => true,
        'code_of_conduct_signed' => true,
        'legal_declaration_signed' => true
    ]
];

// Get current user data
$currentUserData = array_filter($staffMembers, fn($staff) => $staff['id'] == ($_SESSION['user_id'] ?? 1));
$currentUserData = reset($currentUserData);

// Analytics data
$totalStaff = count($staffMembers);
$activeStaff = count(array_filter($staffMembers, fn($s) => $s['account_status'] === 'active'));
$pendingOnboarding = count(array_filter($staffMembers, fn($s) => !$s['onboarding_completed']));
$twoFAEnabled = count(array_filter($staffMembers, fn($s) => $s['two_fa_enabled']));
$elearningCompleted = count(array_filter($staffMembers, fn($s) => $s['elearning_completed']));

include __DIR__ . '/../includes/header.php';
?>

<style>
:root {
    --sidebar-width: 280px;
    --header-height: 70px;
}

.dashboard-container {
    display: flex;
    min-height: 100vh;
    background: var(--background-dark);
}

/* Sidebar Styles */
.dashboard-sidebar {
    width: var(--sidebar-width);
    background: var(--background-light);
    border-right: 1px solid var(--border-color);
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 100;
}

.sidebar-header {
    padding: 2rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.sidebar-title {
    color: white;
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
}

.sidebar-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0.5rem 0 0 0;
}

.sidebar-nav {
    padding: 1rem 0;
}

.nav-section {
    margin-bottom: 2rem;
}

.nav-section-title {
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0 1.5rem 0.75rem;
    margin: 0;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.nav-item:hover {
    background: rgba(230, 79, 33, 0.1);
    color: var(--primary-color);
    border-left-color: var(--primary-color);
}

.nav-item.active {
    background: rgba(230, 79, 33, 0.15);
    color: var(--primary-color);
    border-left-color: var(--primary-color);
}

.nav-item svg {
    width: 20px;
    height: 20px;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.nav-badge {
    background: var(--primary-color);
    color: white;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    margin-left: auto;
    font-weight: 600;
}

/* Main Content Styles */
.dashboard-main {
    flex: 1;
    margin-left: var(--sidebar-width);
    background: var(--background-dark);
}

.dashboard-header {
    background: var(--background-light);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: var(--header-height);
}

.header-title {
    color: var(--text-primary);
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    background: var(--background-dark);
    border-radius: 10px;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.user-profile:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid var(--primary-color);
}

.user-info h4 {
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0;
}

.user-info p {
    color: var(--text-secondary);
    font-size: 0.8rem;
    margin: 0;
}

/* Content Area */
.dashboard-content {
    padding: 2rem;
}

/* Onboarding Banner */
.onboarding-banner {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    padding: 1.5rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.banner-content h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
    font-weight: 700;
}

.banner-content p {
    margin: 0;
    opacity: 0.9;
}

.banner-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.analytics-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.analytics-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.analytics-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--primary-color);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.card-title {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.card-value {
    color: var(--text-primary);
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0.5rem 0 1rem 0;
    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.card-subtitle {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin: 0;
}

.card-trend {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    margin-top: 0.5rem;
    display: inline-block;
}

.trend-up {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}

.trend-down {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* Quick Actions */
.quick-actions {
    margin-bottom: 2rem;
}

.section-title {
    color: var(--text-primary);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 1.5rem 0;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.action-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color);
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(230, 79, 33, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    flex-shrink: 0;
}

.action-content h4 {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.action-content p {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin: 0;
}

/* Recent Activity */
.recent-activity {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.activity-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--background-dark);
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: background 0.3s ease;
}

.activity-item:hover {
    background: rgba(230, 79, 33, 0.05);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid var(--border-color);
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-content h5 {
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.activity-content p {
    color: var(--text-secondary);
    font-size: 0.8rem;
    margin: 0;
}

.activity-time {
    color: var(--text-secondary);
    font-size: 0.75rem;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .dashboard-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .dashboard-sidebar.open {
        transform: translateX(0);
    }
    
    .dashboard-main {
        margin-left: 0;
    }
    
    .dashboard-content {
        padding: 1rem;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        padding: 1rem;
    }
    
    .header-title {
        font-size: 1.4rem;
    }
    
    .user-profile {
        padding: 0.5rem;
    }
    
    .user-info {
        display: none;
    }
    
    .onboarding-banner {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .banner-actions {
        justify-content: center;
    }
}
</style>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-title">HR Dashboard</h2>
            <p class="sidebar-subtitle">Staff Management System</p>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <h3 class="nav-section-title">Overview</h3>
                <a href="#" class="nav-item active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="/staff/team" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Staff Records
                </a>
            </div>
            
            <div class="nav-section">
                <h3 class="nav-section-title">HR Management</h3>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Add Employee
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Onboarding
                    <span class="nav-badge"><?php echo $pendingOnboarding; ?></span>
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Contract Validation
                </a>
            </div>
            
            <div class="nav-section">
                <h3 class="nav-section-title">Operations</h3>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    E-Learning
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    Support Tickets
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0v1a2 2 0 002 2h4a2 2 0 002-2V7m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                    </svg>
                    Time Off
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Task Management
                </a>
            </div>
            
            <div class="nav-section">
                <h3 class="nav-section-title">Administration</h3>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Audit Logs
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Billing & Payroll
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        <header class="dashboard-header">
            <h1 class="header-title">Dashboard Overview</h1>
            <div class="header-actions">
                <div class="user-profile">
                    <img src="/assets/images/Ollie.jpg" alt="User Avatar" class="user-avatar" onerror="this.src='https://i.pravatar.cc/150?img=1';">
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($currentUserData['preferred_name'] ?? 'User'); ?></h4>
                        <p><?php echo htmlspecialchars($currentUserData['job_title'] ?? 'Staff Member'); ?></p>
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-content">
            <?php if ($needsPasswordReset || $needs2FA || $needsOnboarding): ?>
            <!-- Onboarding Banner -->
            <div class="onboarding-banner">
                <div class="banner-content">
                    <h3>Welcome! Please Complete Your Setup</h3>
                    <p>
                        <?php if ($needsPasswordReset): ?>
                            First, reset your password for security.
                        <?php elseif ($needs2FA): ?>
                            Enable 2FA to secure your account.
                        <?php elseif ($needsOnboarding): ?>
                            Complete your onboarding process.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="banner-actions">
                    <?php if ($needsPasswordReset): ?>
                        <a href="#" class="btn btn-secondary">Reset Password</a>
                    <?php elseif ($needs2FA): ?>
                        <a href="#" class="btn btn-secondary">Setup 2FA</a>
                    <?php elseif ($needsOnboarding): ?>
                        <a href="#" class="btn btn-secondary">Complete Onboarding</a>
                    <?php endif; ?>
                    <a href="#" class="btn btn-primary">Get Started</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Analytics Overview -->
            <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Total Staff</h3>
                            <div class="card-value"><?php echo $totalStaff; ?></div>
                            <p class="card-subtitle">Active employees</p>
                        </div>
                        <div class="card-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="card-trend trend-up">↗ 12% this month</div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">2FA Enabled</h3>
                            <div class="card-value"><?php echo $twoFAEnabled; ?></div>
                            <p class="card-subtitle">Security compliance</p>
                        </div>
                        <div class="card-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-6H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V7a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="card-trend trend-up">↗ 95% completion rate</div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Onboarding</h3>
                            <div class="card-value"><?php echo $pendingOnboarding; ?></div>
                            <p class="card-subtitle">Pending completion</p>
                        </div>
                        <div class="card-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="card-trend trend-down">↓ 3 remaining</div>
                </div>

                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">E-Learning</h3>
                            <div class="card-value"><?php echo $elearningCompleted; ?></div>
                            <p class="card-subtitle">Courses completed</p>
                        </div>
                        <div class="card-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="card-trend trend-up">↗ 87% completion rate</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2 class="section-title">Quick Actions</h2>
                <div class="actions-grid">
                    <a href="#" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div class="action-content">
                            <h4>Add New Employee</h4>
                            <p>Create a new staff record and initiate onboarding</p>
                        </div>
                    </a>

                    <a href="#" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="action-content">
                            <h4>Review Contracts</h4>
                            <p>Check pending contract signatures and compliance</p>
                        </div>
                    </a>

                    <a href="#" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <div class="action-content">
                            <h4>Support Tickets</h4>
                            <p>Manage and respond to staff support requests</p>
                        </div>
                    </a>

                    <a href="#" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0v1a2 2 0 002 2h4a2 2 0 002-2V7m-6 0V5a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </div>
                        <div class="action-content">
                            <h4>Time Off Requests</h4>
                            <p>Approve or manage staff time off requests</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <div class="activity-header">
                    <h2 class="section-title">Recent Activity</h2>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <img src="/assets/images/maisie.jpg" alt="Maisie" class="activity-avatar" onerror="this.src='https://i.pravatar.cc/150?img=1';">
                        <div class="activity-content">
                            <h5>Maisie Johnson completed onboarding</h5>
                            <p>All required documents signed and 2FA enabled</p>
                        </div>
                        <span class="activity-time">2 hours ago</span>
                    </div>
                    
                    <div class="activity-item">
                        <img src="/assets/images/Benjamin.jpg" alt="Benjamin" class="activity-avatar" onerror="this.src='https://i.pravatar.cc/150?img=2';">
                        <div class="activity-content">
                            <h5>Benjamin Clarke approved time off request</h5>
                            <p>Logan's vacation request for next week approved</p>
                        </div>
                        <span class="activity-time">4 hours ago</span>
                    </div>
                    
                    <div class="activity-item">
                        <img src="/assets/images/Christopher.jpg" alt="Christopher" class="activity-avatar" onerror="this.src='https://i.pravatar.cc/150?img=3';">
                        <div class="activity-content">
                            <h5>Christopher Davis updated payroll information</h5>
                            <p>Q4 shareholder distributions processed</p>
                        </div>
                        <span class="activity-time">6 hours ago</span>
                    </div>
                    
                    <div class="activity-item">
                        <img src="/assets/images/Paige.jpg" alt="Paige" class="activity-avatar" onerror="this.src='https://i.pravatar.cc/150?img=4';">
                        <div class="activity-content">
                            <h5>Paige Williams completed Security Training</h5>
                            <p>E-learning module on data protection completed</p>
                        </div>
                        <span class="activity-time">8 hours ago</span>
                    </div>
                    
                    <div class="activity-item">
                        <img src="/assets/images/Sam.gif" alt="Sam" class="activity-avatar" onerror="this.src='https://i.pravatar.cc/150?img=5';">
                        <div class="activity-content">
                            <h5>Sam Thompson submitted support ticket</h5>
                            <p>Request for new software license approved</p>
                        </div>
                        <span class="activity-time">1 day ago</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
