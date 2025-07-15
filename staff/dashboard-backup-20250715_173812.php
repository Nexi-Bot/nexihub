<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "Nexi Group Executive Command Center";
$page_description = "Revolutionary Business Intelligence Platform - Multi-Company Operations Dashboard";

// Enhanced user profile with advanced capabilities
$current_user = [
    'full_name' => $_SESSION['staff_name'] ?? 'Executive Administrator',
    'user_id' => $_SESSION['staff_id'] ?? 1,
    'email' => $_SESSION['staff_email'] ?? 'admin@nexihub.com',
    'department' => $_SESSION['staff_department'] ?? 'Executive Operations',
    'role' => $_SESSION['staff_role'] ?? 'Chief Operating Officer',
    'avatar' => '/assets/images/avatars/' . ($_SESSION['staff_id'] ?? '1') . '.jpg',
    'last_login' => date('M j, Y \a\t g:i A'),
    'permissions' => ['hr', 'finance', 'operations', 'analytics', 'security', 'admin'],
    'notifications' => 15,
    'company_access' => ['Nexi Hub', 'Nexi Digital', 'Nexi Consulting'],
    'dashboard_widgets' => ['analytics', 'revenue', 'staff', 'projects', 'security'],
    'preferred_timezone' => 'Europe/London',
    'dashboard_theme' => 'dark',
    'quick_actions' => 8
];

// Advanced real-time analytics across all entities
$analytics = [
    // Core Metrics
    'total_staff' => 52,
    'active_staff' => 48,
    'pending_onboarding' => 6,
    'compliance_issues' => 1,
    'recent_hires' => 9,
    'upcoming_time_off' => 14,
    'remote_workers' => 31,
    'office_workers' => 17,
    
    // Financial Intelligence
    'monthly_revenue' => 687250,
    'quarterly_revenue' => 1954000,
    'annual_projection' => 8216000,
    'profit_margin' => 68.4,
    'operating_expenses' => 234500,
    'pending_invoices' => 12,
    'overdue_payments' => 3,
    'cash_flow_score' => 94,
    
    // Project Portfolio
    'active_projects' => 38,
    'completed_this_month' => 12,
    'overdue_projects' => 2,
    'projects_in_planning' => 8,
    'client_satisfaction' => 4.9,
    'project_success_rate' => 97.3,
    'avg_project_margin' => 42.7,
    
    // Operations & Security
    'open_tickets' => 18,
    'resolved_today' => 23,
    'system_uptime' => 99.94,
    'security_score' => 97.8,
    'backup_success' => 100,
    'server_response_time' => 0.15,
    'data_integrity_score' => 99.9,
    
    // Performance Intelligence
    'avg_satisfaction' => 4.8,
    'retention_rate' => 97.1,
    'productivity_index' => 94.2,
    'training_completion' => 96.5,
    'certification_rate' => 88.3,
    'innovation_projects' => 7,
    'automation_savings' => 156000
];

// Comprehensive staff data across all companies
$staff_members = [
    // Nexi Hub Executive Team
    ['id' => 'NH001', 'name' => 'Oliver Reaney', 'department' => 'Executive', 'role' => 'CEO & Founder', 'status' => 'Active', 'email' => 'oliver@nexihub.com', 'phone' => '+44 7123 456789', 'hire_date' => '2023-01-15', 'company' => 'Nexi Hub', 'salary' => 150000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 5'],
    ['id' => 'NH002', 'name' => 'Benjamin Clarke', 'department' => 'Executive', 'role' => 'Managing Director', 'status' => 'Active', 'email' => 'benjamin@nexihub.com', 'phone' => '+44 7234 567890', 'hire_date' => '2023-02-01', 'company' => 'Nexi Hub', 'salary' => 120000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 5'],
    
    // Technology Team
    ['id' => 'NH003', 'name' => 'Logan Mitchell', 'department' => 'Technology', 'role' => 'Lead Developer', 'status' => 'Onboarding', 'email' => 'logan@nexihub.com', 'phone' => '+44 7345 678901', 'hire_date' => '2025-07-01', 'company' => 'Nexi Hub', 'salary' => 75000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 3'],
    ['id' => 'NH004', 'name' => 'Mykyta Petrenko', 'department' => 'Technology', 'role' => 'Senior Developer', 'status' => 'Active', 'email' => 'mykyta@nexihub.com', 'phone' => '+44 7456 789012', 'hire_date' => '2024-03-15', 'company' => 'Nexi Hub', 'salary' => 65000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 3'],
    ['id' => 'NH005', 'name' => 'Sarah Johnson', 'department' => 'Design', 'role' => 'Senior UI/UX Designer', 'status' => 'Active', 'email' => 'sarah@nexihub.com', 'phone' => '+44 7567 890123', 'hire_date' => '2024-05-20', 'company' => 'Nexi Hub', 'salary' => 55000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 2'],
    
    // Nexi Digital Team
    ['id' => 'ND001', 'name' => 'Marcus Thompson', 'department' => 'Digital Marketing', 'role' => 'Head of Digital', 'status' => 'Active', 'email' => 'marcus@nexidigital.com', 'phone' => '+44 7678 901234', 'hire_date' => '2024-01-10', 'company' => 'Nexi Digital', 'salary' => 70000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 3'],
    ['id' => 'ND002', 'name' => 'Emma Rodriguez', 'department' => 'Content Strategy', 'role' => 'Content Manager', 'status' => 'Active', 'email' => 'emma@nexidigital.com', 'phone' => '+44 7789 012345', 'hire_date' => '2024-04-08', 'company' => 'Nexi Digital', 'salary' => 45000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 2'],
    
    // Nexi Consulting Team
    ['id' => 'NC001', 'name' => 'David Chen', 'department' => 'Business Consulting', 'role' => 'Senior Consultant', 'status' => 'Active', 'email' => 'david@nexiconsulting.com', 'phone' => '+44 7890 123456', 'hire_date' => '2024-02-15', 'company' => 'Nexi Consulting', 'salary' => 80000, 'contract_type' => 'Permanent', 'security_clearance' => 'Level 4'],
    ['id' => 'NC002', 'name' => 'Lisa Wang', 'department' => 'Strategy', 'role' => 'Strategy Analyst', 'status' => 'Active', 'email' => 'lisa@nexiconsulting.com', 'phone' => '+44 7901 234567', 'hire_date' => '2024-06-20', 'company' => 'Nexi Consulting', 'salary' => 50000, 'contract_type' => 'Contract', 'security_clearance' => 'Level 2']
];

// Enhanced time off system
$time_off_requests = [
    ['id' => 'TO001', 'employee' => 'Mykyta Petrenko', 'employee_id' => 'NH004', 'request_date' => '2025-07-10', 'start_date' => '2025-07-20', 'end_date' => '2025-07-24', 'type' => 'Annual Leave', 'status' => 'Pending', 'days' => 5, 'reason' => 'Family vacation to Spain', 'manager' => 'Logan Mitchell', 'cost_impact' => 1250, 'cover_arranged' => true],
    ['id' => 'TO002', 'employee' => 'Logan Mitchell', 'employee_id' => 'NH003', 'request_date' => '2025-07-08', 'start_date' => '2025-07-15', 'end_date' => '2025-07-15', 'type' => 'Personal', 'status' => 'Approved', 'days' => 1, 'reason' => 'Medical appointment', 'manager' => 'Benjamin Clarke', 'cost_impact' => 290, 'cover_arranged' => true],
    ['id' => 'TO003', 'employee' => 'Sarah Johnson', 'employee_id' => 'NH005', 'request_date' => '2025-07-12', 'start_date' => '2025-08-01', 'end_date' => '2025-08-07', 'type' => 'Annual Leave', 'status' => 'Approved', 'days' => 7, 'reason' => 'Summer holiday', 'manager' => 'Oliver Reaney', 'cost_impact' => 1680, 'cover_arranged' => false],
    ['id' => 'TO004', 'employee' => 'David Chen', 'employee_id' => 'NC001', 'request_date' => '2025-07-14', 'start_date' => '2025-08-15', 'end_date' => '2025-08-22', 'type' => 'Annual Leave', 'status' => 'Pending', 'days' => 8, 'reason' => 'Wedding anniversary trip', 'manager' => 'Benjamin Clarke', 'cost_impact' => 2400, 'cover_arranged' => true]
];

// Enhanced activity tracking
$recent_activities = [
    ['time' => '15 minutes ago', 'employee' => 'Logan Mitchell', 'action' => 'Completed React.js security training module', 'status' => 'Completed', 'type' => 'Training', 'priority' => 'Medium'],
    ['time' => '1 hour ago', 'employee' => 'Mykyta Petrenko', 'action' => 'Submitted time off request for vacation', 'status' => 'Pending', 'type' => 'Leave', 'priority' => 'Low'],
    ['time' => '2 hours ago', 'employee' => 'Sarah Johnson', 'action' => 'Uploaded new brand guidelines document', 'status' => 'Completed', 'type' => 'Document', 'priority' => 'High'],
    ['time' => '3 hours ago', 'employee' => 'David Chen', 'action' => 'Submitted quarterly performance review', 'status' => 'Under Review', 'type' => 'Performance', 'priority' => 'High'],
    ['time' => '4 hours ago', 'employee' => 'Emma Rodriguez', 'action' => 'Completed cybersecurity compliance training', 'status' => 'Completed', 'type' => 'Compliance', 'priority' => 'Critical'],
    ['time' => '6 hours ago', 'employee' => 'Marcus Thompson', 'action' => 'Updated client project milestone', 'status' => 'Completed', 'type' => 'Project', 'priority' => 'Medium']
];

// Financial data for business operations
$financial_data = [
    'monthly_revenue' => 487250,
    'monthly_expenses' => 234100,
    'profit_margin' => 51.9,
    'payroll_costs' => 156000,
    'operational_costs' => 78100,
    'outstanding_invoices' => 12,
    'overdue_payments' => 3,
    'cash_flow' => 253150,
    'budget_utilization' => 78.4,
    'quarterly_growth' => 23.7
];

// Project management data
$project_data = [
    ['id' => 'PR001', 'name' => 'E-commerce Platform Redesign', 'client' => 'TechCorp Ltd', 'status' => 'In Progress', 'progress' => 67, 'deadline' => '2025-08-15', 'team_size' => 5, 'budget' => 45000, 'company' => 'Nexi Hub'],
    ['id' => 'PR002', 'name' => 'Digital Marketing Campaign', 'client' => 'Fashion Forward', 'status' => 'Planning', 'progress' => 23, 'deadline' => '2025-09-01', 'team_size' => 3, 'budget' => 25000, 'company' => 'Nexi Digital'],
    ['id' => 'PR003', 'name' => 'Business Process Optimization', 'client' => 'Manufacturing Inc', 'status' => 'In Progress', 'progress' => 89, 'deadline' => '2025-07-30', 'team_size' => 4, 'budget' => 60000, 'company' => 'Nexi Consulting'],
    ['id' => 'PR004', 'name' => 'Mobile App Development', 'client' => 'StartupXYZ', 'status' => 'Testing', 'progress' => 94, 'deadline' => '2025-07-25', 'team_size' => 6, 'budget' => 80000, 'company' => 'Nexi Hub']
];

// Training and compliance data
$training_data = [
    ['course' => 'Cybersecurity Awareness', 'completed' => 42, 'total' => 47, 'deadline' => '2025-08-01', 'compliance' => true],
    ['course' => 'GDPR Data Protection', 'completed' => 47, 'total' => 47, 'deadline' => '2025-07-15', 'compliance' => true],
    ['course' => 'Health & Safety', 'completed' => 39, 'total' => 47, 'deadline' => '2025-09-01', 'compliance' => true],
    ['course' => 'Leadership Development', 'completed' => 15, 'total' => 20, 'deadline' => '2025-12-01', 'compliance' => false],
    ['course' => 'Technical Skills Update', 'completed' => 28, 'total' => 35, 'deadline' => '2025-10-15', 'compliance' => false]
];

include '../includes/header.php';
?>
    ['time' => '2 hours ago', 'employee' => 'Logan Mitchell', 'action' => 'Completed onboarding documents', 'status' => 'Completed'],
    ['time' => '4 hours ago', 'employee' => 'Mykyta Petrenko', 'action' => 'Submitted time off request', 'status' => 'Pending'],
    ['time' => '6 hours ago', 'employee' => 'Oliver Reaney', 'action' => 'Updated staff record', 'status' => 'Completed'],
    ['time' => '1 day ago', 'employee' => 'Benjamin Clarke', 'action' => 'Approved time off request', 'status' => 'Approved'],
    ['time' => '2 days ago', 'employee' => 'Sarah Johnson', 'action' => 'Completed security training', 'status' => 'Completed']
];

include '../includes/header.php';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<style>
:root {
    /* Nexi Brand Colors Enhanced */
    --nexi-primary: #e64f21;
    --nexi-secondary: #ff6b35;
    --nexi-dark: #1a1a1a;
    --nexi-light: #f8f9fa;
    
    /* Advanced Gradients */
    --gradient-primary: linear-gradient(135deg, #e64f21 0%, #ff6b35 50%, #ff8c42 100%);
    --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #8b5cf6 100%);
    --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 50%, #6ee7b7 100%);
    --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #fcd34d 100%);
    --gradient-danger: linear-gradient(135deg, #ef4444 0%, #f87171 50%, #fca5a5 100%);
    --gradient-info: linear-gradient(135deg, #3b82f6 0%, #60a5fa 50%, #93c5fd 100%);
    
    /* Glass Morphism */
    --glass-bg: rgba(255, 255, 255, 0.08);
    --glass-border: rgba(255, 255, 255, 0.15);
    --glass-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    
    /* Advanced Shadows */
    --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07), 0 2px 4px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1), 0 10px 10px rgba(0, 0, 0, 0.04);
    --shadow-2xl: 0 25px 50px rgba(0, 0, 0, 0.25);
    --shadow-glow: 0 0 20px rgba(230, 79, 33, 0.3);
    
    /* Animations */
    --transition-fast: all 0.15s ease;
    --transition-normal: all 0.3s ease;
    --transition-slow: all 0.5s ease;
    --transition-spring: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    background: linear-gradient(135deg, 
        #0f0f23 0%, 
        #1a1a2e 25%, 
        #16213e 50%, 
        #0f3460 75%, 
        #533483 100%
    );
    background-size: 400% 400%;
    animation: cosmicDrift 20s ease infinite;
    min-height: 100vh;
}

@keyframes cosmicDrift {
    0%, 100% { background-position: 0% 50%; }
    25% { background-position: 100% 50%; }
    50% { background-position: 100% 100%; }
    75% { background-position: 0% 100%; }
}

/* Executive Command Center Container */
.executive-command-center {
    background: rgba(15, 23, 42, 0.92);
    backdrop-filter: blur(25px);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.executive-command-center::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(230, 79, 33, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

/* Enhanced Navigation */
.command-nav {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 2rem;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: var(--shadow-lg);
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.nav-brand .logo {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    box-shadow: var(--shadow-glow);
}

.nav-brand h1 {
    color: white;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Hero Section Enhanced */
.hero-section {
    padding: 3rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 50%, #cbd5e1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    line-height: 1.1;
    text-shadow: 0 0 30px rgba(255, 255, 255, 0.1);
}

.hero-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
    font-weight: 500;
}

.hero-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stat {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: var(--transition-spring);
    position: relative;
    overflow: hidden;
}

.hero-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-primary);
    transform: scaleX(0);
    transition: var(--transition-normal);
}

.hero-stat:hover::before {
    transform: scaleX(1);
}

.hero-stat:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
    border-color: rgba(230, 79, 33, 0.3);
}

.hero-stat-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 1rem;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-glow);
}

.hero-stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
    font-family: 'JetBrains Mono', monospace;
}

.hero-stat-label {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Advanced Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.analytics-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: var(--transition-spring);
    cursor: pointer;
}

.analytics-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
    transform: scaleX(0);
    transition: var(--transition-normal);
}

.analytics-card:hover::before {
    transform: scaleX(1);
}

.analytics-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-2xl);
    border-color: rgba(230, 79, 33, 0.4);
}

.analytics-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.analytics-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.analytics-card-icon {
    width: 45px;
    height: 45px;
    background: var(--gradient-secondary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    box-shadow: var(--shadow-md);
}

.analytics-value {
    font-size: 2.8rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
    font-family: 'JetBrains Mono', monospace;
    line-height: 1;
}

.analytics-change {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    font-weight: 500;
}

.analytics-change.positive {
    color: #10b981;
}

.analytics-change.negative {
    color: #ef4444;
}

.analytics-change.neutral {
    color: rgba(255, 255, 255, 0.6);
}

/* Enhanced Progress Bars */
.progress-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    height: 8px;
    overflow: hidden;
    margin-top: 1rem;
    position: relative;
}

.progress-bar {
    height: 100%;
    background: var(--gradient-success);
    border-radius: 10px;
    transition: width 1s ease;
    position: relative;
    overflow: hidden;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Quick Actions Enhanced */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.action-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: var(--transition-spring);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(230, 79, 33, 0.1) 0%, transparent 70%);
    transform: scale(0);
    transition: var(--transition-normal);
}

.action-card:hover::before {
    transform: scale(1);
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
    border-color: rgba(230, 79, 33, 0.3);
}

.action-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-primary);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    margin: 0 auto 1rem;
    box-shadow: var(--shadow-glow);
    position: relative;
    z-index: 2;
}

.action-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.action-description {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    position: relative;
    z-index: 2;
}

/* Live Activity Feed Enhanced */
.activity-feed {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    margin: 2rem;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
}

.activity-header {
    background: rgba(230, 79, 33, 0.1);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--glass-border);
}

.activity-header h3 {
    color: white;
    margin: 0;
    font-size: 1.3rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
    padding: 0;
}

.activity-item {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: var(--transition-normal);
    cursor: pointer;
    position: relative;
}

.activity-item:hover {
    background: rgba(255, 255, 255, 0.03);
    transform: translateX(5px);
}

.activity-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--gradient-primary);
    transform: scaleY(0);
    transition: var(--transition-normal);
}

.activity-item:hover::before {
    transform: scaleY(1);
}

.activity-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.activity-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: var(--gradient-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.activity-details {
    flex: 1;
}

.activity-employee {
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.activity-action {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.activity-time {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.8rem;
    text-align: right;
    flex-shrink: 0;
}

.activity-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: auto;
}

.activity-status.completed {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.activity-status.pending {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.activity-status.approved {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

/* Responsive Design Enhanced */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
        padding: 1rem;
    }
    
    .quick-actions {
        grid-template-columns: repeat(2, 1fr);
        padding: 1rem;
    }
    
    .command-nav {
        padding: 1rem;
    }
    
    .activity-feed {
        margin: 1rem;
    }
}

@media (max-width: 480px) {
    .hero-stats {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}

/* Accessibility Enhancements */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Dark theme optimizations */
@media (prefers-color-scheme: dark) {
    :root {
        --glass-bg: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.1);
    }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: rgba(230, 79, 33, 0.6);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(230, 79, 33, 0.8);
}

.command-center::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    pointer-events: none;
    z-index: 1;
}

.command-header {
    background: rgba(15, 23, 42, 0.98);
    backdrop-filter: blur(30px);
    border-bottom: 1px solid rgba(148, 163, 184, 0.1);
    padding: 1rem 2rem;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow-soft);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1600px;
    margin: 0 auto;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-gradient);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 800;
    box-shadow: var(--shadow-medium);
}

.header-title {
    color: #f8fafc;
    font-size: 1.8rem;
    font-weight: 800;
    margin: 0;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-subtitle {
    color: #94a3b8;
    font-size: 0.9rem;
    margin: 0;
    font-weight: 500;
}

.quick-nav {
    display: flex;
    gap: 0.5rem;
}

.nav-pill {
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    color: #cbd5e1;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.nav-pill:hover, .nav-pill.active {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.notification-center {
    position: relative;
    cursor: pointer;
}

.notification-icon {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cbd5e1;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.notification-icon:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    transform: translateY(-2px);
}

.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 600;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 0.75rem 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.user-profile:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
    box-shadow: var(--shadow-medium);
}

.user-info h4 {
    color: #f8fafc;
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.user-info p {
    color: #94a3b8;
    margin: 0;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Main Dashboard Container */
.dashboard-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 2rem;
    position: relative;
    z-index: 10;
}

/* Welcome Hero Section */
.welcome-hero {
    background: var(--primary-gradient);
    border-radius: 24px;
    padding: 3rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-hard);
}

.welcome-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}

.welcome-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    animation: float 10s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, -30px) rotate(120deg); }
    66% { transform: translate(-20px, 20px) rotate(240deg); }
}

.welcome-content {
    position: relative;
    z-index: 2;
    color: white;
}

.welcome-title {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 1rem;
    line-height: 1.1;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.welcome-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.95;
    font-weight: 500;
    line-height: 1.4;
}

.welcome-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.stat-item {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-4px);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

/* Analytics Grid */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.analytics-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
}

.analytics-card:hover {
    transform: translateY(-8px);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: var(--shadow-hard);
}

.analytics-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.card-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
    box-shadow: var(--shadow-medium);
}

.card-value {
    text-align: right;
}

.card-number {
    font-size: 2.8rem;
    font-weight: 800;
    color: #f8fafc;
    margin: 0;
    line-height: 1;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.card-label {
    color: #94a3b8;
    font-weight: 600;
    margin-top: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-change {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    font-size: 0.85rem;
    font-weight: 600;
}

.change-positive {
    color: #10b981;
}

.change-negative {
    color: #ef4444;
}

.change-neutral {
    color: #6b7280;
}

/* Section Cards */
.section-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.section-header {
    padding: 2rem 2.5rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #f8fafc;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.section-content {
    padding: 2.5rem;
}

/* Action Grid */
.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: rgba(255, 255, 255, 0.03);
    border: 2px dashed rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.action-card:hover {
    border-color: #e64f21;
    background: rgba(230, 79, 33, 0.05);
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
}

.action-card i {
    font-size: 2.5rem;
    color: #e64f21;
    margin-bottom: 1rem;
    display: block;
}

.action-card h4 {
    color: #f8fafc;
    font-weight: 700;
    margin: 0 0 0.75rem 0;
    font-size: 1.1rem;
}

.action-card p {
    color: #94a3b8;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.5;
}

/* Modern Tables */
.modern-table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.modern-table th,
.modern-table td {
    padding: 1.25rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.modern-table th {
    background: rgba(255, 255, 255, 0.05);
    font-weight: 700;
    color: #f8fafc;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modern-table td {
    color: #cbd5e1;
    font-weight: 500;
}

.modern-table tr:hover {
    background: rgba(230, 79, 33, 0.05);
}

.modern-table tr:hover td {
    color: #f8fafc;
}

/* Status Badges */
.status-badge {
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.status-completed {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-onboarding {
    background: rgba(168, 85, 247, 0.2);
    color: #a855f7;
    border: 1px solid rgba(168, 85, 247, 0.3);
}

.status-critical {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

/* Modern Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 8px 25px rgba(230, 79, 33, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(230, 79, 33, 0.4);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.05);
    color: #f8fafc;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: #e64f21;
    color: #e64f21;
    transform: translateY(-2px);
}

.btn-success {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3);
}

.btn-danger {
    background: var(--danger-gradient);
    color: white;
    box-shadow: 0 8px 25px rgba(252, 70, 107, 0.3);
}

.btn-warning {
    background: var(--warning-gradient);
    color: white;
    box-shadow: 0 8px 25px rgba(253, 187, 45, 0.3);
}

/* Tab Navigation */
.tab-navigation {
    display: flex;
    gap: 0.5rem;
    margin: 2rem 0;
    flex-wrap: wrap;
    justify-content: center;
}

.tab-item {
    padding: 1rem 2rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.tab-item:hover,
.tab-item.active {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
    transform: translateY(-3px);
    box-shadow: var(--shadow-medium);
}

.tab-item i {
    font-size: 1.1rem;
}

/* Content Sections */
.content-section {
    display: none;
    animation: fadeInUp 0.5s ease;
}

.content-section.active {
    display: block;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Logout Button */
.logout-btn {
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: var(--danger-gradient);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    z-index: 1000;
    box-shadow: var(--shadow-medium);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.logout-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(252, 70, 107, 0.4);
}

/* Comprehensive Tabbed Sections */

/* Staff Management Section */
section#staff-section {
    display: none;
}

.staff-overview {
    margin-bottom: 2rem;
}

.overview-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.overview-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    transition: var(--transition-spring);
}

.overview-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.card-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-glow);
}

.card-content {
    text-align: center;
}

.card-content h3 {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    color: #f8fafc;
}

.card-content p {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

/* Staff Table */
.staff-table-container {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    overflow: hidden;
}

.table-header {
    padding: 1.5rem 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    margin: 0;
    color: #f8fafc;
    font-size: 1.2rem;
    font-weight: 700;
}

.table-filters {
    display: flex;
    gap: 1rem;
}

.table-filters select {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.03);
    color: #f8fafc;
    font-size: 0.9rem;
    appearance: none;
    background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"%3E%3Cpath fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" d="M7 10l5 5 5-5H7z"/%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
}

.responsive-table {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.data-table th {
    background: rgba(255, 255, 255, 0.05);
    font-weight: 700;
    color: #f8fafc;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.data-table td {
    color: #cbd5e1;
    font-weight: 500;
}

.data-table tr:hover {
    background: rgba(230, 79, 33, 0.05);
}

.data-table tr:hover td {
    color: #f8fafc;
}

/* Project Portfolio Section */
#projects-section {
    display: none;
}

.project-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;
    transition: var(--transition-spring);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.stat-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-glow);
}

.stat-content {
    text-align: center;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    color: #f8fafc;
}

.stat-content p {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

/* Projects Grid */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.project-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;
    transition: var(--transition-spring);
    cursor: pointer;
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.project-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.project-header h3 {
    margin: 0;
    color: #f8fafc;
    font-size: 1.2rem;
    font-weight: 700;
}

.project-header .status-badge {
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.project-details {
    margin-bottom: 1rem;
    color: #cbd5e1;
    font-size: 0.9rem;
}

.project-progress {
    margin-bottom: 1rem;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.progress-bar {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-success);
    border-radius: 10px;
}

/* Financial Overview Section */
#finance-section {
    display: none;
}

.financial-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.finance-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;
    transition: var(--transition-spring);
}

.finance-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.finance-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-glow);
}

.finance-content {
    text-align: center;
}

.finance-content h3 {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    color: #f8fafc;
}

.finance-content p {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

/* Financial Breakdown */
.financial-breakdown {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.breakdown-section {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;
}

.revenue-bars {
    margin-top: 1rem;
}

.revenue-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.revenue-bar {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    flex: 1;
    margin-left: 1rem;
}

.revenue-fill {
    height: 100%;
    background: var(--gradient-success);
    border-radius: 10px;
}

/* Operations Center Section */
#operations-section {
    display: none;
}

.operations-section {
    margin-bottom: 2rem;
}

.request-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: var(--transition-spring);
}

.request-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.employee-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.employee-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
    box-shadow: var(--shadow-medium);
}

.request-details {
    margin-bottom: 1rem;
    color: #cbd5e1;
    font-size: 0.9rem;
}

.request-actions {
    display: flex;
    gap: 0.5rem;
}

/* Training & Compliance */
.training-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.training-item {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    transition: var(--transition-spring);
}

.training-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.training-info {
    margin-bottom: 1rem;
}

.compliance-badge {
    background: #ef4444;
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Business Intelligence Section */
#analytics-section {
    display: none;
}

.analytics-dashboard {
    margin-bottom: 2rem;
}

.analytics-widget {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.metric {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;ader">
    padding: 1rem;ent">
    text-align: center;
}

.metric-value {          <i class="fas fa-rocket"></i>
    font-size: 1.5rem;            </div>
    font-weight: 700;
    color: #f8fafc;eader-title">Nexi Command Center</h1>
    margin-bottom: 0.5rem;itle">Executive Operations Dashboard</p>
}
div>
.metric-label {    
    font-size: 0.9rem;">
    color: rgba(255, 255, 255, 0.7);Section('dashboard')">
}line"></i> Overview

.company-metrics {ick="showSection('staff')">
    display: grid;  <i class="fas fa-users"></i> Staff
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));    </a>
    gap: 1rem;projects')">
}></i> Projects
a>
.company-item {    <a href="#finance" class="nav-pill" onclick="showSection('finance')">
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
}

.ai-insights {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;ader-right">
    padding: 1.5rem;k="toggleNotifications()">
    display: grid;iv class="notification-icon">
    gap: 1.5rem;      <i class="fas fa-bell"></i>
}          <span class="notification-badge"><?= $current_user['notifications'] ?></span>
          </div>
/* Responsive Design */                </div>
@media (max-width: 768px) {
    .header-content {rofile" onclick="toggleUserMenu()">
        flex-direction: column;
        gap: 1rem;per(substr($current_user['full_name'], 0, 1)) ?>
    }
       <div class="user-info">
    .quick-nav {pecialchars($current_user['full_name']) ?></h4>
        order: 3;
        width: 100%;
        justify-content: center;    <i class="fas fa-chevron-down"></i>
    }    </div>
    
    .dashboard-container {
        padding: 1rem;
    }
    ntainer -->
    .welcome-hero {
        padding: 2rem;
        text-align: center;ero Section -->
    } class="welcome-hero loading">
    
    .welcome-title {e back, <?= htmlspecialchars(explode(' ', $current_user['full_name'])[0]) ?>! 🚀</h1>
        font-size: 2rem; empire awaits. Monitor performance, manage teams, and drive growth across all three businesses from this unified command center.</p>
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;  <div class="stat-number"><?= $analytics['total_staff'] ?></div>
    }        <div class="stat-label">Total Team Members</div>
    
    .action-grid {
        grid-template-columns: 1fr;ber_format($financial_data['monthly_revenue']) ?></div>
    }iv class="stat-label">Monthly Revenue</div>
    
    .tab-navigation {
        flex-direction: column;  <div class="stat-number"><?= $analytics['active_projects'] ?></div>
        align-items: center;        <div class="stat-label">Active Projects</div>
    }
    
    .modern-table {$analytics['system_uptime'] ?>%</div>
        font-size: 0.8rem;iv class="stat-label">System Uptime</div>
    }
    
    .modern-table th,
    .modern-table td {
        padding: 0.75rem;
    }ab Navigation -->
}        <nav class="tab-navigation">
ive" onclick="showSection('dashboard')">
/* Loading Animations */hometer-alt"></i> Executive Dashboard
.loading {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;rkforce Management
}
ction('projects')">
@keyframes fadeIn {i> Project Portfolio
    to {
        opacity: 1;lass="tab-item" onclick="showSection('finance')">
    }
}

/* Scroll Animations */ class="fas fa-cogs"></i> Operations Center
.scroll-reveal {
    opacity: 0;
    transform: translateY(50px); class="fas fa-analytics"></i> Business Intelligence
    transition: all 0.6s ease;
}

.scroll-reveal.revealed {ction -->
    opacity: 1;
    transform: translateY(0);
}
</style>eal">
showSection('staff')">
.hr-dashboard-header::before {iv class="card-header">
    content: '';      <div class="card-icon">
    position: absolute;
    top: 0;
    left: 0;
    right: 0;          <h3 class="card-number"><?= $analytics['total_staff'] ?></h3>
    height: 4px;-change change-positive">
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));= $analytics['recent_hires'] ?> this month
}          </div>

.hr-header-content {      </div>
    display: flex;                    <p class="card-label">Total Workforce</p>
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;lick="showSection('finance')">
    gap: 1rem;
}
nd-sign"></i>
.hr-header-left h1 {  </div>
    font-size: 2.5rem;      <div class="card-value">
    font-weight: 800;ata['monthly_revenue'] / 1000) ?>K</h3>
    margin-bottom: 0.5rem; change-positive">
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);growth
    -webkit-background-clip: text;          </div>
    -webkit-text-fill-color: transparent;
    background-clip: text;
}  <p class="card-label">Monthly Revenue</p>

.hr-header-subtitle {  
    color: var(--text-secondary);                <div class="analytics-card" onclick="showSection('projects')">
    font-size: 1.1rem;"card-header">
    margin-bottom: 1rem;
}sks"></i>

.hr-user-info {
    display: flex;?= $analytics['active_projects'] ?></h3>
    align-items: center;      <div class="card-change change-positive">
    gap: 1rem;              <i class="fas fa-arrow-up"></i> 94% completion rate
    background: var(--background-dark);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);  <p class="card-label">Active Projects</p>
}

.user-avatar {iv class="analytics-card">
    width: 50px;
    height: 50px;          <div class="card-icon">
    border-radius: 50%;                            <i class="fas fa-star"></i>
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;ber"><?= $analytics['client_satisfaction'] ?></h3>
    justify-content: center;">
    color: white;arrow-up"></i> Excellent rating
    font-weight: 700;
    font-size: 1.2rem;  </div>
}  </div>

.user-details h3 {
    margin: 0;
    color: var(--text-primary);iv class="analytics-card">
    font-weight: 600;>
}
          <i class="fas fa-shield-alt"></i>
.user-details p {
    margin: 0;          <div class="card-value">
    color: var(--text-secondary);                            <h3 class="card-number"><?= $analytics['security_score'] ?>%</h3>
    font-size: 0.9rem;ss="card-change change-positive">
    font-weight: 500;
}

.hr-nav-tabs {
    display: flex;ty Score</p>
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.hr-nav-tab {          <i class="fas fa-clock"></i>
    padding: 0.75rem 1.5rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);          <h3 class="card-number"><?= $analytics['system_uptime'] ?>%</h3>
    border-radius: 10px;ve">
    color: var(--text-secondary);                  <i class="fas fa-arrow-up"></i> 99.9% uptime
    text-decoration: none;              </div>
    font-weight: 500;                        </div>
    transition: all 0.3s ease;
    cursor: pointer;label">System Uptime</p>
    user-select: none;
}

.hr-nav-tab:hover,  <div class="card-header">
.hr-nav-tab.active {
    background: var(--primary-color);
    color: white;          </div>
    border-color: var(--primary-color);                        <div class="card-value">
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(230, 79, 33, 0.3);s="card-change change-neutral">
} fa-clock"></i> Requires attention
          </div>
.hr-nav-tab i {
    margin-right: 0.5rem;
}      <p class="card-label">Pending Approvals</p>
                </div>
.hr-analytics-grid {
    display: grid;-card">
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;      <div class="card-icon">
    margin-bottom: 2rem;/i>
}
          <div class="card-value">
.hr-analytics-card {                            <h3 class="card-number"><?= $analytics['retention_rate'] ?>%</h3>
    background: var(--background-light);
    border: 1px solid var(--border-color);ass="fas fa-arrow-up"></i> +2.1% this year
    border-radius: 16px;
    padding: 2rem;      </div>
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;  </div>
}            </div>

.hr-analytics-card::before {n -->
    content: '';al">
    position: absolute;iv class="section-header">
    top: 0;
    left: 0;
    right: 0;          Quick Actions
    height: 4px;                    </h2>
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
} fa-plus"></i> View All

.card-header {div>
    display: flex;
    justify-content: space-between;
    align-items: flex-start;          <div class="action-card" onclick="openModal('addStaffModal')">
    margin-bottom: 1rem;              <i class="fas fa-user-plus"></i>
}                            <h4>Recruit New Talent</h4>
eam members and initiate the onboarding process</p>
.card-icon {
    width: 50px;ction-card" onclick="showSection('projects')">
    height: 50px;
    border-radius: 12px;Project</h4>
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));p>
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;          <h4>Approve Time Off</h4>
    font-size: 1.2rem;              <p>Review and approve pending leave requests</p>
}                </div>
ick="generateReport()">
.card-value {
    text-align: right;
}nsive business performance reports</p>

.card-number {dal')">
    font-size: 2.5rem;  <i class="fas fa-receipt"></i>
    font-weight: 800;h4>
    color: var(--text-primary);
    margin: 0;
    line-height: 1;iv class="action-card" onclick="openModal('clientModal')">
}ndshake"></i>

.card-label {
    color: var(--text-secondary);div>
    font-weight: 600;div>
    margin-top: 0.5rem;div>
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;-- Recent Activity Feed -->
}      <div class="section-card scroll-reveal">
="section-header">
.card-change {
    display: flex;  <span class="section-icon"><i class="fas fa-stream"></i></span>
    align-items: center;        Live Activity Feed
    gap: 0.5rem;
    margin-top: 1rem;condary" onclick="refreshActivity()">
    font-size: 0.85rem;lt"></i> Refresh
    font-weight: 600;
}

.change-positive {
    color: #10b981;
}
h>Time</th>
.change-negative {  <th>Team Member</th>
    color: #ef4444;      <th>Activity</th>
}
          <th>Priority</th>
.change-neutral {                <th>Status</th>
    color: #6b7280;
}

/* Section Cards */ities as $activity): ?>
.section-card {r>
    background: rgba(255, 255, 255, 0.05);ialchars($activity['time']) ?></td>
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);ction']) ?></td>
    border-radius: 20px;
    margin-bottom: 2rem;  <span class="status-badge status-<?= strtolower($activity['type']) ?>">
    overflow: hidden;          <?= htmlspecialchars($activity['type']) ?>
    box-shadow: var(--shadow-soft);          </span>
}
          <td>
.section-header {                    <span class="status-badge status-<?= strtolower($activity['priority']) ?>">
    padding: 2rem 2.5rem 1rem;htmlspecialchars($activity['priority']) ?>
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;      <span class="status-badge status-<?= strtolower($activity['status']) ?>">
    flex-wrap: wrap;specialchars($activity['status']) ?>
    gap: 1rem;
}

.section-title {endforeach; ?>
    font-size: 1.4rem;y>
    font-weight: 700;e>
    color: #f8fafc;
    margin: 0;
    display: flex;n>
    align-items: center;
    gap: 0.75rem;
}ent-section">

.section-icon {ction-header">
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-gradient);
    display: flex;splay: flex; gap: 1rem;">
    align-items: center;n class="btn btn-primary" onclick="openModal('addStaffModal')">
    justify-content: center;  <i class="fas fa-user-plus"></i> Add Staff
    color: white;
    font-size: 0.9rem;  <button class="btn btn-secondary" onclick="exportStaffData()">
}            <i class="fas fa-download"></i> Export Data

.section-content {
    padding: 2.5rem;
}
="analytics-grid" style="margin-bottom: 2rem;">
/* Action Grid */rd">
.action-grid {
    display: grid; fa-users"></i></div>
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;  <h3 class="card-number"><?= $analytics['total_staff'] ?></h3>
}      <div class="card-change change-positive">
              <i class="fas fa-arrow-up"></i> +<?= $analytics['recent_hires'] ?> this month
.action-card {
    background: rgba(255, 255, 255, 0.03);          </div>
    border: 2px dashed rgba(255, 255, 255, 0.1);            </div>
    border-radius: 16px;abel">Total Team Members</p>
    padding: 2rem;
    text-align: center;ard">
    transition: all 0.3s ease;
    cursor: pointer;  <div class="card-icon"><i class="fas fa-user-check"></i></div>
    position: relative;-value">
    overflow: hidden;</h3>
}sitive">
ctive
.action-card:hover {  </div>
    border-color: #e64f21;  </div>
    background: rgba(230, 79, 33, 0.05);  </div>
    transform: translateY(-4px);f</p>
    box-shadow: var(--shadow-medium);  </div>
}      <div class="analytics-card">
                            <div class="card-header">
.action-card i {="card-icon"><i class="fas fa-graduation-cap"></i></div>
    font-size: 2.5rem;ue">
    color: #e64f21;s="card-number"><?= $analytics['pending_onboarding'] ?></h3>
    margin-bottom: 1rem;="card-change change-neutral">
    display: block;
}/div>
       </div>
.action-card h4 {
    color: #f8fafc;</p>
    font-weight: 700;>
    margin: 0 0 0.75rem 0;  <div class="analytics-card">
    font-size: 1.1rem;header">
}ard-icon"><i class="fas fa-star"></i></div>

.action-card p {<?= $analytics['avg_satisfaction'] ?></h3>
    color: #94a3b8;ange change-positive">
    font-size: 0.9rem;
    margin: 0;      </div>
    line-height: 1.5;
}
faction Score</p>
/* Modern Tables */
.modern-table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 16px;
    overflow: hidden;  <th>ID</th>
    box-shadow: var(--shadow-soft);
}

.modern-table th,
.modern-table td {  <th>Status</th>
    padding: 1.25rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
>
.modern-table th {
    background: rgba(255, 255, 255, 0.05);
    font-weight: 700;lspecialchars($staff['id']) ?></strong></td>
    color: #f8fafc;
    font-size: 0.85rem;      <div style="display: flex; align-items: center; gap: 0.75rem;">
    text-transform: uppercase;              <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
    letter-spacing: 1px;                      <?= strtoupper(substr($staff['name'], 0, 1)) ?>
    position: sticky;                      </div>
    top: 0;                                        <div>
    z-index: 10;   <div style="font-weight: 600; color: #f8fafc;"><?= htmlspecialchars($staff['name']) ?></div>
}le="font-size: 0.8rem; color: #94a3b8;"><?= htmlspecialchars($staff['email']) ?></div>
v>
.modern-table td {
    color: #cbd5e1;
    font-weight: 500;
}           <span style="padding: 0.3rem 0.6rem; background: rgba(99, 102, 241, 0.2); color: #6366f1; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">

.modern-table tr:hover {
    background: rgba(230, 79, 33, 0.05);   </td>
}          <td><?= htmlspecialchars($staff['department']) ?></td>
pecialchars($staff['role']) ?></td>
.modern-table tr:hover td {
    color: #f8fafc;     <span class="status-badge status-<?= strtolower($staff['status']) ?>">
}        <?= htmlspecialchars($staff['status']) ?>

/* Status Badges */
.status-badge {number_format($staff['salary']) ?></strong></td>
    padding: 0.4rem 0.9rem;
    border-radius: 20px;isplay: flex; gap: 0.5rem;">
    font-size: 0.75rem; class="btn btn-secondary" onclick="viewStaffDetails('<?= $staff['id'] ?>')" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
    font-weight: 700;           <i class="fas fa-eye"></i> View
    text-transform: uppercase;        </button>
    letter-spacing: 0.5px;         <button class="btn btn-primary" onclick="editStaff('<?= $staff['id'] ?>')" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
    display: inline-flex;
    align-items: center;        </button>
    gap: 0.5rem;
}

.status-active {dforeach; ?>
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;s="content-section">
    border: 1px solid rgba(251, 191, 36, 0.3);scroll-reveal">
}header">

.status-completed {></i></span>
    background: rgba(59, 130, 246, 0.2);ashboard
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);isplay: flex; gap: 1rem;">
}rimary" onclick="openModal('projectModal')">
lass="fas fa-plus"></i> New Project
.status-overdue {tton>
    background: rgba(239, 68, 68, 0.2);  <button class="btn btn-secondary" onclick="exportProjectData()">
    color: #ef4444;          <i class="fas fa-chart-line"></i> Analytics
    border: 1px solid rgba(239, 68, 68, 0.3);      </button>
}                    </div>

.status-approved {
    background: rgba(16, 185, 129, 0.2);e="margin-bottom: 2rem;">
    color: #10b981;s-card">
    border: 1px solid rgba(16, 185, 129, 0.3);eader">
}iv>
lue">
.status-denied {           <h3 class="card-number"><?= count($project_data) ?></h3>
    background: rgba(239, 68, 68, 0.2); change-positive">
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}/div>

.status-onboarding {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;iv class="analytics-card">
    border: 1px solid rgba(251, 191, 36, 0.3);      <div class="card-header">
}ard-icon"><i class="fas fa-pound-sign"></i></div>

.hr-content-section {number">£<?= number_format(array_sum(array_column($project_data, 'budget')) / 1000) ?>K</h3>
    display: none;change change-positive">
}

.hr-content-section.active {
    display: block;
}

.hr-btn {alytics-card">
    display: inline-flex;lass="card-header">
    align-items: center;percentage"></i></div>
    gap: 0.5rem;  <div class="card-value">
    padding: 0.75rem 1.5rem;number"><?= round(array_sum(array_column($project_data, 'progress')) / count($project_data)) ?>%</h3>
    border-radius: 8px;change change-positive">
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}alytics-card">
lass="card-header">
.hr-btn-primary {as fa-users"></i></div>
    background: var(--primary-color);  <div class="card-value">
    color: white;number"><?= array_sum(array_column($project_data, 'team_size')) ?></h3>
    box-shadow: 0 4px 12px rgba(230, 79, 33, 0.3);change change-positive">
}

.hr-btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(230, 79, 33, 0.4);
}

.hr-btn-secondary {m: 2rem;">
    background: transparent;foreach($project_data as $project): ?>
    color: var(--text-primary);lick="viewProject('<?= $project['id'] ?>')">
    border: 2px solid var(--border-color);; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
}; color: #e64f21;"></i>
e status-<?= strtolower(str_replace(' ', '', $project['status'])) ?>"><?= $project['status'] ?></span>
.hr-btn-secondary:hover {
    border-color: var(--primary-color);lchars($project['name']) ?></h4>
    color: var(--primary-color);?= htmlspecialchars($project['client']) ?></p>
}ckground: rgba(255,255,255,0.1); border-radius: 8px; height: 8px; margin-bottom: 1rem;">
tyle="background: var(--primary-gradient); height: 100%; border-radius: 8px; width: <?= $project['progress'] ?>%;"></div>
.logout-btn {
    position: fixed;space-between; align-items: center; font-size: 0.8rem;">
    top: 2rem;  <span><i class="fas fa-users"></i> <?= $project['team_size'] ?> members</span>
    right: 2rem;      <span><i class="fas fa-pound-sign"></i> £<?= number_format($project['budget']) ?></span>
    background: rgba(239, 68, 68, 0.9);        </div>
    color: white;
    padding: 0.75rem 1.5rem;ndforeach; ?>
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    z-index: 1000;
}ntent-section">
eal">
.logout-btn:hover {
    background: rgba(239, 68, 68, 1);ction-title">
    transform: translateY(-2px);ass="section-icon"><i class="fas fa-chart-line"></i></span>
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);al Command Center
}
display: flex; gap: 1rem;">
/* Comprehensive Tabbed Sections */
="fas fa-plus"></i> Add Expense
/* Staff Management Section */
section#staff-section {
    display: none;
}

.staff-overview {
    margin-bottom: 2rem;
}" style="margin-bottom: 2rem;">
ics-card">
.overview-cards {s="card-header">
    display: grid; class="card-icon"><i class="fas fa-chart-line"></i></div>
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;nancial_data['monthly_revenue']) ?></h3>
    margin-bottom: 2rem;ass="card-change change-positive">
}   <i class="fas fa-arrow-up"></i> +<?= $financial_data['quarterly_growth'] ?>% growth

.overview-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;cs-card">
    padding: 2rem;s="card-header">
    text-align: center;
    transition: var(--transition-spring); class="card-value">
}t($financial_data['monthly_expenses']) ?></h3>

.overview-card:hover {n budget
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}
>Monthly Expenses</p>
.card-icon {
    width: 48px;nalytics-card">
    height: 48px;class="card-header">
    margin: 0 auto 1rem;-icon"><i class="fas fa-percentage"></i></div>
    background: var(--gradient-primary);<div class="card-value">
    border-radius: 12px;        <h3 class="card-number"><?= $financial_data['profit_margin'] ?>%</h3>
    display: flex;              <div class="card-change change-positive">
    align-items: center;                      <i class="fas fa-arrow-up"></i> Healthy margin
    justify-content: center;                  </div>
    color: white;                                </div>
    font-size: 1.5rem;
    box-shadow: var(--shadow-glow);</p>
}
s-card">
.card-content {eader">
    text-align: center;
}lue">
           <h3 class="card-number">£<?= number_format($financial_data['cash_flow']) ?></h3>
.card-content h3 { change-positive">
    font-size: 2rem;
    font-weight: 800;
    margin: 0;/div>
    color: #f8fafc;
}

.card-content p {iv class="analytics-card">
    font-size: 0.9rem;      <div class="card-header">
    color: rgba(255, 255, 255, 0.7);ard-icon"><i class="fas fa-users"></i></div>
    margin: 0;
}number">£<?= number_format($financial_data['payroll_costs']) ?></h3>
change change-neutral">
/* Staff Table */al_data['payroll_costs'] / $financial_data['monthly_revenue']) * 100) ?>% of revenue
.staff-table-container {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    overflow: hidden;alytics-card">
}lass="card-header">
fa-file-invoice"></i></div>
.table-header {  <div class="card-value">
    padding: 1.5rem 2rem;number"><?= $financial_data['outstanding_invoices'] ?></h3>
    background: rgba(255, 255, 255, 0.05);change change-neutral">
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    margin: 0;
    color: #f8fafc;s: 1fr 1fr; gap: 2rem;">
    font-size: 1.2rem;tyle="background: rgba(255,255,255,0.03); border-radius: 16px; padding: 2rem;">
    font-weight: 700;c; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
}t-pie"></i> Revenue Breakdown

.table-filters {
    display: flex;
    gap: 1rem;pan>
}?= number_format($financial_data['monthly_revenue'] * 0.6) ?></span>

.table-filters select {tyle="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    padding: 0.5rem 1rem;  <span style="color: #cbd5e1;">Nexi Digital</span>
    border-radius: 8px;nt-weight: 600;">£<?= number_format($financial_data['monthly_revenue'] * 0.25) ?></span>
    border: 1px solid rgba(255, 255, 255, 0.2);  </div>
    background: rgba(255, 255, 255, 0.03); flex; justify-content: space-between; align-items: center;">
    color: #f8fafc;r: #cbd5e1;">Nexi Consulting</span>
    font-size: 0.9rem;number_format($financial_data['monthly_revenue'] * 0.15) ?></span>
    appearance: none;
    background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"%3E%3Cpath fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" d="M7 10l5 5 5-5H7z"/%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
}ound: rgba(255,255,255,0.03); border-radius: 16px; padding: 2rem;">
"color: #f8fafc; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
.responsive-table { class="fas fa-exclamation-triangle"></i> Action Required
    overflow-x: auto;
}iv style="space-y: 1rem;">
      <div style="margin-bottom: 1rem;">
.data-table {                <span style="color: #ef4444; font-weight: 600;"><?= $financial_data['overdue_payments'] ?> overdue payments</span>
    width: 100%;.8rem; margin: 0;">Total: £<?= number_format($financial_data['overdue_payments'] * 2850) ?></p>
    border-collapse: collapse;
}

.data-table th,n required</p>
.data-table td {
    padding: 1rem 1.5rem;iv>
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.data-table th {
    background: rgba(255, 255, 255, 0.05);
    font-weight: 700;
    color: #f8fafc;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}tions-section" class="content-section">
="section-card scroll-reveal">
.data-table td {iv class="section-header">
    color: #cbd5e1;  <h2 class="section-title">
    font-weight: 500;                        <span class="section-icon"><i class="fas fa-cogs"></i></span>
}Center

.data-table tr:hover {onclick="openModal('operationModal')">
    background: rgba(230, 79, 33, 0.05);s"></i> New Operation
}

.data-table tr:hover td {
    color: #f8fafc;
}

/* Project Portfolio Section */rem; display: flex; align-items: center; gap: 0.5rem;">
#projects-section {ass="fas fa-calendar-alt"></i> Time Off Management
    display: none;
}
d>
.project-stats {      <tr>
    display: grid;              <th>Employee</th>
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));st Date</th>
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;foreach($time_off_requests as $request): ?>
    transition: var(--transition-spring);r>
}
          <div style="font-weight: 600; color: #f8fafc;"><?= htmlspecialchars($request['employee']) ?></div>
.stat-card:hover {font-size: 0.8rem; color: #94a3b8;"><?= htmlspecialchars($request['employee_id']) ?></div>
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);?></td>
}

.stat-icon {est['end_date'] ? ' - ' . date('M j, Y', strtotime($request['end_date'])) : ', ' . date('Y', strtotime($request['start_date'])) ?>
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;  <span style="padding: 0.3rem 0.6rem; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border-radius: 8px; font-size: 0.75rem; font-weight: 600;">
    background: var(--gradient-primary);          <?= htmlspecialchars($request['type']) ?>
    border-radius: 12px;
    display: flex;      </td>
    align-items: center;$request['days'] ?></strong></td>
    justify-content: center;color: #ef4444;">£<?= number_format($request['cost_impact']) ?></span></td>
    color: white;
    font-size: 1.5rem;tus-badge status-<?= strtolower($request['status']) ?>">
    box-shadow: var(--shadow-glow);
}

.stat-content {
    text-align: center;  <?php if($request['status'] === 'Pending'): ?>
}          <div style="display: flex; gap: 0.5rem;">
btn-success" onclick="approveTimeOff('<?= $request['id'] ?>')" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
.stat-content h3 {                      <i class="fas fa-check"></i> Approve
    font-size: 2rem;tton>
    font-weight: 800;on class="btn btn-danger" onclick="denyTimeOff('<?= $request['id'] ?>')" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
    margin: 0;
    color: #f8fafc;>
}

.stat-content p {ck="viewTimeOffDetails('<?= $request['id'] ?>')" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">
    font-size: 0.9rem;      <i class="fas fa-eye"></i> View
    color: rgba(255, 255, 255, 0.7);      </button>
    margin: 0;      <?php endif; ?>
}
  </tr>
/* Projects Grid */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.project-card {#f8fafc; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
    background: var(--glass-bg);fas fa-graduation-cap"></i> Training & Compliance
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;php foreach($training_data as $training): ?>
    padding: 2rem;rgba(255,255,255,0.03); border-radius: 16px; padding: 1.5rem;">
    transition: var(--transition-spring);flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    cursor: pointer;$training['course']) ?></h4>
}compliance']): ?>
 border-radius: 4px; font-size: 0.7rem; font-weight: 600;">REQUIRED</span>
.project-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);="background: rgba(255,255,255,0.1); border-radius: 8px; height: 8px; margin-bottom: 1rem;">
}iv style="background: var(--primary-gradient); height: 100%; border-radius: 8px; width: <?= ($training['completed'] / $training['total']) * 100 ?>%;"></div>
div>
.project-header {: space-between; align-items: center; font-size: 0.8rem;">
    display: flex;      <span style="color: #cbd5e1;"><?= $training['completed'] ?>/<?= $training['total'] ?> completed</span>
    justify-content: space-between;          <span style="color: #94a3b8;">Due: <?= date('M j', strtotime($training['deadline'])) ?></span>
    align-items: center;            </div>
    margin-bottom: 1rem;
}

.project-header h3 {
    margin: 0;
    color: #f8fafc;
    font-size: 1.2rem;
    font-weight: 700;
}
" class="content-section">
.project-header .status-badge {
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-size: 0.75rem;ection-icon"><i class="fas fa-chart-bar"></i></span>
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}fa-download"></i> Export Analytics

.project-details {
    margin-bottom: 1rem;ss="section-content">
    color: #cbd5e1;
    font-size: 0.9rem;
} Coming Soon</h3>
hensive business intelligence dashboard with AI-powered insights, predictive analytics, and real-time reporting across all three companies.</p>
.project-progress {onclick="requestAnalytics()" style="margin-top: 1rem;">
    margin-bottom: 1rem;n Ready
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.progress-bar {
    height: 8px;"hr-btn hr-btn-primary" onclick="openAddStaffModal()">
    background: rgba(255, 255, 255, 0.1);fas fa-plus"></i> Add New Staff
    border-radius: 10px;
    overflow: hidden;
}lass="hr-section-content">
  <table class="hr-table">
.progress-fill {      <thead>
    height: 100%;                            <tr>
    background: var(--gradient-success);ID</th>
    border-radius: 10px;
}
>
/* Financial Overview Section */>
#finance-section {
    display: none;
}/thead>

.financial-summary {f): ?>
    display: grid;tr>
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));          <td><?= htmlspecialchars($staff['id']) ?></td>
    gap: 1.5rem;pecialchars($staff['name']) ?></td>
    margin-bottom: 2rem;            <td><?= htmlspecialchars($staff['department']) ?></td>
}ialchars($staff['role']) ?></td>

.finance-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);       </span>
    border: 1px solid var(--glass-border);
    border-radius: 16px;>
    padding: 2rem;<a href="#" class="hr-btn hr-btn-secondary" onclick="viewStaffDetails('<?= $staff['id'] ?>')">View</a>
    transition: var(--transition-spring);
}

.finance-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.finance-icon {
    width: 48px;
    height: 48px;" class="hr-content-section">
    margin: 0 auto 1rem;
    background: var(--gradient-primary);on-header">
    border-radius: 12px;n-title">Employee Onboarding</h2>
    display: flex;
    align-items: center;
    justify-content: center;--text-secondary); margin-bottom: 2rem;">
    color: white;documents and training are completed.
    font-size: 1.5rem;
    box-shadow: var(--shadow-glow);
}
tion-card">
.finance-content {s fa-file-contract"></i>
    text-align: center;
}

.finance-content h3 {tion-card">
    font-size: 2rem;
    font-weight: 800;
    margin: 0;d monitor completion of required training</p>
    color: #f8fafc;
}
ield-alt"></i>
.finance-content p {Setup</h4>
    font-size: 0.9rem; 2FA and security protocols</p>
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

/* Financial Breakdown */s and completion</p>
.financial-breakdown {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.breakdown-section {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);-title">Time Off Management</h2>
    border-radius: 16px;="hr-btn hr-btn-primary" onclick="openTimeOffModal()">
    padding: 2rem;New Request
}

.revenue-bars {="hr-section-content">
    margin-top: 1rem;                    <p style="color: var(--text-secondary); margin-bottom: 2rem;">
}ests, approvals, and tracking. Monitor leave balances and upcoming absences.

.revenue-item {
    display: flex;
    justify-content: space-between;d>
    align-items: center;
    margin-bottom: 0.5rem;
}

.revenue-bar {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    flex: 1;
    margin-left: 1rem;
}ach($time_off_requests as $index => $request): ?>

/* Operations Center Section */
#operations-section {
    display: none;
}  <?= date('M j', strtotime($request['start_date'])) ?>
['start_date'] !== $request['end_date'] ? ' - ' . date('M j, Y', strtotime($request['end_date'])) : ', ' . date('Y', strtotime($request['start_date'])) ?>
.operations-section {  </td>
    margin-bottom: 2rem;      <td><?= htmlspecialchars($request['type']) ?></td>
}          <td><?= $request['days'] ?> day<?= $request['days'] > 1 ? 's' : '' ?></td>
              <td>
.request-card {                  <span class="status-badge status-<?= strtolower($request['status']) ?>">
    background: var(--glass-bg);                                        <?= htmlspecialchars($request['status']) ?>
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;($request['status'] === 'Pending'): ?>
    margin-bottom: 1rem;f="#" class="hr-btn hr-btn-primary" onclick="approveTimeOff(<?= $index ?>)" style="margin-right: 0.5rem;">Approve</a>
    transition: var(--transition-spring);k="denyTimeOff(<?= $index ?>)">Deny</a>
}
               <a href="#" class="hr-btn hr-btn-secondary" onclick="viewTimeOffDetails(<?= $index ?>)">View</a>
.request-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);/tr>
}      <?php endforeach; ?>

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
 class="hr-content-section">
.employee-info {-section">
    display: flex;lass="hr-section-header">
    align-items: center;  <h2 class="hr-section-title">Reports & Analytics</h2>
    gap: 0.75rem;div>
}                <div class="hr-section-content">
         <p style="color: var(--text-secondary); margin-bottom: 2rem;">
.employee-avatar {prehensive reports on staff performance, attendance, and organizational metrics.
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-gradient);
    display: flex;    <i class="fas fa-users"></i>
    align-items: center;      <h4>Staff Overview</h4>
    justify-content: center;rectory and status report</p>
    color: white;
    font-weight: 700;ass="hr-action-card">
    font-size: 1rem;lass="fas fa-calendar-check"></i>
    box-shadow: var(--shadow-medium);</h4>
}ce and time off patterns</p>

.request-details {-card">
    margin-bottom: 1rem;art-line"></i>
    color: #cbd5e1;ics</h4>
    font-size: 0.9rem;alyze staff performance and productivity</p>
}
ass="hr-action-card">
.request-actions {
    display: flex;Payroll Summary</h4>
    gap: 0.5rem;
}

/* Training & Compliance */
.training-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.training-item {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);->
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;s="modal">
    transition: var(--transition-spring);content">
}="modal-header">
2 class="modal-title">🚀 Add New Team Member</h2>
.training-item:hover {            <span class="close" onclick="closeModal('addStaffModal')">&times;</span>
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}d; grid-template-columns: 1fr 1fr; gap: 1rem;">

.training-info {
    margin-bottom: 1rem;nput type="text" class="form-input" name="name" required placeholder="Enter full name">
}

.compliance-badge {
    background: #ef4444;ut type="email" class="form-input" name="email" required placeholder="name@company.com">
    color: white;v>
    padding: 0.2rem 0.5rem;
    border-radius: 4px;-columns: 1fr 1fr; gap: 1rem;">
    font-size: 0.7rem;
    font-weight: 600;er</label>
}aceholder="+44 7XXX XXXXXX">

/* Business Intelligence Section */
#analytics-section {
    display: none;"company" required>
}
n value="Nexi Hub">Nexi Hub</option>
.analytics-dashboard {>Nexi Digital</option>
    margin-bottom: 2rem;Consulting</option>
}

.analytics-widget {
    background: var(--glass-bg);-columns: 1fr 1fr; gap: 1rem;">
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);</label>
    border-radius: 16px;
    padding: 2rem;n value="">Select Department</option>
    margin-bottom: 2rem;ption value="Executive">Executive</option>
}  <option value="Technology">Technology</option>
      <option value="Design">Design</option>
.metrics-grid {          <option value="Marketing">Marketing</option>
    display: grid;                        <option value="Sales">Sales</option>
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));Resources</option>
    gap: 1rem;e="Finance">Finance</option>
    margin-bottom: 1.5rem;ns">Operations</option>
}

.metric {
    background: rgba(255, 255, 255, 0.1);el class="form-label">Job Title</label>
    border-radius: 10px;nput type="text" class="form-input" name="role" required placeholder="Senior Developer">
    padding: 1rem;
    text-align: center;
}
ss="form-group">
.metric-value {<label class="form-label">Start Date</label>
    font-size: 1.5rem;="form-input" name="hire_date" required>
    font-weight: 700;
    color: #f8fafc;-group">
    margin-bottom: 0.5rem;ual Salary</label>
}input" name="salary" required placeholder="50000">

.metric-label {
    font-size: 0.9rem;>Contract Type</label>
    color: rgba(255, 255, 255, 0.7); name="contract_type" required>
}">Permanent</option>
alue="Contract">Contract</option>
.company-metrics {value="Internship">Internship</option>
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
)">Cancel</button>
.company-item {" class="btn btn-primary">Add Team Member</button>
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
}

.ai-insights {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;
    padding: 1.5rem;️ Time Off Request</h2>
    display: grid;ick="closeModal('timeOffModal')">&times;</span>
    gap: 1.5rem;
}

/* Responsive Design */</label>
@media (max-width: 768px) {
    .header-content {e</option>
        flex-direction: column;aff_members as $staff): ?>
        gap: 1rem;alue="<?= htmlspecialchars($staff['name']) ?>"><?= htmlspecialchars($staff['name']) ?> (<?= htmlspecialchars($staff['id']) ?>)</option>
    }
    
    .quick-nav {
        order: 3;="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        width: 100%;iv class="form-group">
        justify-content: center;      <label class="form-label">Start Date</label>
    }                    <input type="date" class="form-input" name="start_date" required>
    
    .dashboard-container {
        padding: 1rem;rm-label">End Date</label>
    }orm-input" name="end_date" required>
    
    .welcome-hero {
        padding: 2rem;late-columns: 1fr 1fr; gap: 1rem;">
        text-align: center;
    }
    ect class="form-select" name="type" required>
    .welcome-title {    <option value="">Select Type</option>
        font-size: 2rem;al Leave</option>
    }sonal Day</option>
    /option>
    .analytics-grid {Conference/Training</option>
        grid-template-columns: 1fr;
    }n value="Other">Other</option>
    
    .action-grid {
        grid-template-columns: 1fr;
    }
    ass="form-select" name="cover" required>
    .tab-navigation {overage arranged</option>
        flex-direction: column;ge</option>
        align-items: center;
    }
    
    .modern-table {
        font-size: 0.8rem;
    }="reason" placeholder="Brief description of the time off request..." required></textarea>
    
    .modern-table th,actions">
    .modern-table td {pe="button" class="btn btn-secondary" onclick="closeModal('timeOffModal')">Cancel</button>
        padding: 0.75rem;n type="submit" class="btn btn-primary">Submit Request</button>
    }
}>

/* Loading Animations */
.loading {
    opacity: 0;t Modal -->
    animation: fadeIn 0.5s ease forwards;d="projectModal" class="modal">
}    <div class="modal-content">

@keyframes fadeIn {            <h2 class="modal-title">🚀 New Project</h2>
    to {"close" onclick="closeModal('projectModal')">&times;</span>
        opacity: 1;
    }onsubmit="createProject(event)">
}rid; grid-template-columns: 1fr 1fr; gap: 1rem;">

/* Scroll Animations */
.scroll-reveal {      <input type="text" class="form-input" name="project_name" required placeholder="E-commerce Platform">
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.6s ease;abel">Client Name</label>
}"client_name" required placeholder="Company Ltd">

.scroll-reveal.revealed {
    opacity: 1;rid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
    transform: translateY(0);
}
</style>elect class="form-select" name="company" required>
      <option value="">Select Company</option>
<div class="executive-command-center" id="commandCenter">
    <!-- Advanced Navigation Header -->exi Digital">Nexi Digital</option>
    <nav class="command-nav">lting</option>
        <div class="nav-brand">
            <div class="logo">NH</div>
            <h1>Nexi Group Command Center</h1>
            <span class="nav-subtitle">Multi-Company Executive Dashboard</span>el>
        </div>" required placeholder="50000">
        
        <div class="nav-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>ed>
                <input type="text" placeholder="Search anything..." id="globalSearch">
            </div>
            lass="form-group">
            <div class="nav-actions">
                <div class="notification-hub" onclick="toggleNotifications()">xtarea" name="description" placeholder="Detailed project requirements and scope..." required></textarea>
                    <i class="fas fa-bell"></i>
                    <span class="notification-count"><?= $current_user['notifications'] ?></span>
                    <div class="notification-pulse"></div>lick="closeModal('projectModal')">Cancel</button>
                </div> Project</button>
                
                <div class="quick-actions-menu" onclick="toggleQuickActions()">
                    <i class="fas fa-plus"></i>
                </div>
                
                <div class="user-profile-menu" onclick="toggleUserProfile()">
                    <div class="profile-avatar">
                        <img src="<?= $current_user['avatar'] ?>" alt="Profile" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">" style="max-width: 800px;">
                        <div class="avatar-fallback" style="display:none;"><?= strtoupper(substr($current_user['full_name'], 0, 1)) ?></div>dal-header">
                    </div>taff Profile</h2>
                    <div class="profile-info">lsModal')">&times;</span>
                        <span class="profile-name"><?= htmlspecialchars($current_user['full_name']) ?></span>
                        <span class="profile-role"><?= htmlspecialchars($current_user['role']) ?></span>DetailsContent">
                    </div>ynamic content will be loaded here -->
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Advanced Animation -->
    <section class="hero-section">
        <div class="hero-content animate__animated animate__fadeInUp">"modal-title">💰 Add Expense</h2>
            <h1 class="hero-title">"closeModal('expenseModal')">&times;</span>
                Welcome Back, <?= htmlspecialchars(explode(' ', $current_user['full_name'])[0]) ?>!
            </h1>
            <p class="hero-subtitle">r; gap: 1rem;">
                Your unified command center for managing <?= count($current_user['company_access']) ?> companies, 
                <?= $analytics['total_staff'] ?> team members, and £<?= number_format($analytics['monthly_revenue'] / 1000) ?>K monthly revenue.
            </p>lass="form-select" name="expense_type" required>
              <option value="">Select Type</option>
            <div class="hero-stats">      <option value="Office Supplies">Office Supplies</option>
                <div class="hero-stat" data-count="<?= $analytics['total_staff'] ?>">Travel">Travel</option>
                    <div class="hero-stat-icon">
                        <i class="fas fa-users"></i>
                    </div>      <option value="Marketing">Marketing</option>
                    <div class="hero-stat-value">0</div>         <option value="Utilities">Utilities</option>
                    <div class="hero-stat-label">Team Members</div>              <option value="Other">Other</option>
                </div>              </select>
                                </div>
                <div class="hero-stat" data-count="<?= $analytics['active_projects'] ?>">ass="form-group">
                    <div class="hero-stat-icon">m-label">Amount (£)</label>
                        <i class="fas fa-project-diagram"></i>="number" step="0.01" class="form-input" name="amount" required placeholder="250.00">
                    </div>
                    <div class="hero-stat-value">0</div>
                    <div class="hero-stat-label">Active Projects</div>
                </div>  <div class="form-group">
                
                <div class="hero-stat" data-count="<?= round($analytics['monthly_revenue'] / 1000) ?>">orm-select" name="company" required>
                    <div class="hero-stat-icon">ption>
                        <i class="fas fa-chart-line"></i>
                    </div>ital</option>
                    <div class="hero-stat-value">0</div>nsulting</option>
                    <div class="hero-stat-label">Revenue (£K)</div>
                </div>
                s="form-group">
                <div class="hero-stat" data-count="<?= round($analytics['system_uptime'], 1) ?>">  <label class="form-label">Date</label>
                    <div class="hero-stat-icon">red>
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="hero-stat-value">0</div>
                    <div class="hero-stat-label">System Uptime (%)</div> class="form-label">Description</label>
                </div>xtarea" name="description" placeholder="Detailed description of the expense..." required></textarea>
            </div>
        </div>
    </section>n type="button" class="btn btn-secondary" onclick="closeModal('expenseModal')">Cancel</button>
utton type="submit" class="btn btn-primary">Add Expense</button>
    <!-- Advanced Analytics Grid -->
    <section class="analytics-grid">
        <!-- Staff Analytics -->
        <div class="analytics-card" onclick="navigateToSection('staff')" data-aos="fade-up" data-aos-delay="100">
            <div class="analytics-card-header">
                <h3 class="analytics-card-title">Workforce Intelligence</h3>
                <div class="analytics-card-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
            </div>
            <div class="analytics-value"><?= $analytics['total_staff'] ?></div>
            <div class="analytics-change positive">
                <i class="fas fa-arrow-up"></i> +<?= $analytics['recent_hires'] ?> new hires
            </div>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?= ($analytics['active_staff'] / $analytics['total_staff']) * 100 ?>%"></div>
            </div>
            <p class="analytics-subtitle"><?= $analytics['active_staff'] ?> active • <?= $analytics['remote_workers'] ?> remote</p>
        </div>n_encode($financial_data) ?>;
?= json_encode($training_data) ?>;
        <!-- Revenue Analytics -->
        <div class="analytics-card" onclick="navigateToSection('finance')" data-aos="fade-up" data-aos-delay="200">
            <div class="analytics-card-header">
                <h3 class="analytics-card-title">Revenue Performance</h3>
                <div class="analytics-card-icon"> = document.querySelectorAll('.content-section');
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="analytics-value">£<?= number_format($analytics['monthly_revenue'] / 1000) ?>K</div>
            <div class="analytics-change positive">ctive class from all navigation items
                <i class="fas fa-arrow-up"></i> +<?= round((($analytics['monthly_revenue'] - 450000) / 450000) * 100, 1) ?>% vs last monthnavItems = document.querySelectorAll('.nav-pill, .tab-item');
            </div>vItems.forEach(item => {
            <div class="progress-container">        item.classList.remove('active');
                <div class="progress-bar" style="width: 78%"></div>
            </div>
            <p class="analytics-subtitle">Profit margin: <?= $analytics['profit_margin'] ?>%</p>th animation
        </div>nt.getElementById(sectionName + '-section');

        <!-- Project Analytics -->
        <div class="analytics-card" onclick="navigateToSection('projects')" data-aos="fade-up" data-aos-delay="300">
            <div class="analytics-card-header">
                <h3 class="analytics-card-title">Project Portfolio</h3>
                <div class="analytics-card-icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="analytics-value"><?= $analytics['active_projects'] ?></div>
            <div class="analytics-change positive">
                <i class="fas fa-arrow-up"></i> +<?= $analytics['projects_this_month'] ?> launched this month
            </div>
            <div class="progress-container">
                <div class="progress-bar" style="width: 92%"></div>
            </div>
            <p class="analytics-subtitle">Success rate: <?= $analytics['project_success_rate'] ?>%</p>
        </div>

        <!-- Security Score -->
        <div class="analytics-card" onclick="navigateToSection('operations')" data-aos="fade-up" data-aos-delay="400">
            <div class="analytics-card-header">
                <h3 class="analytics-card-title">Security Status</h3> 'block';
                <div class="analytics-card-icon">style.overflow = 'hidden';
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <div class="analytics-value"><?= $analytics['security_score'] ?>%</div>tInput = modal.querySelector('input, select, textarea');
            <div class="analytics-change positive">ocus();
                <i class="fas fa-arrow-up"></i> Excellent rating
            </div>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?= $analytics['security_score'] ?>%"></div>
            </div>
            <p class="analytics-subtitle">Last scan: 2 hours ago</p>
        </div>
e.display = 'none';
        <!-- System Performance -->'auto';
        <div class="analytics-card" onclick="navigateToSection('operations')" data-aos="fade-up" data-aos-delay="500">
            <div class="analytics-card-header">
                <h3 class="analytics-card-title">System Health</h3>
                <div class="analytics-card-icon">ification System
                    <i class="fas fa-heartbeat"></i>howNotification(message, type = 'success', duration = 5000) {
                </div>nst notification = document.getElementById('notification');
            </div>    
            <div class="analytics-value"><?= $analytics['system_uptime'] ?>%</div>
            <div class="analytics-change positive">
                <i class="fas fa-arrow-up"></i> <?= $analytics['server_response_time'] ?>s responseap: 0.75rem;">
            </div>e === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?= $analytics['system_uptime'] ?>%"></div>toUpperCase() + type.slice(1)}</div>
            </div>  <div style="opacity: 0.9; font-size: 0.9rem;">${message}</div>
            <p class="analytics-subtitle">All systems operational</p>
        </div>

        <!-- Client Satisfaction -->
        <div class="analytics-card" onclick="navigateToSection('analytics')" data-aos="fade-up" data-aos-delay="600">tification.className = `notification ${type} show`;
            <div class="analytics-card-header">    
                <h3 class="analytics-card-title">Client Satisfaction</h3>
                <div class="analytics-card-icon">
                    <i class="fas fa-smile"></i>remove('show');
                </div>
            </div>
            <div class="analytics-value"><?= $analytics['client_satisfaction'] ?>/5</div>
            <div class="analytics-change positive">ve Staff Management
                <i class="fas fa-arrow-up"></i> +0.2 this quarter
            </div>
            <div class="progress-container">target);
                <div class="progress-bar" style="width: <?= ($analytics['client_satisfaction'] / 5) * 100 ?>%"></div>
            </div>
            <p class="analytics-subtitle">Based on 47 reviews</p>
        </div>l' ? 'ND' : 'NC';
    </section>m() * 900) + 100).padStart(3, '0');

    <!-- Quick Actions Enhanced -->
    <section class="quick-actions">
        <div class="action-card" onclick="openModal('addStaffModal')" data-aos="zoom-in" data-aos-delay="100">
            <div class="action-icon">
                <i class="fas fa-user-plus"></i>('company'),
            </div>rmData.get('department'),
            <h4 class="action-title">Add Staff Member</h4>
            <p class="action-description">Onboard a new team member across any company</p>
        </div>
mData.get('hire_date'),
        <div class="action-card" onclick="openModal('newProjectModal')" data-aos="zoom-in" data-aos-delay="200">rseInt(formData.get('salary')),
            <div class="action-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h4 class="action-title">Launch Project</h4>
            <p class="action-description">Start a new client project or internal initiative</p>
        </div>
{newStaff.company} with ID ${staffId}! Welcome to the team!`, 'success');
        <div class="action-card" onclick="navigateToSection('time-off')" data-aos="zoom-in" data-aos-delay="300">
            <div class="action-icon">
                <i class="fas fa-calendar-check"></i>Staff);
            </div>
            <h4 class="action-title">Approve Time Off</h4>
            <p class="action-description">Review and approve pending leave requests</p>
        </div>();

        <div class="action-card" onclick="openModal('expenseModal')" data-aos="zoom-in" data-aos-delay="400">it's active
            <div class="action-icon">
                <i class="fas fa-receipt"></i>
            </div>on.reload();
            <h4 class="action-title">Process Expenses</h4>
            <p class="action-description">Review and approve expense claims</p>
        </div>

        <div class="action-card" onclick="openModal('clientModal')" data-aos="zoom-in" data-aos-delay="500">affDetails(staffId) {
            <div class="action-icon">staff = staffData.find(s => s.id === staffId);
                <i class="fas fa-handshake"></i> (!staff) return;
            </div>    
            <h4 class="action-title">Onboard Client</h4>
            <p class="action-description">Add new client and setup project pipeline</p>
        </div>            <div style="display: flex; align-items: center; gap: 1.5rem; padding: 1.5rem; background: var(--primary-gradient); border-radius: 16px; color: white;">
tyle="width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800;">
        <div class="action-card" onclick="generateReport()" data-aos="zoom-in" data-aos-delay="600">
            <div class="action-icon">
                <i class="fas fa-chart-bar"></i>            <div>
            </div>                    <h3 style="margin: 0; font-size: 1.5rem;">${staff.name}</h3>
            <h4 class="action-title">Generate Reports</h4>            <p style="margin: 0; opacity: 0.9;">${staff.role} at ${staff.company}</p>
            <p class="action-description">Create comprehensive business analytics</p>rgin: 0; opacity: 0.8; font-size: 0.9rem;">${staff.id} • ${staff.status}</p>
        </div>
    </section>

    <!-- Live Activity Feed Enhanced -->ns: 1fr 1fr; gap: 2rem;">
    <section class="activity-feed" data-aos="fade-up">
        <div class="activity-header">                    <h4 style="color: #f8fafc; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem;">Contact Information</h4>
            <h3><i class="fas fa-pulse"></i> Live Activity Stream</h3>le="space-y: 0.75rem;">
            <div class="activity-controls">g style="color: #cbd5e1;">Email:</strong> <span style="color: #94a3b8;">${staff.email}</span></div>
                <button class="activity-filter active" data-filter="all">All</button>e="color: #cbd5e1;">Phone:</strong> <span style="color: #94a3b8;">${staff.phone}</span></div>
                <button class="activity-filter" data-filter="staff">Staff</button>ment:</strong> <span style="color: #94a3b8;">${staff.department}</span></div>
                <button class="activity-filter" data-filter="projects">Projects</button>
                <button class="activity-filter" data-filter="finance">Finance</button>
            </div>         
        </div>            <div>
        n-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.5rem;">Employment Details</h4>
        <div class="activity-list" id="activityList">
            <?php foreach($recent_activities as $activity): ?>strong style="color: #cbd5e1;">Hire Date:</strong> <span style="color: #94a3b8;">${new Date(staff.hire_date).toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' })}</span></div>
            <div class="activity-item" data-type="<?= strtolower($activity['type']) ?>">le="color: #cbd5e1;">Salary:</strong> <span style="color: #94a3b8;">£${staff.salary.toLocaleString()}/year</span></div>
                <div class="activity-content">                 <div><strong style="color: #cbd5e1;">Contract:</strong> <span style="color: #94a3b8;">${staff.contract_type}</span></div>
                    <div class="activity-avatar">                    <div><strong style="color: #cbd5e1;">Security Level:</strong> <span style="color: #94a3b8;">${staff.security_clearance}</span></div>
                        <?= strtoupper(substr($activity['employee'], 0, 1)) ?>
                    </div>
                    <div class="activity-details">
                        <div class="activity-employee"><?= htmlspecialchars($activity['employee']) ?></div>
                        <div class="activity-action"><?= htmlspecialchars($activity['action']) ?></div>    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                    </div>ndary" onclick="generateStaffReport('${staff.id}')">
                    <div class="activity-meta">ss="fas fa-file-alt"></i> Generate Report
                        <div class="activity-time"><?= $activity['time'] ?></div>
                        <div class="activity-status <?= strtolower($activity['status']) ?>"><?= $activity['status'] ?></div><button class="btn btn-primary" onclick="editStaff('${staff.id}')">
                    </div>               <i class="fas fa-edit"></i> Edit Profile
                </div>            </button>
            </div>secondary" onclick="closeModal('staffDetailsModal')">Close</button>
            <?php endforeach; ?>
        </div>
    </section>;

    <!-- Comprehensive Tabbed Sections -->tById('staffDetailsContent').innerHTML = content;

    <!-- Staff Management Section -->
    <section id="staff-section" class="content-section" style="display: none;">
        <div class="section-header">Id) {
            <h2><i class="fas fa-users-cog"></i> Workforce Management</h2> functionality for ${staffId} will be available soon! Advanced staff management tools are being developed.`, 'info');
            <div class="section-actions">
                <button class="btn btn-primary" onclick="openModal('addStaffModal')">
                    <i class="fas fa-user-plus"></i> Add Staff Member
                </button>
                <button class="btn btn-secondary" onclick="exportStaffData()"> submitTimeOff(event) {
                    <i class="fas fa-download"></i> Export Data
                </button>ormData(event.target);
            </div>
        </div>art_date'));
e = new Date(formData.get('end_date'));
        <div class="staff-overview">onst days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            <div class="overview-cards">   
                <div class="overview-card">    const request = {
                    <div class="card-icon"><i class="fas fa-users"></i></div>.floor(Math.random() * 900) + 100).padStart(3, '0'),
                    <div class="card-content">
                        <h3><?= $analytics['total_staff'] ?></h3>te: formData.get('start_date'),
                        <p>Total Staff</p>ate'),
                    </div>
                </div>   days: days,
                <div class="overview-card">       status: 'Pending',
                    <div class="card-icon"><i class="fas fa-user-check"></i></div>        reason: formData.get('reason'),
                    <div class="card-content">a.get('cover') === 'true'
                        <h3><?= $analytics['active_staff'] ?></h3>
                        <p>Active Staff</p>
                    </div>showNotification(`🏖️ Time off request for ${request.employee} (${days} days) has been submitted successfully!`, 'success');
                </div>
                <div class="overview-card">;
                    <div class="card-icon"><i class="fas fa-home"></i></div>
                    <div class="card-content">
                        <h3><?= $analytics['remote_workers'] ?></h3> => {
                        <p>Remote Workers</p>
                    </div>
                </div>
                <div class="overview-card">
                    <div class="card-icon"><i class="fas fa-building"></i></div>
                    <div class="card-content">
                        <h3><?= $analytics['office_workers'] ?></h3>
                        <p>In Office</p>const request = timeOffData.find(r => r.id === requestId);
                    </div> {
                </div>on(`✅ Time off request for ${request.employee} has been approved! Notification sent to employee.`, 'success');
            </div>00);
        </div>

        <div class="staff-table-container">
            <div class="table-header">
                <h3>Staff Directory</h3>fData.find(r => r.id === requestId);
                <div class="table-filters">
                    <select id="companyFilter" onchange="filterStaff()">reason for denial:');
                        <option value="">All Companies</option>    showNotification(`❌ Time off request for ${request.employee} has been denied. Reason: ${reason || 'No reason provided'}`, 'error');
                        <option value="Nexi Hub">Nexi Hub</option>on.reload(), 2000);
                        <option value="Nexi Digital">Nexi Digital</option>
                        <option value="Nexi Consulting">Nexi Consulting</option>
                    </select>
                    <select id="departmentFilter" onchange="filterStaff()">roject Management
                        <option value="">All Departments</option>nt) {
                        <option value="Executive">Executive</option>lt();
                        <option value="Technology">Technology</option> new FormData(event.target);
                        <option value="Design">Design</option>
                        <option value="Digital Marketing">Digital Marketing</option>floor(Math.random() * 900) + 100).padStart(3, '0');
                        <option value="Business Consulting">Business Consulting</option>
                    </select>
                </div>
            </div>ame'),
            
            <div class="responsive-table">
                <table class="data-table" id="staffTable">
                    <thead>t('deadline'),
                        <tr>scription'),
                            <th>ID</th>  status: 'Planning',
                            <th>Name</th>    progress: 0,
                            <th>Company</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Status</th> Project "${project.name}" has been created successfully! Project ID: ${projectId}`, 'success');
                            <th>Salary</th>
                            <th>Actions</th>closeModal('projectModal');
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($staff_members as $staff): ?>    if (document.getElementById('projects-section').classList.contains('active')) {
                        <tr data-company="<?= $staff['company'] ?>" data-department="<?= $staff['department'] ?>">
                            <td><span class="staff-id"><?= $staff['id'] ?></span></td>
                            <td>
                                <div class="staff-info">
                                    <div class="staff-avatar"><?= strtoupper(substr($staff['name'], 0, 1)) ?></div>
                                    <div>Project(projectId) {
                                        <strong><?= htmlspecialchars($staff['name']) ?></strong>   const project = projectData.find(p => p.id === projectId);
                                        <br><small><?= htmlspecialchars($staff['email']) ?></small>    if (project) {
                                    </div> project details for "${project.name}" - Advanced project dashboard coming soon!`, 'info');
                                </div>
                            </td>
                            <td><span class="company-tag"><?= $staff['company'] ?></span></td>
                            <td><?= $staff['department'] ?></td>nt
                            <td><?= $staff['role'] ?></td>
                            <td><span class="status-badge status-<?= strtolower($staff['status']) ?>"><?= $staff['status'] ?></span></td>
                            <td>£<?= number_format($staff['salary']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-small btn-primary" onclick="viewStaffDetails('<?= $staff['id'] ?>')">a.get('expense_type'),
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-small btn-secondary" onclick="editStaff('<?= $staff['id'] ?>')">
                                        <i class="fas fa-edit"></i>ormData.get('description')
                                    </button>
                                </div>
                            </td>e} has been recorded for ${expense.company}!`, 'success');
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>enerating comprehensive financial report across all three companies... This will be ready in your downloads folder shortly!`, 'info');

    <!-- Project Portfolio Section -->) => {
    <section id="projects-section" class="content-section" style="display: none;">ion(`✅ Financial report generated successfully! The report includes revenue, expenses, profit margins, and cash flow analysis.`, 'success');
        <div class="section-header">
            <h2><i class="fas fa-project-diagram"></i> Project Portfolio</h2>
            <div class="section-actions">
                <button class="btn btn-primary" onclick="openModal('newProjectModal')">
                    <i class="fas fa-plus"></i> New Project
                </button>ics.`, 'info');
                <button class="btn btn-secondary" onclick="openProjectAnalytics()">
                    <i class="fas fa-chart-bar"></i> Analytics
                </button>cation(`✅ Business report generated successfully! Check your downloads folder for the complete 47-page executive summary.`, 'success');
            </div>;
        </div>

        <div class="project-stats">
            <div class="stat-card">Exporting staff data for all ${staffData.length} employees across all companies... Format: Excel with security compliance.`, 'info');
                <div class="stat-icon"><i class="fas fa-tasks"></i></div>
                <div class="stat-content">
                    <h3><?= $analytics['active_projects'] ?></h3>`✅ Staff data exported successfully! File includes contact details, employment history, and performance metrics.`, 'success');
                    <p>Active Projects</p>
                    <span class="stat-trend positive">+<?= $analytics['projects_this_month'] ?> this month</span>
                </div>
            </div>on refreshActivity() {
            <div class="stat-card">showNotification(`🔄 Refreshing live activity feed... Fetching latest updates from all company systems.`, 'info');
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-content">
                    <h3><?= $analytics['completed_projects'] ?></h3>       showNotification(`✅ Activity feed refreshed! ${Math.floor(Math.random() * 15) + 5} new activities loaded.`, 'success');
                    <p>Completed</p>        location.reload();
                    <span class="stat-trend positive"><?= $analytics['project_success_rate'] ?>% success rate</span>
                </div>
            </div>
            <div class="stat-card">unction generateAdvancedReport() {
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>    showNotification(`🤖 Generating AI-powered business intelligence report... This includes predictive analytics and strategic recommendations.`, 'info');
                <div class="stat-content">
                    <h3><?= $analytics['overdue_projects'] ?></h3>
                    <p>Overdue</p> Advanced analytics report ready! Includes market insights, growth predictions, and optimization opportunities.`, 'success');
                    <span class="stat-trend negative">Needs attention</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-content">showNotification(`🔔 Notification center opened! You have ${Math.floor(Math.random() * 10) + 3} unread notifications from across all systems.`, 'info');
                    <h3><?= $analytics['client_satisfaction'] ?></h3>
                    <p>Client Rating</p>
                    <span class="stat-trend positive">+0.2 this quarter</span>
                </div>ss profile settings, security options, and account preferences.`, 'info');
            </div>
        </div>
lytics() {
        <div class="projects-grid">ou'll be notified when the advanced analytics dashboard is ready! Expected completion: Q3 2025`, 'success');
            <?php foreach($project_data as $project): ?>
            <div class="project-card" onclick="viewProject('<?= $project['id'] ?>')">
                <div class="project-header">oll Reveal Animation System
                    <h3><?= htmlspecialchars($project['name']) ?></h3>tion revealOnScroll() {
                    <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $project['status'])) ?>"><?= $project['status'] ?></span>
                </div>
                <div class="project-details">{
                    <p><i class="fas fa-user"></i> <?= htmlspecialchars($project['client']) ?></p>t = window.innerHeight;
                    <p><i class="fas fa-building"></i> <?= $project['company'] ?></p>    const elementTop = element.getBoundingClientRect().top;
                    <p><i class="fas fa-calendar"></i> Due: <?= date('M j, Y', strtotime($project['deadline'])) ?></p>isible = 150;
                    <p><i class="fas fa-pound-sign"></i> £<?= number_format($project['budget']) ?></p>
                </div>wHeight - elementVisible) {
                <div class="project-progress">   element.classList.add('revealed');
                    <div class="progress-info">
                        <span>Progress</span>   });
                        <span><?= $project['progress'] ?>%</span>}
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $project['progress'] ?>%"></div>istener('DOMContentLoaded', function() {
                    </div>
                </div>
                <div class="project-team">   document.querySelectorAll('.loading').forEach(el => {
                    <i class="fas fa-users"></i> <?= $project['team_size'] ?> team members           el.style.opacity = '1';
                </div>        });
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Financial Overview Section -->ommand Center! Your ultimate business management dashboard is now active. All systems operational.`, 'success', 6000);
    <section id="finance-section" class="content-section" style="display: none;">, 1000);
        <div class="section-header">   
            <h2><i class="fas fa-chart-line"></i> Financial Overview</h2>    // Hash-based navigation
            <div class="section-actions">on.hash) {
                <button class="btn btn-primary" onclick="openModal('expenseModal')">location.hash.substring(1);
                    <i class="fas fa-plus"></i> Add Expense);
                </button>
                <button class="btn btn-secondary" onclick="generateFinancialReport()">
                    <i class="fas fa-file-pdf"></i> Generate Report
                </button>setInterval(() => {
            </div>Auto-refreshing dashboard data...');
        </div>on, this would fetch fresh data from the API

        <div class="financial-summary">
            <div class="finance-card">
                <div class="finance-icon revenue"><i class="fas fa-chart-line"></i></div>
                <div class="finance-content">croll);
                    <h3>£<?= number_format($analytics['monthly_revenue']) ?></h3>
                    <p>Monthly Revenue</p>lose
                    <span class="trend positive">+<?= $analytics['quarterly_growth'] ?>% vs last month</span>ner('click', function(event) {
                </div>.classList.contains('modal')) {
            </div>  event.target.style.display = 'none';
            <div class="finance-card">    document.body.style.overflow = 'auto';
                <div class="finance-icon expenses"><i class="fas fa-credit-card"></i></div>
                <div class="finance-content">
                    <h3>£<?= number_format($analytics['monthly_expenses']) ?></h3>
                    <p>Operating Expenses</p>
                    <span class="trend neutral">Within budget</span>ment.addEventListener('keydown', function(event) {
                </div>dals
            </div>
            <div class="finance-card">All('.modal').forEach(modal => {
                <div class="finance-icon profit"><i class="fas fa-coins"></i></div>   modal.style.display = 'none';
                <div class="finance-content">
                    <h3>£<?= number_format($analytics['monthly_revenue'] - $analytics['operating_expenses']) ?></h3>       document.body.style.overflow = 'auto';
                    <p>Net Profit</p>    }
                    <span class="trend positive"><?= $analytics['profit_margin'] ?>% margin</span>
                </div>
            </div>Key && event.shiftKey && event.key === 'N') {
            <div class="finance-card">
                <div class="finance-icon cash"><i class="fas fa-wallet"></i></div>   openModal('addStaffModal');
                <div class="finance-content">   }
                    <h3>£<?= number_format($analytics['cash_flow_score'] * 5000) ?></h3>});
                    <p>Cash Flow</p>
                    <span class="trend positive">Healthy</span>
                </div> new PerformanceObserver((list) => {
            </div>
        </div>    if (entry.entryType === 'navigation') {
og(`Dashboard loaded in ${entry.loadEventEnd - entry.loadEventStart}ms`);
        <div class="financial-breakdown">
            <div class="breakdown-section">
                <h3>Revenue Breakdown by Company</h3>
                <div class="revenue-bars">
                    <div class="revenue-item">ation']});
                        <span>Nexi Hub</span>pt>
                        <div class="revenue-bar">
                            <div class="revenue-fill" style="width: 65%"></div>
                        </div>                        <span>£316,000</span>                    </div>                    <div class="revenue-item">                        <span>Nexi Digital</span>                        <div class="revenue-bar">                            <div class="revenue-fill" style="width: 45%"></div>                        </div>                        <span>£215,000</span>                    </div>                    <div class="revenue-item">                        <span>Nexi Consulting</span>                        <div class="revenue-bar">                            <div class="revenue-fill" style="width: 35%"></div>                        </div>                        <span>£156,250</span>                    </div>                </div>            </div>            <div class="breakdown-section">                <h3>Outstanding Actions</h3>                <div class="action-items">                    <div class="action-item urgent">                        <i class="fas fa-exclamation-circle"></i>                        <div>                            <strong><?= $analytics['overdue_payments'] ?> overdue payments</strong>                            <p>Total: £<?= number_format($analytics['overdue_payments'] * 15000) ?></p>                        </div>                        <button class="btn btn-small btn-danger" onclick="reviewOverduePayments()">Review</button>                    </div>                    <div class="action-item warning">                        <i class="fas fa-clock"></i>                        <div>                            <strong><?= $analytics['pending_invoices'] ?> pending invoices</strong>                            <p>Awaiting approval</p>                        </div>                        <button class="btn btn-small btn-warning" onclick="reviewPendingInvoices()">Review</button>                    </div>                </div>            </div>        </div>    </section>    <!-- Operations Center Section -->    <section id="operations-section" class="content-section" style="display: none;">        <div class="section-header">            <h2><i class="fas fa-cogs"></i> Operations Center</h2>            <div class="section-actions">                <button class="btn btn-primary" onclick="runSystemDiagnostics()">                    <i class="fas fa-heartbeat"></i> System Check                </button>                <button class="btn btn-secondary" onclick="viewSystemLogs()">                    <i class="fas fa-list"></i> View Logs                </button>            </div>        </div>        <!-- Time Off Management -->        <div class="operations-section">            <h3><i class="fas fa-calendar-alt"></i> Time Off Management</h3>            <div class="time-off-requests">                <?php foreach($time_off_requests as $request): ?>                <div class="request-card">                    <div class="request-header">                        <div class="employee-info">                            <div class="employee-avatar"><?= strtoupper(substr($request['employee'], 0, 1)) ?></div>                            <div>                                <strong><?= htmlspecialchars($request['employee']) ?></strong>                                <p><?= $request['type'] ?> • <?= $request['days'] ?> days</p>                            </div>                        </div>                        <span class="status-badge status-<?= strtolower($request['status']) ?>"><?= $request['status'] ?></span>                    </div>                    <div class="request-details">                        <p><i class="fas fa-calendar"></i> <?= date('M j', strtotime($request['start_date'])) ?> - <?= date('M j, Y', strtotime($request['end_date'])) ?></p>                        <p><i class="fas fa-comment"></i> <?= htmlspecialchars($request['reason']) ?></p>