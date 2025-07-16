<?php
// Test adding a staff member directly
echo "🧪 Testing staff addition...\n";

try {
    $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add a test staff member
    $stmt = $db->prepare("
        INSERT INTO staff_profiles (
            staff_id, full_name, job_title, department, nexi_email,
            account_status, date_joined, two_fa_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        'NXH002',
        'John Test',
        'Test Manager',
        'Corporate Functions',
        'john.test@nexihub.com',
        'Active',
        date('Y-m-d'),
        0
    ]);
    
    echo "✅ Test staff member added!\n";
    
    // Verify it was added
    $verifyStmt = $db->prepare("SELECT * FROM staff_profiles ORDER BY staff_id");
    $verifyStmt->execute();
    $staff = $verifyStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Current staff members:\n";
    foreach ($staff as $member) {
        echo "  - {$member['full_name']} ({$member['staff_id']}) - {$member['department']}\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
