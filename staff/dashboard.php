<?php
require_once __DIR__ . '/../config/config.php';

// requireAuth(); // Enable when ready

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
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

try {
    $db->exec($createTableSQL);
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
            'Executive',
            'oliver@nexihub.com',
            'Active',
            '2024-01-01',
            1
        ]);
    } catch (PDOException $e) {
        error_log("Error inserting Oliver Reaney: " . $e->getMessage());
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
                        staff_id, manager, full_name, job_title, department, 
                        preferred_name, nexi_email, private_email, phone_number,
                        discord_username, discord_id, nationality, country_of_residence,
                        date_of_birth, two_fa_status, date_joined, elearning_status,
                        time_off_balance, parent_contact, account_status, internal_notes
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                try {
                    $stmt->execute([
                        $_POST['staff_id'],
                        $_POST['manager'],
                        $_POST['full_name'],
                        $_POST['job_title'],
                        $_POST['department'],
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
                        $_POST['internal_notes']
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
                        manager = ?, full_name = ?, job_title = ?, department = ?,
                        preferred_name = ?, nexi_email = ?, private_email = ?, phone_number = ?,
                        discord_username = ?, discord_id = ?, nationality = ?, country_of_residence = ?,
                        date_of_birth = ?, two_fa_status = ?, date_joined = ?, elearning_status = ?,
                        time_off_balance = ?, parent_contact = ?, account_status = ?, internal_notes = ?,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?
                ");
                
                try {
                    $stmt->execute([
                        $_POST['manager'],
                        $_POST['full_name'],
                        $_POST['job_title'],
                        $_POST['department'],
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
                        $_POST['staff_id']
                    ]);
                    header("Location: dashboard.php?success=Staff member updated successfully");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error updating staff member: " . $e->getMessage();
                }
                break;
        }
    }
}

// Fetch all staff members
$stmt = $db->prepare("SELECT * FROM staff_profiles ORDER BY full_name");
$stmt->execute();
$staff_members = $stmt->fetchAll();

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
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    text-align: center;
}

.dashboard-header h1 {
    margin: 0;
    font-size: 2.5em;
    font-weight: 300;
}

.dashboard-header p {
    margin: 10px 0 0 0;
    opacity: 0.9;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: 500;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.9em;
}

.staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.staff-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.staff-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.staff-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.staff-name {
    font-size: 1.3em;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.staff-id {
    background-color: #667eea;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    font-weight: 500;
}

.staff-details {
    display: grid;
    gap: 8px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
}

.detail-label {
    font-weight: 500;
    color: #6c757d;
    min-width: 100px;
}

.detail-value {
    color: #333;
    text-align: right;
}

.status-active {
    color: #28a745;
    font-weight: 600;
}

.status-inactive {
    color: #dc3545;
    font-weight: 600;
}

.two-fa-enabled {
    color: #28a745;
    font-weight: 600;
}

.two-fa-disabled {
    color: #dc3545;
    font-weight: 600;
}

.staff-actions {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
    text-align: center;
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
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.modal-title {
    font-size: 1.5em;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.close {
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: #aaa;
}

.close:hover {
    color: #000;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #e9ecef;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.checkbox-group input[type="checkbox"] {
    width: auto;
    margin: 0;
}

@media (max-width: 768px) {
    .staff-grid {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 10px auto;
        padding: 20px;
    }
}
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Staff Management Dashboard</h1>
        <p>Secure Staff Information Management System</p>
    </div>

    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <button onclick="openAddModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Staff Member
        </button>
        <span style="margin-left: 20px; color: #6c757d;">
            Total Staff: <strong><?php echo count($staff_members); ?></strong>
        </span>
    </div>

    <div class="staff-grid">
        <?php foreach ($staff_members as $staff): ?>
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
                        <span class="detail-label">Department:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($staff['department'] ?: 'Not Set'); ?></span>
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
                    <?php if ($staff['date_of_birth'] && calculateAge($staff['date_of_birth']) < 16): ?>
                        <div class="detail-row">
                            <span class="detail-label">Age:</span>
                            <span class="detail-value" style="color: #ffc107; font-weight: 600;">
                                <?php echo calculateAge($staff['date_of_birth']); ?> (Minor)
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="staff-actions">
                    <button onclick="viewStaff(<?php echo $staff['id']; ?>)" class="btn btn-success btn-sm">View Details</button>
                    <button onclick="editStaff(<?php echo $staff['id']; ?>)" class="btn btn-warning btn-sm">Edit</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($staff_members)): ?>
        <div style="text-align: center; padding: 60px; color: #6c757d;">
            <i class="fas fa-users" style="font-size: 3em; margin-bottom: 20px; opacity: 0.5;"></i>
            <h3>No Staff Members Found</h3>
            <p>Click "Add New Staff Member" to get started.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Add Staff Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Staff Member</h2>
            <span class="close" onclick="closeAddModal()">&times;</span>
        </div>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="add_staff">
            
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
                        <option value="Executive">Executive</option>
                        <option value="Development">Development</option>
                        <option value="Design">Design</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales</option>
                        <option value="Support">Support</option>
                        <option value="HR">Human Resources</option>
                        <option value="Finance">Finance</option>
                    </select>
                </div>
            </div>
            
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
            
            <div style="text-align: right; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                <button type="button" onclick="closeAddModal()" class="btn" style="background: #6c757d; color: white; margin-right: 10px;">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Staff Member</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function viewStaff(staffId) {
    // TODO: Implement view modal
    alert('View staff details for ID: ' + staffId + '\n\nThis feature will be implemented in the next update.');
}

function editStaff(staffId) {
    // TODO: Implement edit modal
    alert('Edit staff for ID: ' + staffId + '\n\nThis feature will be implemented in the next update.');
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('addModal');
    if (event.target === modal) {
        closeAddModal();
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
