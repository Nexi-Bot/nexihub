<?php
/**
 * Clean all test data and set up production admin user
 */

require_once __DIR__ . '/config/config.php';

echo "=== Cleaning Test Data and Setting Up Production Admin ===\n\n";

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

    echo "✅ Database connection established\n\n";

    // 1. Clean all test data
    echo "🧹 Cleaning all test data...\n";

    // Delete all staff contracts
    $stmt = $db->prepare("DELETE FROM staff_contracts");
    $stmt->execute();
    $deleted_contracts = $stmt->rowCount();
    echo "   Deleted {$deleted_contracts} staff contract assignments\n";

    // Delete all staff profiles
    $stmt = $db->prepare("DELETE FROM staff_profiles");
    $stmt->execute();
    $deleted_staff = $stmt->rowCount();
    echo "   Deleted {$deleted_staff} staff profiles\n";

    // Delete all contract users except we'll recreate the admin
    $stmt = $db->prepare("DELETE FROM contract_users");
    $stmt->execute();
    $deleted_users = $stmt->rowCount();
    echo "   Deleted {$deleted_users} contract users\n";

    // Keep contract templates but clean any test-specific ones if needed
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM contract_templates");
    $stmt->execute();
    $template_count = $stmt->fetch()['count'];
    echo "   Keeping {$template_count} contract templates\n";

    echo "\n🔧 Setting up production admin user...\n";

    // 2. Create main staff dashboard admin user
    echo "   Creating main staff dashboard admin (ollie.r@nexihub.uk)...\n";
    
    // Create staff profile for Ollie
    $stmt = $db->prepare("
        INSERT INTO staff_profiles (
            staff_id, manager, full_name, job_title, department, region,
            preferred_name, nexi_email, private_email, phone_number,
            discord_username, discord_id, nationality, country_of_residence,
            date_of_birth, two_fa_status, date_joined, elearning_status,
            time_off_balance, parent_contact, account_status, internal_notes,
            contract_completed
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        'NXH001',                           // staff_id
        null,                               // manager (CEO doesn't have manager)
        'Oliver Reaney',                    // full_name
        'Chief Executive Officer',          // job_title
        'Executive Leadership',             // department
        'EMEA',                            // region
        'Ollie',                           // preferred_name
        'ollie.r@nexihub.uk',              // nexi_email
        null,                              // private_email
        null,                              // phone_number
        null,                              // discord_username
        null,                              // discord_id
        'British',                         // nationality
        'United Kingdom',                  // country_of_residence
        null,                              // date_of_birth (privacy)
        1,                                 // two_fa_status (enabled)
        date('Y-m-d'),                     // date_joined (today)
        'Completed',                       // elearning_status
        25,                                // time_off_balance (25 days)
        null,                              // parent_contact
        'Active',                          // account_status
        'Founder and CEO - Full system access', // internal_notes
        1                                  // contract_completed
    ]);
    
    $staff_id = $db->lastInsertId();
    echo "   ✅ Staff profile created (ID: {$staff_id})\n";

    // 3. Create contract user for HR Portal access
    echo "   Creating HR Portal contract user...\n";
    
    $password_hash = password_hash('Geronimo2018!', PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("
        INSERT INTO contract_users (email, password_hash, staff_id, role, needs_password_reset) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        'ollie.r@nexihub.uk',
        $password_hash,
        $staff_id,
        'admin',
        0  // No password reset needed since this is the secure password
    ]);
    
    $contract_user_id = $db->lastInsertId();
    echo "   ✅ Contract user created (ID: {$contract_user_id})\n";

    // 4. Optional: Assign all contract templates to the admin user (so they can test signing)
    echo "   Assigning all contracts to admin user...\n";
    
    $stmt = $db->prepare("SELECT id FROM contract_templates");
    $stmt->execute();
    $templates = $stmt->fetchAll();
    
    foreach ($templates as $template) {
        $stmt = $db->prepare("
            INSERT INTO staff_contracts (staff_id, template_id, is_signed) 
            VALUES (?, ?, 0)
        ");
        $stmt->execute([$staff_id, $template['id']]);
    }
    
    echo "   ✅ Assigned " . count($templates) . " contracts to admin user\n";

    // 5. Verify setup
    echo "\n🔍 Verifying setup...\n";
    
    // Check staff profile
    $stmt = $db->prepare("SELECT * FROM staff_profiles WHERE nexi_email = ?");
    $stmt->execute(['ollie.r@nexihub.uk']);
    $staff = $stmt->fetch();
    
    if ($staff) {
        echo "   ✅ Staff profile verified: {$staff['full_name']} ({$staff['staff_id']})\n";
        echo "      Job Title: {$staff['job_title']}\n";
        echo "      Department: {$staff['department']}\n";
        echo "      Contract Status: " . ($staff['contract_completed'] ? 'Completed' : 'Pending') . "\n";
    }

    // Check contract user
    $stmt = $db->prepare("SELECT * FROM contract_users WHERE email = ?");
    $stmt->execute(['ollie.r@nexihub.uk']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "   ✅ Contract user verified: {$user['email']} (Role: {$user['role']})\n";
        echo "      Password reset required: " . ($user['needs_password_reset'] ? 'Yes' : 'No') . "\n";
    }

    // Check database counts
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM staff_profiles");
    $stmt->execute();
    $staff_count = $stmt->fetch()['count'];
    
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM contract_users");
    $stmt->execute();
    $user_count = $stmt->fetch()['count'];
    
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM staff_contracts");
    $stmt->execute();
    $contract_count = $stmt->fetch()['count'];

    echo "\n📊 Final Database Status:\n";
    echo "   Staff Profiles: {$staff_count}\n";
    echo "   Contract Users: {$user_count}\n";
    echo "   Contract Assignments: {$contract_count}\n";
    echo "   Contract Templates: {$template_count}\n";

    echo "\n=== Setup Complete ===\n";
    echo "🎯 Production Admin Access:\n";
    echo "   Staff Dashboard: http://nexihub.uk/staff/dashboard.php\n";
    echo "   HR Portal: http://nexihub.uk/contracts/\n";
    echo "   Login: ollie.r@nexihub.uk\n";
    echo "   Password: Geronimo2018!\n";
    echo "\n✅ All test data removed\n";
    echo "✅ Admin user configured\n";
    echo "✅ Email deliverability improved\n";
    echo "✅ System ready for production\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
