<?php
require_once __DIR__ . '/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MYSQL DATABASE STRUCTURE ===\n\n";
    
    // Show staff table structure
    echo "STAFF TABLE STRUCTURE:\n";
    $stmt = $db->prepare("DESCRIBE staff");
    $stmt->execute();
    $columns = $stmt->fetchAll();
    foreach ($columns as $column) {
        echo "{$column['Field']} - {$column['Type']}\n";
    }
    
    echo "\nSTAFF TABLE DATA (first 5 rows):\n";
    $stmt = $db->prepare("SELECT * FROM staff LIMIT 5");
    $stmt->execute();
    $staff = $stmt->fetchAll();
    foreach ($staff as $person) {
        print_r($person);
        echo "\n";
    }
    
    // Check contract tables
    echo "\nCONTRACT_TEMPLATES TABLE:\n";
    $stmt = $db->prepare("SELECT * FROM contract_templates LIMIT 5");
    $stmt->execute();
    $templates = $stmt->fetchAll();
    foreach ($templates as $template) {
        echo "ID: {$template['id']}, Name: {$template['name']}\n";
    }
    
    echo "\nSTAFF_CONTRACTS TABLE STRUCTURE:\n";
    $stmt = $db->prepare("DESCRIBE staff_contracts");
    $stmt->execute();
    $columns = $stmt->fetchAll();
    foreach ($columns as $column) {
        echo "{$column['Field']} - {$column['Type']}\n";
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
?>
