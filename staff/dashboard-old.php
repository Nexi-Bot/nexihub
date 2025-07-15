<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "HR Dashboard";
$page_description = "Nexi Hub HR Management System - Complete Staff Portal";

// Get current user info
$current_user = getCurrentUser();
$user_role = getUserRole($current_user['user_id']);

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
    justify-content: between;
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

.btn-success {
    background: #48bb78;
    color: white;
}

.btn-warning {
    background: #ed8936;
    color: white;
}

.btn-danger {
    background: #f56565;
    color: white;
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
.status-incomplete { background: #fbb6ce; color: #702459; }

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

        <!-- Onboarding Section -->
        <div id="onboarding-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Employee Onboarding</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                This section will contain detailed onboarding management features.
                You mentioned you'll provide more details about this module.
            </p>
        </div>

        <!-- Contracts Section -->
        <div id="contracts-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Contract & Consent Validation</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Contract validation and compliance tracking will be implemented here.
            </p>
        </div>

        <!-- E-Learning Section -->
        <div id="elearning-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>E-Learning Management</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                E-learning module will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <!-- Tickets Section -->
        <div id="tickets-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Support Tickets</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Ticket management system will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <!-- Time Off Section -->
        <div id="time-off-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Time Off Management</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Time off request and approval system will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <!-- Tasks Section -->
        <div id="tasks-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Task Management</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Task management system will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <!-- Audit Logs Section -->
        <div id="audit-logs-section" class="content-section" style="display: none;">
            <div class="section-header">
                <h2>Audit Logs</h2>
            </div>
            <p style="padding: 20px 30px; margin: 0; color: #718096;">
                Comprehensive audit logging will be implemented here. Awaiting your specifications.
            </p>
        </div>

        <!-- Billing Section -->
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

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="dateOfBirth">Date of Birth *</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nationality">Nationality *</label>
                            <input type="text" id="nationality" name="nationality" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="countryOfResidence">Country of Residence *</label>
                            <input type="text" id="countryOfResidence" name="countryOfResidence" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="discordUsername">Discord Username</label>
                            <input type="text" id="discordUsername" name="discordUsername" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="manager">Manager</label>
                            <select id="manager" name="manager" class="form-control">
                                <option value="">Select Manager</option>
                                <option value="1">Oliver Reaney</option>
                                <option value="2">Benjamin Clark</option>
                            </select>
                        </div>
                    </div>

                    <h4 style="margin: 30px 0 15px 0; color: #2d3748;">Required Documents Upload</h4>
                    <p style="color: #718096; margin-bottom: 20px;">All documents must be uploaded before the employee can be created.</p>

                    <div class="form-group">
                        <label>Age Category *</label>
                        <div style="margin-top: 10px;">
                            <input type="radio" id="over16" name="ageCategory" value="over16" required onchange="toggleContractType()">
                            <label for="over16" style="margin-left: 8px; margin-right: 20px;">Over 16</label>
                            <input type="radio" id="under16" name="ageCategory" value="under16" required onchange="toggleContractType()">
                            <label for="under16" style="margin-left: 8px;">Under 16</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contractUpload">Contract Upload *</label>
                        <input type="file" id="contractUpload" name="contractUpload" class="form-control" accept=".pdf,.doc,.docx" required>
                        <small style="color: #718096;">Upload the appropriate contract based on age category</small>
                    </div>

                    <div class="form-group">
                        <label for="ndaUpload">NDA Upload *</label>
                        <input type="file" id="ndaUpload" name="ndaUpload" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>

                    <div class="form-group">
                        <label for="policiesUpload">Company Policies Upload *</label>
                        <input type="file" id="policiesUpload" name="policiesUpload" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>

                    <div class="form-group">
                        <label for="codeOfConductUpload">Code of Conduct Upload *</label>
                        <input type="file" id="codeOfConductUpload" name="codeOfConductUpload" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>

                    <div class="form-group">
                        <label for="legalDeclarationUpload">Legal Declaration Upload *</label>
                        <input type="file" id="legalDeclarationUpload" name="legalDeclarationUpload" class="form-control" accept=".pdf,.doc,.docx" required>
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

function toggleContractType() {
    const ageCategory = document.querySelector('input[name="ageCategory"]:checked').value;
    const contractLabel = document.querySelector('label[for="contractUpload"]');
    
    if (ageCategory === 'under16') {
        contractLabel.textContent = 'Under 16 Contract Upload *';
    } else {
        contractLabel.textContent = 'Over 16 Contract Upload *';
    }
}

// Form submission
function addNewStaff(event) {
    event.preventDefault();
    
    // Validate all required files are uploaded
    const requiredFiles = ['contractUpload', 'ndaUpload', 'policiesUpload', 'codeOfConductUpload', 'legalDeclarationUpload'];
    let allFilesUploaded = true;
    
    requiredFiles.forEach(fileInputId => {
        const fileInput = document.getElementById(fileInputId);
        if (!fileInput.files || fileInput.files.length === 0) {
            allFilesUploaded = false;
        }
    });
    
    if (!allFilesUploaded) {
        alert('Please upload all required documents before creating the staff member.');
        return;
    }
    
    // Generate staff ID
    const staffId = 'NEXI' + String(Math.floor(Math.random() * 9000) + 1000);
    
    // Here you would normally submit the form data to your backend
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
        'role' => 'Chief Executive Officer & Founder',
        'department' => 'Executive Leadership',
        'manager' => null,
        'nexi_email' => 'ollie.r@nexihub.uk',
        'private_email' => 'oliver.reaney@gmail.com',
        'phone_number' => '+44 7700 900123',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1995-03-15',
        'status' => 'active',
        'last_login' => '2024-01-15 16:30:00',
        'created' => '2020-01-01 10:00:00',
        'two_fa_enabled' => true,
        'employment_type' => 'Full-time'
    ],
    2 => [
        'staff_id' => 'NEXI002',
        'full_name' => 'Benjamin Clarke',
        'preferred_name' => 'Benjamin',
        'discord_username' => 'benjaminclarke',
        'discord_id' => '234567890123456789',
        'discord_avatar' => '/assets/images/Benjamin.jpg',
        'role' => 'Managing Director',
        'department' => 'Executive Leadership',
        'manager' => 'Oliver Reaney',
        'nexi_email' => 'benjamin@nexihub.uk',
        'private_email' => 'benjamin.clarke@outlook.com',
        'phone_number' => '+44 7700 900124',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1992-08-22',
        'status' => 'active',
        'last_login' => '2024-01-15 14:20:00',
        'created' => '2020-02-01 09:30:00',
        'two_fa_enabled' => true,
        'employment_type' => 'Full-time'
    ],
    3 => [
        'staff_id' => 'NEXI003',
        'full_name' => 'Maisie Reaney',
        'preferred_name' => 'Maisie',
        'discord_username' => 'maisiereaney',
        'discord_id' => '345678901234567890',
        'discord_avatar' => '/assets/images/maisie.jpg',
        'role' => 'Head of Corporate Functions',
        'department' => 'Corporate Functions',
        'manager' => 'Oliver Reaney',
        'nexi_email' => 'maisie@nexihub.uk',
        'private_email' => 'maisie.reaney@gmail.com',
        'phone_number' => '+44 7700 900125',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1998-11-08',
        'status' => 'active',
        'last_login' => '2024-01-15 12:45:00',
        'created' => '2021-03-01 11:00:00',
        'two_fa_enabled' => true,
        'employment_type' => 'Full-time'
    ]
];

// Get current staff information from session
$currentStaffId = $_SESSION['staff_id'] ?? 1;
$staff = $staffMembers[$currentStaffId] ?? $staffMembers[1];

// Add basic session information
$staff['current_ip'] = getUserIP();
$staff['session_start'] = date('g:i A');
$staff['session_expires'] = date('g:i A', strtotime('+5 minutes'));

// Try to get actual session information from database
try {
    // Get last login from staff_sessions table
    $stmt = $pdo->prepare("
        SELECT created_at, ip_address, two_fa_verified 
        FROM staff_sessions 
        WHERE staff_id = ? 
        ORDER BY created_at DESC 
        LIMIT 2
    ");
    $stmt->execute([$currentStaffId]);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($sessions) {
        // Current session info
        $currentSession = $sessions[0] ?? null;
        $lastSession = $sessions[1] ?? null;
        
        // Update staff data with real session info
        if ($currentSession) {
            $staff['session_start'] = date('g:i A', strtotime($currentSession['created_at']));
            $staff['current_2fa_status'] = $currentSession['two_fa_verified'];
        }
        
        if ($lastSession) {
            $staff['actual_last_login'] = $lastSession['created_at'];
        }
    }
    
} catch (Exception $e) {
    // Continue with basic info if database query fails
    error_log("Dashboard database error: " . $e->getMessage());
}

// Set defaults if not set from database
$staff['actual_last_login'] = $staff['actual_last_login'] ?? $staff['last_login'];
$staff['current_2fa_status'] = $staff['current_2fa_status'] ?? $staff['two_fa_enabled'];

// Get dashboard analytics safely
$totalUsers = 5; // Default fallback
$premiumUsers = 3; // Default fallback
$openTickets = 4; // Default fallback  
$urgentTickets = 1; // Default fallback
$systemIssues = 0; // Default fallback

try {
    // User stats
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 5;
    $premiumUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE premium = 1")->fetchColumn() ?: 3;
    
    // Support stats
    $openTickets = $pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status IN ('open', 'in-progress')")->fetchColumn() ?: 4;
    $urgentTickets = $pdo->query("SELECT COUNT(*) FROM support_tickets WHERE priority = 'urgent' AND status != 'closed'")->fetchColumn() ?: 1;
} catch (Exception $e) {
    // Use defaults if database queries fail
    error_log("Dashboard analytics error: " . $e->getMessage());
}

include __DIR__ . '/../includes/header.php';
?>

<style>
:root {
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --error-color: #ef4444;
}

.dashboard-container {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--background-dark) 0%, var(--background-light) 100%);
    padding: 2rem 0;
}

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

.staff-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.staff-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid var(--primary-color);
}

.staff-details h1 {
    color: var(--text-primary);
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.staff-details p {
    color: var(--text-secondary);
    margin: 0.25rem 0;
    font-size: 0.9rem;
}

.session-info {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1.5rem;
}

.session-info h3 {
    color: var(--text-primary);
    font-size: 1rem;
    margin: 0 0 0.75rem 0;
}

.session-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.session-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.session-detail svg {
    width: 16px;
    height: 16px;
    color: var(--primary-color);
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.dashboard-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border-color: var(--primary-color);
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.card-icon svg {
    width: 24px;
    height: 24px;
    color: white;
}

.card-title {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
}

.card-description {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.card-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.card-btn {
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.card-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(230, 79, 33, 0.3);
}

.card-btn.secondary {
    background: var(--background-dark);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.card-btn.secondary:hover {
    color: var(--text-primary);
    border-color: var(--primary-color);
    background: var(--background-dark);
    box-shadow: none;
    transform: translateY(-2px);
}

.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.quick-stat {
    text-align: center;
    padding: 1rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 500;
}

.logout-btn {
    position: fixed;
    top: 2rem;
    right: 2rem;
    padding: 0.75rem 1.5rem;
    background: rgba(239, 68, 68, 0.9);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 1);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
}
</style>

<div class="dashboard-container">
    <div class="container">
        <a href="/staff/logout" class="logout-btn">Logout</a>
        
        <div class="dashboard-header">
            <div class="staff-info">
                <img src="<?php echo htmlspecialchars($staff['discord_avatar']); ?>" 
                     alt="<?php echo htmlspecialchars($staff['preferred_name']); ?>'s Avatar" 
                     class="staff-avatar"
                     onerror="this.src='https://cdn.discordapp.com/embed/avatars/0.png';">
                <div class="staff-details">
                    <h1>Welcome, <?php echo htmlspecialchars($staff['preferred_name']); ?></h1>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($staff['department']); ?></p>
                    <p><strong>Job Title:</strong> <?php echo htmlspecialchars($staff['role']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($staff['nexi_email']); ?></p>
                    <p><strong>Employment Type:</strong> <?php echo htmlspecialchars($staff['employment_type']); ?></p>
                    <p><strong>Last Login:</strong> <?php 
                        if (isset($staff['actual_last_login']) && $staff['actual_last_login']) {
                            echo date('F j, Y \a\t g:i A', strtotime($staff['actual_last_login']));
                        } else {
                            echo 'First login';
                        }
                    ?></p>
                    <p><strong>2FA Status:</strong> 
                        <?php if ($staff['current_2fa_status']): ?>
                            <span style="color: var(--success-color);">✅ Enabled</span>
                        <?php else: ?>
                            <span style="color: var(--warning-color);">⚠️ Not Enabled</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <div class="session-info">
                <h3>Current Session</h3>
                <div class="session-details">
                    <div class="session-detail">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.9L16.2,16.2Z"/>
                        </svg>
                        Session started: <?php echo $staff['session_start']; ?>
                    </div>
                    <div class="session-detail">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.9L16.2,16.2Z"/>
                        </svg>
                        IP Address: <?php echo htmlspecialchars($staff['current_ip']); ?>
                    </div>
                    <div class="session-detail">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9,10H7V12H9V10M13,10H11V12H13V10M17,10H15V12H17V10M19,3H18V1H16V3H8V1H6V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3M19,19H5V8H19V19Z"/>
                        </svg>
                        Expires: <?php echo $staff['session_expires']; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- User Management -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16,4C16.88,4 17.67,4.16 18.37,4.43C17.5,5.13 17,6.21 17,7.5C17,8.79 17.5,9.87 18.37,10.57C17.67,10.84 16.88,11 16,11C13.24,11 11,8.76 11,6S13.24,1 16,1 21,3.24 21,6 18.76,11 16,11M16,13C18.67,13 24,14.33 24,17V20H8V17C8,14.33 13.33,13 16,13M7.5,12A2.5,2.5 0 0,0 10,9.5A2.5,2.5 0 0,0 7.5,7A2.5,2.5 0 0,0 5,9.5A2.5,2.5 0 0,0 7.5,12M1,15.5C1,13.65 4.27,12.5 7.5,12.5C8.2,12.5 8.9,12.56 9.54,12.68C9.18,13.17 9,13.82 9,14.5V17.5H1V15.5Z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">User Management</h2>
                </div>
                <p class="card-description">
                    Manage customer accounts, subscriptions, and user data across all Nexi Hub platforms.
                </p>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo number_format($totalUsers); ?></span>
                        <span class="stat-label">Total Users</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo number_format($premiumUsers); ?></span>
                        <span class="stat-label">Premium Users</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="/staff/users" class="card-btn">Manage Users</a>
                    <a href="/staff/users/export" class="card-btn secondary">Export Data</a>
                </div>
            </div>

            <!-- Billing & Payments -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4M20,18H4V8H20V18M20,6V8H4V6H20M7,10V12H17V10H7Z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Billing & Payments</h2>
                </div>
                <p class="card-description">
                    Monitor Stripe transactions, manage subscriptions, and handle billing disputes.
                </p>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <span class="stat-number">£2,400</span>
                        <span class="stat-label">This Month</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number">£890</span>
                        <span class="stat-label">Pending</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="/staff/billing" class="card-btn">View Billing</a>
                    <a href="/staff/billing/disputes" class="card-btn secondary">Disputes</a>
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18,6L6,18M6,6L18,18M21,3H3A2,2 0 0,0 1,5V19A2,2 0 0,0 3,21H21A2,2 0 0,0 23,19V5A2,2 0 0,0 21,3Z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Support Tickets</h2>
                </div>
                <p class="card-description">
                    Handle customer support requests, bug reports, and feature requests.
                </p>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo number_format($openTickets); ?></span>
                        <span class="stat-label">Open</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo number_format($urgentTickets); ?></span>
                        <span class="stat-label">Urgent</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="/staff/support" class="card-btn">View Tickets</a>
                    <a href="/staff/support/new" class="card-btn secondary">Create Ticket</a>
                </div>
            </div>

            <!-- System Health -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">System Health</h2>
                </div>
                <p class="card-description">
                    Monitor server status, API performance, and platform uptime across all services.
                </p>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo $systemIssues === 0 ? '99.9%' : '98.' . (9 - $systemIssues) . '%'; ?></span>
                        <span class="stat-label">Uptime</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number"><?php echo $systemIssues; ?></span>
                        <span class="stat-label">Issues</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="/staff/system" class="card-btn">View Status</a>
                    <a href="/staff/system/logs" class="card-btn secondary">View Logs</a>
                </div>
            </div>

            <!-- Analytics -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22,21H2V3H4V19H6V17H10V19H12V16H16V19H18V17H22V21M4,15H8V17H4V15M10,13H14V15H10V13M16,15H20V17H16V15M4,11H8V13H4V11M10,9H14V11H10V9M16,11H20V13H16V11M4,7H8V9H4V7M10,5H14V7H10V5M16,7H20V9H16V7Z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Analytics</h2>
                </div>
                <p class="card-description">
                    View platform usage statistics, user behavior, and performance metrics.
                </p>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <span class="stat-number">1.2k</span>
                        <span class="stat-label">Daily Users</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number">4.8k</span>
                        <span class="stat-label">Page Views</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="/staff/analytics" class="card-btn">View Analytics</a>
                    <a href="/staff/analytics/reports" class="card-btn secondary">Generate Report</a>
                </div>
            </div>

            <!-- Staff Management -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,5A3.5,3.5 0 0,0 8.5,8.5A3.5,3.5 0 0,0 12,12A3.5,3.5 0 0,0 15.5,8.5A3.5,3.5 0 0,0 12,5M12,7A1.5,1.5 0 0,1 13.5,8.5A1.5,1.5 0 0,1 12,10A1.5,1.5 0 0,1 10.5,8.5A1.5,1.5 0 0,1 12,7M5.5,8A2.5,2.5 0 0,0 3,10.5C3,11.44 3.53,12.25 4.29,12.68C4.65,12.88 5.06,13 5.5,13A2.5,2.5 0 0,0 8,10.5A2.5,2.5 0 0,0 5.5,8M18.5,8A2.5,2.5 0 0,0 16,10.5A2.5,2.5 0 0,0 18.5,13C18.94,13 19.35,12.88 19.71,12.68C20.47,12.25 21,11.44 21,10.5A2.5,2.5 0 0,0 18.5,8M12,14C10,14 6,15 6,17V19H18V17C18,15 14,14 12,14M5.5,14.5C5.1,14.5 4.69,14.5 4.27,14.56C3.61,15.07 3,15.96 3,17V19H6V17C6,16.64 6.09,16.31 6.26,16.03C6.07,15.6 5.75,15.17 5.5,14.5M18.5,14.5C18.25,15.17 17.93,15.6 17.74,16.03C17.91,16.31 18,16.64 18,17V19H21V17C21,15.96 20.39,15.07 19.73,14.56C19.31,14.5 18.9,14.5 18.5,14.5Z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Staff Management</h2>
                </div>
                <p class="card-description">
                    Manage staff accounts, permissions, and access controls for the team.
                </p>
                <div class="quick-stats">
                    <div class="quick-stat">
                        <span class="stat-number">8</span>
                        <span class="stat-label">Active Staff</span>
                    </div>
                    <div class="quick-stat">
                        <span class="stat-number">3</span>
                        <span class="stat-label">Online Now</span>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="/staff/team" class="card-btn">Manage Staff</a>
                    <a href="/staff/team/permissions" class="card-btn secondary">Permissions</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
