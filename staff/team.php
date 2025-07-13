<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "Staff Management";
$page_description = "Manage staff accounts and permissions";

// Handle actions
$action = $_GET['action'] ?? '';
$staffId = $_GET['id'] ?? '';
$message = '';

if ($_POST && $action) {
    switch($action) {
        case 'update_permissions':
            $staffId = $_POST['staff_id'];
            $permissions = $_POST['permissions'] ?? [];
            // In a real app, you'd update the staff permissions in the database
            $message = "Staff permissions updated successfully!";
            break;
            
        case 'deactivate_staff':
            $staffId = $_POST['staff_id'];
            // In a real app, you'd deactivate the staff member
            $message = "Staff member deactivated successfully!";
            break;
            
        case 'invite_staff':
            $email = $_POST['email'];
            $role = $_POST['role'];
            // In a real app, you'd send an invitation email
            $message = "Invitation sent to " . htmlspecialchars($email) . " successfully!";
            break;
    }
}

// Sample staff data (in a real app, this would come from your database)
$staffMembers = [
    [
        'id' => 1,
        'email' => 'ollie.r@nexihub.uk',
        'discord_username' => 'Ollie',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/123456789/avatar.png',
        'role' => 'Administrator',
        'status' => 'active',
        'last_login' => '2024-01-15 16:30:00',
        'created' => '2023-01-01 10:00:00',
        'two_fa_enabled' => true,
        'permissions' => ['user_management', 'billing', 'support', 'system', 'analytics', 'staff_management']
    ],
    [
        'id' => 2,
        'email' => 'sarah.m@nexihub.uk',
        'discord_username' => 'Sarah',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/987654321/avatar.png',
        'role' => 'Support Manager',
        'status' => 'active',
        'last_login' => '2024-01-15 14:20:00',
        'created' => '2023-03-15 09:30:00',
        'two_fa_enabled' => true,
        'permissions' => ['support', 'user_management']
    ],
    [
        'id' => 3,
        'email' => 'mike.j@nexihub.uk',
        'discord_username' => 'Mike',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/456789123/avatar.png',
        'role' => 'Developer',
        'status' => 'active',
        'last_login' => '2024-01-15 12:45:00',
        'created' => '2023-05-20 14:15:00',
        'two_fa_enabled' => false,
        'permissions' => ['system', 'analytics']
    ],
    [
        'id' => 4,
        'email' => 'emma.w@nexihub.uk',
        'discord_username' => 'Emma',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/789123456/avatar.png',
        'role' => 'Billing Specialist',
        'status' => 'active',
        'last_login' => '2024-01-14 16:10:00',
        'created' => '2023-07-10 11:00:00',
        'two_fa_enabled' => true,
        'permissions' => ['billing', 'user_management']
    ],
    [
        'id' => 5,
        'email' => 'alex.b@nexihub.uk',
        'discord_username' => 'Alex',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/321654987/avatar.png',
        'role' => 'Junior Support',
        'status' => 'inactive',
        'last_login' => '2024-01-10 09:30:00',
        'created' => '2023-09-05 13:20:00',
        'two_fa_enabled' => false,
        'permissions' => ['support']
    ],
];

$availablePermissions = [
    'user_management' => 'User Management',
    'billing' => 'Billing & Payments',
    'support' => 'Support Tickets',
    'system' => 'System Health',
    'analytics' => 'Analytics',
    'staff_management' => 'Staff Management'
];

$availableRoles = [
    'Administrator',
    'Support Manager',
    'Developer',
    'Billing Specialist',
    'Junior Support',
    'Moderator'
];

// Get specific staff member for editing
$editStaff = null;
if ($action === 'edit' && $staffId) {
    $editStaff = array_filter($staffMembers, fn($s) => $s['id'] == $staffId);
    $editStaff = reset($editStaff);
}

include __DIR__ . '/../includes/header.php';
?>

<style>
.staff-management {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--background-dark) 0%, var(--background-light) 100%);
    padding: 2rem 0;
}

.page-header {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.header-title {
    color: var(--text-primary);
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.invite-btn {
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.invite-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.back-btn {
    padding: 0.75rem 1.5rem;
    background: var(--background-dark);
    color: var(--text-secondary);
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.back-btn:hover {
    color: var(--text-primary);
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.staff-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.staff-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.staff-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border-color: var(--primary-color);
}

.staff-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.staff-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid var(--primary-color);
}

.staff-info h3 {
    color: var(--text-primary);
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.staff-info p {
    color: var(--text-secondary);
    margin: 0.1rem 0;
    font-size: 0.9rem;
}

.staff-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-inactive {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.staff-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.detail-item {
    color: var(--text-secondary);
}

.detail-label {
    font-weight: 600;
    color: var(--text-primary);
}

.permissions-list {
    margin-bottom: 1.5rem;
}

.permissions-title {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.permission-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.permission-tag {
    padding: 0.25rem 0.75rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.staff-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.action-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.action-btn.edit {
    background: var(--primary-color);
    color: white;
}

.action-btn.deactivate {
    background: rgba(239, 68, 68, 0.9);
    color: white;
}

.action-btn.permissions {
    background: var(--background-dark);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.message {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22c55e;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    backdrop-filter: blur(10px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    width: 90%;
    max-width: 500px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.modal-title {
    color: var(--text-primary);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
}

.close-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-input, .form-select {
    width: 100%;
    padding: 0.75rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-color);
}

.checkbox-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.checkbox-item input[type="checkbox"] {
    accent-color: var(--primary-color);
}

.checkbox-item label {
    color: var(--text-secondary);
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-secondary {
    background: var(--background-dark);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.btn:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .staff-grid {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .modal-content {
        width: 95%;
        padding: 1.5rem;
    }
}
</style>

<div class="staff-management">
    <div class="container">
        <div class="page-header">
            <div class="header-content">
                <h1 class="header-title">Staff Management</h1>
                <div class="header-actions">
                    <button onclick="openInviteModal()" class="invite-btn">+ Invite Staff</button>
                    <a href="/staff/dashboard" class="back-btn">← Back to Dashboard</a>
                </div>
            </div>
            
            <?php if ($message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($staffMembers); ?></span>
                    <span class="stat-label">Total Staff</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($staffMembers, fn($s) => $s['status'] === 'active')); ?></span>
                    <span class="stat-label">Active Staff</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($staffMembers, fn($s) => $s['two_fa_enabled'])); ?></span>
                    <span class="stat-label">2FA Enabled</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($staffMembers, fn($s) => strtotime($s['last_login']) > strtotime('-24 hours'))); ?></span>
                    <span class="stat-label">Active Today</span>
                </div>
            </div>
        </div>

        <div class="staff-grid">
            <?php foreach ($staffMembers as $staff): ?>
                <div class="staff-card">
                    <span class="staff-status status-<?php echo $staff['status']; ?>">
                        <?php echo ucfirst($staff['status']); ?>
                    </span>
                    
                    <div class="staff-header">
                        <img src="<?php echo htmlspecialchars($staff['discord_avatar'] ?? '/assets/default-avatar.png'); ?>" 
                             alt="Staff Avatar" class="staff-avatar">
                        <div class="staff-info">
                            <h3><?php echo htmlspecialchars($staff['discord_username']); ?></h3>
                            <p><?php echo htmlspecialchars($staff['email']); ?></p>
                            <p><strong><?php echo htmlspecialchars($staff['role']); ?></strong></p>
                        </div>
                    </div>
                    
                    <div class="staff-details">
                        <div class="detail-item">
                            <div class="detail-label">Last Login</div>
                            <?php echo $staff['last_login'] ? date('M j, Y g:i A', strtotime($staff['last_login'])) : 'Never'; ?>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">2FA Status</div>
                            <?php echo $staff['two_fa_enabled'] ? '✅ Enabled' : '❌ Disabled'; ?>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Joined</div>
                            <?php echo date('M j, Y', strtotime($staff['created'])); ?>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Permissions</div>
                            <?php echo count($staff['permissions']); ?> granted
                        </div>
                    </div>
                    
                    <div class="permissions-list">
                        <div class="permissions-title">Permissions</div>
                        <div class="permission-tags">
                            <?php foreach ($staff['permissions'] as $permission): ?>
                                <span class="permission-tag">
                                    <?php echo htmlspecialchars($availablePermissions[$permission] ?? $permission); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="staff-actions">
                        <button onclick="openPermissionsModal(<?php echo htmlspecialchars(json_encode($staff)); ?>)" class="action-btn permissions">
                            Edit Permissions
                        </button>
                        <?php if ($staff['status'] === 'active'): ?>
                            <button onclick="deactivateStaff(<?php echo $staff['id']; ?>)" class="action-btn deactivate">
                                Deactivate
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Invite Staff Modal -->
<div id="inviteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Invite New Staff Member</h2>
            <button class="close-btn" onclick="closeModal('inviteModal')">&times;</button>
        </div>
        <form method="POST" action="?action=invite_staff">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" required 
                       placeholder="staff@nexihub.uk">
            </div>
            <div class="form-group">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="">Select a role...</option>
                    <?php foreach ($availableRoles as $role): ?>
                        <option value="<?php echo htmlspecialchars($role); ?>"><?php echo htmlspecialchars($role); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" onclick="closeModal('inviteModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Send Invitation</button>
            </div>
        </form>
    </div>
</div>

<!-- Permissions Modal -->
<div id="permissionsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Permissions</h2>
            <button class="close-btn" onclick="closeModal('permissionsModal')">&times;</button>
        </div>
        <form method="POST" action="?action=update_permissions">
            <input type="hidden" id="staff_id" name="staff_id">
            <div class="form-group">
                <label class="form-label">Permissions</label>
                <div class="checkbox-group">
                    <?php foreach ($availablePermissions as $key => $label): ?>
                        <div class="checkbox-item">
                            <input type="checkbox" id="perm_<?php echo $key; ?>" name="permissions[]" value="<?php echo $key; ?>">
                            <label for="perm_<?php echo $key; ?>"><?php echo htmlspecialchars($label); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" onclick="closeModal('permissionsModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Permissions</button>
            </div>
        </form>
    </div>
</div>

<script>
function openInviteModal() {
    document.getElementById('inviteModal').style.display = 'block';
}

function openPermissionsModal(staff) {
    document.getElementById('staff_id').value = staff.id;
    
    // Clear all checkboxes first
    document.querySelectorAll('#permissionsModal input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });
    
    // Check the staff member's current permissions
    staff.permissions.forEach(permission => {
        const checkbox = document.getElementById('perm_' + permission);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
    
    document.getElementById('permissionsModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function deactivateStaff(staffId) {
    if (confirm('Are you sure you want to deactivate this staff member?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?action=deactivate_staff';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'staff_id';
        input.value = staffId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
