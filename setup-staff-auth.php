<?php
/**
 * Setup script to prepare staff authentication for ollie.r@nexihub.uk
 * Adds required columns and configures the admin user for 3-step auth
 */

require_once __DIR__ . '/config/config.php';

try {
    $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Setting up staff authentication...\n";

    // Check current staff table structure
    $result = $db->query('PRAGMA table_info(staff)');
    $columns = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['name'];
    }

    // Add missing authentication columns
    $required_columns = [
        'discord_id' => 'TEXT',
        'two_fa_enabled' => 'BOOLEAN DEFAULT 0',
        'two_fa_secret' => 'TEXT',
        'is_active' => 'BOOLEAN DEFAULT 1'
    ];

    foreach ($required_columns as $column => $type) {
        if (!in_array($column, $columns)) {
            echo "Adding column: $column\n";
            $db->exec("ALTER TABLE staff ADD COLUMN $column $type");
        } else {
            echo "Column $column already exists\n";
        }
    }

    // Check if admin user exists in staff table
    $stmt = $db->prepare('SELECT * FROM staff WHERE email = ?');
    $stmt->execute(['ollie.r@nexihub.uk']);
    $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin_user) {
        echo "Creating admin user in staff table...\n";
        $stmt = $db->prepare('INSERT INTO staff (name, email, department, role, status, hire_date, salary, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            'Oliver Reaney',
            'ollie.r@nexihub.uk', 
            'Executive Leadership',
            'Managing Director',
            'active',
            '2023-01-01',
            85000.00,
            1,
            date('Y-m-d H:i:s')
        ]);
        $admin_id = $db->lastInsertId();
        echo "Admin user created with ID: $admin_id\n";
    } else {
        echo "Admin user already exists with ID: " . $admin_user['id'] . "\n";
        // Make sure the user is active
        $stmt = $db->prepare('UPDATE staff SET is_active = 1 WHERE email = ?');
        $stmt->execute(['ollie.r@nexihub.uk']);
        echo "Admin user set to active\n";
    }

    // Verify the setup
    $stmt = $db->prepare('SELECT * FROM staff WHERE email = ?');
    $stmt->execute(['ollie.r@nexihub.uk']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "\n--- Admin User Setup Complete ---\n";
        echo "ID: " . $user['id'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Status: " . $user['status'] . "\n";
        echo "Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
        echo "Discord ID: " . ($user['discord_id'] ?: 'Not linked yet') . "\n";
        echo "2FA Enabled: " . ($user['two_fa_enabled'] ? 'Yes' : 'No') . "\n";
        
        echo "\n--- Next Steps ---\n";
        echo "1. Go to: https://nexihub.uk/staff/login\n";
        echo "2. Click 'Login with Discord' to link Discord account\n";
        echo "3. Verify email step\n";
        echo "4. Setup 2FA on first login\n";
        echo "\nCredentials:\n";
        echo "Email: ollie.r@nexihub.uk\n";
        echo "Password: Geronimo2018! (for any additional verification)\n";
    }

    echo "\nAuthentication setup completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
