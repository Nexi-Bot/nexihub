<?php
require_once 'config/config.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS elearning_module_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        staff_id INT NOT NULL,
        module_id INT NOT NULL,
        completed TINYINT(1) DEFAULT 0,
        completed_at DATETIME NULL,
        quiz_score INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(staff_id, module_id)
    )";
    $pdo->exec($sql);
    echo "Module progress table created successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
