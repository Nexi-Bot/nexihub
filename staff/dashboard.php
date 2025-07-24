<?php
require_once __DIR__ . '/../config/config.php';

// Prevent caching to ensure fresh data
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

requireAuth(); // Enable when ready

$page_title = "Staff Management Dashboard";
$page_description = "Nexi Hub Staff Management System";

// Use the global $pdo connection from config.php
$db = $pdo;

// Detect database type for proper SQL syntax
$is_mysql = ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql');
$auto_increment = $is_mysql ? 'AUTO_INCREMENT' : 'AUTOINCREMENT';
$int_primary = $is_mysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';

// Create staff table if it doesn't exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS staff_profiles (
    id $int_primary,
    staff_id VARCHAR(20) UNIQUE NOT NULL,
    manager VARCHAR(100),
    full_name VARCHAR(100) NOT NULL,
    job_title VARCHAR(100),
    department VARCHAR(100),
    region VARCHAR(10),
    preferred_name VARCHAR(50),
    nexi_email VARCHAR(100),
    private_email VARCHAR(100),
    phone_number VARCHAR(20),
    discord_username VARCHAR(50),
    discord_id VARCHAR(50),
    nationality VARCHAR(50),
    country_of_residence VARCHAR(50),
    date_of_birth DATE,
    last_login DATETIME,
    two_fa_status BOOLEAN DEFAULT 0,
    date_joined DATE,
    elearning_status VARCHAR(50) DEFAULT 'Not Started',
    time_off_balance " . ($is_mysql ? 'INT' : 'INTEGER') . " DEFAULT 0,
    parent_contact TEXT,
    payroll_info TEXT,
    password_reset_history TEXT,
    account_status VARCHAR(20) DEFAULT 'Active',
    internal_notes TEXT,
    contract_completed BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP" . ($is_mysql ? " ON UPDATE CURRENT_TIMESTAMP" : "") . "
)";

try {
    $db->exec($createTableSQL);
    
    // Add contract_completed column if it doesn't exist
    try {
        $db->exec("ALTER TABLE staff_profiles ADD COLUMN contract_completed BOOLEAN DEFAULT 0");
    } catch (PDOException $e) {
        // Column already exists, ignore error
        if (!str_contains($e->getMessage(), 'duplicate column name') && !str_contains($e->getMessage(), 'Duplicate column name')) {
            error_log("Error adding contract_completed column: " . $e->getMessage());
        }
    }
    
    // Create contract management tables
    $contractTablesSQL = [
        "CREATE TABLE IF NOT EXISTS contract_templates (
            id $int_primary,
            name VARCHAR(100) NOT NULL,
            type VARCHAR(50) NOT NULL,
            content LONGTEXT NOT NULL,
            is_assignable BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP" . ($is_mysql ? " ON UPDATE CURRENT_TIMESTAMP" : "") . "
        )",
        "CREATE TABLE IF NOT EXISTS staff_contracts (
            id $int_primary,
            staff_id " . ($is_mysql ? 'INT' : 'INTEGER') . " NOT NULL,
            template_id " . ($is_mysql ? 'INT' : 'INTEGER') . " NOT NULL,
            signed_at DATETIME,
            signature_data TEXT,
            is_signed BOOLEAN DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP" . ($is_mysql ? ",
            FOREIGN KEY (staff_id) REFERENCES staff_profiles(id),
            FOREIGN KEY (template_id) REFERENCES contract_templates(id)" : "") . "
        )",
        "CREATE TABLE IF NOT EXISTS contract_users (
            id $int_primary,
            email VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            staff_id " . ($is_mysql ? 'INT' : 'INTEGER') . ",
            role VARCHAR(20) DEFAULT 'staff',
            needs_password_reset BOOLEAN DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP" . ($is_mysql ? ",
            FOREIGN KEY (staff_id) REFERENCES staff_profiles(id)" : "") . "
        )"
    ];
    
    foreach ($contractTablesSQL as $sql) {
        $db->exec($sql);
    }

    // Add shareholder tracking columns to staff_profiles
    try {
        $db->exec("ALTER TABLE staff_profiles ADD COLUMN is_shareholder BOOLEAN DEFAULT 0");
        $db->exec("ALTER TABLE staff_profiles ADD COLUMN shareholder_percentage DECIMAL(5,2) DEFAULT 0.00");
        
        // Add staff_type column with proper syntax for both MySQL and SQLite
        if ($is_mysql) {
            $db->exec("ALTER TABLE staff_profiles ADD COLUMN staff_type VARCHAR(20) DEFAULT 'volunteer'");
        } else {
            $db->exec("ALTER TABLE staff_profiles ADD COLUMN staff_type TEXT DEFAULT 'volunteer'");
        }
    } catch (PDOException $e) {
        // Columns may already exist
        if (!str_contains($e->getMessage(), 'duplicate column name') && !str_contains($e->getMessage(), 'Duplicate column name')) {
            error_log("Error adding shareholder columns: " . $e->getMessage());
        }
    }
    
    // Add is_assignable column to contract_templates if it doesn't exist
    try {
        $db->exec("ALTER TABLE contract_templates ADD COLUMN is_assignable BOOLEAN DEFAULT 1");
    } catch (PDOException $e) {
        // Column already exists, ignore error
        if (!str_contains($e->getMessage(), 'duplicate column name') && !str_contains($e->getMessage(), 'Duplicate column name')) {
            error_log("Error adding is_assignable column: " . $e->getMessage());
        }
    }

    // Contract templates are managed separately - no auto-initialization needed
    
} catch (PDOException $e) {
    error_log("Error creating staff_profiles table: " . $e->getMessage());
}

// Check if Oliver Reaney exists, if not add him
$checkOliver = $db->prepare("SELECT COUNT(*) FROM staff_profiles WHERE staff_id = ?");
$checkOliver->execute(['NXH001']);
$oliverExists = $checkOliver->fetchColumn();

if (!$oliverExists) {
    $insertOliver = $db->prepare("
        INSERT INTO staff_profiles (
            staff_id, full_name, job_title, department, nexi_email,
            account_status, date_joined, two_fa_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    try {
        $insertOliver->execute([
            'NXH001',
            'Oliver Reaney',
            'Founder & CEO',
            'Executive Leadership',
            'oliver@nexihub.com',
            'Active',
            '2024-01-01',
            1
        ]);
    } catch (PDOException $e) {
        error_log("Error inserting Oliver Reaney: " . $e->getMessage());
    }
}

// Contract templates are now properly initialized - removed duplicate initialization code

// Initialize contract portal user if it doesn't exist
$checkContractUser = $db->prepare("SELECT COUNT(*) FROM contract_users WHERE email = ?");
$checkContractUser->execute(['contract@nexihub.uk']);
$contractUserExists = $checkContractUser->fetchColumn();

if (!$contractUserExists) {
    $insertContractUser = $db->prepare("INSERT INTO contract_users (email, password_hash, role) VALUES (?, ?, ?)");
    try {
        $insertContractUser->execute([
            'contract@nexihub.uk',
            '$2y$12$sXiFjaox6dAUXIHvjb6mbuCeXBc3caww3V.p63jllXvJey8bZ/.3q',
            'staff'
        ]);
    } catch (PDOException $e) {
        error_log("Error inserting contract user: " . $e->getMessage());
    }
}

// Success/error messages
$success_message = '';
$error_message = '';

if (isset($_GET['success'])) {
    $success_message = htmlspecialchars($_GET['success']);
}
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_staff':
                // Add new staff member
                $stmt = $db->prepare("
                    INSERT INTO staff_profiles (
                        staff_id, manager, full_name, job_title, department, region,
                        preferred_name, nexi_email, private_email, phone_number,
                        discord_username, discord_id, nationality, country_of_residence,
                        date_of_birth, two_fa_status, date_joined, elearning_status,
                        time_off_balance, parent_contact, account_status, internal_notes,
                        contract_completed, staff_type, is_shareholder, shareholder_percentage
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                try {
                    $staff_type = $_POST['staff_type'] ?? 'volunteer';
                    $is_shareholder = ($staff_type === 'shareholder') ? 1 : 0;
                    $shareholder_percentage = ($staff_type === 'shareholder') ? floatval($_POST['shareholder_percentage'] ?? 0) : 0.00;
                    
                    $stmt->execute([
                        $_POST['staff_id'],
                        $_POST['manager'],
                        $_POST['full_name'],
                        $_POST['job_title'],
                        $_POST['department'],
                        $_POST['region'],
                        $_POST['preferred_name'],
                        $_POST['nexi_email'],
                        $_POST['private_email'],
                        $_POST['phone_number'],
                        $_POST['discord_username'],
                        $_POST['discord_id'],
                        $_POST['nationality'],
                        $_POST['country_of_residence'],
                        $_POST['date_of_birth'],
                        isset($_POST['two_fa_status']) ? 1 : 0,
                        $_POST['date_joined'],
                        $_POST['elearning_status'],
                        intval($_POST['time_off_balance']),
                        $_POST['parent_contact'],
                        $_POST['account_status'],
                        $_POST['internal_notes'],
                        isset($_POST['contract_completed']) ? 1 : 0,
                        $staff_type,
                        $is_shareholder,
                        $shareholder_percentage
                    ]);
                    header("Location: dashboard.php?success=Staff member added successfully");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error adding staff member: " . $e->getMessage();
                }
                break;
                
            case 'update_staff':
                // Update existing staff member
                $stmt = $db->prepare("
                    UPDATE staff_profiles SET 
                        manager = ?, full_name = ?, job_title = ?, department = ?, region = ?,
                        preferred_name = ?, nexi_email = ?, private_email = ?, phone_number = ?,
                        discord_username = ?, discord_id = ?, nationality = ?, country_of_residence = ?,
                        date_of_birth = ?, two_fa_status = ?, date_joined = ?, elearning_status = ?,
                        time_off_balance = ?, parent_contact = ?, account_status = ?, internal_notes = ?,
                        contract_completed = ?, staff_type = ?, is_shareholder = ?, shareholder_percentage = ?, 
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                
                try {
                    $staff_type = $_POST['staff_type'] ?? 'volunteer';
                    $is_shareholder = ($staff_type === 'shareholder') ? 1 : 0;
                    $shareholder_percentage = ($staff_type === 'shareholder') ? floatval($_POST['shareholder_percentage'] ?? 0) : 0.00;
                    
                    $stmt->execute([
                        $_POST['manager'],
                        $_POST['full_name'],
                        $_POST['job_title'],
                        $_POST['department'],
                        $_POST['region'],
                        $_POST['preferred_name'],
                        $_POST['nexi_email'],
                        $_POST['private_email'],
                        $_POST['phone_number'],
                        $_POST['discord_username'],
                        $_POST['discord_id'],
                        $_POST['nationality'],
                        $_POST['country_of_residence'],
                        $_POST['date_of_birth'],
                        isset($_POST['two_fa_status']) ? 1 : 0,
                        $_POST['date_joined'],
                        $_POST['elearning_status'],
                        intval($_POST['time_off_balance']),
                        $_POST['parent_contact'],
                        $_POST['account_status'],
                        $_POST['internal_notes'],
                        isset($_POST['contract_completed']) ? 1 : 0,
                        $staff_type,
                        $is_shareholder,
                        $shareholder_percentage,
                        $_POST['edit_staff_id']
                    ]);
                    header("Location: dashboard.php?success=Staff member updated successfully");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error updating staff member: " . $e->getMessage();
                }
                break;
                
            case 'delete_staff':
                // Delete staff member
                $stmt = $db->prepare("DELETE FROM staff_profiles WHERE id = ?");
                try {
                    $stmt->execute([$_POST['delete_staff_id']]);
                    header("Location: dashboard.php?success=Staff member deleted successfully");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error deleting staff member: " . $e->getMessage();
                }
                break;
                
            case 'add_contract_template':
                // Add new contract template
                $stmt = $db->prepare("INSERT INTO contract_templates (name, type, content) VALUES (?, ?, ?)");
                try {
                    $stmt->execute([
                        $_POST['contract_name'],
                        $_POST['contract_type'],
                        $_POST['contract_content']
                    ]);
                    header("Location: dashboard.php?success=Contract template added successfully");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error adding contract template: " . $e->getMessage();
                }
                break;
                
            case 'update_contract_template':
                // Update contract template
                $stmt = $db->prepare("UPDATE contract_templates SET name = ?, type = ?, content = ? WHERE id = ?");
                try {
                    $stmt->execute([
                        $_POST['contract_name'],
                        $_POST['contract_type'],
                        $_POST['contract_content'],
                        $_POST['contract_id']
                    ]);
                    header("Location: dashboard.php?success=Contract template updated successfully");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error updating contract template: " . $e->getMessage();
                }
                break;
                
            case 'create_contract_user':
                // Create new contract user
                $staff_id = $_POST['staff_id'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $selected_contracts = $_POST['selected_contracts'] ?? [];
                
                try {
                    // Hash the password
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert contract user
                    $stmt = $db->prepare("INSERT INTO contract_users (email, password_hash, staff_id, role, needs_password_reset) VALUES (?, ?, ?, 'staff', 1)");
                    $stmt->execute([$email, $password_hash, $staff_id]);
                    
                    // Get the contract user ID
                    $contract_user_id = $db->lastInsertId();
                    
                    // Assign selected contracts to the staff member
                    foreach ($selected_contracts as $template_id) {
                        $stmt = $db->prepare("INSERT IGNORE INTO staff_contracts (staff_id, template_id, is_signed) VALUES (?, ?, 0)");
                        $stmt->execute([$staff_id, $template_id]);
                    }
                    
                    header("Location: dashboard.php?success=Contract user created successfully. Login email: $email");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error creating contract user: " . $e->getMessage();
                }
                break;
                
            case 'download_signed_contract':
                // Download signed contract PDF
                if (isset($_POST['contract_id'])) {
                    require_once __DIR__ . '/../includes/ContractEmailNotifier.php';
                    
                    try {
                        // Get contract details
                        $stmt = $db->prepare("
                            SELECT ct.name, ct.content, ct.type, ct.id as template_id,
                                   sc.id as contract_id, sc.is_signed, sc.signed_at, sc.signature_data,
                                   sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
                                   sc.is_under_17, sc.guardian_full_name, sc.guardian_email, 
                                   sc.guardian_signature_data, sc.signed_timestamp, sc.staff_id
                            FROM contract_templates ct
                            JOIN staff_contracts sc ON ct.id = sc.template_id 
                            WHERE sc.id = ? AND sc.is_signed = 1
                        ");
                        $stmt->execute([$_POST['contract_id']]);
                        $contract = $stmt->fetch();
                        
                        if ($contract) {
                            // Use the same PDF generation as the contract system
                            $notifier = new ContractEmailNotifier();
                            $reflector = new ReflectionClass($notifier);
                            $generatePDF = $reflector->getMethod('generateContractPDF');
                            $generatePDF->setAccessible(true);
                            
                            $pdf_content = $generatePDF->invoke($notifier, $contract['template_id'], $contract['staff_id']);
                            
                            if ($pdf_content) {
                                $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $contract['name']) . '_signed.pdf';
                                
                                header('Content-Type: application/pdf');
                                header('Content-Disposition: attachment; filename="' . $filename . '"');
                                header('Content-Length: ' . strlen($pdf_content));
                                echo $pdf_content;
                                exit;
                            }
                        }
                        
                        $error_message = "Contract not found or not signed";
                    } catch (Exception $e) {
                        $error_message = "Error generating PDF: " . $e->getMessage();
                    }
                }
                break;
                
            case 'delete_contract_template':
                // Delete contract template
                $stmt = $db->prepare("DELETE FROM contract_templates WHERE id = ?");
                try {
                    $stmt->execute([$_POST['contract_id']]);
                    header("Location: dashboard.php?success=Contract template deleted successfully");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error deleting contract template: " . $e->getMessage();
                }
                break;
                
            case 'assign_contract':
                // Assign contract to staff member
                $stmt = $db->prepare("
                    INSERT IGNORE INTO staff_contracts (staff_id, template_id, is_signed) 
                    VALUES (?, ?, 0)
                ");
                try {
                    $stmt->execute([
                        $_POST['staff_id'],
                        $_POST['template_id']
                    ]);
                    header("Location: dashboard.php?success=Contract assigned to staff member");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error assigning contract: " . $e->getMessage();
                }
                break;
        }
    }
}

// Function to update contract completion status for all staff
function updateContractCompletionStatus($db) {
    try {
        // Get total number of contract templates
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM contract_templates");
        $stmt->execute();
        $total_contracts = $stmt->fetch()['total'];
        
        if ($total_contracts == 0) return;
        
        // Get all staff members
        $stmt = $db->prepare("SELECT id FROM staff_profiles");
        $stmt->execute();
        $staff_members = $stmt->fetchAll();
        
        foreach ($staff_members as $staff) {
            // Count signed contracts for this staff member
            $stmt = $db->prepare("
                SELECT COUNT(*) as signed_count 
                FROM staff_contracts 
                WHERE staff_id = ? AND is_signed = 1
            ");
            $stmt->execute([$staff['id']]);
            $signed_count = $stmt->fetch()['signed_count'];
            
            // Update contract_completed status
            $is_completed = ($signed_count >= $total_contracts && $total_contracts > 0) ? 1 : 0;
            $stmt = $db->prepare("UPDATE staff_profiles SET contract_completed = ? WHERE id = ?");
            $stmt->execute([$is_completed, $staff['id']]);
        }
    } catch (PDOException $e) {
        error_log("Error updating contract completion status: " . $e->getMessage());
    }
}

// Update contract completion status for all staff
updateContractCompletionStatus($db);

// Fetch all staff members
$stmt = $db->prepare("SELECT * FROM staff_profiles ORDER BY department, region, full_name");
$stmt->execute();
$staff_members = $stmt->fetchAll();

// Fetch all contract templates
$stmt = $db->prepare("SELECT * FROM contract_templates WHERE is_assignable = 1 ORDER BY name");
$stmt->execute();
$contract_templates = $stmt->fetchAll();

// Fetch contract assignments and signatures
$stmt = $db->prepare("
    SELECT 
        sp.id as staff_id,
        sp.full_name,
        ct.id as template_id,
        ct.name as template_name,
        ct.type as template_type,
        sc.is_signed,
        sc.signed_at,
        sc.signature_data,
        sc.signer_full_name,
        sc.signer_position,
        sc.signer_date_of_birth,
        sc.is_under_17,
        sc.guardian_full_name,
        sc.guardian_email,
        sc.guardian_signature_data,
        sc.signed_timestamp
    FROM staff_profiles sp
    LEFT JOIN staff_contracts sc ON sp.id = sc.staff_id
    LEFT JOIN contract_templates ct ON sc.template_id = ct.id
    ORDER BY sp.full_name, ct.name
");
$stmt->execute();
$contract_assignments = $stmt->fetchAll();

// Group contract assignments by staff
$contracts_by_staff = [];
foreach ($contract_assignments as $assignment) {
    $staff_id = $assignment['staff_id'];
    if (!isset($contracts_by_staff[$staff_id])) {
        $contracts_by_staff[$staff_id] = [];
    }
    if ($assignment['template_id']) {
        $contracts_by_staff[$staff_id][] = $assignment;
    }
}

// Group staff by department and region
$staff_by_department = [];
foreach ($staff_members as $staff) {
    $dept = $staff['department'] ?: 'Unassigned';
    $region = $staff['region'] ?: 'No Region';
    
    if (!isset($staff_by_department[$dept])) {
        $staff_by_department[$dept] = [];
    }
    if (!isset($staff_by_department[$dept][$region])) {
        $staff_by_department[$dept][$region] = [];
    }
    
    $staff_by_department[$dept][$region][] = $staff;
}

// Calculate age from date of birth
function calculateAge($dateOfBirth) {
    if (!$dateOfBirth) return 'N/A';
    $dob = new DateTime($dateOfBirth);
    $now = new DateTime();
    return $now->diff($dob)->y;
}

include __DIR__ . '/../includes/header.php';
?>

<style>
/* Dashboard Styles matching Nexi Hub theme */
.dashboard-section {
    background: var(--background-dark);
    padding: 4rem 0;
    min-height: 100vh;
}

.dashboard-header {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.dashboard-header h1 {
    font-size: 3rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.dashboard-header p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 1.2rem;
}

.alert {
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 8px;
    border: 1px solid transparent;
    font-weight: 500;
}

.alert-success {
    background-color: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-color: rgba(16, 185, 129, 0.2);
}

.alert-danger {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.2);
}

.dashboard-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
}

.dashboard-stats {
    color: var(--text-secondary);
    font-size: 1rem;
}

.dashboard-stats strong {
    color: var(--primary-color);
    font-size: 1.2rem;
}

.staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 2rem;
}

.staff-card {
    background: var(--background-light);
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.staff-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transform: scaleX(0);
    transition: all 0.3s ease;
}

.staff-card:hover::before {
    transform: scaleX(1);
}

.staff-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px var(--shadow-medium);
    border-color: var(--primary-color);
}

.staff-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.staff-name {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.staff-id {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.staff-details {
    display: grid;
    gap: 0.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.3rem 0;
}

.detail-label {
    font-weight: 500;
    color: var(--text-secondary);
    min-width: 100px;
}

.detail-value {
    color: var(--text-primary);
    text-align: right;
}

.status-active {
    color: #10b981;
    font-weight: 600;
}

.status-inactive {
    color: #ef4444;
    font-weight: 600;
}

.two-fa-enabled {
    color: #10b981;
    font-weight: 600;
}

.two-fa-disabled {
    color: #ef4444;
    font-weight: 600;
}

.contract-completed {
    color: #10b981;
    font-weight: 600;
}

.contract-pending {
    color: #f59e0b;
    font-weight: 600;
}

.staff-shareholder {
    color: #e64f21;
    font-weight: 600;
}

.staff-volunteer {
    color: #6b7280;
    font-weight: 600;
}

.staff-actions {
    margin-top: auto;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.btn-sm {
    padding: 0.6rem 1.2rem;
    font-size: 0.9rem;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-success {
    background: #10b981;
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
}

.btn-warning {
    background: #f59e0b;
    color: white;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
}

.btn-warning:hover {
    background: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
}

.btn-danger {
    background: #ef4444;
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-secondary);
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
    color: var(--primary-color);
}

.empty-state h3 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

/* Department and Region Styles */
.department-section {
    margin-bottom: 3rem;
}

.department-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 16px;
    color: white;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.department-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.department-title i {
    font-size: 1.5rem;
}

.department-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.region-section {
    margin-bottom: 2rem;
}

.region-header {
    margin-bottom: 1.5rem;
}

.region-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    border-left: 4px solid var(--primary-color);
}

.region-title i {
    color: var(--primary-color);
    font-size: 1.1rem;
}

.region-count {
    color: var(--text-secondary);
    font-weight: 500;
    margin-left: 0.5rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: var(--background-light);
    margin: 2% auto;
    padding: 0;
    border-radius: 16px;
    width: 90%;
    max-width: 700px;
    max-height: 95vh;
    overflow-y: auto;
    border: 1px solid var(--border-color);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--background-dark);
    border-radius: 16px 16px 0 0;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.close {
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    color: var(--text-secondary);
    background: none;
    border: none;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.close:hover {
    color: var(--primary-color);
    background: rgba(230, 79, 33, 0.1);
}

.modal-body {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section h3 {
    color: var(--primary-color);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    background: var(--background-dark);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(230, 79, 33, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--background-dark);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.checkbox-group input[type="checkbox"] {
    width: auto;
    margin: 0;
    accent-color: var(--primary-color);
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    background: var(--background-dark);
    border-radius: 0 0 16px 16px;
}

.btn-secondary {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: var(--border-color);
}

/* Tab Navigation Styles */
.tab-navigation {
    display: flex;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    margin-bottom: 2rem;
    padding: 0.5rem;
    gap: 0.5rem;
}

.tab-button {
    flex: 1;
    padding: 1rem 1.5rem;
    background: transparent;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    color: var(--text-secondary);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.tab-button:hover {
    background: var(--background-dark);
    color: var(--text-primary);
}

.tab-button.active {
    background: var(--primary-color);
    color: white;
    box-shadow: 0 4px 12px rgba(230, 79, 33, 0.3);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Contract Management Styles */
.contracts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.contract-card {
    background: var(--background-light);
    border-radius: 16px;
    padding: 0;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
    overflow: hidden;
}

.contract-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px var(--shadow-medium);
    border-color: var(--primary-color);
}

.contract-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--background-dark);
}

.contract-header h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1.2rem;
}

.contract-type-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.contract-type-badge.shareholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.contract-type-badge.nda {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.contract-type-badge.conduct {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.contract-type-badge.policies {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.contract-content {
    padding: 1.5rem;
}

.contract-preview {
    background: var(--background-dark);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.contract-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

/* E-Learning Styles */
.elearning-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px var(--shadow-medium);
    border-color: var(--primary-color);
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.elearning-staff-table {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 2rem;
}

.elearning-staff-table h3 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
}

.table-responsive {
    overflow-x: auto;
}

.staff-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--background-dark);
    border-radius: 8px;
    overflow: hidden;
}

.staff-table th,
.staff-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.staff-table th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.staff-table tr:last-child td {
    border-bottom: none;
}

.staff-table tr:hover {
    background: var(--background-light);
}

.staff-info strong {
    color: var(--text-primary);
    display: block;
    margin-bottom: 0.25rem;
}

.staff-info small {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge.completed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.status-badge.in-progress {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.status-badge.not-started {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
}

.btn-outline:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.portal-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.portal-link:hover {
    text-decoration: underline;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--background-dark);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    margin-bottom: 0.75rem;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    font-weight: 500;
    color: var(--text-primary);
    min-width: 120px;
}

.detail-value {
    color: var(--text-secondary);
    text-align: right;
}

/* Portal Access Styles */
.portal-header {
    background: var(--background-light);
    padding: 2rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
    text-align: center;
}

.portal-header h2 {
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
}

.portal-header p {
    color: var(--text-secondary);
    margin: 0;
}

.portal-info-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.portal-info-card h3 {
    color: var(--primary-color);
    margin: 0 0 1rem 0;
    font-size: 1.3rem;
}

.login-details {
    display: grid;
    gap: 1rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--background-dark);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.detail-row strong {
    color: var(--text-primary);
    min-width: 120px;
}

.detail-row a {
    color: var(--primary-color);
    text-decoration: none;
}

.detail-row a:hover {
    text-decoration: underline;
}

.contract-status-section {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 2rem;
}

.contract-status-section h3 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
}

.staff-contracts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.staff-contract-card {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.staff-contract-card:hover {
    border-color: var(--primary-color);
    box-shadow: 0 8px 20px var(--shadow-light);
}

.staff-contract-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.staff-contract-header h4 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.contract-progress {
    text-align: right;
}

.progress-text {
    font-size: 0.85rem;
    color: var(--text-secondary);
    display: block;
    margin-bottom: 0.5rem;
}

.progress-bar {
    width: 100px;
    height: 6px;
    background: var(--border-color);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transition: width 0.3s ease;
}

.contract-list {
    display: grid;
    gap: 0.75rem;
}

.contract-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--background-light);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.contract-name {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.status {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.3rem 0.75rem;
    border-radius: 12px;
}

.status.signed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.status.assigned {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.status.not-assigned {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.assign-btn {
    margin-left: 0.5rem;
    padding: 0.3rem 0.75rem !important;
    font-size: 0.75rem !important;
}

/* E-Learning Portal Management Styles */
.elearning-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.stat-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transform: scaleX(0);
    transition: all 0.3s ease;
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px var(--shadow-medium);
    border-color: var(--primary-color);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.8;
}

.stat-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary-color);
    margin: 0;
}

.stat-label {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 500;
    margin: 0;
}

.elearning-staff-table {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-top: 2rem;
}

.elearning-staff-table h3 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
}

.table-responsive {
    overflow-x: auto;
}

.staff-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--background-dark);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px var(--shadow-light);
}

.staff-table thead {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.staff-table th,
.staff-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.staff-table th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.staff-table tbody tr {
    transition: all 0.3s ease;
}

.staff-table tbody tr:hover {
    background: var(--background-light);
}

.staff-table tbody tr:last-child td {
    border-bottom: none;
}

.staff-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.staff-info strong {
    color: var(--text-primary);
    font-size: 1rem;
}

.staff-info small {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.completed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-badge.in-progress {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-badge.not-started {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

/* Time Off Management Styles */
.timeoff-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.timeoff-section {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin: 2rem 0;
}

.timeoff-section h3 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
}

.timeoff-requests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.timeoff-request-card {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
}

.timeoff-request-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    border-radius: 12px 12px 0 0;
}

.timeoff-request-card.pending::before {
    background: #f59e0b;
}

.timeoff-request-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--shadow-light);
    border-color: var(--primary-color);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.request-header h4 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.request-details {
    margin-bottom: 1rem;
}

.request-reason {
    background: var(--background-light);
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid var(--primary-color);
}

.request-reason strong {
    color: var(--text-primary);
    display: block;
    margin-bottom: 0.5rem;
}

.request-reason p {
    color: var(--text-secondary);
    margin: 0.5rem 0;
    line-height: 1.5;
}

.request-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.request-actions form {
    width: 100%;
}

.request-actions input[type="text"] {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background: var(--background-light);
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    box-sizing: border-box;
}

.request-actions input[type="text"]:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(230, 79, 33, 0.1);
}

.timeoff-all-requests {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-top: 2rem;
}

.timeoff-all-requests h3 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
}

.timeoff-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--background-dark);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px var(--shadow-light);
}

.timeoff-table thead {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.timeoff-table th,
.timeoff-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.timeoff-table th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.timeoff-table tbody tr {
    transition: all 0.3s ease;
}

.timeoff-table tbody tr:hover {
    background: var(--background-light);
}

.timeoff-table tbody tr:last-child td {
    border-bottom: none;
}

.text-muted {
    color: var(--text-secondary);
    font-style: italic;
}

@media (max-width: 768px) {
    .dashboard-header h1 {
        font-size: 2rem;
    }
    
    .dashboard-actions {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .staff-grid {
        grid-template-columns: 1fr;
    }
    
    .department-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1.5rem;
    }
    
    .department-title {
        font-size: 1.5rem;
    }
    
    .region-title {
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 5% auto;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-header, .modal-footer {
        padding: 1rem;
    }
}
</style>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header">
            <h1>Staff Management Dashboard</h1>
            <p>Secure Staff Information Management System - Region-Based Dashboard</p>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-button active" onclick="showTab('staff-tab')">
                <i class="fas fa-users"></i> Staff Management
            </button>
            <button class="tab-button" onclick="showTab('contracts-tab')">
                <i class="fas fa-file-contract"></i> Contract Management
            </button>
            <button class="tab-button" onclick="showTab('elearning-tab')">
                <i class="fas fa-graduation-cap"></i> E-Learning Portal
            </button>
            <button class="tab-button" onclick="showTab('timeoff-tab')">
                <i class="fas fa-calendar-alt"></i> Time Off Management
            </button>
            <button class="tab-button" onclick="showTab('contract-portal-tab')">
                <i class="fas fa-signature"></i> Nexi HR Portal Access
            </button>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Staff Management Tab -->
        <div id="staff-tab" class="tab-content active">
            <div class="dashboard-actions">
                <div class="dashboard-stats">
                    <i class="fas fa-users"></i> Total Staff: <strong><?php echo count($staff_members); ?></strong>
                </div>
                <button onclick="openAddModal()" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Staff Member
                </button>
            </div>

        <?php if (!empty($staff_members)): ?>
            <?php foreach ($staff_by_department as $department => $regions): ?>
                <div class="department-section">
                    <div class="department-header">
                        <h2 class="department-title">
                            <i class="fas fa-building"></i>
                            <?php echo htmlspecialchars($department); ?>
                        </h2>
                        <div class="department-count">
                            <?php 
                            $dept_count = 0;
                            foreach ($regions as $region_staff) {
                                $dept_count += count($region_staff);
                            }
                            echo $dept_count . ' member' . ($dept_count !== 1 ? 's' : '');
                            ?>
                        </div>
                    </div>
                    
                    <?php foreach ($regions as $region => $region_staff): ?>
                        <div class="region-section">
                            <div class="region-header">
                                <h3 class="region-title">
                                    <i class="fas fa-globe"></i>
                                    <?php echo htmlspecialchars($region); ?>
                                    <span class="region-count">(<?php echo count($region_staff); ?>)</span>
                                </h3>
                            </div>
                            
                            <div class="staff-grid">
                                <?php foreach ($region_staff as $staff): ?>
                                    <div class="staff-card">
                                        <div class="staff-header">
                                            <h3 class="staff-name"><?php echo htmlspecialchars($staff['full_name']); ?></h3>
                                            <span class="staff-id"><?php echo htmlspecialchars($staff['staff_id']); ?></span>
                                        </div>
                                        
                                        <div class="staff-details">
                                            <div class="detail-row">
                                                <span class="detail-label">Job Title:</span>
                                                <span class="detail-value"><?php echo htmlspecialchars($staff['job_title'] ?: 'Not Set'); ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Email:</span>
                                                <span class="detail-value"><?php echo htmlspecialchars($staff['nexi_email'] ?: 'Not Set'); ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Phone:</span>
                                                <span class="detail-value"><?php echo htmlspecialchars($staff['phone_number'] ?: 'Not Set'); ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Status:</span>
                                                <span class="detail-value <?php echo $staff['account_status'] === 'Active' ? 'status-active' : 'status-inactive'; ?>">
                                                    <?php echo htmlspecialchars($staff['account_status']); ?>
                                                </span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">2FA:</span>
                                                <span class="detail-value <?php echo $staff['two_fa_status'] ? 'two-fa-enabled' : 'two-fa-disabled'; ?>">
                                                    <?php echo $staff['two_fa_status'] ? 'Enabled' : 'Disabled'; ?>
                                                </span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Date Joined:</span>
                                                <span class="detail-value"><?php echo $staff['date_joined'] ? date('M j, Y', strtotime($staff['date_joined'])) : 'Not Set'; ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Contract:</span>
                                                <span class="detail-value <?php echo $staff['contract_completed'] ? 'contract-completed' : 'contract-pending'; ?>">
                                                    <?php echo $staff['contract_completed'] ? 'Completed' : 'Pending'; ?>
                                                </span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="detail-label">Staff Type:</span>
                                                <span class="detail-value <?php echo ($staff['staff_type'] ?? 'volunteer') === 'shareholder' ? 'staff-shareholder' : 'staff-volunteer'; ?>">
                                                    <?php 
                                                    $staff_type = $staff['staff_type'] ?? 'volunteer';
                                                    echo ucfirst($staff_type);
                                                    if ($staff_type === 'shareholder' && !empty($staff['shareholder_percentage'])) {
                                                        echo ' (' . number_format(floatval($staff['shareholder_percentage']), 2) . '%)';
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <?php if ($staff['date_of_birth'] && calculateAge($staff['date_of_birth']) < 16): ?>
                                                <div class="detail-row">
                                                    <span class="detail-label">Age:</span>
                                                    <span class="detail-value" style="color: var(--primary-color); font-weight: 600;">
                                                        <?php echo calculateAge($staff['date_of_birth']); ?> (Minor)
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="staff-actions">
                                            <button onclick="viewStaff(<?php echo $staff['id']; ?>)" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button onclick="editStaff(<?php echo $staff['id']; ?>)" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button onclick="deleteStaff(<?php echo $staff['id']; ?>, '<?php echo htmlspecialchars($staff['full_name']); ?>')" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h3>No Staff Members Found</h3>
                <p>Click "Add New Staff Member" to get started.</p>
            </div>
        <?php endif; ?>
        </div>

        <!-- Contract Management Tab -->
        <div id="contracts-tab" class="tab-content">
            <div class="dashboard-actions">
                <div class="dashboard-stats">
                    <i class="fas fa-file-contract"></i> Total Templates: <strong><?php echo count($contract_templates); ?></strong>
                </div>
                <button onclick="openAddContractModal()" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Contract Template
                </button>
            </div>

            <div class="contracts-grid">
                <?php foreach ($contract_templates as $template): ?>
                    <div class="contract-card">
                        <div class="contract-header">
                            <h3><?php echo htmlspecialchars($template['name']); ?></h3>
                            <span class="contract-type-badge <?php echo strtolower($template['type']); ?>">
                                <?php echo ucfirst($template['type']); ?>
                            </span>
                        </div>
                        
                        <div class="contract-content">
                            <div class="contract-preview">
                                <?php echo nl2br(htmlspecialchars(substr($template['content'], 0, 200))); ?>
                                <?php if (strlen($template['content']) > 200): ?>...<?php endif; ?>
                            </div>
                            
                            <div class="contract-actions">
                                <button onclick="viewContract(<?php echo $template['id']; ?>)" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button onclick="editContract(<?php echo $template['id']; ?>)" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteContract(<?php echo $template['id']; ?>, '<?php echo htmlspecialchars($template['name']); ?>')" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($contract_templates)): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-contract"></i>
                        <h3>No Contract Templates Found</h3>
                        <p>Create your first contract template to get started.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- E-Learning Portal Tab -->
        <div id="elearning-tab" class="tab-content">
            <div class="portal-header">
                <h2>E-Learning Portal Management</h2>
                <p>Monitor staff training progress and manage e-learning completion status</p>
            </div>

            <div class="portal-info-card">
                <h3>E-Learning Portal Access</h3>
                <div class="login-details">
                    <div class="detail-item">
                        <span class="detail-label">Portal URL:</span>
                        <span class="detail-value">
                            <a href="/elearning" target="_blank" class="portal-link">
                                https://nexihub.uk/elearning
                            </a>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Access Method:</span>
                        <span class="detail-value">Same login as HR Portal (Discord + Email + 2FA)</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Training Modules:</span>
                        <span class="detail-value">7 comprehensive modules covering company policies and procedures</span>
                    </div>
                </div>
            </div>

            <div class="elearning-stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🎓</div>
                    <div class="stat-content">
                        <div class="stat-number">
                            <?php 
                            $completed_count = 0;
                            foreach ($staff_members as $member) {
                                if ($member['elearning_status'] === 'Completed') $completed_count++;
                            }
                            echo $completed_count;
                            ?>
                        </div>
                        <div class="stat-label">Completed Training</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-content">
                        <div class="stat-number">
                            <?php 
                            $in_progress_count = 0;
                            foreach ($staff_members as $member) {
                                if ($member['elearning_status'] === 'In Progress') $in_progress_count++;
                            }
                            echo $in_progress_count;
                            ?>
                        </div>
                        <div class="stat-label">In Progress</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-content">
                        <div class="stat-number">
                            <?php 
                            $not_started_count = 0;
                            foreach ($staff_members as $member) {
                                if ($member['elearning_status'] === 'Not Started') $not_started_count++;
                            }
                            echo $not_started_count;
                            ?>
                        </div>
                        <div class="stat-label">Not Started</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <div class="stat-number">
                            <?php 
                            $total_staff = count($staff_members);
                            $completion_rate = $total_staff > 0 ? round(($completed_count / $total_staff) * 100) : 0;
                            echo $completion_rate . '%';
                            ?>
                        </div>
                        <div class="stat-label">Completion Rate</div>
                    </div>
                </div>
            </div>

            <div class="elearning-staff-table">
                <h3>Staff Training Status</h3>
                <div class="table-responsive">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Staff Member</th>
                                <th>Department</th>
                                <th>E-Learning Status</th>
                                <th>Date Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staff_members as $member): ?>
                                <tr>
                                    <td>
                                        <div class="staff-info">
                                            <strong><?php echo htmlspecialchars($member['full_name']); ?></strong>
                                            <small><?php echo htmlspecialchars($member['nexi_email']); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($member['department']); ?></td>
                                    <td>
                                        <span class="status-badge <?php 
                                            echo strtolower(str_replace(' ', '-', $member['elearning_status'])); 
                                        ?>">
                                            <?php 
                                            $status = $member['elearning_status'];
                                            switch($status) {
                                                case 'Completed':
                                                    echo '✅ Completed';
                                                    break;
                                                case 'In Progress':
                                                    echo '📚 In Progress';
                                                    break;
                                                default:
                                                    echo '⏳ Not Started';
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo $member['date_joined'] ? date('M j, Y', strtotime($member['date_joined'])) : 'N/A'; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline" onclick="resetElearning(<?php echo $member['id']; ?>)">
                                            <i class="fas fa-redo"></i> Reset Progress
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Time Off Management Tab -->
        <div id="timeoff-tab" class="tab-content">
            <div class="portal-header">
                <h2>Time Off Management</h2>
                <p>Manage staff time off requests and view portal access information</p>
            </div>

            <div class="portal-info-card">
                <h3>Time Off Portal Access</h3>
                <div class="login-details">
                    <div class="detail-item">
                        <span class="detail-label">Portal URL:</span>
                        <span class="detail-value">
                            <a href="/timeoff" target="_blank" class="portal-link">
                                https://nexihub.uk/timeoff
                            </a>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Access Method:</span>
                        <span class="detail-value">Same login as HR Portal (Email + Password)</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Features:</span>
                        <span class="detail-value">Submit requests, track status, email notifications</span>
                    </div>
                </div>
            </div>

            <?php
            // Get all time off requests for management
            $timeoff_stmt = $pdo->prepare("
                SELECT tor.*, sp.full_name, sp.staff_id, sp.department, sp.nexi_email,
                       reviewer.full_name as reviewer_name
                FROM time_off_requests tor
                LEFT JOIN staff_profiles sp ON tor.staff_id = sp.id
                LEFT JOIN staff_profiles reviewer ON tor.reviewed_by = reviewer.id
                ORDER BY tor.created_at DESC
            ");
            $timeoff_stmt->execute();
            $timeoff_requests = $timeoff_stmt->fetchAll(PDO::FETCH_ASSOC);

            // Handle time off management actions
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['timeoff_action'])) {
                $action = $_POST['timeoff_action'];
                $request_id = $_POST['request_id'];
                $review_notes = $_POST['review_notes'] ?? '';
                
                if (in_array($action, ['approve', 'deny'])) {
                    $new_status = $action === 'approve' ? 'approved' : 'denied';
                    
                    // Get the request details first
                    $request_stmt = $pdo->prepare("
                        SELECT tor.*, sp.full_name, sp.nexi_email, sp.time_off_balance 
                        FROM time_off_requests tor 
                        LEFT JOIN staff_profiles sp ON tor.staff_id = sp.id 
                        WHERE tor.id = ?
                    ");
                    $request_stmt->execute([$request_id]);
                    $request = $request_stmt->fetch();
                    
                    if ($request) {
                        // Update the request
                        $update_stmt = $pdo->prepare("
                            UPDATE time_off_requests 
                            SET status = ?, reviewed_by = ?, reviewed_at = NOW(), review_notes = ? 
                            WHERE id = ?
                        ");
                        $update_stmt->execute([$new_status, 1, $review_notes, $request_id]); // Using 1 as admin user
                        
                        // Add audit log
                        $audit_stmt = $pdo->prepare("
                            INSERT INTO time_off_audit_log (request_id, staff_id, action, old_status, new_status, notes, created_by)
                            VALUES (?, ?, ?, 'pending', ?, ?, 1)
                        ");
                        $audit_stmt->execute([$request_id, $request['staff_id'], $action, $new_status, $review_notes]);
                        
                        // If approved, update staff time off balance
                        if ($new_status === 'approved') {
                            $new_balance = max(0, $request['time_off_balance'] - $request['total_days']);
                            $balance_stmt = $pdo->prepare("UPDATE staff_profiles SET time_off_balance = ? WHERE id = ?");
                            $balance_stmt->execute([$new_balance, $request['staff_id']]);
                        }
                        
                        // Send email notification
                        sendTimeOffNotificationEmail($request, $new_status, $review_notes);
                        
                        $success_message = "Request " . $new_status . " successfully!";
                        
                        // Refresh the data
                        $timeoff_stmt->execute();
                        $timeoff_requests = $timeoff_stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                }
            }

            // Email notification function
            function sendTimeOffNotificationEmail($request, $status, $notes = '') {
                $to_staff = $request['nexi_email'];
                $to_hr = 'hr@nexihub.uk';
                
                $subject = "Time Off Request " . ucfirst($status) . " - " . $request['full_name'];
                
                $message = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .header { background: #e64f21; color: white; padding: 20px; text-align: center; }
                        .content { padding: 20px; }
                        .details { background: #f9f9f9; padding: 15px; border-left: 4px solid #e64f21; margin: 15px 0; }
                        .footer { text-align: center; padding: 10px; color: #666; font-size: 12px; }
                        .status-" . $status . " { color: " . ($status === 'approved' ? '#10b981' : '#ef4444') . "; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h2>Nexi Hub - Time Off Request " . ucfirst($status) . "</h2>
                    </div>
                    <div class='content'>
                        <h3>Your request has been <span class='status-" . $status . "'>" . strtoupper($status) . "</span></h3>
                        <div class='details'>
                            <p><strong>Staff Member:</strong> " . htmlspecialchars($request['full_name']) . "</p>
                            <p><strong>Request Type:</strong> " . htmlspecialchars($request['request_type']) . "</p>
                            <p><strong>Dates:</strong> " . date('M j, Y', strtotime($request['start_date'])) . " to " . date('M j, Y', strtotime($request['end_date'])) . "</p>
                            <p><strong>Total Days:</strong> " . $request['total_days'] . "</p>
                            <p><strong>Reason:</strong> " . htmlspecialchars($request['reason']) . "</p>
                            " . ($notes ? "<p><strong>Review Notes:</strong> " . htmlspecialchars($notes) . "</p>" : "") . "
                            <p><strong>Reviewed Date:</strong> " . date('M j, Y g:i A') . "</p>
                        </div>
                        " . ($status === 'approved' ? 
                            "<p style='color: #10b981; font-weight: bold;'>✅ Your time off request has been approved. Please ensure proper handover of responsibilities.</p>" : 
                            "<p style='color: #ef4444; font-weight: bold;'>❌ Your time off request has been denied. Please contact HR if you have any questions.</p>") . "
                    </div>
                    <div class='footer'>
                        <p>This is an automated message from the Nexi Hub Time Off Portal.</p>
                    </div>
                </body>
                </html>
                ";
                
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Nexi Hub <noreply@nexihub.uk>" . "\r\n";
                
                // Send to staff
                if ($to_staff) {
                    @mail($to_staff, $subject, $message, $headers);
                }
                
                // Send to HR
                @mail($to_hr, $subject, $message, $headers);
            }

            // Group requests by status
            $pending_requests = array_filter($timeoff_requests, function($r) { return $r['status'] === 'pending'; });
            $approved_requests = array_filter($timeoff_requests, function($r) { return $r['status'] === 'approved'; });
            $denied_requests = array_filter($timeoff_requests, function($r) { return $r['status'] === 'denied'; });
            ?>

            <div class="timeoff-stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo count($pending_requests); ?></div>
                        <div class="stat-label">Pending Requests</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo count($approved_requests); ?></div>
                        <div class="stat-label">Approved</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">❌</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo count($denied_requests); ?></div>
                        <div class="stat-label">Denied</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo count($timeoff_requests); ?></div>
                        <div class="stat-label">Total Requests</div>
                    </div>
                </div>
            </div>

            <?php if (!empty($pending_requests)): ?>
                <div class="timeoff-section">
                    <h3>Pending Requests - Require Action</h3>
                    <div class="timeoff-requests-grid">
                        <?php foreach ($pending_requests as $request): ?>
                            <div class="timeoff-request-card pending">
                                <div class="request-header">
                                    <h4><?php echo htmlspecialchars($request['full_name']); ?></h4>
                                    <span class="staff-id"><?php echo htmlspecialchars($request['staff_id']); ?></span>
                                </div>
                                
                                <div class="request-details">
                                    <div class="detail-row">
                                        <span class="detail-label">Type:</span>
                                        <span class="detail-value"><?php echo ucfirst(str_replace('_', ' ', $request['request_type'])); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Dates:</span>
                                        <span class="detail-value">
                                            <?php echo date('M j', strtotime($request['start_date'])); ?> - 
                                            <?php echo date('M j, Y', strtotime($request['end_date'])); ?>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Days:</span>
                                        <span class="detail-value"><?php echo $request['total_days']; ?> day<?php echo $request['total_days'] > 1 ? 's' : ''; ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Department:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($request['department']); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Submitted:</span>
                                        <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($request['created_at'])); ?></span>
                                    </div>
                                </div>
                                
                                <div class="request-reason">
                                    <strong>Reason:</strong>
                                    <p><?php echo htmlspecialchars($request['reason']); ?></p>
                                    <?php if ($request['notes']): ?>
                                        <strong>Notes:</strong>
                                        <p><?php echo htmlspecialchars($request['notes']); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="request-actions">
                                    <form method="POST" style="display: inline-block; margin-right: 10px;">
                                        <input type="hidden" name="timeoff_action" value="approve">
                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                        <input type="text" name="review_notes" placeholder="Approval notes (optional)" 
                                               style="margin-bottom: 10px; width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this request?')">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    
                                    <form method="POST" style="display: inline-block;">
                                        <input type="hidden" name="timeoff_action" value="deny">
                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                        <input type="text" name="review_notes" placeholder="Denial reason (required)" 
                                               style="margin-bottom: 10px; width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Deny this request?')">
                                            <i class="fas fa-times"></i> Deny
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="timeoff-all-requests">
                <h3>All Time Off Requests</h3>
                <div class="table-responsive">
                    <table class="timeoff-table">
                        <thead>
                            <tr>
                                <th>Staff Member</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Reviewed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timeoff_requests as $request): ?>
                                <tr>
                                    <td>
                                        <div class="staff-info">
                                            <strong><?php echo htmlspecialchars($request['full_name']); ?></strong>
                                            <small><?php echo htmlspecialchars($request['staff_id']) . ' - ' . htmlspecialchars($request['department']); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $request['request_type'])); ?></td>
                                    <td>
                                        <?php 
                                        echo date('M j', strtotime($request['start_date'])); 
                                        if ($request['start_date'] !== $request['end_date']) {
                                            echo ' - ' . date('M j, Y', strtotime($request['end_date']));
                                        } else {
                                            echo ', ' . date('Y', strtotime($request['start_date']));
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $request['total_days']; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $request['status']; ?>">
                                            <i class="fas fa-<?php 
                                                echo $request['status'] === 'approved' ? 'check-circle' : 
                                                    ($request['status'] === 'denied' ? 'times-circle' : 'clock'); 
                                            ?>"></i>
                                            <?php echo ucfirst($request['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($request['created_at'])); ?></td>
                                    <td>
                                        <?php if ($request['reviewed_at']): ?>
                                            <?php echo date('M j, Y', strtotime($request['reviewed_at'])); ?>
                                            <?php if ($request['reviewer_name']): ?>
                                                <br><small>by <?php echo htmlspecialchars($request['reviewer_name']); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nexi HR Portal Access Tab -->
        <div id="contract-portal-tab" class="tab-content">
            <div class="portal-header">
                <h2>Nexi HR Portal Access</h2>
                <p>View staff contract signing status and portal access information</p>
            </div>

            <div class="portal-info-card">
                <h3>Portal Login Information</h3>
                <div class="login-details">
                    <div class="detail-row">
                        <strong>Portal URL:</strong> 
                        <a href="<?php echo SITE_URL; ?>/contracts/" target="_blank"><?php echo SITE_URL; ?>/contracts/</a>
                    </div>
                    <div class="detail-row">
                        <strong>Login Email:</strong> contract@nexihub.uk
                    </div>
                    <div class="detail-row">
                        <strong>Password:</strong> test1212
                    </div>
                </div>
            </div>

            <div class="contract-user-section">
                <h3>Create Contract Users</h3>
                <p>Create login accounts for staff members to access the Nexi HR Portal</p>
                
                <div class="contract-user-actions">
                    <button onclick="openCreateContractUserModal()" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Create Contract User
                    </button>
                </div>
            </div>

            <div class="contract-status-section">
                <h3>Staff Contract Status</h3>
                <div class="staff-contracts-grid">
                    <?php foreach ($staff_members as $staff): ?>
                        <?php 
                        $staff_contracts = $contracts_by_staff[$staff['id']] ?? [];
                        $total_contracts = count($contract_templates);
                        $signed_contracts = array_filter($staff_contracts, function($c) { return $c['is_signed']; });
                        $signed_count = count($signed_contracts);
                        ?>
                        <div class="staff-contract-card">
                            <div class="staff-contract-header">
                                <h4><?php echo htmlspecialchars($staff['full_name']); ?></h4>
                                <div class="contract-progress">
                                    <span class="progress-text"><?php echo $signed_count; ?>/<?php echo $total_contracts; ?> signed</span>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo $total_contracts ? ($signed_count / $total_contracts * 100) : 0; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="contract-list">
                                <?php foreach ($contract_templates as $template): ?>
                                    <?php 
                                    $staff_contract = null;
                                    foreach ($staff_contracts as $sc) {
                                        if ($sc['template_id'] == $template['id']) {
                                            $staff_contract = $sc;
                                            break;
                                        }
                                    }
                                    ?>
                                    <div class="contract-item">
                                        <span class="contract-name"><?php echo htmlspecialchars($template['name']); ?></span>
                                        <?php if ($staff_contract && $staff_contract['is_signed']): ?>
                                            <span class="status signed">
                                                <i class="fas fa-check-circle"></i> Signed
                                            </span>
                                            <button onclick="viewSignedContract(<?php echo $staff_contract['id']; ?>, '<?php echo htmlspecialchars($template['name']); ?>')" class="btn btn-sm btn-success view-btn">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="action" value="download_signed_contract">
                                                <input type="hidden" name="contract_id" value="<?php echo $staff_contract['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-secondary download-btn">
                                                    <i class="fas fa-download"></i> PDF
                                                </button>
                                            </form>
                                        <?php elseif ($staff_contract): ?>
                                            <span class="status assigned">
                                                <i class="fas fa-clock"></i> Assigned
                                            </span>
                                        <?php else: ?>
                                            <span class="status not-assigned">
                                                <i class="fas fa-times-circle"></i> Not Assigned
                                            </span>
                                            <button onclick="assignContract(<?php echo $staff['id']; ?>, <?php echo $template['id']; ?>)" class="btn btn-sm btn-secondary assign-btn">
                                                Assign
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Staff Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Staff Member</h2>
            <span class="close" onclick="closeAddModal()">&times;</span>
        </div>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="add_staff">
            
            <div class="modal-body">
                <div class="form-section">
                    <h3>Basic Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Staff ID *</label>
                            <input type="text" name="staff_id" class="form-control" required placeholder="e.g., NXH002">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Manager</label>
                            <input type="text" name="manager" class="form-control" placeholder="Manager name">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" required placeholder="Full legal name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Preferred Name</label>
                            <input type="text" name="preferred_name" class="form-control" placeholder="Nickname or preferred name">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="job_title" class="form-control" placeholder="Position title">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-control">
                                <option value="">Select Department</option>
                                <option value="Executive Leadership">Executive Leadership</option>
                                <option value="Senior Leadership">Senior Leadership</option>
                                <option value="Regional Leadership Team">Regional Leadership Team</option>
                                <option value="Corporate Functions">Corporate Functions</option>
                                <option value="Shared Services">Shared Services</option>
                                <option value="Company Leadership Team">Company Leadership Team</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Region</label>
                            <select name="region" class="form-control">
                                <option value="">Select Region</option>
                                <option value="NAM">North America (NAM)</option>
                                <option value="EMEA">Europe, Middle East & Africa (EMEA)</option>
                                <option value="APAC">Asia-Pacific (APAC)</option>
                                <option value="LATAM">Latin America (LATAM)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Contact Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nexi Email</label>
                            <input type="email" name="nexi_email" class="form-control" placeholder="user@nexihub.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Private Email</label>
                            <input type="email" name="private_email" class="form-control" placeholder="personal@email.com">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control" placeholder="+1 (555) 123-4567">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Discord Username</label>
                            <input type="text" name="discord_username" class="form-control" placeholder="username#1234">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Discord ID</label>
                            <input type="text" name="discord_id" class="form-control" placeholder="Discord user ID">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nationality</label>
                            <input type="text" name="nationality" class="form-control" placeholder="Country of citizenship">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country of Residence</label>
                            <input type="text" name="country_of_residence" class="form-control" placeholder="Current country">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Employment Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date Joined</label>
                            <input type="date" name="date_joined" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">E-learning Status</label>
                            <select name="elearning_status" class="form-control">
                                <option value="Not Started">Not Started</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Time Off Balance (days)</label>
                            <input type="number" name="time_off_balance" class="form-control" value="0" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Status</label>
                            <select name="account_status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Parent Contact (if under 16)</label>
                        <textarea name="parent_contact" class="form-control" rows="3" placeholder="Parent/guardian contact information"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Internal Notes</label>
                        <textarea name="internal_notes" class="form-control" rows="3" placeholder="Internal notes (confidential)"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="two_fa_status" id="two_fa_status">
                            <label for="two_fa_status" class="form-label">Two-Factor Authentication Enabled</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="contract_completed" id="contract_completed">
                            <label for="contract_completed" class="form-label">Contract Completed</label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Staff Type</label>
                            <select name="staff_type" id="staff_type" class="form-control" onchange="toggleShareholderPercentage()">
                                <option value="volunteer">Volunteer</option>
                                <option value="shareholder">Shareholder</option>
                            </select>
                        </div>
                        <div class="form-group" id="shareholder_percentage_group" style="display: none;">
                            <label class="form-label">Shareholder Percentage (%)</label>
                            <input type="number" name="shareholder_percentage" id="shareholder_percentage" class="form-control" min="0" max="100" step="0.01" value="0.00">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Staff Member</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Staff Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Staff Member</h2>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="update_staff">
            <input type="hidden" name="edit_staff_id" id="edit_staff_id">
            
            <div class="modal-body">
                <div class="form-section">
                    <h3>Basic Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Staff ID *</label>
                            <input type="text" name="staff_id" id="edit_staff_id_display" class="form-control" readonly style="background: var(--background-dark); opacity: 0.7;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Manager</label>
                            <input type="text" name="manager" id="edit_manager" class="form-control" placeholder="Manager name">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="full_name" id="edit_full_name" class="form-control" required placeholder="Full legal name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Preferred Name</label>
                            <input type="text" name="preferred_name" id="edit_preferred_name" class="form-control" placeholder="Nickname or preferred name">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="job_title" id="edit_job_title" class="form-control" placeholder="Position title">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <select name="department" id="edit_department" class="form-control">
                                <option value="">Select Department</option>
                                <option value="Executive Leadership">Executive Leadership</option>
                                <option value="Senior Leadership">Senior Leadership</option>
                                <option value="Regional Leadership Team">Regional Leadership Team</option>
                                <option value="Corporate Functions">Corporate Functions</option>
                                <option value="Shared Services">Shared Services</option>
                                <option value="Company Leadership Team">Company Leadership Team</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Region</label>
                            <select name="region" id="edit_region" class="form-control">
                                <option value="">Select Region</option>
                                <option value="NAM">North America (NAM)</option>
                                <option value="EMEA">Europe, Middle East & Africa (EMEA)</option>
                                <option value="APAC">Asia-Pacific (APAC)</option>
                                <option value="LATAM">Latin America (LATAM)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Contact Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nexi Email</label>
                            <input type="email" name="nexi_email" id="edit_nexi_email" class="form-control" placeholder="user@nexihub.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Private Email</label>
                            <input type="email" name="private_email" id="edit_private_email" class="form-control" placeholder="personal@email.com">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone_number" id="edit_phone_number" class="form-control" placeholder="+1 (555) 123-4567">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="edit_date_of_birth" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Discord Username</label>
                            <input type="text" name="discord_username" id="edit_discord_username" class="form-control" placeholder="username#1234">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Discord ID</label>
                            <input type="text" name="discord_id" id="edit_discord_id" class="form-control" placeholder="Discord user ID">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nationality</label>
                            <input type="text" name="nationality" id="edit_nationality" class="form-control" placeholder="Country of citizenship">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country of Residence</label>
                            <input type="text" name="country_of_residence" id="edit_country_of_residence" class="form-control" placeholder="Current country">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Employment Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date Joined</label>
                            <input type="date" name="date_joined" id="edit_date_joined" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">E-learning Status</label>
                            <select name="elearning_status" id="edit_elearning_status" class="form-control">
                                <option value="Not Started">Not Started</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Time Off Balance (days)</label>
                            <input type="number" name="time_off_balance" id="edit_time_off_balance" class="form-control" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Status</label>
                            <select name="account_status" id="edit_account_status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Parent Contact (if under 16)</label>
                        <textarea name="parent_contact" id="edit_parent_contact" class="form-control" rows="3" placeholder="Parent/guardian contact information"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Internal Notes</label>
                        <textarea name="internal_notes" id="edit_internal_notes" class="form-control" rows="3" placeholder="Internal notes (confidential)"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="two_fa_status" id="edit_two_fa_status">
                            <label for="edit_two_fa_status" class="form-label">Two-Factor Authentication Enabled</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="contract_completed" id="edit_contract_completed">
                            <label for="edit_contract_completed" class="form-label">Contract Completed</label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Staff Type</label>
                            <select name="staff_type" id="edit_staff_type" class="form-control" onchange="toggleEditShareholderPercentage()">
                                <option value="volunteer">Volunteer</option>
                                <option value="shareholder">Shareholder</option>
                            </select>
                        </div>
                        <div class="form-group" id="edit_shareholder_percentage_group" style="display: none;">
                            <label class="form-label">Shareholder Percentage (%)</label>
                            <input type="number" name="shareholder_percentage" id="edit_shareholder_percentage" class="form-control" min="0" max="100" step="0.01" value="0.00">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Staff Member</button>
            </div>
        </form>
    </div>
</div>

<!-- View Staff Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Staff Member Details</h2>
            <span class="close" onclick="closeViewModal()">&times;</span>
        </div>
        <div class="modal-body" id="viewModalBody">
            <!-- Content will be dynamically populated -->
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeViewModal()" class="btn btn-secondary">Close</button>
            <button type="button" onclick="editStaffFromView()" class="btn btn-primary" id="editFromViewBtn">Edit Staff</button>
        </div>
    </div>
</div>

<!-- Add Contract Modal -->
<div id="addContractModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add Contract Template</h2>
            <span class="close" onclick="closeAddContractModal()">&times;</span>
        </div>    <div class="form-section">
        <form method="POST" action="">
            <input type="hidden" name="action" value="add_contract_template">
            
            <div class="modal-body">
                <div class="form-section">
                    <h3>Contract Information</h3>
                    <div class="form-group">
                        <label class="form-label">Contract Name *</label>
                        <input type="text" name="contract_name" class="form-control" required placeholder="e.g., Employee NDA">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contract Type *</label>
                        <select name="contract_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="shareholder">Voluntary/Shareholder Agreement</option>
                            <option value="nda">Non-Disclosure Agreement</option>
                            <option value="conduct">Code of Conduct</option>
                            <option value="policies">Company Policies</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contract Content *</label>
                        <textarea name="contract_content" class="form-control" rows="10" required placeholder="Enter the full contract text..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeAddContractModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Contract Template</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Contract Modal -->
<div id="editContractModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Contract Template</h2>
            <span class="close" onclick="closeEditContractModal()">&times;</span>
        </div>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="update_contract_template">
            <input type="hidden" name="contract_id" id="edit_contract_id">
            
            <div class="modal-body">
                <div class="form-section">
                    <h3>Contract Information</h3>
                    <div class="form-group">
                        <label class="form-label">Contract Name *</label>
                        <input type="text" name="contract_name" id="edit_contract_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contract Type *</label>
                        <select name="contract_type" id="edit_contract_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="shareholder">Voluntary/Shareholder Agreement</option>
                            <option value="nda">Non-Disclosure Agreement</option>
                            <option value="conduct">Code of Conduct</option>
                            <option value="policies">Company Policies</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Contract Content *</label>
                        <textarea name="contract_content" id="edit_contract_content" class="form-control" rows="10" required></textarea>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeEditContractModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Contract Template</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Contract User Modal -->
<div id="createContractUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">    
            <h2 class="modal-title">Create Contract User</h2>
            <span class="close" onclick="closeCreateContractUserModal()">&times;</span>
        </div>        <h3>Contract Information</h3>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create_contract_user">
            <div class="modal-body">
                <div class="form-section">
                    <h3>User Account Information</h3>
                    <div class="form-group">
                        <label class="form-label">Staff Member *</label>
                        <select name="staff_id" class="form-control" required>
                            <option value="">Select Staff Member</option>
                            <?php foreach ($staff_members as $staff): ?>
                                <option value="<?php echo $staff['id']; ?>">
                                    <?php echo htmlspecialchars($staff['full_name'] . ' (' . $staff['staff_id'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Login Email *</label>
                        <input type="email" name="email" class="form-control" required placeholder="user@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Temporary Password *</label>
                        <input type="password" name="password" class="form-control" required placeholder="Temporary password">
                        <small class="form-text">User will be required to reset this password on first login</small>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeCreateContractUserModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Contract User</button>
            </div>
        </form>
    </div>
</div>

<!-- Staff Management Modals -->

<!-- Add Staff Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Staff Member</h2>
            <button onclick="closeAddModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="staff_id">Staff ID *</label>
                        <input type="text" id="staff_id" name="staff_id" required placeholder="e.g., NXH001">
                    </div>
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" id="job_title" name="job_title">
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" id="department" name="department">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="manager">Manager</label>
                        <input type="text" id="manager" name="manager">
                    </div>
                    <div class="form-group">
                        <label for="region">Region</label>
                        <select id="region" name="region">
                            <option value="">Select Region</option>
                            <option value="UK">UK</option>
                            <option value="US">US</option>
                            <option value="EU">EU</option>
                            <option value="APAC">APAC</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nexi_email">Nexi Email</label>
                        <input type="email" id="nexi_email" name="nexi_email">
                    </div>
                    <div class="form-group">
                        <label for="private_email">Private Email</label>
                        <input type="email" id="private_email" name="private_email">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number">
                    </div>
                    <div class="form-group">
                        <label for="discord_username">Discord Username</label>
                        <input type="text" id="discord_username" name="discord_username">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality">
                    </div>
                    <div class="form-group">
                        <label for="country_of_residence">Country of Residence</label>
                        <input type="text" id="country_of_residence" name="country_of_residence">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth">
                    </div>
                    <div class="form-group">
                        <label for="date_joined">Date Joined</label>
                        <input type="date" id="date_joined" name="date_joined">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="time_off_balance">Time Off Balance (days)</label>
                        <input type="number" id="time_off_balance" name="time_off_balance" value="0" min="0">
                    </div>
                    <div class="form-group">
                        <label for="account_status">Account Status</label>
                        <select id="account_status" name="account_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Staff Member</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Staff Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Staff Member</h2>
            <button onclick="closeEditModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="editId" name="id">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="editStaffId">Staff ID *</label>
                        <input type="text" id="editStaffId" name="staff_id" required>
                    </div>
                    <div class="form-group">
                        <label for="editFullName">Full Name *</label>
                        <input type="text" id="editFullName" name="full_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editJobTitle">Job Title</label>
                        <input type="text" id="editJobTitle" name="job_title">
                    </div>
                    <div class="form-group">
                        <label for="editDepartment">Department</label>
                        <input type="text" id="editDepartment" name="department">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editManager">Manager</label>
                        <input type="text" id="editManager" name="manager">
                    </div>
                    <div class="form-group">
                        <label for="editRegion">Region</label>
                        <select id="editRegion" name="region">
                            <option value="">Select Region</option>
                            <option value="UK">UK</option>
                            <option value="US">US</option>
                            <option value="EU">EU</option>
                            <option value="APAC">APAC</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPreferredName">Preferred Name</label>
                        <input type="text" id="editPreferredName" name="preferred_name">
                    </div>
                    <div class="form-group">
                        <label for="editNexiEmail">Nexi Email</label>
                        <input type="email" id="editNexiEmail" name="nexi_email">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPrivateEmail">Private Email</label>
                        <input type="email" id="editPrivateEmail" name="private_email">
                    </div>
                    <div class="form-group">
                        <label for="editPhoneNumber">Phone Number</label>
                        <input type="tel" id="editPhoneNumber" name="phone_number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editDiscordUsername">Discord Username</label>
                        <input type="text" id="editDiscordUsername" name="discord_username">
                    </div>
                    <div class="form-group">
                        <label for="editDiscordId">Discord ID</label>
                        <input type="text" id="editDiscordId" name="discord_id">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editNationality">Nationality</label>
                        <input type="text" id="editNationality" name="nationality">
                    </div>
                    <div class="form-group">
                        <label for="editCountryOfResidence">Country of Residence</label>
                        <input type="text" id="editCountryOfResidence" name="country_of_residence">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editDateOfBirth">Date of Birth</label>
                        <input type="date" id="editDateOfBirth" name="date_of_birth">
                    </div>
                    <div class="form-group">
                        <label for="editDateJoined">Date Joined</label>
                        <input type="date" id="editDateJoined" name="date_joined">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editTimeOffBalance">Time Off Balance (days)</label>
                        <input type="number" id="editTimeOffBalance" name="time_off_balance" min="0">
                    </div>
                    <div class="form-group">
                        <label for="editAccountStatus">Account Status</label>
                        <select id="editAccountStatus" name="account_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="editTwoFaStatus" name="two_fa_status" value="1">
                            <label for="editTwoFaStatus">Two-Factor Authentication Enabled</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="editContractCompleted" name="contract_completed" value="1">
                            <label for="editContractCompleted">Contract Completed</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Staff Member</button>
            </div>
        </form>
    </div>
</div>

<!-- View Staff Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Staff Details</h2>
            <button onclick="closeViewModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="staff-view-grid">
                <div class="view-item">
                    <div class="view-label">Staff ID:</div>
                    <div class="view-value" id="viewStaffId">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Full Name:</div>
                    <div class="view-value" id="viewFullName">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Job Title:</div>
                    <div class="view-value" id="viewJobTitle">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Department:</div>
                    <div class="view-value" id="viewDepartment">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Manager:</div>
                    <div class="view-value" id="viewManager">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Region:</div>
                    <div class="view-value" id="viewRegion">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Nexi Email:</div>
                    <div class="view-value" id="viewNexiEmail">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Private Email:</div>
                    <div class="view-value" id="viewPrivateEmail">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Phone Number:</div>
                    <div class="view-value" id="viewPhoneNumber">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Discord Username:</div>
                    <div class="view-value" id="viewDiscordUsername">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Nationality:</div>
                    <div class="view-value" id="viewNationality">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Country:</div>
                    <div class="view-value" id="viewCountryOfResidence">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Date of Birth:</div>
                    <div class="view-value" id="viewDateOfBirth">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Date Joined:</div>
                    <div class="view-value" id="viewDateJoined">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Account Status:</div>
                    <div class="view-value" id="viewAccountStatus">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Two-Factor Auth:</div>
                    <div class="view-value" id="viewTwoFaStatus">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Time Off Balance:</div>
                    <div class="view-value" id="viewTimeOffBalance">-</div>
                </div>
                <div class="view-item">
                    <div class="view-label">Contract Completed:</div>
                    <div class="view-value" id="viewContractCompleted">-</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeViewModal()" class="btn btn-secondary">Close</button>
        </div>
    </div>
</div>

<!-- View Contract Modal -->
<div id="viewContractModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Contract Details</h2>
            <button onclick="closeViewContractModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="view-item">
                <div class="view-label">Contract Name:</div>
                <div class="view-value" id="viewContractName">-</div>
            </div>
            <div class="view-item">
                <div class="view-label">Type:</div>
                <div class="view-value" id="viewContractType">-</div>
            </div>
            <div class="view-item">
                <div class="view-label">Assignable:</div>
                <div class="view-value" id="viewContractAssignable">-</div>
            </div>
            <div class="view-item-full">
                <div class="view-label">Content:</div>
                <div class="view-value" id="viewContractContent">-</div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeViewContractModal()" class="btn btn-secondary">Close</button>
        </div>
    </div>
</div>

<script>
// Staff data for JavaScript operations
const staffData = <?php echo json_encode($staff_members); ?>;
const contractData = <?php echo json_encode($contract_templates); ?>;

// Tab Navigation Functions
function showTab(tabId) {
    // Hide all tab contents
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Remove active class from all tab buttons
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(button => button.classList.remove('active'));
    
    // Show selected tab
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}

function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function openEditModal() {
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openViewModal() {
    document.getElementById('viewModal').style.display = 'block';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

function openAddContractModal() {
    document.getElementById('addContractModal').style.display = 'block';
}

function closeAddContractModal() {
    document.getElementById('addContractModal').style.display = 'none';
}

function openEditContractModal() {
    document.getElementById('editContractModal').style.display = 'block';
}

function closeEditContractModal() {
    document.getElementById('editContractModal').style.display = 'none';
}

// Contract User Management Functions
function openCreateContractUserModal() {
    document.getElementById('createContractUserModal').style.display = 'block';
}

function closeCreateContractUserModal() {
    document.getElementById('createContractUserModal').style.display = 'none';
}

// Contract viewing and download functions
function viewSignedContract(contractId, contractName) {
    // Create a modal to display contract details
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'block';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Signed Contract: ${contractName}</h2>
                <button onclick="this.closest('.modal').remove()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; padding: 2rem;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i>
                    <p>Loading contract details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="downloadContractPDF(${contractId})" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download PDF
                </button>
                <button onclick="this.closest('.modal').remove()" class="btn btn-secondary">Close</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Fetch contract details via AJAX
    fetch(`../contracts/dashboard.php?action=get_contract&contract_id=${contractId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const contract = data.contract;
                const modalBody = modal.querySelector('.modal-body');
                modalBody.innerHTML = `
                    <div class="contract-view-content">
                        <div class="contract-info-section">
                            <h3>Contract Information</h3>
                            <div class="contract-details">
                                <div class="detail-row">
                                    <span class="detail-label">Signer Name:</span>
                                    <span class="detail-value">${contract.signer_full_name || 'Not recorded'}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Position:</span>
                                    <span class="detail-value">${contract.signer_position || 'Not recorded'}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Date of Birth:</span>
                                    <span class="detail-value">${contract.signer_date_of_birth || 'Not recorded'}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Signed On:</span>
                                    <span class="detail-value">${new Date(contract.signed_timestamp || contract.signed_at).toLocaleString()}</span>
                                </div>
                            </div>
                        </div>
                        
                        ${contract.is_under_17 && contract.guardian_full_name ? `
                        <div class="guardian-info-section">
                            <h3>Guardian Information</h3>
                            <div class="contract-details">
                                <div class="detail-row">
                                    <span class="detail-label">Guardian Name:</span>
                                    <span class="detail-value">${contract.guardian_full_name}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Guardian Email:</span>
                                    <span class="detail-value">${contract.guardian_email}</span>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                        
                        <div class="signature-display-section">
                            <h3>Signatures</h3>
                            <div class="signatures-grid">
                                <div class="signature-box">
                                    <h4>Employee Signature</h4>
                                    ${contract.signature_data ? `<img src="${contract.signature_data}" alt="Employee Signature" class="signature-image">` : '<p>Signature not available</p>'}
                                </div>
                                ${contract.is_under_17 && contract.guardian_signature_data ? `
                                <div class="signature-box guardian-signature">
                                    <h4>Guardian Signature</h4>
                                    <img src="${contract.guardian_signature_data}" alt="Guardian Signature" class="signature-image">
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                modal.querySelector('.modal-body').innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: var(--danger-color);">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Error loading contract details: ${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            modal.querySelector('.modal-body').innerHTML = `
                <div style="text-align: center; padding: 2rem; color: var(--danger-color);">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>Error loading contract: ${error.message}</p>
                </div>
            `;
        });
    
    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function downloadContractPDF(contractId) {
    // Create a temporary link to download the PDF
    const link = document.createElement('a');
    link.href = `../contracts/download-pdf.php?contract_id=${contractId}`;
    link.download = '';
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Staff Management Functions
function viewStaff(staffId) {
    // Find staff data
    const staff = staffData.find(s => s.id == staffId);
    if (!staff) {
        alert('Staff member not found');
        return;
    }
    
    // Populate view modal
    document.getElementById('viewStaffId').textContent = staff.staff_id;
    document.getElementById('viewFullName').textContent = staff.full_name;
    document.getElementById('viewJobTitle').textContent = staff.job_title || 'Not specified';
    document.getElementById('viewDepartment').textContent = staff.department || 'Not specified';
    document.getElementById('viewManager').textContent = staff.manager || 'Not specified';
    document.getElementById('viewRegion').textContent = staff.region || 'Not specified';
    document.getElementById('viewNexiEmail').textContent = staff.nexi_email || 'Not specified';
    document.getElementById('viewPrivateEmail').textContent = staff.private_email || 'Not specified';
    document.getElementById('viewPhoneNumber').textContent = staff.phone_number || 'Not specified';
    document.getElementById('viewDiscordUsername').textContent = staff.discord_username || 'Not specified';
    document.getElementById('viewNationality').textContent = staff.nationality || 'Not specified';
    document.getElementById('viewCountryOfResidence').textContent = staff.country_of_residence || 'Not specified';
    document.getElementById('viewDateOfBirth').textContent = staff.date_of_birth || 'Not specified';
    document.getElementById('viewDateJoined').textContent = staff.date_joined || 'Not specified';
    document.getElementById('viewAccountStatus').textContent = staff.account_status || 'Active';
    document.getElementById('viewTwoFaStatus').textContent = staff.two_fa_status ? 'Enabled' : 'Disabled';
    document.getElementById('viewTimeOffBalance').textContent = staff.time_off_balance || '0';
    document.getElementById('viewContractCompleted').textContent = staff.contract_completed ? 'Yes' : 'No';
    
    openViewModal();
}

function editStaff(staffId) {
    // Find staff data
    const staff = staffData.find(s => s.id == staffId);
    if (!staff) {
        alert('Staff member not found');
        return;
    }
    
    // Populate edit modal
    document.getElementById('editId').value = staff.id;
    document.getElementById('editStaffId').value = staff.staff_id;
    document.getElementById('editFullName').value = staff.full_name;
    document.getElementById('editJobTitle').value = staff.job_title || '';
    document.getElementById('editDepartment').value = staff.department || '';
    document.getElementById('editManager').value = staff.manager || '';
    document.getElementById('editRegion').value = staff.region || '';
    document.getElementById('editPreferredName').value = staff.preferred_name || '';
    document.getElementById('editNexiEmail').value = staff.nexi_email || '';
    document.getElementById('editPrivateEmail').value = staff.private_email || '';
    document.getElementById('editPhoneNumber').value = staff.phone_number || '';
    document.getElementById('editDiscordUsername').value = staff.discord_username || '';
    document.getElementById('editDiscordId').value = staff.discord_id || '';
    document.getElementById('editNationality').value = staff.nationality || '';
    document.getElementById('editCountryOfResidence').value = staff.country_of_residence || '';
    document.getElementById('editDateOfBirth').value = staff.date_of_birth || '';
    document.getElementById('editDateJoined').value = staff.date_joined || '';
    document.getElementById('editTimeOffBalance').value = staff.time_off_balance || '0';
    document.getElementById('editAccountStatus').value = staff.account_status || 'Active';
    document.getElementById('editTwoFaStatus').checked = staff.two_fa_status == 1;
    document.getElementById('editContractCompleted').checked = staff.contract_completed == 1;
    
    openEditModal();
}

function deleteStaff(staffId, staffName) {
    if (confirm(`Are you sure you want to delete ${staffName}? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = staffId;
        
        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Contract Management Functions
function viewContract(contractId) {
    const contract = contractData.find(c => c.id == contractId);
    if (!contract) {
        alert('Contract not found');
        return;
    }
    
    document.getElementById('viewContractName').textContent = contract.name;
    document.getElementById('viewContractType').textContent = contract.type;
    document.getElementById('viewContractContent').innerHTML = contract.content;
    document.getElementById('viewContractAssignable').textContent = contract.is_assignable ? 'Yes' : 'No';
    
    document.getElementById('viewContractModal').style.display = 'block';
}

function closeViewContractModal() {
    document.getElementById('viewContractModal').style.display = 'none';
}

function editContract(contractId) {
    const contract = contractData.find(c => c.id == contractId);
    if (!contract) {
        alert('Contract not found');
        return;
    }
    
    document.getElementById('editContractId').value = contract.id;
    document.getElementById('editContractName').value = contract.name;
    document.getElementById('editContractType').value = contract.type;
    document.getElementById('editContractContent').value = contract.content;
    document.getElementById('editContractAssignable').checked = contract.is_assignable == 1;
    
    openEditContractModal();
}

function deleteContract(contractId, contractName) {
    if (confirm(`Are you sure you want to delete contract "${contractName}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_contract';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'contract_id';
        idInput.value = contractId;
        
        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// E-Learning Functions
function resetElearning(staffId) {
    if (confirm('Are you sure you want to reset this staff member\'s e-learning progress? This will delete all their progress and certificates.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'reset_elearning';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'staff_id';
        idInput.value = staffId;
        
        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Modal Functions
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Window click outside modal to close
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Any initialization code here
    console.log('Staff Dashboard loaded successfully');
});
</script>

<style>
.staff-view-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.view-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem;
    background: var(--background-dark);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.view-item-full {
    padding: 1rem;
    background: var(--background-dark);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.view-label {
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 120px;
}

.view-value {
    color: var(--text-primary);
    text-align: right;
    word-break: break-word;
}

@media (max-width: 768px) {
    .staff-view-grid {
        grid-template-columns: 1fr;
    }
    
    .view-item {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .view-value {
        text-align: left;
    }
}

/* Contract Specific Styles */
.contract-details {
    display: grid;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.contract-full-content {
    background: var(--background-dark);
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    line-height: 1.8;
    color: var(--text-primary);
    max-height: 400px;
    overflow-y: auto;
    font-size: 0.95rem;
}

.contract-details .detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--background-dark);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.contract-details .detail-label {
    font-weight: 600;
    color: var(--text-secondary);
    min-width: 100px;
}

.contract-details .detail-value {
    color: var(--text-primary);
    text-align: right;
}

/* E-Learning Styles */
.elearning-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px var(--shadow-medium);
    border-color: var(--primary-color);
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.elearning-staff-table {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 2rem;
}

.elearning-staff-table h3 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
}

.table-responsive {
    overflow-x: auto;
}

.staff-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--background-dark);
    border-radius: 8px;
    overflow: hidden;
}

.staff-table th,
.staff-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.staff-table th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.staff-table tr:last-child td {
    border-bottom: none;
}

.staff-table tr:hover {
    background: var(--background-light);
}

.staff-info strong {
    color: var(--text-primary);
    display: block;
    margin-bottom: 0.25rem;
}

.staff-info small {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge.completed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.status-badge.in-progress {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.status-badge.not-started {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
}

.btn-outline:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.portal-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.portal-link:hover {
    text-decoration: underline;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--background-dark);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    margin-bottom: 0.75rem;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    font-weight: 500;
    color: var(--text-primary);
    min-width: 120px;
}

.detail-value {
    color: var(--text-secondary);
    text-align: right;
}

/* Portal Access Styles */
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
