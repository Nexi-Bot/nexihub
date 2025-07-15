<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "HR Dashboard";
$page_description = "Nexi Hub HR Management System - Complete Staff Portal";

// Get current user info from session
$current_user = [
    'full_name' => 'HR Administrator',
    'user_id' => $_SESSION['staff_id'] ?? 1
];
$user_role = 'HR Administrator';

// Analytics data for dashboard
$analytics = [
    'total_staff' => 15,
    'active_staff' => 14,
    'pending_onboarding' => 2,
    'compliance_issues' => 1,
    'recent_hires' => 3,
    'upcoming_time_off' => 5,
    'open_tickets' => 8,
    'overdue_tasks' => 2
];

include '../includes/header.php';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
<style>
/* HR Dashboard Specific Styles - Built on Nexi Hub Design System */
.hr-dashboard-container {
    background: var(--background-dark);
    min-height: 100vh;
    padding-top: 2rem;
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
            </div>

            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Quick Actions</h2>
                </div>
                <div class="hr-section-content grid">
                    <div class="hr-action-card" onclick="showSection('staff-records')">
                        <i class="fas fa-user-plus"></i>
                        <h4>Add New Staff</h4>
                        <p>Register a new employee and start onboarding</p>
                    </div>
                    <div class="hr-action-card" onclick="showSection('time-off')">
                        <i class="fas fa-calendar-check"></i>
                        <h4>Review Time Off</h4>
                        <p>Approve or deny pending time off requests</p>
                    </div>
                    <div class="hr-action-card" onclick="showSection('reports')">
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
                            <tr>
                                <td>2 hours ago</td>
                                <td>Logan Mitchell</td>
                                <td>Completed onboarding documents</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                            </tr>
                            <tr>
                                <td>4 hours ago</td>
                                <td>Mykyta Petrenko</td>
                                <td>Submitted time off request</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>6 hours ago</td>
                                <td>Oliver Reaney</td>
                                <td>Updated staff record</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                            </tr>
                            <tr>
                                <td>1 day ago</td>
                                <td>Benjamin Clarke</td>
                                <td>Approved time off request</td>
                                <td><span class="status-badge status-active">Approved</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Staff Records Section -->
        <div id="staff-records-section" class="hr-content-section">
            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Staff Records</h2>
                    <a href="#" class="hr-btn hr-btn-primary" onclick="alert('Add Staff functionality will be implemented')">
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
                            <tr>
                                <td>NEXI001</td>
                                <td>Oliver Reaney</td>
                                <td>Executive</td>
                                <td>CEO & Founder</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>
                                    <a href="#" class="hr-btn hr-btn-secondary" onclick="alert('View details functionality will be implemented')">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>NEXI002</td>
                                <td>Benjamin Clarke</td>
                                <td>Executive</td>
                                <td>Managing Director</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>
                                    <a href="#" class="hr-btn hr-btn-secondary" onclick="alert('View details functionality will be implemented')">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>NEXI003</td>
                                <td>Logan Mitchell</td>
                                <td>Technology</td>
                                <td>Lead Developer</td>
                                <td><span class="status-badge status-pending">Onboarding</span></td>
                                <td>
                                    <a href="#" class="hr-btn hr-btn-secondary" onclick="alert('View details functionality will be implemented')">View</a>
                                </td>
                            </tr>
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

        <!-- Time Off Section -->
        <div id="time-off-section" class="hr-content-section">
            <div class="hr-section">
                <div class="hr-section-header">
                    <h2 class="hr-section-title">Time Off Management</h2>
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
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mykyta Petrenko</td>
                                <td>July 10, 2025</td>
                                <td>July 20-24, 2025</td>
                                <td>Vacation</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>
                                    <a href="#" class="hr-btn hr-btn-primary" onclick="alert('Approve functionality will be implemented')">Approve</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Logan Mitchell</td>
                                <td>July 8, 2025</td>
                                <td>July 15, 2025</td>
                                <td>Personal</td>
                                <td><span class="status-badge status-active">Approved</span></td>
                                <td>
                                    <a href="#" class="hr-btn hr-btn-secondary" onclick="alert('View details functionality will be implemented')">View</a>
                                </td>
                            </tr>
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

<script>
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
</script>

<?php include '../includes/footer.php'; ?>
