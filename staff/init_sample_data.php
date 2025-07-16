<?php
// Initialize sample financial and project data for the dashboard
require_once __DIR__ . '/../config/config.php';

// Database connection
$db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if financial records exist
$financial_count = $db->query("SELECT COUNT(*) FROM financial_records")->fetchColumn();

if ($financial_count == 0) {
    echo "Adding sample financial data...\n";
    
    // Sample financial records
    $financial_records = [
        // Income records
        ['income', 45000, 'Nexi Hub subscription revenue', 'revenue', '2025-07-01'],
        ['income', 28000, 'Nexi Digital licensing', 'revenue', '2025-07-02'],
        ['income', 15000, 'Nexi Consulting services', 'revenue', '2025-07-03'],
        ['income', 32000, 'Custom development project', 'revenue', '2025-07-05'],
        ['income', 18000, 'Annual support contract', 'revenue', '2025-07-10'],
        ['income', 25000, 'Platform integration services', 'revenue', '2025-07-12'],
        
        // Expense records
        ['expense', 15000, 'Staff salaries', 'salaries', '2025-07-01'],
        ['expense', 2500, 'Office rent', 'office_expenses', '2025-07-01'],
        ['expense', 1200, 'Software licenses', 'software', '2025-07-02'],
        ['expense', 800, 'Marketing campaigns', 'marketing', '2025-07-05'],
        ['expense', 450, 'Travel expenses', 'travel', '2025-07-08'],
        ['expense', 300, 'Office supplies', 'office_expenses', '2025-07-10'],
    ];
    
    $stmt = $db->prepare("INSERT INTO financial_records (type, amount, description, category, date) VALUES (?, ?, ?, ?, ?)");
    foreach ($financial_records as $record) {
        $stmt->execute($record);
    }
}

// Check if projects exist
$project_count = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();

if ($project_count == 0) {
    echo "Adding sample project data...\n";
    
    // Sample projects
    $projects = [
        ['Nexi Hub v4.0 Upgrade', 'Major platform upgrade with new features', 'active', 'Internal', 50000, '2025-06-01', '2025-09-01', 65],
        ['Client Portal Redesign', 'Complete UI/UX overhaul for client portal', 'active', 'Acme Corp', 25000, '2025-07-01', '2025-08-15', 40],
        ['API Integration Suite', 'Third-party integrations for Nexi Digital', 'active', 'TechFlow Ltd', 35000, '2025-06-15', '2025-08-30', 55],
        ['Mobile App Development', 'iOS and Android apps for Nexi platform', 'pending', 'StartupXYZ', 40000, '2025-08-01', '2025-11-01', 0],
        ['Security Audit & Upgrade', 'Comprehensive security review and improvements', 'completed', 'Internal', 15000, '2025-05-01', '2025-06-30', 100],
        ['E-commerce Integration', 'WooCommerce plugin for Nexi Hub', 'active', 'E-Shop Solutions', 20000, '2025-07-10', '2025-09-10', 25],
    ];
    
    $stmt = $db->prepare("INSERT INTO projects (name, description, status, client_name, budget, start_date, end_date, completion_percentage) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($projects as $project) {
        $stmt->execute($project);
    }
}

// Add some time off requests for the approval system
$timeoff_count = $db->query("SELECT COUNT(*) FROM time_off_requests")->fetchColumn();

if ($timeoff_count == 0) {
    echo "Adding sample time off requests...\n";
    
    $timeoff_requests = [
        [1, '2025-08-01', '2025-08-05', 'Summer vacation', 'pending'],
        [2, '2025-07-25', '2025-07-26', 'Personal day', 'pending'],
        [3, '2025-08-15', '2025-08-20', 'Family vacation', 'approved'],
        [4, '2025-07-30', '2025-07-30', 'Medical appointment', 'pending'],
    ];
    
    $stmt = $db->prepare("INSERT INTO time_off_requests (staff_id, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, ?)");
    foreach ($timeoff_requests as $request) {
        $stmt->execute($request);
    }
}

echo "Sample data initialization complete!\n";
echo "Dashboard should now show meaningful analytics and data.\n";
?>
