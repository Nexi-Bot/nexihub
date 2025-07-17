<?php
require_once __DIR__ . '/config/config.php';

echo "<h2>Creating Test Staff Profiles</h2>";

try {
    // Database connection
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Check if staff profiles exist
    $stmt = $db->query("SELECT COUNT(*) as count FROM staff_profiles");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        echo "<p>No staff profiles found. Creating test profiles...</p>";
        
        // Create test staff profiles
        $staff_members = [
            [
                'staff_id' => 'EMP001',
                'full_name' => 'John Smith',
                'job_title' => 'Software Developer',
                'department' => 'Technology',
                'nexi_email' => 'john.smith@nexihub.uk',
                'private_email' => 'john@example.com',
                'date_of_birth' => '1995-06-15',
                'date_joined' => '2023-01-15',
                'phone_number' => '+44 7700 900123'
            ],
            [
                'staff_id' => 'EMP002',
                'full_name' => 'Sarah Johnson',
                'job_title' => 'Marketing Manager',
                'department' => 'Marketing',
                'nexi_email' => 'sarah.johnson@nexihub.uk',
                'private_email' => 'sarah@example.com',
                'date_of_birth' => '1990-03-22',
                'date_joined' => '2023-02-01',
                'phone_number' => '+44 7700 900124'
            ],
            [
                'staff_id' => 'EMP003',
                'full_name' => 'Alex Thompson',
                'job_title' => 'Junior Developer',
                'department' => 'Technology',
                'nexi_email' => 'alex.thompson@nexihub.uk',
                'private_email' => 'alex@example.com',
                'date_of_birth' => '2008-11-30', // Under 17 for testing guardian features
                'date_joined' => '2024-06-01',
                'phone_number' => '+44 7700 900125'
            ],
            [
                'staff_id' => 'EMP004',
                'full_name' => 'Emily Davis',
                'job_title' => 'HR Coordinator',
                'department' => 'Human Resources',
                'nexi_email' => 'emily.davis@nexihub.uk',
                'private_email' => 'emily@example.com',
                'date_of_birth' => '1988-09-12',
                'date_joined' => '2022-11-15',
                'phone_number' => '+44 7700 900126'
            ]
        ];
        
        $stmt = $db->prepare("
            INSERT INTO staff_profiles (
                staff_id, full_name, job_title, department, nexi_email, 
                private_email, date_of_birth, date_joined, phone_number,
                region, account_status, elearning_status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'UK', 'Active', 'Not Started')
        ");
        
        foreach ($staff_members as $staff) {
            $stmt->execute([
                $staff['staff_id'],
                $staff['full_name'],
                $staff['job_title'],
                $staff['department'],
                $staff['nexi_email'],
                $staff['private_email'],
                $staff['date_of_birth'],
                $staff['date_joined'],
                $staff['phone_number']
            ]);
            
            echo "<p>✅ Created: " . $staff['full_name'] . " (" . $staff['staff_id'] . ")</p>";
        }
        
        echo "<p><strong>Test staff profiles created successfully!</strong></p>";
        
    } else {
        echo "<p>Staff profiles already exist (" . $result['count'] . " found).</p>";
        
        // Show existing profiles
        $stmt = $db->query("SELECT staff_id, full_name, job_title, date_of_birth FROM staff_profiles LIMIT 10");
        $profiles = $stmt->fetchAll();
        
        echo "<h3>Existing Staff Profiles:</h3><ul>";
        foreach ($profiles as $profile) {
            echo "<li>" . $profile['staff_id'] . " - " . $profile['full_name'] . " (" . $profile['job_title'] . ")</li>";
        }
        echo "</ul>";
    }
    
    // Also check contract templates
    $stmt = $db->query("SELECT COUNT(*) as count FROM contract_templates");
    $result = $stmt->fetch();
    echo "<p>Contract templates in database: " . $result['count'] . "</p>";
    
    if ($result['count'] > 0) {
        $stmt = $db->query("SELECT name, type FROM contract_templates");
        $templates = $stmt->fetchAll();
        echo "<h3>Available Contract Templates:</h3><ul>";
        foreach ($templates as $template) {
            echo "<li>" . $template['name'] . " (" . $template['type'] . ")</li>";
        }
        echo "</ul>";
    }
    
    echo "<p><a href='contracts/login-test.php'>→ Test Contract Portal Login</a></p>";
    
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}
?>
