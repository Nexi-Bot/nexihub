<?php
require_once __DIR__ . '/../config/config.php';

// Enhanced staff table with job information - check and add columns one by one
function addColumnIfNotExists($pdo, $table, $column, $definition) {
    try {
        $result = $pdo->query("SHOW COLUMNS FROM {$table} LIKE '{$column}'");
        if ($result->rowCount() == 0) {
            $pdo->exec("ALTER TABLE {$table} ADD COLUMN {$column} {$definition}");
            echo "Added column {$column} to {$table}\n";
        } else {
            echo "Column {$column} already exists in {$table}\n";
        }
    } catch (PDOException $e) {
        echo "Error adding column {$column}: " . $e->getMessage() . "\n";
    }
}

// Create users table for customer management
$createUsersTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(20),
    country VARCHAR(100),
    subscription_plan ENUM('free', 'basic', 'premium', 'enterprise') DEFAULT 'free',
    subscription_status ENUM('active', 'cancelled', 'expired', 'trial') DEFAULT 'active',
    subscription_start DATE,
    subscription_end DATE,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    last_login DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_subscription (subscription_plan, subscription_status)
)";

// Create support tickets table
$createSupportTable = "
CREATE TABLE IF NOT EXISTS support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(20) NOT NULL UNIQUE,
    user_id INT,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('open', 'in-progress', 'waiting-response', 'resolved', 'closed') DEFAULT 'open',
    category VARCHAR(100),
    assigned_to INT,
    created_by_staff BOOLEAN DEFAULT FALSE,
    resolution TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES staff(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_priority (priority)
)";

// Create billing table
$createBillingTable = "
CREATE TABLE IF NOT EXISTS billing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'GBP',
    description TEXT,
    status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method ENUM('stripe', 'paypal', 'bank_transfer') DEFAULT 'stripe',
    stripe_payment_intent_id VARCHAR(255),
    due_date DATE,
    paid_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_user (user_id)
)";

// Create analytics table
$createAnalyticsTable = "
CREATE TABLE IF NOT EXISTS analytics_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(100) NOT NULL,
    user_id INT,
    session_id VARCHAR(255),
    page_url VARCHAR(500),
    user_agent TEXT,
    ip_address VARCHAR(45),
    country VARCHAR(100),
    referrer VARCHAR(500),
    metadata JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at),
    INDEX idx_user_id (user_id)
)";

// Create system health table
$createSystemHealthTable = "
CREATE TABLE IF NOT EXISTS system_health (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    status ENUM('operational', 'degraded', 'outage') DEFAULT 'operational',
    response_time_ms INT,
    cpu_usage DECIMAL(5,2),
    memory_usage DECIMAL(5,2),
    disk_usage DECIMAL(5,2),
    error_count INT DEFAULT 0,
    last_check DATETIME DEFAULT CURRENT_TIMESTAMP,
    metadata JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_service (service_name),
    INDEX idx_status (status),
    INDEX idx_last_check (last_check)
)";

try {
    // Update staff table with new columns
    addColumnIfNotExists($pdo, 'staff', 'department', 'VARCHAR(100)');
    addColumnIfNotExists($pdo, 'staff', 'job_title', 'VARCHAR(100)');
    addColumnIfNotExists($pdo, 'staff', 'hire_date', 'DATE');
    addColumnIfNotExists($pdo, 'staff', 'employment_type', "ENUM('full-time', 'part-time', 'contractor', 'intern', 'volunteer') DEFAULT 'full-time'");
    addColumnIfNotExists($pdo, 'staff', 'manager_id', 'INT');
    addColumnIfNotExists($pdo, 'staff', 'phone', 'VARCHAR(20)');
    addColumnIfNotExists($pdo, 'staff', 'emergency_contact_name', 'VARCHAR(100)');
    addColumnIfNotExists($pdo, 'staff', 'emergency_contact_phone', 'VARCHAR(20)');
    addColumnIfNotExists($pdo, 'staff', 'profile_picture', 'VARCHAR(255)');
    addColumnIfNotExists($pdo, 'staff', 'bio', 'TEXT');
    addColumnIfNotExists($pdo, 'staff', 'skills', 'JSON');
    addColumnIfNotExists($pdo, 'staff', 'permissions', 'JSON');
    addColumnIfNotExists($pdo, 'staff', 'salary_band', 'VARCHAR(20)');
    
    echo "Staff table updated with job information\n";
    
    // Create new tables
    $pdo->exec($createUsersTable);
    echo "Users table created\n";
    
    $pdo->exec($createSupportTable);
    echo "Support tickets table created\n";
    
    $pdo->exec($createBillingTable);
    echo "Billing table created\n";
    
    $pdo->exec($createAnalyticsTable);
    echo "Analytics events table created\n";
    
    $pdo->exec($createSystemHealthTable);
    echo "System health table created\n";
    
    // Update Ollie's record with job information (only if columns exist)
    try {
        $updateOllie = $pdo->prepare("
            UPDATE staff 
            SET department = 'Executive', 
                job_title = 'Chief Executive Officer & Founder',
                hire_date = '2020-01-01',
                employment_type = 'full-time',
                phone = '+44 7700 900123',
                profile_picture = 'assets/images/Ollie.jpg',
                bio = 'Visionary leader and founder of Nexi Hub, driving strategic direction and innovation across our platform ecosystem.',
                skills = ?,
                permissions = ?
            WHERE email = 'ollie.r@nexihub.uk'
        ");
        $skills = json_encode(['Leadership', 'Strategic Planning', 'Product Development', 'Digital Innovation']);
        $permissions = json_encode(['admin' => true, 'users' => true, 'billing' => true, 'support' => true, 'analytics' => true, 'staff' => true, 'system' => true]);
        $updateOllie->execute([$skills, $permissions]);
        echo "Updated Ollie's job information\n";
    } catch (PDOException $e) {
        echo "Could not update Ollie's info (columns may not exist yet): " . $e->getMessage() . "\n";
    }
    
    // Insert sample users
    $sampleUsers = [
        ['john.doe@example.com', 'johndoe', 'John', 'Doe', '+44 7700 900001', 'United Kingdom', 'premium', 'active', '2023-01-15', '2024-01-15', 299.99],
        ['jane.smith@example.com', 'janesmith', 'Jane', 'Smith', '+44 7700 900002', 'United Kingdom', 'basic', 'active', '2023-03-10', '2024-03-10', 99.99],
        ['mike.wilson@example.com', 'mikewilson', 'Mike', 'Wilson', '+1 555 123 4567', 'United States', 'enterprise', 'active', '2022-11-20', '2024-11-20', 1999.99],
        ['sarah.brown@example.com', 'sarahbrown', 'Sarah', 'Brown', '+61 412 345 678', 'Australia', 'free', 'active', '2023-06-05', NULL, 0.00],
        ['alex.johnson@example.com', 'alexjohnson', 'Alex', 'Johnson', '+49 30 12345678', 'Germany', 'basic', 'cancelled', '2023-02-01', '2023-08-01', 199.98]
    ];
    
    $insertUser = $pdo->prepare("
        INSERT INTO users (email, username, first_name, last_name, phone, country, subscription_plan, subscription_status, subscription_start, subscription_end, total_spent)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE email = email
    ");
    
    foreach ($sampleUsers as $user) {
        $insertUser->execute($user);
    }
    echo "Sample users inserted\n";
    
    // Insert sample support tickets
    $sampleTickets = [
        ['TKT-001', 1, 'Login Issues', 'Cannot access my account after password reset', 'high', 'in-progress', 'Authentication', 1],
        ['TKT-002', 2, 'Billing Question', 'Need clarification on recent charge', 'medium', 'open', 'Billing', null],
        ['TKT-003', 3, 'Feature Request', 'Would like to see dark mode option', 'low', 'open', 'Enhancement', 1],
        ['TKT-004', 4, 'Bug Report', 'Dashboard not loading properly on mobile', 'high', 'resolved', 'Technical', 1],
        ['TKT-005', 5, 'Account Cancellation', 'Want to cancel my subscription', 'medium', 'waiting-response', 'Account', null]
    ];
    
    $insertTicket = $pdo->prepare("
        INSERT INTO support_tickets (ticket_number, user_id, subject, description, priority, status, category, assigned_to)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE ticket_number = ticket_number
    ");
    
    foreach ($sampleTickets as $ticket) {
        $insertTicket->execute($ticket);
    }
    echo "Sample support tickets inserted\n";
    
    // Insert sample billing records
    $sampleBilling = [
        [1, 'INV-2023-001', 299.99, 'GBP', 'Premium Plan - Annual', 'paid', 'stripe', 'pi_1234567890', '2023-01-15', '2023-01-15'],
        [2, 'INV-2023-002', 99.99, 'GBP', 'Basic Plan - Annual', 'paid', 'stripe', 'pi_1234567891', '2023-03-10', '2023-03-10'],
        [3, 'INV-2023-003', 1999.99, 'GBP', 'Enterprise Plan - Annual', 'paid', 'stripe', 'pi_1234567892', '2022-11-20', '2022-11-20'],
        [2, 'INV-2023-004', 9.99, 'GBP', 'Basic Plan - Monthly', 'pending', 'stripe', 'pi_1234567893', '2024-01-10', null],
        [5, 'INV-2023-005', 99.99, 'GBP', 'Basic Plan - Annual', 'failed', 'stripe', 'pi_1234567894', '2023-08-01', null]
    ];
    
    $insertBilling = $pdo->prepare("
        INSERT INTO billing (user_id, invoice_number, amount, currency, description, status, payment_method, stripe_payment_intent_id, due_date, paid_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE invoice_number = invoice_number
    ");
    
    foreach ($sampleBilling as $bill) {
        $insertBilling->execute($bill);
    }
    echo "Sample billing records inserted\n";
    
    // Insert sample analytics events
    $eventTypes = ['page_view', 'button_click', 'form_submit', 'download', 'signup', 'login', 'logout'];
    $pages = ['/dashboard', '/billing', '/support', '/profile', '/settings'];
    
    $insertAnalytics = $pdo->prepare("
        INSERT INTO analytics_events (event_type, user_id, page_url, user_agent, ip_address, country, metadata)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    for ($i = 0; $i < 100; $i++) {
        $userId = rand(1, 5);
        $eventType = $eventTypes[array_rand($eventTypes)];
        $page = $pages[array_rand($pages)];
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
        $ip = '192.168.' . rand(1, 255) . '.' . rand(1, 255);
        $country = ['United Kingdom', 'United States', 'Germany', 'France', 'Australia'][array_rand(['United Kingdom', 'United States', 'Germany', 'France', 'Australia'])];
        $metadata = json_encode(['timestamp' => time(), 'session_duration' => rand(30, 3600)]);
        
        $insertAnalytics->execute([$eventType, $userId, $page, $userAgent, $ip, $country, $metadata]);
    }
    echo "Sample analytics events inserted\n";
    
    // Insert system health data
    $services = [
        ['Web Server', 'operational', 120, 25.5, 45.2, 15.8],
        ['Database', 'operational', 45, 15.3, 32.1, 8.4],
        ['API Gateway', 'operational', 89, 18.7, 28.9, 12.3],
        ['Payment Service', 'degraded', 450, 65.2, 78.4, 45.6],
        ['Email Service', 'operational', 234, 8.9, 22.1, 5.2]
    ];
    
    $insertHealth = $pdo->prepare("
        INSERT INTO system_health (service_name, status, response_time_ms, cpu_usage, memory_usage, disk_usage, error_count, metadata)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            status = VALUES(status),
            response_time_ms = VALUES(response_time_ms),
            cpu_usage = VALUES(cpu_usage),
            memory_usage = VALUES(memory_usage),
            disk_usage = VALUES(disk_usage),
            last_check = CURRENT_TIMESTAMP
    ");
    
    foreach ($services as $service) {
        $metadata = json_encode(['last_restart' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 168) . ' hours'))]);
        $errorCount = $service[1] === 'degraded' ? rand(5, 20) : rand(0, 3);
        $insertHealth->execute(array_merge($service, [$errorCount, $metadata]));
    }
    echo "System health data inserted\n";
    
    echo "\nDatabase enhancement completed successfully!\n";
    echo "All tables updated with real data structures and sample data.\n";
    
} catch (PDOException $e) {
    echo "Error enhancing database: " . $e->getMessage() . "\n";
}
?>
