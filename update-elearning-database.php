<?php
require_once 'config/config.php';

echo "Creating the correct E-Learning database table structure...\n";

try {
    // Drop the existing table first
    $pdo->exec("DROP TABLE IF EXISTS elearning_progress");
    echo "✅ Dropped old elearning_progress table\n";
    
    // Create the correct table structure
    $createTable = "
    CREATE TABLE elearning_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        staff_id INT NOT NULL,
        module_id INT NOT NULL,
        completed_at DATETIME NOT NULL,
        quiz_score INT DEFAULT 80,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_staff_module (staff_id, module_id),
        FOREIGN KEY (staff_id) REFERENCES staff_profiles(id) ON DELETE CASCADE
    )";
    
    $pdo->exec($createTable);
    echo "✅ Created correct elearning_progress table\n";
    
    echo "\nTable structure:\n";
    echo "- id: Primary key\n";
    echo "- staff_id: Foreign key to staff_profiles.id\n";
    echo "- module_id: Which module (1-5)\n";
    echo "- completed_at: When the module was completed\n";
    echo "- quiz_score: Score achieved on module quiz\n";
    echo "- created_at: Record creation timestamp\n";
    echo "- Unique constraint: One record per staff_id + module_id\n";
    
    echo "\nE-Learning database table updated successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
