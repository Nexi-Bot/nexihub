<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "HR Dashboard";
$page_description = "Nexi Hub Staff Portal - Human Resources Management";

// Get current user's IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    if ($ip === '::1' || $ip === '127.0.0.1') {
        $ip = '192.168.1.' . rand(100, 254);
    }
    
    return $ip;
}

// Current user information
$currentStaffId = $_SESSION['staff_id'] ?? 1;
$currentUserRole = $_SESSION['role'] ?? 'Employee';

// Mock HR Data (would be from database in production)
$hrStats = [
    'total_employees' => 42,
    'active_employees' => 39,
    'pending_onboarding' => 3,
    'contracts_expiring' => 5,
    'pending_time_off' => 8,
    'open_tickets' => 12,
    'training_completed' => 85,
    'this_month_hires' => 4
];

// Mock staff records for HR view
$staffRecords = [
    [
        'id' => 1,
        'employee_id' => 'NEXI001',
        'name' => 'Oliver Reaney',
        'email' => 'ollie.r@nexihub.uk',
        'role' => 'Chief Executive Officer',
        'department' => 'Executive',
        'status' => 'Active',
        'hire_date' => '2020-01-01',
        'contract_expiry' => '2025-12-31',
        'avatar' => '/assets/images/Ollie.jpg',
        'salary' => 150000,
        'location' => 'London, UK',
        'manager' => null
    ],
    [
        'id' => 2,
        'employee_id' => 'NEXI002',
        'name' => 'Benjamin Clarke',
        'email' => 'benjamin@nexihub.uk',
        'role' => 'Managing Director',
        'department' => 'Executive',
        'status' => 'Active',
        'hire_date' => '2020-02-01',
        'contract_expiry' => '2025-12-31',
        'avatar' => '/assets/images/Benjamin.jpg',
        'salary' => 120000,
        'location' => 'London, UK',
        'manager' => 'Oliver Reaney'
    ],
    [
        'id' => 3,
        'employee_id' => 'NEXI003',
        'name' => 'Maisie Reaney',
        'email' => 'maisie@nexihub.uk',
        'role' => 'Internal Communications Manager',
        'department' => 'Corporate Functions',
        'status' => 'Active',
        'hire_date' => '2021-03-01',
        'contract_expiry' => '2025-12-31',
        'avatar' => '/assets/images/maisie.jpg',
        'salary' => 65000,
        'location' => 'London, UK',
        'manager' => 'Oliver Reaney'
    ],
    [
        'id' => 4,
        'employee_id' => 'NEXI004',
        'name' => 'Joseph Richardson',
        'email' => 'joseph@nexihub.uk',
        'role' => 'Regional Director - EMEA',
        'department' => 'Operations',
        'status' => 'Active',
        'hire_date' => '2024-01-15',
        'contract_expiry' => '2026-01-14',
        'avatar' => '/assets/images/joe.jpg',
        'salary' => 95000,
        'location' => 'Berlin, Germany',
        'manager' => 'Benjamin Clarke'
    ],
    [
        'id' => 5,
        'employee_id' => 'NEXI005',
        'name' => 'Oliver Mills',
        'email' => 'ollie.m@nexihub.uk',
        'role' => 'Head of HR',
        'department' => 'Human Resources',
        'status' => 'Active',
        'hire_date' => '2024-01-20',
        'contract_expiry' => '2026-01-19',
        'avatar' => '/assets/images/olliem.jpg',
        'salary' => 85000,
        'location' => 'Manchester, UK',
        'manager' => 'Maisie Reaney'
    ]
];

// Mock pending onboarding
$pendingOnboarding = [
    [
        'name' => 'Logan Smith',
        'role' => 'Talent Acquisition Manager',
        'start_date' => '2024-02-01',
        'completion' => 75,
        'avatar' => '/assets/images/logan.jpg'
    ],
    [
        'name' => 'Mykyta Volkov',
        'role' => 'Learning & Development Manager',
        'start_date' => '2024-02-05',
        'completion' => 45,
        'avatar' => '/assets/images/mykyta.jpg'
    ],
    [
        'name' => 'Lanre Ogundimu',
        'role' => 'Business Intelligence Manager',
        'start_date' => '2024-02-10',
        'completion' => 20,
        'avatar' => '/assets/images/lanre.jpg'
    ]
];

include __DIR__ . '/../includes/header.php';
?>

<style>
:root {
    --odoo-primary: #714B67;
    --odoo-secondary: #17a2b8;
    --odoo-success: #28a745;
    --odoo-warning: #ffc107;
    --odoo-danger: #dc3545;
    --odoo-info: #17a2b8;
    --odoo-light: #f8f9fa;
    --odoo-dark: #343a40;
    --sidebar-width: 280px;
    --header-height: 70px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f5f7;
    overflow-x: hidden;
}

.hr-dashboard {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.hr-sidebar {
    width: var(--sidebar-width);
    background: white;
    border-right: 1px solid #e0e0e0;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e0e0e0;
    background: var(--odoo-primary);
    color: white;
}

.sidebar-logo {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.sidebar-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
}

.sidebar-nav {
    padding: 1rem 0;
}

.nav-section {
    margin-bottom: 2rem;
}

.nav-section-title {
    padding: 0.5rem 1.5rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.nav-item:hover {
    background: #f8f9fa;
    color: var(--odoo-primary);
    border-left-color: var(--odoo-primary);
}

.nav-item.active {
    background: #f0f0f0;
    color: var(--odoo-primary);
    border-left-color: var(--odoo-primary);
    font-weight: 600;
}

.nav-item i {
    width: 20px;
    margin-right: 1rem;
    font-size: 1rem;
}

.nav-badge {
    margin-left: auto;
    background: var(--odoo-danger);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Main Content */
.hr-main {
    margin-left: var(--sidebar-width);
    flex: 1;
}

.hr-header {
    background: white;
    height: var(--header-height);
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    position: sticky;
    top: 0;
    z-index: 999;
}

.header-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-search {
    position: relative;
}

.header-search input {
    padding: 0.5rem 1rem 0.5rem 2.5rem;
    border: 1px solid #ddd;
    border-radius: 20px;
    width: 300px;
    font-size: 0.9rem;
}

.header-search i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.header-profile {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border-radius: 25px;
    background: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-profile:hover {
    background: #e9ecef;
}

.profile-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-info {
    text-align: right;
}

.profile-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #333;
}

.profile-role {
    font-size: 0.75rem;
    color: #666;
}

/* Content Area */
.hr-content {
    padding: 2rem;
}

/* Dashboard Cards */
.dashboard-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.overview-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid var(--odoo-primary);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.overview-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
    color: white;
}

.card-icon.employees { background: var(--odoo-primary); }
.card-icon.onboarding { background: var(--odoo-info); }
.card-icon.contracts { background: var(--odoo-warning); }
.card-icon.timeoff { background: var(--odoo-success); }

.card-info h3 {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 0.25rem;
}

.card-info p {
    color: #666;
    font-size: 0.9rem;
}

.card-trend {
    font-size: 0.8rem;
    margin-top: 0.5rem;
}

.trend-up { color: var(--odoo-success); }
.trend-down { color: var(--odoo-danger); }

/* Main Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Staff Records Table */
.staff-records {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-header {
    background: #f8f9fa;
    padding: 1.5rem;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--odoo-primary);
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.staff-table {
    width: 100%;
    border-collapse: collapse;
}

.staff-table th,
.staff-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.staff-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.staff-row {
    transition: background-color 0.3s ease;
}

.staff-row:hover {
    background: #f8f9fa;
}

.staff-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.staff-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.staff-details h4 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.staff-details p {
    font-size: 0.8rem;
    color: #666;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

/* Onboarding Panel */
.onboarding-panel {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.panel-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e0e0e0;
}

.panel-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.onboarding-list {
    padding: 1rem;
}

.onboarding-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.onboarding-item:hover {
    background: #e9ecef;
}

.onboarding-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 1rem;
}

.onboarding-info {
    flex: 1;
}

.onboarding-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.onboarding-role {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.progress-bar {
    background: #e0e0e0;
    border-radius: 10px;
    height: 8px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--odoo-success);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.75rem;
    color: #666;
    margin-top: 0.25rem;
}

/* Analytics Section */
.analytics-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.analytics-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 1.5rem;
}

.analytics-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

.chart-placeholder {
    height: 200px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-style: italic;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .hr-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .hr-sidebar.open {
        transform: translateX(0);
    }
    
    .hr-main {
        margin-left: 0;
    }
}

@media (max-width: 768px) {
    .dashboard-overview {
        grid-template-columns: 1fr;
    }
    
    .analytics-section {
        grid-template-columns: 1fr;
    }
    
    .header-search input {
        width: 200px;
    }
    
    .hr-content {
        padding: 1rem;
    }
}

/* Module Placeholders */
.module-placeholder {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 2rem;
    text-align: center;
    color: #666;
}

.module-placeholder i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #ddd;
}

.module-placeholder h3 {
    margin-bottom: 1rem;
    color: #333;
}
</style>

<div class="hr-dashboard">
    <!-- Sidebar -->
    <div class="hr-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">Nexi Hub</div>
            <div class="sidebar-subtitle">Human Resources</div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Dashboard</div>
                <a href="#" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    Overview
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    Analytics
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Employee Management</div>
                <a href="#" class="nav-item">
                    <i class="fas fa-users"></i>
                    Staff Records
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-user-plus"></i>
                    Onboarding
                    <span class="nav-badge"><?php echo count($pendingOnboarding); ?></span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-file-contract"></i>
                    Contracts
                    <span class="nav-badge"><?php echo $hrStats['contracts_expiring']; ?></span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-calendar-check"></i>
                    Time Off
                    <span class="nav-badge"><?php echo $hrStats['pending_time_off']; ?></span>
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Learning & Development</div>
                <a href="#" class="nav-item">
                    <i class="fas fa-graduation-cap"></i>
                    E-Learning
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-certificate"></i>
                    Certifications
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-star"></i>
                    Performance
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Operations</div>
                <a href="#" class="nav-item">
                    <i class="fas fa-ticket-alt"></i>
                    Support Tickets
                    <span class="nav-badge"><?php echo $hrStats['open_tickets']; ?></span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-tasks"></i>
                    Task Management
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-history"></i>
                    Audit Logs
                </a>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Finance</div>
                <a href="#" class="nav-item">
                    <i class="fas fa-money-bill-wave"></i>
                    Payroll
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-receipt"></i>
                    Billing
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-chart-pie"></i>
                    Reports
                </a>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="hr-main">
        <!-- Header -->
        <header class="hr-header">
            <h1 class="header-title">HR Dashboard</h1>
            
            <div class="header-actions">
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search employees, contracts, tickets...">
                </div>
                
                <div class="header-profile">
                    <img src="/assets/images/Ollie.jpg" alt="Profile" class="profile-avatar">
                    <div class="profile-info">
                        <div class="profile-name">Oliver Reaney</div>
                        <div class="profile-role">CEO</div>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <main class="hr-content">
            <!-- Overview Cards -->
            <div class="dashboard-overview">
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon employees">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-info">
                            <h3><?php echo $hrStats['total_employees']; ?></h3>
                            <p>Total Employees</p>
                        </div>
                    </div>
                    <div class="card-trend trend-up">
                        <i class="fas fa-arrow-up"></i> +<?php echo $hrStats['this_month_hires']; ?> this month
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon onboarding">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="card-info">
                            <h3><?php echo $hrStats['pending_onboarding']; ?></h3>
                            <p>Pending Onboarding</p>
                        </div>
                    </div>
                    <div class="card-trend">
                        <i class="fas fa-clock"></i> Average 3 days to complete
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon contracts">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="card-info">
                            <h3><?php echo $hrStats['contracts_expiring']; ?></h3>
                            <p>Contracts Expiring</p>
                        </div>
                    </div>
                    <div class="card-trend trend-down">
                        <i class="fas fa-exclamation-triangle"></i> Requires attention
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-icon timeoff">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="card-info">
                            <h3><?php echo $hrStats['pending_time_off']; ?></h3>
                            <p>Pending Time Off</p>
                        </div>
                    </div>
                    <div class="card-trend">
                        <i class="fas fa-clock"></i> Avg. 2 days approval time
                    </div>
                </div>
            </div>
            
            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Staff Records -->
                <div class="staff-records">
                    <div class="table-header">
                        <h2 class="table-title">Staff Records</h2>
                        <div class="table-actions">
                            <a href="#" class="btn btn-secondary">
                                <i class="fas fa-download"></i> Export
                            </a>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Employee
                            </a>
                        </div>
                    </div>
                    
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Contract</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($staffRecords, 0, 5) as $staff): ?>
                            <tr class="staff-row">
                                <td>
                                    <div class="staff-info">
                                        <img src="<?php echo htmlspecialchars($staff['avatar']); ?>" 
                                             alt="<?php echo htmlspecialchars($staff['name']); ?>" 
                                             class="staff-avatar"
                                             onerror="this.src='https://i.pravatar.cc/150?img=0';">
                                        <div class="staff-details">
                                            <h4><?php echo htmlspecialchars($staff['name']); ?></h4>
                                            <p><?php echo htmlspecialchars($staff['employee_id']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($staff['role']); ?></td>
                                <td><?php echo htmlspecialchars($staff['department']); ?></td>
                                <td>
                                    <span class="status-badge status-active">
                                        <?php echo htmlspecialchars($staff['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M Y', strtotime($staff['contract_expiry'])); ?></td>
                                <td>
                                    <a href="#" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Onboarding Panel -->
                <div class="onboarding-panel">
                    <div class="panel-header">
                        <h3 class="panel-title">Active Onboarding</h3>
                    </div>
                    
                    <div class="onboarding-list">
                        <?php foreach ($pendingOnboarding as $onboarding): ?>
                        <div class="onboarding-item">
                            <img src="<?php echo htmlspecialchars($onboarding['avatar']); ?>" 
                                 alt="<?php echo htmlspecialchars($onboarding['name']); ?>" 
                                 class="onboarding-avatar"
                                 onerror="this.src='https://i.pravatar.cc/150?img=0';">
                            <div class="onboarding-info">
                                <div class="onboarding-name"><?php echo htmlspecialchars($onboarding['name']); ?></div>
                                <div class="onboarding-role"><?php echo htmlspecialchars($onboarding['role']); ?></div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $onboarding['completion']; ?>%"></div>
                                </div>
                                <div class="progress-text"><?php echo $onboarding['completion']; ?>% complete</div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Analytics Section -->
            <div class="analytics-section">
                <div class="analytics-card">
                    <h3 class="analytics-title">Employee Growth</h3>
                    <div class="chart-placeholder">
                        Employee growth chart placeholder
                    </div>
                </div>
                
                <div class="analytics-card">
                    <h3 class="analytics-title">Department Distribution</h3>
                    <div class="chart-placeholder">
                        Department distribution chart placeholder
                    </div>
                </div>
                
                <div class="analytics-card">
                    <h3 class="analytics-title">Performance Metrics</h3>
                    <div class="chart-placeholder">
                        Performance metrics chart placeholder
                    </div>
                </div>
            </div>
            
            <!-- Module Placeholders -->
            <div style="margin-top: 3rem;">
                <h2 style="margin-bottom: 2rem; color: #333;">Additional HR Modules</h2>
                
                <div class="analytics-section">
                    <div class="module-placeholder">
                        <i class="fas fa-graduation-cap"></i>
                        <h3>E-Learning Platform</h3>
                        <p>Course management, progress tracking, and certification system</p>
                    </div>
                    
                    <div class="module-placeholder">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>Support Ticket System</h3>
                        <p>Internal support requests, IT helpdesk, and issue tracking</p>
                    </div>
                    
                    <div class="module-placeholder">
                        <i class="fas fa-tasks"></i>
                        <h3>Task Management</h3>
                        <p>Project assignments, deadlines, and team collaboration</p>
                    </div>
                    
                    <div class="module-placeholder">
                        <i class="fas fa-history"></i>
                        <h3>Audit Logs</h3>
                        <p>System activity tracking, compliance reporting, and security logs</p>
                    </div>
                    
                    <div class="module-placeholder">
                        <i class="fas fa-money-bill-wave"></i>
                        <h3>Payroll & Billing</h3>
                        <p>Salary management, expense tracking, and financial reporting</p>
                    </div>
                    
                    <div class="module-placeholder">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Time Off Management</h3>
                        <p>Leave requests, holiday tracking, and absence management</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Basic interactivity for the HR dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar toggle
    const sidebar = document.querySelector('.hr-sidebar');
    const toggleBtn = document.createElement('button');
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    toggleBtn.className = 'mobile-toggle';
    toggleBtn.style.cssText = `
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1001;
        background: var(--odoo-primary);
        color: white;
        border: none;
        padding: 0.5rem;
        border-radius: 6px;
        display: none;
    `;
    
    document.body.appendChild(toggleBtn);
    
    // Show toggle button on mobile
    function checkMobile() {
        if (window.innerWidth <= 1024) {
            toggleBtn.style.display = 'block';
        } else {
            toggleBtn.style.display = 'none';
            sidebar.classList.remove('open');
        }
    }
    
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });
    
    window.addEventListener('resize', checkMobile);
    checkMobile();
    
    // Search functionality placeholder
    const searchInput = document.querySelector('.header-search input');
    searchInput.addEventListener('input', function(e) {
        console.log('Searching for:', e.target.value);
        // Implement search functionality here
    });
    
    // Profile dropdown placeholder
    const profileDropdown = document.querySelector('.header-profile');
    profileDropdown.addEventListener('click', function() {
        console.log('Profile dropdown clicked');
        // Implement profile dropdown functionality here
    });
    
    // Navigation item clicks
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            console.log('Navigating to:', this.textContent.trim());
            // Implement navigation functionality here
        });
    });
    
    // Table row clicks
    const staffRows = document.querySelectorAll('.staff-row');
    staffRows.forEach(row => {
        row.addEventListener('click', function() {
            console.log('Staff row clicked:', this);
            // Implement staff detail view here
        });
    });
    
    // Onboarding item clicks
    const onboardingItems = document.querySelectorAll('.onboarding-item');
    onboardingItems.forEach(item => {
        item.addEventListener('click', function() {
            console.log('Onboarding item clicked:', this);
            // Implement onboarding detail view here
        });
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
