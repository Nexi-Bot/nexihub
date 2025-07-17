<?php
require_once __DIR__ . '/../config/config.php';

requireAuth(); // Enable when ready

$page_title = "Staff Management Dashboard";
$page_description = "Nexi Hub Staff Management System";

// Database connection
try {
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed");
}

// Create staff table if it doesn't exist
$createTableSQL = "
CREATE TABLE IF NOT EXISTS staff_profiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
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
    time_off_balance INTEGER DEFAULT 0,
    parent_contact TEXT,
    payroll_info TEXT,
    password_reset_history TEXT,
    account_status VARCHAR(20) DEFAULT 'Active',
    internal_notes TEXT,
    contract_completed BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

try {
    $db->exec($createTableSQL);
    
    // Add contract_completed column if it doesn't exist
    try {
        $db->exec("ALTER TABLE staff_profiles ADD COLUMN contract_completed BOOLEAN DEFAULT 0");
    } catch (PDOException $e) {
        // Column already exists, ignore error
        if (!str_contains($e->getMessage(), 'duplicate column name')) {
            error_log("Error adding contract_completed column: " . $e->getMessage());
        }
    }
    
    // Create contract management tables
    $contractTablesSQL = [
        "CREATE TABLE IF NOT EXISTS contract_templates (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            type VARCHAR(50) NOT NULL,
            content TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS staff_contracts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            staff_id INTEGER NOT NULL,
            template_id INTEGER NOT NULL,
            signed_at DATETIME,
            signature_data TEXT,
            is_signed BOOLEAN DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (staff_id) REFERENCES staff_profiles(id),
            FOREIGN KEY (template_id) REFERENCES contract_templates(id)
        )",
        "CREATE TABLE IF NOT EXISTS contract_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            staff_id INTEGER,
            role VARCHAR(20) DEFAULT 'staff',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (staff_id) REFERENCES staff_profiles(id)
        )"
    ];
    
    foreach ($contractTablesSQL as $sql) {
        $db->exec($sql);
    }
    
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

// Initialize default contract templates if they don't exist
$checkTemplates = $db->prepare("SELECT COUNT(*) FROM contract_templates");
$checkTemplates->execute();
$templateCount = $checkTemplates->fetchColumn();

if ($templateCount == 0) {
    $defaultTemplates = [
        [
            'name' => 'Voluntary/Shareholder Agreement',
            'type' => 'shareholder',
            'content' => 'This is a Voluntary/Shareholder Agreement template. By signing this document, you agree to the terms and conditions outlined herein regarding your role as a voluntary contributor and potential shareholder at Nexi Hub. This agreement establishes your rights, responsibilities, and compensation structure.'
        ],
        [
            'name' => 'Non-Disclosure Agreement (NDA)',
            'type' => 'nda',
            'content' => 'This Non-Disclosure Agreement (NDA) establishes confidentiality requirements for your work at Nexi Hub. You agree to maintain the confidentiality of all proprietary information, trade secrets, and sensitive business data you may encounter during your engagement with the company.'
        ],
        [
            'name' => 'Code of Conduct',
            'type' => 'conduct',
            'content' => 'This Code of Conduct outlines the behavioral expectations and professional standards required of all Nexi Hub team members. By signing this document, you commit to maintaining the highest standards of professional conduct, respect, and integrity in all business interactions.'
        ],
        [
            'name' => 'Company Policies',
            'type' => 'policies',
            'content' => 'This document outlines all company policies including but not limited to: workplace safety, anti-harassment, data protection, remote work guidelines, communication standards, and general operational procedures that all team members must follow.'
        ]
    ];
    
    $insertTemplate = $db->prepare("INSERT INTO contract_templates (name, type, content) VALUES (?, ?, ?)");
    foreach ($defaultTemplates as $template) {
        try {
            $insertTemplate->execute([$template['name'], $template['type'], $template['content']]);
        } catch (PDOException $e) {
            error_log("Error inserting contract template: " . $e->getMessage());
        }
    }
}

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
                        contract_completed
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                try {
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
                        isset($_POST['contract_completed']) ? 1 : 0
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
                        contract_completed = ?, updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                
                try {
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
                    INSERT OR IGNORE INTO staff_contracts (staff_id, template_id, is_signed) 
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

// Fetch all staff members
$stmt = $db->prepare("SELECT * FROM staff_profiles ORDER BY department, region, full_name");
$stmt->execute();
$staff_members = $stmt->fetchAll();

// Fetch all contract templates
$stmt = $db->prepare("SELECT * FROM contract_templates ORDER BY name");
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
        sc.signature_data
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
            <button class="tab-button" onclick="showTab('contract-portal-tab')">
                <i class="fas fa-signature"></i> Contract Portal Access
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

        <!-- Contract Portal Access Tab -->
        <div id="contract-portal-tab" class="tab-content">
            <div class="portal-header">
                <h2>Contract Portal Access</h2>
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
                    </div>
                    
                    <div class="form-row">
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
                        <div class="form-group">
                            <!-- Empty for balance -->
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
        </div>
        
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

<!-- View Contract Modal -->
<div id="viewContractModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="viewContractTitle">Contract Template</h2>
            <span class="close" onclick="closeViewContractModal()">&times;</span>
        </div>
        
        <div class="modal-body" id="viewContractBody">
            <!-- Content will be populated by JavaScript -->
        </div>
        
        <div class="modal-footer">
            <button type="button" onclick="closeViewContractModal()" class="btn btn-secondary">Close</button>
            <button type="button" onclick="editContractFromView()" class="btn btn-primary" id="editContractFromViewBtn">Edit Contract</button>
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
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

// Contract Management Functions
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

function openViewContractModal() {
    document.getElementById('viewContractModal').style.display = 'block';
}

function closeViewContractModal() {
    document.getElementById('viewContractModal').style.display = 'none';
}

function viewContract(contractId) {
    const contract = contractData.find(c => c.id == contractId);
    if (!contract) {
        alert('Contract not found');
        return;
    }
    
    document.getElementById('viewContractTitle').textContent = contract.name;
    document.getElementById('viewContractBody').innerHTML = `
        <div class="form-section">
            <h3>Contract Details</h3>
            <div class="contract-details">
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">${contract.name}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type:</span>
                    <span class="detail-value">${contract.type.charAt(0).toUpperCase() + contract.type.slice(1)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Created:</span>
                    <span class="detail-value">${new Date(contract.created_at).toLocaleDateString()}</span>
                </div>
            </div>
        </div>
        <div class="form-section">
            <h3>Contract Content</h3>
            <div class="contract-full-content">
                ${contract.content.replace(/\n/g, '<br>')}
            </div>
        </div>
    `;
    
    document.getElementById('editContractFromViewBtn').onclick = () => editContract(contractId);
    openViewContractModal();
}

function editContract(contractId) {
    const contract = contractData.find(c => c.id == contractId);
    if (!contract) {
        alert('Contract not found');
        return;
    }
    
    document.getElementById('edit_contract_id').value = contract.id;
    document.getElementById('edit_contract_name').value = contract.name;
    document.getElementById('edit_contract_type').value = contract.type;
    document.getElementById('edit_contract_content').value = contract.content;
    
    closeViewContractModal();
    openEditContractModal();
}

function editContractFromView() {
    const contractId = document.getElementById('edit_contract_id').value;
    editContract(contractId);
}

function deleteContract(contractId, contractName) {
    if (confirm(`Are you sure you want to delete "${contractName}"?\n\nThis action cannot be undone and will remove all associated staff assignments.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete_contract_template">
            <input type="hidden" name="contract_id" value="${contractId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function assignContract(staffId, templateId) {
    if (confirm('Are you sure you want to assign this contract to the staff member?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="assign_contract">
            <input type="hidden" name="staff_id" value="${staffId}">
            <input type="hidden" name="template_id" value="${templateId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Staff Management Functions

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

function viewStaff(staffId) {
    const staff = staffData.find(s => s.id == staffId);
    if (!staff) {
        alert('Staff member not found');
        return;
    }
    
    const age = staff.date_of_birth ? calculateAge(staff.date_of_birth) : 'N/A';
    const isMinor = age !== 'N/A' && age < 16;
    
    const modalBody = document.getElementById('viewModalBody');
    modalBody.innerHTML = `
        <div class="form-section">
            <h3>Basic Information</h3>
            <div class="staff-view-grid">
                <div class="view-item">
                    <span class="view-label">Staff ID:</span>
                    <span class="view-value">${staff.staff_id || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Full Name:</span>
                    <span class="view-value">${staff.full_name || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Preferred Name:</span>
                    <span class="view-value">${staff.preferred_name || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Manager:</span>
                    <span class="view-value">${staff.manager || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Job Title:</span>
                    <span class="view-value">${staff.job_title || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Department:</span>
                    <span class="view-value">${staff.department || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Region:</span>
                    <span class="view-value">${staff.region || 'Not Set'}</span>
                </div>
                ${isMinor ? `
                <div class="view-item">
                    <span class="view-label">Age:</span>
                    <span class="view-value" style="color: var(--primary-color); font-weight: 600;">${age} (Minor)</span>
                </div>
                ` : ''}
            </div>
        </div>
        
        <div class="form-section">
            <h3>Contact Information</h3>
            <div class="staff-view-grid">
                <div class="view-item">
                    <span class="view-label">Nexi Email:</span>
                    <span class="view-value">${staff.nexi_email || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Private Email:</span>
                    <span class="view-value">${staff.private_email || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Phone Number:</span>
                    <span class="view-value">${staff.phone_number || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Date of Birth:</span>
                    <span class="view-value">${staff.date_of_birth ? formatDate(staff.date_of_birth) : 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Contract Status:</span>
                    <span class="view-value ${staff.contract_completed == '1' ? 'contract-completed' : 'contract-pending'}">${staff.contract_completed == '1' ? 'Completed' : 'Pending'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Discord Username:</span>
                    <span class="view-value">${staff.discord_username || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Discord ID:</span>
                    <span class="view-value">${staff.discord_id || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Nationality:</span>
                    <span class="view-value">${staff.nationality || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Country of Residence:</span>
                    <span class="view-value">${staff.country_of_residence || 'Not Set'}</span>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3>Employment Details</h3>
            <div class="staff-view-grid">
                <div class="view-item">
                    <span class="view-label">Date Joined:</span>
                    <span class="view-value">${staff.date_joined ? formatDate(staff.date_joined) : 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">E-learning Status:</span>
                    <span class="view-value">${staff.elearning_status || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Time Off Balance:</span>
                    <span class="view-value">${staff.time_off_balance || 0} days</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Account Status:</span>
                    <span class="view-value ${staff.account_status === 'Active' ? 'status-active' : 'status-inactive'}">${staff.account_status || 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Two-Factor Auth:</span>
                    <span class="view-value ${staff.two_fa_status ? 'two-fa-enabled' : 'two-fa-disabled'}">${staff.two_fa_status ? 'Enabled' : 'Disabled'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Last Login:</span>
                    <span class="view-value">${staff.last_login ? formatDateTime(staff.last_login) : 'Never'}</span>
                </div>
            </div>
        </div>
        
        ${isMinor && staff.parent_contact ? `
        <div class="form-section">
            <h3>Parent Contact</h3>
            <div class="view-item-full">
                <span class="view-value">${staff.parent_contact}</span>
            </div>
        </div>
        ` : ''}
        
        ${staff.internal_notes ? `
        <div class="form-section">
            <h3>Internal Notes</h3>
            <div class="view-item-full">
                <span class="view-value">${staff.internal_notes}</span>
            </div>
        </div>
        ` : ''}
        
        <div class="form-section">
            <h3>Record Information</h3>
            <div class="staff-view-grid">
                <div class="view-item">
                    <span class="view-label">Created:</span>
                    <span class="view-value">${staff.created_at ? formatDateTime(staff.created_at) : 'Not Set'}</span>
                </div>
                <div class="view-item">
                    <span class="view-label">Last Updated:</span>
                    <span class="view-value">${staff.updated_at ? formatDateTime(staff.updated_at) : 'Not Set'}</span>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('editFromViewBtn').onclick = () => editStaff(staffId);
    openViewModal();
}

function editStaff(staffId) {
    const staff = staffData.find(s => s.id == staffId);
    if (!staff) {
        alert('Staff member not found');
        return;
    }
    
    // Populate edit form
    document.getElementById('edit_staff_id').value = staff.id;
    document.getElementById('edit_staff_id_display').value = staff.staff_id || '';
    document.getElementById('edit_manager').value = staff.manager || '';
    document.getElementById('edit_full_name').value = staff.full_name || '';
    document.getElementById('edit_preferred_name').value = staff.preferred_name || '';
    document.getElementById('edit_job_title').value = staff.job_title || '';
    document.getElementById('edit_department').value = staff.department || '';
    document.getElementById('edit_region').value = staff.region || '';
    document.getElementById('edit_nexi_email').value = staff.nexi_email || '';
    document.getElementById('edit_private_email').value = staff.private_email || '';
    document.getElementById('edit_phone_number').value = staff.phone_number || '';
    document.getElementById('edit_date_of_birth').value = staff.date_of_birth || '';
    document.getElementById('edit_discord_username').value = staff.discord_username || '';
    document.getElementById('edit_discord_id').value = staff.discord_id || '';
    document.getElementById('edit_nationality').value = staff.nationality || '';
    document.getElementById('edit_country_of_residence').value = staff.country_of_residence || '';
    document.getElementById('edit_date_joined').value = staff.date_joined || '';
    document.getElementById('edit_elearning_status').value = staff.elearning_status || 'Not Started';
    document.getElementById('edit_time_off_balance').value = staff.time_off_balance || 0;
    document.getElementById('edit_account_status').value = staff.account_status || 'Active';
    document.getElementById('edit_parent_contact').value = staff.parent_contact || '';
    document.getElementById('edit_internal_notes').value = staff.internal_notes || '';
    document.getElementById('edit_two_fa_status').checked = staff.two_fa_status == '1';
    document.getElementById('edit_contract_completed').checked = staff.contract_completed == '1';
    
    closeViewModal();
    openEditModal();
}

function editStaffFromView() {
    const staffId = document.getElementById('edit_staff_id').value;
    editStaff(staffId);
}

function deleteStaff(staffId, staffName) {
    if (confirm(`Are you sure you want to delete ${staffName}?\n\nThis action cannot be undone and will permanently remove all staff data.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_staff';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'delete_staff_id';
        idInput.value = staffId;
        
        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modals when clicking outside of them
window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    const viewModal = document.getElementById('viewModal');
    const addContractModal = document.getElementById('addContractModal');
    const editContractModal = document.getElementById('editContractModal');
    const viewContractModal = document.getElementById('viewContractModal');
    
    if (event.target === addModal) {
        closeAddModal();
    } else if (event.target === editModal) {
        closeEditModal();
    } else if (event.target === viewModal) {
        closeViewModal();
    } else if (event.target === addContractModal) {
        closeAddContractModal();
    } else if (event.target === editContractModal) {
        closeEditContractModal();
    } else if (event.target === viewContractModal) {
        closeViewContractModal();
    }
}

// Helper functions
function calculateAge(dateOfBirth) {
    if (!dateOfBirth) return 'N/A';
    const dob = new Date(dateOfBirth);
    const now = new Date();
    const diffTime = Math.abs(now - dob);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return Math.floor(diffDays / 365);
}

function formatDate(dateString) {
    if (!dateString) return 'Not Set';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'Not Set';
    const date = new Date(dateTimeString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
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
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
