<?php
require_once __DIR__ . '/../config/config.php';

try {
    // Create elearning_progress table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS elearning_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        staff_id INT NOT NULL,
        current_module INT DEFAULT 1,
        modules_completed TEXT,
        total_modules INT DEFAULT 7,
        started_at DATETIME,
        completed_at DATETIME NULL,
        status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
        certificate_generated BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (staff_id) REFERENCES staff_profiles(id) ON DELETE CASCADE,
        UNIQUE KEY unique_staff_progress (staff_id)
    )";
    
    $pdo->exec($sql);
    echo "E-Learning database table created successfully!\n";
    
} catch (Exception $e) {
    die("Error creating database table: " . $e->getMessage() . "\n");
}
