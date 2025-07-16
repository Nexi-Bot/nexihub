<?php
/**
 * Initialize MySQL database with all required tables
 */

require_once __DIR__ . '/config/api_config.php';

try {
    echo "🔄 Connecting to MySQL database...\n";
    
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    
    echo "✓ Connected to MySQL database: " . DB_NAME . "\n\n";
    
    echo "📋 Creating tables...\n";
    
    // Create staff table
    $db->exec("CREATE TABLE IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        department VARCHAR(100),
        role VARCHAR(100),
        status VARCHAR(20) DEFAULT 'active',
        hire_date DATE,
        salary DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ Staff table created\n";
    
    // Create projects table
    $db->exec("CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        status VARCHAR(20) DEFAULT 'active',
        client_name VARCHAR(255),
        budget DECIMAL(10,2),
        start_date DATE,
        end_date DATE,
        completion_percentage INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ Projects table created\n";
    
    // Create financial_records table
    $db->exec("CREATE TABLE IF NOT EXISTS financial_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(20) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        description TEXT,
        category VARCHAR(100),
        date DATE,
        status VARCHAR(20) DEFAULT 'completed',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ Financial records table created\n";
    
    // Create time_off_requests table
    $db->exec("CREATE TABLE IF NOT EXISTS time_off_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        staff_id INT,
        start_date DATE,
        end_date DATE,
        reason TEXT,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (staff_id) REFERENCES staff(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ Time off requests table created\n";
    
    // Create activity_log table
    $db->exec("CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL,
        action VARCHAR(255) NOT NULL,
        details TEXT,
        user_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ Activity log table created\n";
    
    // Create platforms table
    $db->exec("CREATE TABLE IF NOT EXISTS platforms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        status VARCHAR(20) DEFAULT 'active',
        users_count INT DEFAULT 0,
        revenue DECIMAL(10,2) DEFAULT 0,
        uptime DECIMAL(5,2) DEFAULT 99.9,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ Platforms table created\n";
    
    echo "\n🎉 MySQL database initialized successfully!\n";
    echo "All tables created and ready for use.\n";
    echo "\n💡 The dashboard should now work without HTTP 500 errors.\n";
    echo "You can add real data through the dashboard interface.\n";
    
} catch (Exception $e) {
    echo "❌ Error initializing database: " . $e->getMessage() . "\n";
    echo "Please check your database credentials and permissions.\n";
}
