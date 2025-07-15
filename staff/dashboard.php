<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "HR Dashboard";
$page_description = "Nexi Hub HR Management System - Complete Staff Portal";

// Get current user info from session
$current_user = [
    'full_name' => $_SESSION['staff_name'] ?? 'HR Administrator',
    'user_id' => $_SESSION['staff_id'] ?? 1,
    'email' => $_SESSION['staff_email'] ?? 'hr@nexihub.com',
    'department' => $_SESSION['staff_department'] ?? 'Human Resources',
    'last_login' => date('M j, Y \a\t g:i A')
];
$user_role = $_SESSION['staff_role'] ?? 'HR Administrator';

// Analytics data for dashboard
$analytics = [
    'total_staff' => 15,
    'active_staff' => 14,
    'pending_onboarding' => 2,
    'compliance_issues' => 1,
    'recent_hires' => 3,
    'upcoming_time_off' => 5,
    'open_tickets' => 8,
    'overdue_tasks' => 2,
    'total_departments' => 6,
    'avg_satisfaction' => 4.7,
    'retention_rate' => 94.2
];

// Sample data for functionality
$staff_members = [
    ['id' => 'NEXI001', 'name' => 'Oliver Reaney', 'department' => 'Executive', 'role' => 'CEO & Founder', 'status' => 'Active', 'email' => 'oliver@nexihub.com', 'phone' => '+44 7123 456789', 'hire_date' => '2023-01-15'],
    ['id' => 'NEXI002', 'name' => 'Benjamin Clarke', 'department' => 'Executive', 'role' => 'Managing Director', 'status' => 'Active', 'email' => 'benjamin@nexihub.com', 'phone' => '+44 7234 567890', 'hire_date' => '2023-02-01'],
    ['id' => 'NEXI003', 'name' => 'Logan Mitchell', 'department' => 'Technology', 'role' => 'Lead Developer', 'status' => 'Onboarding', 'email' => 'logan@nexihub.com', 'phone' => '+44 7345 678901', 'hire_date' => '2025-07-01'],
    ['id' => 'NEXI004', 'name' => 'Mykyta Petrenko', 'department' => 'Technology', 'role' => 'Senior Developer', 'status' => 'Active', 'email' => 'mykyta@nexihub.com', 'phone' => '+44 7456 789012', 'hire_date' => '2024-03-15'],
    ['id' => 'NEXI005', 'name' => 'Sarah Johnson', 'department' => 'Design', 'role' => 'UI/UX Designer', 'status' => 'Active', 'email' => 'sarah@nexihub.com', 'phone' => '+44 7567 890123', 'hire_date' => '2024-05-20']
];

$time_off_requests = [
    ['employee' => 'Mykyta Petrenko', 'request_date' => '2025-07-10', 'start_date' => '2025-07-20', 'end_date' => '2025-07-24', 'type' => 'Vacation', 'status' => 'Pending', 'days' => 5],
    ['employee' => 'Logan Mitchell', 'request_date' => '2025-07-08', 'start_date' => '2025-07-15', 'end_date' => '2025-07-15', 'type' => 'Personal', 'status' => 'Approved', 'days' => 1],
    ['employee' => 'Sarah Johnson', 'request_date' => '2025-07-12', 'start_date' => '2025-08-01', 'end_date' => '2025-08-07', 'type' => 'Vacation', 'status' => 'Approved', 'days' => 7],
    ['employee' => 'Benjamin Clarke', 'request_date' => '2025-07-05', 'start_date' => '2025-07-25', 'end_date' => '2025-07-26', 'type' => 'Conference', 'status' => 'Approved', 'days' => 2]
];

$recent_activities = [
    ['time' => '2 hours ago', 'employee' => 'Logan Mitchell', 'action' => 'Completed onboarding documents', 'status' => 'Completed'],
    ['time' => '4 hours ago', 'employee' => 'Mykyta Petrenko', 'action' => 'Submitted time off request', 'status' => 'Pending'],
    ['time' => '6 hours ago', 'employee' => 'Oliver Reaney', 'action' => 'Updated staff record', 'status' => 'Completed'],
    ['time' => '1 day ago', 'employee' => 'Benjamin Clarke', 'action' => 'Approved time off request', 'status' => 'Approved'],
    ['time' => '2 days ago', 'employee' => 'Sarah Johnson', 'action' => 'Completed security training', 'status' => 'Completed']
];

include '../includes/header.php';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
/* HR Dashboard Specific Styles - Built on Nexi Hub Design System */
.hr-dashboard-container {
    background: var(--background-dark);
    min-height: 100vh;
    padding-top: 2rem;
}

.welcome-banner {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.welcome-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.welcome-banner::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 150px;
    height: 150px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.welcome-content {
    position: relative;
    z-index: 2;
}

.welcome-title {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    opacity: 0;
    animation: slideInUp 0.8s ease forwards;
}

.welcome-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
    opacity: 0;
    animation: slideInUp 0.8s ease 0.2s forwards;
}

.welcome-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    opacity: 0;
    animation: slideInUp 0.8s ease 0.4s forwards;
}

.welcome-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.welcome-stat i {
    font-size: 1.2rem;
    opacity: 0.8;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hr-dashboard-header {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: var(--background-light);
    margin: 5% auto;
    padding: 2rem;
    border-radius: 16px;
    width: 90%;
    max-width: 600px;
    border: 1px solid var(--border-color);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.close {
    color: var(--text-secondary);
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: var(--primary-color);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--background-dark);
    color: var(--text-primary);
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(230, 79, 33, 0.1);
}

.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--background-dark);
    color: var(--text-primary);
    font-size: 1rem;
}

.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--background-dark);
    color: var(--text-primary);
    font-size: 1rem;
    min-height: 100px;
    resize: vertical;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.notification {
    position: fixed;
    top: 2rem;
    right: 2rem;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    z-index: 1001;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s ease;
}

.notification.show {
    opacity: 1;
    transform: translateX(0);
}

.notification.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.notification.info {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.hr-dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.hr-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.hr-header-left h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hr-header-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.hr-user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--background-dark);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
}

.user-details h3 {
    margin: 0;
    color: var(--text-primary);
    font-weight: 600;
}

.user-details p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.hr-nav-tabs {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.hr-nav-tab {
    padding: 0.75rem 1.5rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    user-select: none;
}

.hr-nav-tab:hover,
.hr-nav-tab.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(230, 79, 33, 0.3);
}

.hr-nav-tab i {
    margin-right: 0.5rem;
}

.hr-analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.hr-analytics-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.hr-analytics-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.hr-analytics-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border-color: var(--primary-color);
}

.analytics-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.analytics-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.analytics-value {
    text-align: right;
}

.analytics-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0;
    line-height: 1;
}

.analytics-label {
    color: var(--text-secondary);
    font-weight: 500;
    margin-top: 0.5rem;
}

.analytics-change {
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.analytics-change.positive {
    color: #10b981;
}

.analytics-change.negative {
    color: #ef4444;
}

.hr-section {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 2rem;
    overflow: hidden;
}

.hr-section-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.hr-section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.hr-section-content {
    padding: 2rem;
}

.hr-section-content.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.hr-action-card {
    background: var(--background-dark);
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.hr-action-card:hover {
    border-color: var(--primary-color);
    background: rgba(230, 79, 33, 0.05);
    transform: translateY(-2px);
}

.hr-action-card i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.hr-action-card h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.hr-action-card p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 0;
}

.hr-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--background-dark);
    border-radius: 12px;
    overflow: hidden;
}

.hr-table th,
.hr-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.hr-table th {
    background: var(--background-light);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hr-table td {
    color: var(--text-secondary);
}

.hr-table tr:hover td {
    background: rgba(230, 79, 33, 0.05);
    color: var(--text-primary);
}

.status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.status-completed {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.status-overdue {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.status-approved {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-denied {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.status-onboarding {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.hr-content-section {
    display: none;
}

.hr-content-section.active {
    display: block;
}

.hr-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

.hr-btn-primary {
    background: var(--primary-color);
    color: white;
    box-shadow: 0 4px 12px rgba(230, 79, 33, 0.3);
}

.hr-btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(230, 79, 33, 0.4);
}

.hr-btn-secondary {
    background: transparent;
    color: var(--text-primary);
    border: 2px solid var(--border-color);
}

.hr-btn-secondary:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.logout-btn {
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: rgba(239, 68, 68, 0.9);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    z-index: 1000;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 1);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
}

@media (max-width: 768px) {
    .hr-header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .hr-nav-tabs {
        justify-content: center;
    }
    
    .hr-analytics-grid {
        grid-template-columns: 1fr;
    }
    
    .hr-section-content.grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="hr-dashboard-container">
    <div class="container">
        <a href="/staff/logout" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        
        <!-- Professional Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-content">
                <h1 class="welcome-title">Welcome back, <?= htmlspecialchars(explode(' ', $current_user['full_name'])[0]) ?>!</h1>
                <p class="welcome-subtitle">Ready to manage your team and drive organizational excellence. Here's what's happening today.</p>
                <div class="welcome-stats">
                    <div class="welcome-stat">
                        <i class="fas fa-clock"></i>
                        <span>Last login: <?= $current_user['last_login'] ?></span>
                    </div>
                    <div class="welcome-stat">
                        <i class="fas fa-users"></i>
                        <span><?= $analytics['total_staff'] ?> team members</span>
                    </div>
                    <div class="welcome-stat">
                        <i class="fas fa-chart-line"></i>
                        <span><?= $analytics['retention_rate'] ?>% retention rate</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hr-dashboard-header">
            <div class="hr-header-content">
                <div class="hr-header-left">
                    <h1>HR Management System</h1>
                    <p class="hr-header-subtitle">Complete staff management and organizational tools</p>
                    
                    <div class="hr-nav-tabs">
                        <div class="hr-nav-tab active" onclick="showSection('dashboard')">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </div>
                        <div class="hr-nav-tab" onclick="showSection('staff-records')">
                            <i class="fas fa-users"></i> Staff Records
                        </div>
                        <div class="hr-nav-tab" onclick="showSection('onboarding')">
                            <i class="fas fa-user-plus"></i> Onboarding
                        </div>
                        <div class="hr-nav-tab" onclick="showSection('time-off')">
                            <i class="fas fa-calendar-alt"></i> Time Off
                        </div>
                        <div class="hr-nav-tab" onclick="showSection('reports')">
                            <i class="fas fa-chart-bar"></i> Reports
                        </div>
                    </div>
                </div>
                
                <div class="hr-user-info">
                    <div class="user-avatar">
                        <?= strtoupper(substr($current_user['full_name'], 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <h3><?= htmlspecialchars($current_user['full_name']) ?></h3>
                        <p><?= htmlspecialchars($user_role) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div id="dashboard-section" class="hr-content-section active">
            <div class="hr-analytics-grid">
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['total_staff'] ?></h3>
                            <div class="analytics-change positive">
                                <i class="fas fa-arrow-up"></i> +2 this month
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Total Staff Members</p>
                </div>
                
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['active_staff'] ?></h3>
                            <div class="analytics-change positive">
                                <i class="fas fa-arrow-up"></i> 93% active
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Active Staff</p>
                </div>
                
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['pending_onboarding'] ?></h3>
                            <div class="analytics-change negative">
                                <i class="fas fa-arrow-down"></i> -1 this week
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Pending Onboarding</p>
                </div>
                
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['upcoming_time_off'] ?></h3>
                            <div class="analytics-change positive">
                                <i class="fas fa-arrow-up"></i> Next 30 days
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Upcoming Time Off</p>
                </div>
                
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['total_departments'] ?></h3>
                            <div class="analytics-change positive">
                                <i class="fas fa-arrow-up"></i> Active departments
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Departments</p>
                </div>
                
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['avg_satisfaction'] ?></h3>
                            <div class="analytics-change positive">
                                <i class="fas fa-arrow-up"></i> /5.0 rating
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Satisfaction Score</p>
                </div>
                
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['retention_rate'] ?>%</h3>
                            <div class="analytics-change positive">
                                <i class="fas fa-arrow-up"></i> +2.1% this year
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Retention Rate</p>
                </div>
                
                <div class="hr-analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="analytics-value">
                            <h3 class="analytics-number"><?= $analytics['compliance_issues'] ?></h3>
                            <div class="analytics-change negative">
                                <i class="fas fa-arrow-down"></i> Requires attention
                            </div>
                        </div>
                    </div>
                    <p class="analytics-label">Compliance Issues</p>
                </div>
            </div>

            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Quick Actions</h2>
                </div>
                <div class="hr-section-content grid">
                    <div class="hr-action-card" onclick="openAddStaffModal()">
                        <i class="fas fa-user-plus"></i>
                        <h4>Add New Staff</h4>
                        <p>Register a new employee and start onboarding</p>
                    </div>
                    <div class="hr-action-card" onclick="showSection('time-off')">
                        <i class="fas fa-calendar-check"></i>
                        <h4>Review Time Off</h4>
                        <p>Approve or deny pending time off requests</p>
                    </div>
                    <div class="hr-action-card" onclick="generateReport()">
                        <i class="fas fa-file-alt"></i>
                        <h4>Generate Reports</h4>
                        <p>Create detailed staff and performance reports</p>
                    </div>
                    <div class="hr-action-card" onclick="showSection('onboarding')">
                        <i class="fas fa-clipboard-check"></i>
                        <h4>Track Onboarding</h4>
                        <p>Monitor new employee progress</p>
                    </div>
                </div>
            </div>

            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Recent Activity</h2>
                    <a href="#" class="hr-btn hr-btn-secondary">View All</a>
                </div>
                <div class="hr-section-content">
                    <table class="hr-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Staff Member</th>
                                <th>Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_activities as $activity): ?>
                            <tr>
                                <td><?= htmlspecialchars($activity['time']) ?></td>
                                <td><?= htmlspecialchars($activity['employee']) ?></td>
                                <td><?= htmlspecialchars($activity['action']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($activity['status']) ?>">
                                        <?= htmlspecialchars($activity['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="staff-records-section" class="hr-content-section">
            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Staff Records</h2>
                    <a href="#" class="hr-btn hr-btn-primary" onclick="openAddStaffModal()">
                        <i class="fas fa-plus"></i> Add New Staff
                    </a>
                </div>
                <div class="hr-section-content">
                    <table class="hr-table">
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($staff_members as $staff): ?>
                            <tr>
                                <td><?= htmlspecialchars($staff['id']) ?></td>
                                <td><?= htmlspecialchars($staff['name']) ?></td>
                                <td><?= htmlspecialchars($staff['department']) ?></td>
                                <td><?= htmlspecialchars($staff['role']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($staff['status']) ?>">
                                        <?= htmlspecialchars($staff['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="hr-btn hr-btn-secondary" onclick="viewStaffDetails('<?= $staff['id'] ?>')">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Onboarding Section -->
        <div id="onboarding-section" class="hr-content-section">
            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Employee Onboarding</h2>
                </div>
                <div class="hr-section-content">
                    <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                        Track and manage the onboarding process for new employees. Ensure all required documents and training are completed.
                    </p>
                    
                    <div class="hr-section-content grid">
                        <div class="hr-action-card">
                            <i class="fas fa-file-contract"></i>
                            <h4>Contract Management</h4>
                            <p>Upload and track employment contracts and NDAs</p>
                        </div>
                        <div class="hr-action-card">
                            <i class="fas fa-graduation-cap"></i>
                            <h4>Training Modules</h4>
                            <p>Assign and monitor completion of required training</p>
                        </div>
                        <div class="hr-action-card">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Security Setup</h4>
                            <p>Configure 2FA and security protocols</p>
                        </div>
                        <div class="hr-action-card">
                            <i class="fas fa-check-circle"></i>
                            <h4>Completion Tracking</h4>
                            <p>Monitor onboarding progress and completion</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="time-off-section" class="hr-content-section">
            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Time Off Management</h2>
                    <a href="#" class="hr-btn hr-btn-primary" onclick="openTimeOffModal()">
                        <i class="fas fa-plus"></i> New Request
                    </a>
                </div>
                <div class="hr-section-content">
                    <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                        Manage staff time off requests, approvals, and tracking. Monitor leave balances and upcoming absences.
                    </p>
                    
                    <table class="hr-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Request Date</th>
                                <th>Leave Dates</th>
                                <th>Type</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($time_off_requests as $index => $request): ?>
                            <tr>
                                <td><?= htmlspecialchars($request['employee']) ?></td>
                                <td><?= date('M j, Y', strtotime($request['request_date'])) ?></td>
                                <td>
                                    <?= date('M j', strtotime($request['start_date'])) ?>
                                    <?= $request['start_date'] !== $request['end_date'] ? ' - ' . date('M j, Y', strtotime($request['end_date'])) : ', ' . date('Y', strtotime($request['start_date'])) ?>
                                </td>
                                <td><?= htmlspecialchars($request['type']) ?></td>
                                <td><?= $request['days'] ?> day<?= $request['days'] > 1 ? 's' : '' ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($request['status']) ?>">
                                        <?= htmlspecialchars($request['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($request['status'] === 'Pending'): ?>
                                        <a href="#" class="hr-btn hr-btn-primary" onclick="approveTimeOff(<?= $index ?>)" style="margin-right: 0.5rem;">Approve</a>
                                        <a href="#" class="hr-btn hr-btn-secondary" onclick="denyTimeOff(<?= $index ?>)">Deny</a>
                                    <?php else: ?>
                                        <a href="#" class="hr-btn hr-btn-secondary" onclick="viewTimeOffDetails(<?= $index ?>)">View</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div id="reports-section" class="hr-content-section">
            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Reports & Analytics</h2>
                </div>
                <div class="hr-section-content">
                    <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                        Generate comprehensive reports on staff performance, attendance, and organizational metrics.
                    </p>
                    
                    <div class="hr-section-content grid">
                        <div class="hr-action-card">
                            <i class="fas fa-users"></i>
                            <h4>Staff Overview</h4>
                            <p>Complete staff directory and status report</p>
                        </div>
                        <div class="hr-action-card">
                            <i class="fas fa-calendar-check"></i>
                            <h4>Attendance Report</h4>
                            <p>Track attendance and time off patterns</p>
                        </div>
                        <div class="hr-action-card">
                            <i class="fas fa-chart-line"></i>
                            <h4>Performance Metrics</h4>
                            <p>Analyze staff performance and productivity</p>
                        </div>
                        <div class="hr-action-card">
                            <i class="fas fa-money-bill-wave"></i>
                            <h4>Payroll Summary</h4>
                            <p>Generate payroll and compensation reports</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Staff Modal -->
<div id="addStaffModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Staff Member</h2>
            <span class="close" onclick="closeModal('addStaffModal')">&times;</span>
        </div>
        <form id="addStaffForm" onsubmit="addStaff(event)">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-input" name="name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" name="email" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-input" name="phone" required>
            </div>
            <div class="form-group">
                <label class="form-label">Department</label>
                <select class="form-select" name="department" required>
                    <option value="">Select Department</option>
                    <option value="Executive">Executive</option>
                    <option value="Technology">Technology</option>
                    <option value="Design">Design</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Sales">Sales</option>
                    <option value="Human Resources">Human Resources</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Job Title</label>
                <input type="text" class="form-input" name="role" required>
            </div>
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-input" name="hire_date" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="hr-btn hr-btn-secondary" onclick="closeModal('addStaffModal')">Cancel</button>
                <button type="submit" class="hr-btn hr-btn-primary">Add Staff Member</button>
            </div>
        </form>
    </div>
</div>

<!-- Time Off Request Modal -->
<div id="timeOffModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">New Time Off Request</h2>
            <span class="close" onclick="closeModal('timeOffModal')">&times;</span>
        </div>
        <form id="timeOffForm" onsubmit="submitTimeOff(event)">
            <div class="form-group">
                <label class="form-label">Employee</label>
                <select class="form-select" name="employee" required>
                    <option value="">Select Employee</option>
                    <?php foreach($staff_members as $staff): ?>
                        <option value="<?= htmlspecialchars($staff['name']) ?>"><?= htmlspecialchars($staff['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-input" name="start_date" required>
            </div>
            <div class="form-group">
                <label class="form-label">End Date</label>
                <input type="date" class="form-input" name="end_date" required>
            </div>
            <div class="form-group">
                <label class="form-label">Type</label>
                <select class="form-select" name="type" required>
                    <option value="">Select Type</option>
                    <option value="Vacation">Vacation</option>
                    <option value="Personal">Personal</option>
                    <option value="Sick">Sick Leave</option>
                    <option value="Conference">Conference</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Reason (Optional)</label>
                <textarea class="form-textarea" name="reason" placeholder="Brief description of the time off request..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="hr-btn hr-btn-secondary" onclick="closeModal('timeOffModal')">Cancel</button>
                <button type="submit" class="hr-btn hr-btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<!-- Staff Details Modal -->
<div id="staffDetailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Staff Details</h2>
            <span class="close" onclick="closeModal('staffDetailsModal')">&times;</span>
        </div>
        <div id="staffDetailsContent">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notification" class="notification"></div>

<script>
// Staff data for JavaScript functionality
const staffData = <?= json_encode($staff_members) ?>;
const timeOffData = <?= json_encode($time_off_requests) ?>;

function showSection(sectionName) {
    // Hide all sections
    const sections = document.querySelectorAll('.hr-content-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.hr-nav-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected section
    const selectedSection = document.getElementById(sectionName + '-section');
    if (selectedSection) {
        selectedSection.classList.add('active');
    }
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}

function openAddStaffModal() {
    document.getElementById('addStaffModal').style.display = 'block';
}

function openTimeOffModal() {
    document.getElementById('timeOffModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 4000);
}

function addStaff(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    // Generate staff ID
    const staffId = 'NEXI' + String(Math.floor(Math.random() * 900) + 100).padStart(3, '0');
    
    // Simulate adding staff
    showNotification(`Staff member ${formData.get('name')} has been successfully added with ID ${staffId}!`);
    closeModal('addStaffModal');
    event.target.reset();
    
    // In a real application, this would send data to the server
    setTimeout(() => {
        location.reload(); // Refresh to show updated data
    }, 2000);
}

function submitTimeOff(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    showNotification(`Time off request for ${formData.get('employee')} has been submitted successfully!`);
    closeModal('timeOffModal');
    event.target.reset();
    
    // In a real application, this would send data to the server
    setTimeout(() => {
        location.reload(); // Refresh to show updated data
    }, 2000);
}

function viewStaffDetails(staffId) {
    const staff = staffData.find(s => s.id === staffId);
    if (!staff) return;
    
    const content = `
        <div style="display: grid; gap: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <strong style="color: var(--text-primary);">Staff ID:</strong><br>
                    <span style="color: var(--text-secondary);">${staff.id}</span>
                </div>
                <div>
                    <strong style="color: var(--text-primary);">Full Name:</strong><br>
                    <span style="color: var(--text-secondary);">${staff.name}</span>
                </div>
                <div>
                    <strong style="color: var(--text-primary);">Email:</strong><br>
                    <span style="color: var(--text-secondary);">${staff.email}</span>
                </div>
                <div>
                    <strong style="color: var(--text-primary);">Phone:</strong><br>
                    <span style="color: var(--text-secondary);">${staff.phone}</span>
                </div>
                <div>
                    <strong style="color: var(--text-primary);">Department:</strong><br>
                    <span style="color: var(--text-secondary);">${staff.department}</span>
                </div>
                <div>
                    <strong style="color: var(--text-primary);">Role:</strong><br>
                    <span style="color: var(--text-secondary);">${staff.role}</span>
                </div>
                <div>
                    <strong style="color: var(--text-primary);">Status:</strong><br>
                    <span class="status-badge status-${staff.status.toLowerCase()}">${staff.status}</span>
                </div>
                <div>
                    <strong style="color: var(--text-primary);">Hire Date:</strong><br>
                    <span style="color: var(--text-secondary);">${new Date(staff.hire_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                </div>
            </div>
            <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                <button class="hr-btn hr-btn-primary" onclick="editStaff('${staff.id}')">Edit Details</button>
                <button class="hr-btn hr-btn-secondary" onclick="closeModal('staffDetailsModal')">Close</button>
            </div>
        </div>
    `;
    
    document.getElementById('staffDetailsContent').innerHTML = content;
    document.getElementById('staffDetailsModal').style.display = 'block';
}

function editStaff(staffId) {
    showNotification(`Edit functionality for staff ${staffId} will be implemented soon!`, 'info');
    closeModal('staffDetailsModal');
}

function approveTimeOff(index) {
    const request = timeOffData[index];
    showNotification(`Time off request for ${request.employee} has been approved!`, 'success');
    
    // In a real application, this would update the database
    setTimeout(() => {
        location.reload();
    }, 2000);
}

function denyTimeOff(index) {
    const request = timeOffData[index];
    const reason = prompt('Please provide a reason for denial (optional):');
    showNotification(`Time off request for ${request.employee} has been denied.`, 'error');
    
    // In a real application, this would update the database
    setTimeout(() => {
        location.reload();
    }, 2000);
}

function viewTimeOffDetails(index) {
    const request = timeOffData[index];
    showNotification(`Viewing details for ${request.employee}'s time off request`, 'info');
}

function generateReport() {
    showNotification('Generating comprehensive HR report...', 'info');
    
    setTimeout(() => {
        showNotification('HR Report generated successfully! Check your downloads folder.', 'success');
    }, 3000);
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Show welcome animation
    setTimeout(() => {
        showNotification('Welcome to the Nexi Hub HR Dashboard! All systems are operational.', 'success');
    }, 1000);
    
    // Auto-refresh analytics every 5 minutes (in a real application)
    setInterval(() => {
        console.log('Auto-refreshing analytics data...');
    }, 300000);
});
</script>

<?php include '../includes/footer.php'; ?>
