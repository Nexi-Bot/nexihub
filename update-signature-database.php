<?php
require_once __DIR__ . '/config/config.php';

try {
    // Connect to database
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    echo "🔄 Updating staff_contracts table for enhanced signature system...\n";

    // Add new columns to staff_contracts table
    $alterQueries = [
        "ALTER TABLE staff_contracts ADD COLUMN signer_full_name VARCHAR(100)",
        "ALTER TABLE staff_contracts ADD COLUMN signer_position VARCHAR(100)", 
        "ALTER TABLE staff_contracts ADD COLUMN signer_date_of_birth DATE",
        "ALTER TABLE staff_contracts ADD COLUMN is_under_17 BOOLEAN DEFAULT 0",
        "ALTER TABLE staff_contracts ADD COLUMN guardian_full_name VARCHAR(100)",
        "ALTER TABLE staff_contracts ADD COLUMN guardian_email VARCHAR(100)",
        "ALTER TABLE staff_contracts ADD COLUMN guardian_signature_data TEXT",
        "ALTER TABLE staff_contracts ADD COLUMN signed_timestamp DATETIME"
    ];

    foreach ($alterQueries as $query) {
        try {
            $db->exec($query);
            echo "✅ Added column: " . substr($query, strpos($query, 'ADD COLUMN') + 11) . "\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate column name') !== false) {
                echo "⚠️  Column already exists: " . substr($query, strpos($query, 'ADD COLUMN') + 11) . "\n";
            } else {
                echo "❌ Error adding column: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "\n✅ Database update completed successfully!\n";

} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
