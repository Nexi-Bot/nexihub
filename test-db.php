<?php
// Test database connection
try {
    $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful!\n";
    
    // Test reading from staff_profiles
    $stmt = $db->prepare("SELECT * FROM staff_profiles");
    $stmt->execute();
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Found " . count($staff) . " staff members:\n";
    foreach ($staff as $member) {
        echo "  - {$member['full_name']} ({$member['staff_id']}) - {$member['department']}\n";
    }
    
    // Test writing to database
    echo "\n🧪 Testing database write...\n";
    $testStmt = $db->prepare("INSERT INTO staff_profiles (staff_id, full_name, job_title, department, account_status, date_joined) VALUES (?, ?, ?, ?, ?, ?)");
    $testStmt->execute(['TEST001', 'Test User', 'Test Position', 'Executive Leadership', 'Active', date('Y-m-d')]);
    
    echo "✅ Write test successful!\n";
    
    // Clean up test record
    $cleanupStmt = $db->prepare("DELETE FROM staff_profiles WHERE staff_id = 'TEST001'");
    $cleanupStmt->execute();
    
    echo "🧹 Test record cleaned up.\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "📁 Current directory: " . __DIR__ . "\n";
    echo "🗄️ Database path: " . __DIR__ . "/database/nexihub.db\n";
    echo "📂 Database exists: " . (file_exists(__DIR__ . "/database/nexihub.db") ? "Yes" : "No") . "\n";
}
?>
