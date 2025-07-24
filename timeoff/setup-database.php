<?php
require_once __DIR__ . '/../config/config.php';

echo "Setting up Time Off portal database...\n";

try {
    // Create time_off_requests table
    $sql = "CREATE TABLE IF NOT EXISTS time_off_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        staff_id INT NOT NULL,
        request_type ENUM('Holiday', 'Sick Leave', 'Personal Leave', 'Bereavement', 'Maternity/Paternity', 'Other') NOT NULL DEFAULT 'Holiday',
        reason TEXT NOT NULL,
        date_from DATE NOT NULL,
        date_to DATE NOT NULL,
        days_requested INT NOT NULL,
        status ENUM('Pending', 'Approved', 'Declined', 'Cancelled') NOT NULL DEFAULT 'Pending',
        notes TEXT,
        emergency_contact VARCHAR(255),
        cover_arrangements TEXT,
        requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        decided_at TIMESTAMP NULL,
        decided_by INT NULL,
        decision_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_staff_id (staff_id),
        INDEX idx_status (status),
        INDEX idx_dates (date_from, date_to),
        FOREIGN KEY (staff_id) REFERENCES staff_profiles(id) ON DELETE CASCADE,
        FOREIGN KEY (decided_by) REFERENCES staff_profiles(id) ON DELETE SET NULL
    )";
    
    $pdo->exec($sql);
    echo "✅ time_off_requests table created successfully!\n";
    
    // Create time_off_audit table for logging
    $sql = "CREATE TABLE IF NOT EXISTS time_off_audit (
        id INT AUTO_INCREMENT PRIMARY KEY,
        request_id INT NOT NULL,
        action ENUM('Created', 'Approved', 'Declined', 'Cancelled', 'Modified') NOT NULL,
        performed_by INT NOT NULL,
        previous_status VARCHAR(50),
        new_status VARCHAR(50),
        notes TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_request_id (request_id),
        INDEX idx_performed_by (performed_by),
        FOREIGN KEY (request_id) REFERENCES time_off_requests(id) ON DELETE CASCADE,
        FOREIGN KEY (performed_by) REFERENCES staff_profiles(id) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql);
    echo "✅ time_off_audit table created successfully!\n";
    
    // Add time_off_balance column to staff_profiles if it doesn't exist
    try {
        $sql = "ALTER TABLE staff_profiles ADD COLUMN time_off_balance INT DEFAULT 25";
        $pdo->exec($sql);
        echo "✅ time_off_balance column added to staff_profiles!\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "✅ time_off_balance column already exists!\n";
        } else {
            throw $e;
        }
    }
    
    // Update existing staff to have default balance if null
    $sql = "UPDATE staff_profiles SET time_off_balance = 25 WHERE time_off_balance IS NULL OR time_off_balance = 0";
    $pdo->exec($sql);
    echo "✅ Default time off balances set for existing staff!\n";
    
    echo "\n🎉 Time Off portal database setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error setting up database: " . $e->getMessage() . "\n";
}
?>
