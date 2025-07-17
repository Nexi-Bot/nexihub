<?php
/**
 * Setup script for production MySQL database
 * Creates the staff table and admin user for production
 */

require_once __DIR__ . '/config/config.php';

echo "Setting up production database...\n";

try {
    // Force MySQL connection for production setup
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    echo "✓ Connected to MySQL database\n";

    // Create staff table if it doesn't exist
    $createTable = "
    CREATE TABLE IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        department VARCHAR(255),
        role VARCHAR(255),
        status VARCHAR(50) DEFAULT 'active',
        hire_date DATE,
        salary DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        discord_id VARCHAR(255),
        two_fa_enabled BOOLEAN DEFAULT 0,
        two_fa_secret TEXT,
        is_active BOOLEAN DEFAULT 1,
        password_hash TEXT
    )";
    
    $pdo->exec($createTable);
    echo "✓ Staff table created/verified\n";

    // Check if admin user exists
    $stmt = $pdo->prepare('SELECT * FROM staff WHERE email = ?');
    $stmt->execute(['ollie.r@nexihub.uk']);
    $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin_user) {
        echo "Creating admin user in production database...\n";
        
        // Hash the password
        $password_hash = password_hash('Geronimo2018!', PASSWORD_ARGON2ID);
        
        $stmt = $pdo->prepare('INSERT INTO staff (name, email, department, role, status, hire_date, salary, is_active, discord_id, password_hash, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            'Oliver Reaney',
            'ollie.r@nexihub.uk', 
            'Executive Leadership',
            'Managing Director',
            'active',
            '2023-01-01',
            85000.00,
            1,
            '876400589628670022', // Discord ID
            $password_hash,
            date('Y-m-d H:i:s')
        ]);
        $admin_id = $pdo->lastInsertId();
        echo "✓ Admin user created with ID: $admin_id\n";
    } else {
        echo "Admin user already exists with ID: " . $admin_user['id'] . "\n";
        
        // Update admin user with required fields
        $password_hash = password_hash('Geronimo2018!', PASSWORD_ARGON2ID);
        $stmt = $pdo->prepare('UPDATE staff SET discord_id = ?, password_hash = ?, is_active = 1 WHERE email = ?');
        $stmt->execute(['876400589628670022', $password_hash, 'ollie.r@nexihub.uk']);
        echo "✓ Admin user updated\n";
    }

    // Verify the setup
    $stmt = $pdo->prepare('SELECT * FROM staff WHERE email = ?');
    $stmt->execute(['ollie.r@nexihub.uk']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "\n--- Production Admin User Setup Complete ---\n";
        echo "ID: " . $user['id'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Status: " . $user['status'] . "\n";
        echo "Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
        echo "Discord ID: " . ($user['discord_id'] ?: 'Not linked') . "\n";
        echo "2FA Enabled: " . ($user['two_fa_enabled'] ? 'Yes' : 'No') . "\n";
        echo "Password Hash: " . (strlen($user['password_hash']) > 0 ? 'Set' : 'Not set') . "\n";
    }

    echo "\nProduction database setup completed successfully!\n";
    echo "The staff login at https://nexihub.uk/staff/login should now work.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
