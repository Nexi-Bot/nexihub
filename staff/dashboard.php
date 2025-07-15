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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Nexi Hub</title>
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1a202c;
            line-height: 1.6;
        }

        /* HR Dashboard Specific Styles */
        .hr-dashboard {
            display: flex;
            min-height: 100vh;
            background: #f8fafc;
        }

        .hr-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .hr-sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .hr-sidebar-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .hr-nav {
            padding: 20px 0;
        }

        .hr-nav-item {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .hr-nav-item:hover, .hr-nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: #fff;
            color: white;
        }

        .hr-nav-item i {
            width: 20px;
            margin-right: 12px;
        }

        .hr-main {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }

        .hr-header {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hr-header h1 {
            margin: 0;
            color: #2d3748;
            font-size: 28px;
            font-weight: 600;
        }

        .hr-user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .analytics-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid;
            transition: transform 0.2s ease;
        }

        .analytics-card:hover {
            transform: translateY(-2px);
        }

        .analytics-card.primary { border-left-color: #667eea; }
        .analytics-card.success { border-left-color: #48bb78; }
        .analytics-card.warning { border-left-color: #ed8936; }
        .analytics-card.danger { border-left-color: #f56565; }
        .analytics-card.info { border-left-color: #4299e1; }

        .analytics-card h3 {
            margin: 0 0 10px 0;
            color: #2d3748;
            font-size: 32px;
            font-weight: 700;
        }

        .analytics-card p {
            margin: 0;
            color: #718096;
            font-weight: 500;
        }

        .analytics-card i {
            float: right;
            font-size: 24px;
            opacity: 0.3;
            margin-top: -5px;
        }

        .content-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h2 {
            margin: 0;
            color: #2d3748;
            font-size: 20px;
            font-weight: 600;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table th {
            background: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }

        .data-table tr:hover {
            background: #f7fafc;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-active { background: #c6f6d5; color: #22543d; }
        .status-pending { background: #fed7d7; color: #742a2a; }
        .status-completed { background: #bee3f8; color: #2a4365; }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px 30px;
        }

        .action-card {
            padding: 20px;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-card:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .action-card i {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .action-card h4 {
            margin: 0 0 5px 0;
            color: #2d3748;
        }

        .action-card p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #2d3748;
        }

        .close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #718096;
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2d3748;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .hr-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .hr-main {
                margin-left: 0;
            }
            
            .analytics-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="hr-dashboard">
    <div class="hr-sidebar">
        <div class="hr-sidebar-header">
            <h2><i class="fas fa-users"></i> HR Portal</h2>
        </div>
        <nav class="hr-nav">
            <a href="#dashboard" class="hr-nav-item active" onclick="showSection('dashboard')">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="#staff-records" class="hr-nav-item" onclick="showSection('staff-records')">
                <i class="fas fa-users"></i> Staff Records
            </a>
            <a href="#onboarding" class="hr-nav-item" onclick="showSection('onboarding')">
                <i class="fas fa-user-plus"></i> Onboarding
            </a>
            <a href="#contracts" class="hr-nav-item" onclick="showSection('contracts')">
                <i class="fas fa-file-contract"></i> Contracts
            </a>
            <a href="#elearning" class="hr-nav-item" onclick="showSection('elearning')">
                <i class="fas fa-graduation-cap"></i> E-Learning
            </a>
            <a href="#tickets" class="hr-nav-item" onclick="showSection('tickets')">
                <i class="fas fa-ticket-alt"></i> Tickets
            </a>
            <a href="#time-off" class="hr-nav-item" onclick="showSection('time-off')">
                <i class="fas fa-calendar-alt"></i> Time Off
            </a>
            <a href="#tasks" class="hr-nav-item" onclick="showSection('tasks')">
                <i class="fas fa-tasks"></i> Task Management
            </a>
            <a href="#audit-logs" class="hr-nav-item" onclick="showSection('audit-logs')">
                <i class="fas fa-history"></i> Audit Logs
            </a>
            <a href="#billing" class="hr-nav-item" onclick="showSection('billing')">
                <i class="fas fa-money-bill-wave"></i> Billing
            </a>
        </nav>
    </div>

    <div class="hr-main">
        <div class="hr-header">
            <h1 id="page-title">HR Dashboard</h1>
            <div class="hr-user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr($current_user['full_name'] ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <div style="font-weight: 600;"><?= htmlspecialchars($current_user['full_name'] ?? 'User') ?></div>
                    <div style="font-size: 14px; color: #718096;"><?= htmlspecialchars($user_role) ?></div>
                </div>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div id="dashboard-section" class="content-section">
            <div class="analytics-grid">
                <div class="analytics-card primary">
                    <i class="fas fa-users"></i>
                    <h3><?= $analytics['total_staff'] ?></h3>
                    <p>Total Staff Members</p>
                </div>
                <div class="analytics-card success">
                    <i class="fas fa-user-check"></i>
                    <h3><?= $analytics['active_staff'] ?></h3>
                    <p>Active Staff</p>
                </div>
                <div class="analytics-card warning">
                    <i class="fas fa-user-clock"></i>
                    <h3><?= $analytics['pending_onboarding'] ?></h3>
                    <p>Pending Onboarding</p>
                </div>
                <div class="analytics-card danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3><?= $analytics['compliance_issues'] ?></h3>
                    <p>Compliance Issues</p>
                </div>
                <div class="analytics-card info">
                    <i class="fas fa-calendar"></i>
                    <h3><?= $analytics['upcoming_time_off'] ?></h3>
                    <p>Upcoming Time Off</p>
                </div>
                <div class="analytics-card primary">
                    <i class="fas fa-ticket-alt"></i>
                    <h3><?= $analytics['open_tickets'] ?></h3>
                    <p>Open Tickets</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Quick Actions</h2>
                </div>
                <div class="quick-actions">
                    <div class="action-card" onclick="showAddStaffModal()">
                        <i class="fas fa-user-plus"></i>
                        <h4>Add New Staff</h4>
                        <p>Register a new employee</p>
                    </div>
                    <div class="action-card" onclick="showSection('onboarding')">
                        <i class="fas fa-clipboard-check"></i>
                        <h4>Manage Onboarding</h4>
                        <p>Track onboarding progress</p>
                    </div>
                    <div class="action-card" onclick="showSection('time-off')">
                        <i class="fas fa-calendar-plus"></i>
                        <h4>Time Off Requests</h4>
                        <p>Review pending requests</p>
                    </div>
                    <div class="action-card" onclick="showSection('tickets')">
                        <i class="fas fa-headset"></i>
                        <h4>Support Tickets</h4>
                        <p>Manage support requests</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Recent Activity</h2>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
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
                                <td>Ollie Roberts</td>
                                <td>Updated staff record</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Staff Records Section -->
        <div id="staff-records-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Staff Records</h2>
                <button class="btn btn-primary" onclick="showAddStaffModal()">
                    <i class="fas fa-plus"></i> Add New Staff
                </button>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Onboarding</th>
                            <th>2FA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>NEXI001</td>
                            <td>Oliver Reaney</td>
                            <td>Executive</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td><span class="status-badge status-completed">Complete</span></td>
                            <td><span class="status-badge status-completed">Enabled</span></td>
                            <td>
                                <button class="btn btn-primary" onclick="viewStaffDetails(1)">View</button>
                            </td>
                        </tr>
                        <!-- More staff records will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Other Sections -->
        <div id="onboarding-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Employee Onboarding</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                This section will contain detailed onboarding management features.
            </p>
        </div>

        <div id="contracts-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Contract & Consent Validation</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Contract validation and compliance tracking will be implemented here.
            </p>
        </div>

        <div id="elearning-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>E-Learning Management</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                E-learning module will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <div id="tickets-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Support Tickets</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Ticket management system will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <div id="time-off-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Time Off Management</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Time off request and approval system will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <div id="tasks-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Task Management</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Task management system will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <div id="audit-logs-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Audit Logs</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Comprehensive audit logging will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <div id="billing-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Billing & Payroll</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Billing and payroll management will be implemented here. Awaiting your specifications.
            </p>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div id="addStaffModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Staff Member</h3>
                <button class="close" onclick="closeModal('addStaffModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addStaffForm" onsubmit="addNewStaff(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullName">Full Name *</label>
                            <input type="text" id="fullName" name="fullName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="preferredName">Preferred Name</label>
                            <input type="text" id="preferredName" name="preferredName" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="jobTitle">Job Title *</label>
                            <input type="text" id="jobTitle" name="jobTitle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="department">Department *</label>
                            <select id="department" name="department" class="form-control" required>
                                <option value="">Select Department</option>
                                <option value="Executive">Executive</option>
                                <option value="Technology">Technology</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Finance">Finance</option>
                                <option value="Legal">Legal</option>
                                <option value="Regional">Regional</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nexiEmail">Nexi Email *</label>
                            <input type="email" id="nexiEmail" name="nexiEmail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="privateEmail">Private Email *</label>
                            <input type="email" id="privateEmail" name="privateEmail" class="form-control" required>
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn" onclick="closeModal('addStaffModal')" style="background: #e2e8f0; color: #2d3748; margin-right: 10px;">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Staff Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
// Navigation and UI functionality
function showSection(sectionName) {
    // Hide all sections
    const sections = ['dashboard', 'staff-records', 'onboarding', 'contracts', 'elearning', 'tickets', 'time-off', 'tasks', 'audit-logs', 'billing'];
    sections.forEach(section => {
        const element = document.getElementById(section + '-section');
        if (element) element.style.display = 'none';
    });

    // Show selected section
    const selectedSection = document.getElementById(sectionName + '-section');
    if (selectedSection) selectedSection.style.display = 'block';

    // Update navigation
    document.querySelectorAll('.hr-nav-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.classList.add('active');

    // Update page title
    const titles = {
        'dashboard': 'HR Dashboard',
        'staff-records': 'Staff Records',
        'onboarding': 'Employee Onboarding',
        'contracts': 'Contract & Consent Validation',
        'elearning': 'E-Learning Management',
        'tickets': 'Support Tickets',
        'time-off': 'Time Off Management',
        'tasks': 'Task Management',
        'audit-logs': 'Audit Logs',
        'billing': 'Billing & Payroll'
    };
    document.getElementById('page-title').textContent = titles[sectionName] || 'HR Dashboard';
}

// Modal functionality
function showAddStaffModal() {
    document.getElementById('addStaffModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Form submission
function addNewStaff(event) {
    event.preventDefault();
    
    // Generate staff ID
    const staffId = 'NEXI' + String(Math.floor(Math.random() * 9000) + 1000);
    
    alert(`Staff member will be created with ID: ${staffId}\n\nThe new employee will receive an email with login instructions and will be required to:\n1. Reset their password\n2. Set up 2FA\n3. Complete document signing process`);
    
    closeModal('addStaffModal');
    document.getElementById('addStaffForm').reset();
}

function viewStaffDetails(staffId) {
    alert('Staff details view will be implemented with full profile management.');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
