<?php
require_once 'config/config.php';

echo "=== DATABASE SCHEMA CHECK ===\n\n";

try {
    // Check elearning_progress table structure
    echo "1. Checking elearning_progress table structure...\n";
    $stmt = $pdo->query("DESCRIBE elearning_progress");
    $columns = $stmt->fetchAll();
    
    echo "   Table columns:\n";
    foreach ($columns as $column) {
        echo "   - {$column['Field']} ({$column['Type']}) - {$column['Null']} - {$column['Key']}\n";
    }
    echo "\n";
    
    // Check staff_profiles table structure
    echo "2. Checking staff_profiles table structure...\n";
    $stmt = $pdo->query("DESCRIBE staff_profiles");
    $columns = $stmt->fetchAll();
    
    echo "   Relevant columns:\n";
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['id', 'full_name', 'elearning_status'])) {
            echo "   - {$column['Field']} ({$column['Type']}) - {$column['Null']} - {$column['Key']}\n";
        }
    }
    echo "\n";
    
    // Show sample data
    echo "3. Sample staff data:\n";
    $stmt = $pdo->query("SELECT id, full_name, elearning_status FROM staff_profiles LIMIT 3");
    $staff = $stmt->fetchAll();
    foreach ($staff as $member) {
        echo "   ID: {$member['id']}, Name: {$member['full_name']}, Status: " . ($member['elearning_status'] ?: 'NULL') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
