<?php
// Test database connection from staff directory
echo "📍 Testing from staff directory...\n";
echo "📁 Current directory: " . __DIR__ . "\n";
echo "🗄️ Database path: " . __DIR__ . "/../database/nexihub.db\n";
echo "📂 Database exists: " . (file_exists(__DIR__ . "/../database/nexihub.db") ? "Yes" : "No") . "\n";

try {
    $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful from staff directory!\n";
    
    // Test reading from staff_profiles
    $stmt = $db->prepare("SELECT * FROM staff_profiles");
    $stmt->execute();
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Found " . count($staff) . " staff members:\n";
    foreach ($staff as $member) {
        echo "  - {$member['full_name']} ({$member['staff_id']}) - {$member['department']}\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
?>
