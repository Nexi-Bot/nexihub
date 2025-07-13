<?php
require_once __DIR__ . '/../config/config.php';

// Create staff table
$createStaffTable = "
CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    discord_id VARCHAR(255) UNIQUE,
    discord_username VARCHAR(255),
    discord_discriminator VARCHAR(10),
    discord_avatar VARCHAR(255),
    two_fa_secret VARCHAR(255),
    two_fa_enabled BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_discord_id (discord_id)
)";

// Create password reset tokens table
$createResetTokensTable = "
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
)";

// Create staff sessions table
$createSessionsTable = "
CREATE TABLE IF NOT EXISTS staff_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    discord_verified BOOLEAN DEFAULT FALSE,
    email_verified BOOLEAN DEFAULT FALSE,
    two_fa_verified BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
    INDEX idx_token (session_token),
    INDEX idx_expires (expires_at)
)";

try {
    // Execute table creation
    $pdo->exec($createStaffTable);
    echo "Staff table created successfully\n";
    
    $pdo->exec($createResetTokensTable);
    echo "Password reset tokens table created successfully\n";
    
    $pdo->exec($createSessionsTable);
    echo "Staff sessions table created successfully\n";
    
    // Insert default staff member
    $defaultEmail = 'ollie.r@nexihub.uk';
    $defaultPassword = 'test1212';
    $hashedPassword = hashPassword($defaultPassword);
    
    $checkStaff = $pdo->prepare("SELECT id FROM staff WHERE email = ?");
    $checkStaff->execute([$defaultEmail]);
    
    if (!$checkStaff->fetch()) {
        $insertStaff = $pdo->prepare("
            INSERT INTO staff (email, password_hash) 
            VALUES (?, ?)
        ");
        $insertStaff->execute([$defaultEmail, $hashedPassword]);
        echo "Default staff member created: {$defaultEmail}\n";
    } else {
        echo "Default staff member already exists\n";
    }
    
    echo "Database setup completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error setting up database: " . $e->getMessage() . "\n";
}
?>
