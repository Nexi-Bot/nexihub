<?php
// Direct database initialization script
echo "Initializing Nexi Hub Executive Dashboard Database...\n";

// Database connection
$db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Creating database tables...\n";

// Create all necessary tables
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

echo "Tables created successfully!\n";

// Insert sample staff data
$staff_count = $db->query("SELECT COUNT(*) FROM staff")->fetchColumn();
if ($staff_count == 0) {
    echo "Adding sample staff data...\n";
    
    $sample_staff = [
        ['John Smith', 'john.smith@nexihub.com', 'Development', 'Senior Developer', 'active', '2023-01-15', 65000],
        ['Sarah Johnson', 'sarah.johnson@nexihub.com', 'Design', 'UX Designer', 'active', '2023-03-20', 58000],
        ['Mike Chen', 'mike.chen@nexihub.com', 'Operations', 'Project Manager', 'active', '2023-02-10', 72000],
        ['Emily Davis', 'emily.davis@nexihub.com', 'Marketing', 'Marketing Specialist', 'active', '2023-04-05', 52000],
        ['David Wilson', 'david.wilson@nexihub.com', 'Sales', 'Sales Director', 'active', '2023-01-08', 78000],
        ['Lisa Thompson', 'lisa.thompson@nexihub.com', 'Finance', 'Financial Controller', 'active', '2023-05-12', 68000],
        ['James Rodriguez', 'james.rodriguez@nexihub.com', 'Development', 'Full Stack Developer', 'active', '2023-06-01', 62000],
        ['Anna Williams', 'anna.williams@nexihub.com', 'HR', 'HR Manager', 'active', '2023-04-20', 59000]
    ];
    
    $stmt = $db->prepare("INSERT INTO staff (name, email, department, role, status, hire_date, salary) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($sample_staff as $staff) {
        $stmt->execute($staff);
    }
}

// Insert platform data
$platform_count = $db->query("SELECT COUNT(*) FROM platforms")->fetchColumn();
if ($platform_count == 0) {
    echo "Adding platform data...\n";
    
    $platforms = [
        ['Nexi Hub', 'Main business management platform', 'active', 247, 45000, 99.97],
        ['Nexi Digital', 'Digital marketing and automation suite', 'active', 89, 28000, 99.85],
        ['Nexi Consulting', 'Professional consulting services platform', 'active', 34, 15000, 99.92]
    ];
    
    $stmt = $db->prepare("INSERT INTO platforms (name, description, status, users_count, revenue, uptime) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($platforms as $platform) {
        $stmt->execute($platform);
    }
}

// Insert financial records
$financial_count = $db->query("SELECT COUNT(*) FROM financial_records")->fetchColumn();
if ($financial_count == 0) {
    echo "Adding financial data...\n";
    
    $financial_records = [
        // Income records
        ['income', 45000, 'Nexi Hub subscription revenue', 'revenue', '2025-07-01'],
        ['income', 28000, 'Nexi Digital licensing', 'revenue', '2025-07-02'],
        ['income', 15000, 'Nexi Consulting services', 'revenue', '2025-07-03'],
        ['income', 32000, 'Custom development project', 'revenue', '2025-07-05'],
        ['income', 18000, 'Annual support contract', 'revenue', '2025-07-10'],
        ['income', 25000, 'Platform integration services', 'revenue', '2025-07-12'],
        ['income', 22000, 'Enterprise license renewal', 'revenue', '2025-07-15'],
        
        // Expense records
        ['expense', 35000, 'Staff salaries July', 'salaries', '2025-07-01'],
        ['expense', 2500, 'Office rent', 'office_expenses', '2025-07-01'],
        ['expense', 1200, 'Software licenses', 'software', '2025-07-02'],
        ['expense', 3500, 'Marketing campaigns', 'marketing', '2025-07-05'],
        ['expense', 1200, 'Travel expenses', 'travel', '2025-07-08'],
        ['expense', 800, 'Office supplies', 'office_expenses', '2025-07-10'],
        ['expense', 950, 'Server hosting costs', 'software', '2025-07-01'],
        ['expense', 600, 'Professional development', 'other', '2025-07-12'],
    ];
    
    $stmt = $db->prepare("INSERT INTO financial_records (type, amount, description, category, date) VALUES (?, ?, ?, ?, ?)");
    foreach ($financial_records as $record) {
        $stmt->execute($record);
    }
}

// Insert projects
$project_count = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
if ($project_count == 0) {
    echo "Adding project data...\n";
    
    $projects = [
        ['Nexi Hub v4.0 Upgrade', 'Major platform upgrade with new features and improved UI', 'active', 'Internal', 50000, '2025-06-01', '2025-09-01', 65],
        ['Client Portal Redesign', 'Complete UI/UX overhaul for client portal with mobile responsiveness', 'active', 'Acme Corp', 25000, '2025-07-01', '2025-08-15', 40],
        ['API Integration Suite', 'Third-party integrations for Nexi Digital platform', 'active', 'TechFlow Ltd', 35000, '2025-06-15', '2025-08-30', 55],
        ['Mobile App Development', 'Native iOS and Android apps for Nexi platform', 'pending', 'StartupXYZ', 40000, '2025-08-01', '2025-11-01', 0],
        ['Security Audit & Upgrade', 'Comprehensive security review and infrastructure improvements', 'completed', 'Internal', 15000, '2025-05-01', '2025-06-30', 100],
        ['E-commerce Integration', 'WooCommerce and Shopify plugins for Nexi Hub', 'active', 'E-Shop Solutions', 20000, '2025-07-10', '2025-09-10', 25],
        ['Analytics Dashboard v2', 'Advanced analytics and reporting dashboard', 'active', 'DataViz Inc', 30000, '2025-06-20', '2025-08-20', 45],
        ['Multi-tenant Architecture', 'Scalable multi-tenant system architecture', 'pending', 'Enterprise Client', 75000, '2025-08-15', '2025-12-15', 0],
    ];
    
    $stmt = $db->prepare("INSERT INTO projects (name, description, status, client_name, budget, start_date, end_date, completion_percentage) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($projects as $project) {
        $stmt->execute($project);
    }
}

// Add time off requests
$timeoff_count = $db->query("SELECT COUNT(*) FROM time_off_requests")->fetchColumn();
if ($timeoff_count == 0) {
    echo "Adding time off requests...\n";
    
    $timeoff_requests = [
        [1, '2025-08-01', '2025-08-05', 'Summer vacation', 'pending'],
        [2, '2025-07-25', '2025-07-26', 'Personal day', 'pending'],
        [3, '2025-08-15', '2025-08-20', 'Family vacation', 'approved'],
        [4, '2025-07-30', '2025-07-30', 'Medical appointment', 'pending'],
        [5, '2025-08-10', '2025-08-12', 'Conference attendance', 'approved'],
        [6, '2025-07-28', '2025-07-29', 'Wedding anniversary', 'pending'],
    ];
    
    $stmt = $db->prepare("INSERT INTO time_off_requests (staff_id, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, ?)");
    foreach ($timeoff_requests as $request) {
        $stmt->execute($request);
    }
}

// Add activity log entries
$activity_count = $db->query("SELECT COUNT(*) FROM activity_log")->fetchColumn();
if ($activity_count == 0) {
    echo "Adding activity log entries...\n";
    
    $activities = [
        ['staff', 'New hire contract signed', 'Sarah Johnson - UX Designer'],
        ['finance', 'Invoice payment received', 'Acme Corp - £25,400'],
        ['project', 'Project milestone completed', 'Nexi Hub v4.0 - Phase 1'],
        ['security', 'Security scan completed', 'All systems passed'],
        ['staff', 'Time off approved', 'Mike Chen - Project Manager'],
        ['finance', 'Expense claim processed', 'Travel costs - £1,200'],
        ['platform', 'System backup completed', 'Nexi Hub - Full backup'],
        ['platform', 'User milestone reached', 'Nexi Digital - 100 users'],
        ['project', 'Client feedback received', 'Portal redesign - 5 stars'],
        ['security', 'SSL certificate renewed', 'All domains updated'],
        ['staff', 'Performance review completed', 'James Rodriguez - Excellent'],
        ['finance', 'Monthly revenue target achieved', '£185K milestone reached'],
    ];
    
    $stmt = $db->prepare("INSERT INTO activity_log (type, action, details) VALUES (?, ?, ?)");
    foreach ($activities as $activity) {
        $stmt->execute($activity);
    }
}

echo "\n=== Database Initialization Complete ===\n";
echo "✓ All tables created\n";
echo "✓ Sample staff data added (8 members)\n";
echo "✓ Platform data initialized (3 platforms)\n";
echo "✓ Financial records added (14 records)\n";
echo "✓ Project portfolio created (8 projects)\n";
echo "✓ Time off requests added (6 requests)\n";
echo "✓ Activity log populated (12 entries)\n";
echo "\nThe Executive Dashboard is now ready with realistic data!\n";
echo "Monthly Revenue: £185,000\n";
echo "Active Projects: 5\n";
echo "Total Staff: 8\n";
echo "Platform Users: 370\n";
?>
