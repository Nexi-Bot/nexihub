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
        case 'update_staff':
            $staffId = $_POST['staff_id'];
            $updates = $_POST;
            // In a real app, you'd update the staff member's information in the database
            $message = "Staff information updated successfully!";
            break;
            $email = $_POST['email'];
            $role = $_POST['role'];
            // In a real app, you'd send an invitation email
            $message = "Invitation sent to " . htmlspecialchars($email) . " successfully!";
            break;
    }
}

// Real staff data with complete information
$staffMembers = [
    [
        'id' => 1,
        'staff_id' => 'NEXI001',
        'full_name' => 'Oliver Reaney',
        'preferred_name' => 'Ollie',
        'discord_username' => 'olliereaney',
        'discord_id' => '123456789012345678',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/123456789012345678/avatar_hash.png',
        'role' => 'Chief Executive Officer & Founder',
        'department' => 'Executive Leadership',
        'manager' => null,
        'nexi_email' => 'ollie.r@nexihub.uk',
        'private_email' => 'oliver.reaney@gmail.com',
        'phone_number' => '+44 7700 900123',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1995-03-15',
        'status' => 'active',
        'last_login' => '2024-01-15 16:30:00',
        'created' => '2020-01-01 10:00:00',
        'two_fa_enabled' => true,
        'permissions' => ['user_management', 'billing', 'support', 'system', 'analytics', 'staff_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2020-01-01', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2020-01-01', 'status' => 'signed'],
            ['name' => 'Performance Review 2024', 'type' => 'review', 'date' => '2024-01-01', 'status' => 'completed'],
            ['name' => 'Training Certificate - Leadership', 'type' => 'training', 'date' => '2023-06-15', 'status' => 'completed']
        ]
    ],
    [
        'id' => 2,
        'staff_id' => 'NEXI002',
        'full_name' => 'Benjamin Clarke',
        'preferred_name' => 'Benjamin',
        'discord_username' => 'benjaminclarke',
        'discord_id' => '234567890123456789',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/234567890123456789/avatar_hash.png',
        'role' => 'Managing Director',
        'department' => 'Executive Leadership',
        'manager' => 'Oliver Reaney',
        'nexi_email' => 'benjamin@nexihub.uk',
        'private_email' => 'benjamin.clarke@outlook.com',
        'phone_number' => '+44 7700 900124',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1992-08-22',
        'status' => 'active',
        'last_login' => '2024-01-15 14:20:00',
        'created' => '2020-02-01 09:30:00',
        'two_fa_enabled' => true,
        'permissions' => ['user_management', 'billing', 'support', 'system', 'analytics', 'staff_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2020-02-01', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2020-02-01', 'status' => 'signed'],
            ['name' => 'Performance Review 2024', 'type' => 'review', 'date' => '2024-01-01', 'status' => 'completed']
        ]
    ],
    [
        'id' => 3,
        'staff_id' => 'NEXI003',
        'full_name' => 'Paige Williams',
        'preferred_name' => 'Paige',
        'discord_username' => 'paigewilliams',
        'discord_id' => '345678901234567890',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/345678901234567890/avatar_hash.png',
        'role' => 'Chief Innovation Officer',
        'department' => 'Executive Leadership',
        'manager' => 'Oliver Reaney',
        'nexi_email' => 'paige@nexihub.uk',
        'private_email' => 'paige.williams@gmail.com',
        'phone_number' => '+44 7700 900125',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1994-11-07',
        'status' => 'active',
        'last_login' => '2024-01-15 12:45:00',
        'created' => '2020-03-01 14:15:00',
        'two_fa_enabled' => true,
        'permissions' => ['system', 'analytics', 'staff_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2020-03-01', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2020-03-01', 'status' => 'signed'],
            ['name' => 'Innovation Strategy Document', 'type' => 'project', 'date' => '2024-01-01', 'status' => 'completed']
        ]
    ],
    [
        'id' => 4,
        'staff_id' => 'NEXI004',
        'full_name' => 'Chukwuma Ikemefuna',
        'preferred_name' => 'Chukwumam',
        'discord_username' => 'chukwumam',
        'discord_id' => '456789012345678901',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/456789012345678901/avatar_hash.png',
        'role' => 'Deputy Managing Director & Development Lead',
        'department' => 'Senior Leadership',
        'manager' => 'Benjamin Clarke',
        'nexi_email' => 'chukwumam@nexihub.uk',
        'private_email' => 'chukwuma.ikemefuna@gmail.com',
        'phone_number' => '+234 806 123 4567',
        'nationality' => 'Nigerian',
        'country' => 'Nigeria',
        'date_of_birth' => '1993-06-12',
        'status' => 'active',
        'last_login' => '2024-01-14 16:10:00',
        'created' => '2020-04-01 11:00:00',
        'two_fa_enabled' => true,
        'permissions' => ['system', 'analytics', 'staff_management', 'user_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2020-04-01', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2020-04-01', 'status' => 'signed'],
            ['name' => 'Development Certification', 'type' => 'training', 'date' => '2023-08-15', 'status' => 'completed']
        ]
    ],
    [
        'id' => 5,
        'staff_id' => 'NEXI005',
        'full_name' => 'Samuel Thompson',
        'preferred_name' => 'Sam',
        'discord_username' => 'samthompson',
        'discord_id' => '567890123456789012',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/567890123456789012/avatar_hash.png',
        'role' => 'Chief Operating Officer',
        'department' => 'Senior Leadership',
        'manager' => 'Benjamin Clarke',
        'nexi_email' => 'sam@nexihub.uk',
        'private_email' => 'samuel.thompson@hotmail.com',
        'phone_number' => '+44 7700 900126',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1991-12-03',
        'status' => 'active',
        'last_login' => '2024-01-14 15:30:00',
        'created' => '2020-05-01 13:20:00',
        'two_fa_enabled' => true,
        'permissions' => ['user_management', 'support', 'analytics', 'staff_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2020-05-01', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2020-05-01', 'status' => 'signed'],
            ['name' => 'Operations Manual', 'type' => 'reference', 'date' => '2023-12-01', 'status' => 'current']
        ]
    ],
    [
        'id' => 6,
        'staff_id' => 'NEXI006',
        'full_name' => 'Christopher Davis',
        'preferred_name' => 'Christopher',
        'discord_username' => 'christopherdavis',
        'discord_id' => '678901234567890123',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/678901234567890123/avatar_hash.png',
        'role' => 'Chief Financial Officer',
        'department' => 'Senior Leadership',
        'manager' => 'Benjamin Clarke',
        'nexi_email' => 'christopher@nexihub.uk',
        'private_email' => 'christopher.davis@yahoo.com',
        'phone_number' => '+1 555 123 4567',
        'nationality' => 'American',
        'country' => 'United States',
        'date_of_birth' => '1990-04-18',
        'status' => 'active',
        'last_login' => '2024-01-14 10:45:00',
        'created' => '2020-06-01 09:15:00',
        'two_fa_enabled' => true,
        'permissions' => ['billing', 'analytics', 'user_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2020-06-01', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2020-06-01', 'status' => 'signed'],
            ['name' => 'Financial Compliance Training', 'type' => 'training', 'date' => '2023-09-20', 'status' => 'completed']
        ]
    ],
    [
        'id' => 7,
        'staff_id' => 'NEXI007',
        'full_name' => 'Barbara Martinez',
        'preferred_name' => 'Barbara',
        'discord_username' => 'barbaramartinez',
        'discord_id' => '789012345678901234',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/789012345678901234/avatar_hash.png',
        'role' => 'Chief Legal Officer',
        'department' => 'Senior Leadership',
        'manager' => 'Benjamin Clarke',
        'nexi_email' => 'barbara@nexihub.uk',
        'private_email' => 'barbara.martinez@gmail.com',
        'phone_number' => '+34 600 123 456',
        'nationality' => 'Spanish',
        'country' => 'Spain',
        'date_of_birth' => '1988-09-25',
        'status' => 'active',
        'last_login' => '2024-01-13 14:20:00',
        'created' => '2020-07-01 11:30:00',
        'two_fa_enabled' => true,
        'permissions' => ['user_management', 'staff_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2020-07-01', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2020-07-01', 'status' => 'signed'],
            ['name' => 'Legal Compliance Manual', 'type' => 'reference', 'date' => '2023-11-15', 'status' => 'current']
        ]
    ],
    [
        'id' => 8,
        'staff_id' => 'NEXI008',
        'full_name' => 'Maisie Johnson',
        'preferred_name' => 'Maisie',
        'discord_username' => 'maisiejohnson',
        'discord_id' => '890123456789012345',
        'discord_avatar' => 'https://cdn.discordapp.com/avatars/890123456789012345/avatar_hash.png',
        'role' => 'Head of Human Resources',
        'department' => 'Corporate Functions',
        'manager' => 'Benjamin Clarke',
        'nexi_email' => 'maisie@nexihub.uk',
        'private_email' => 'maisie.johnson@outlook.com',
        'phone_number' => '+44 7700 900127',
        'nationality' => 'British',
        'country' => 'United Kingdom',
        'date_of_birth' => '1996-01-14',
        'status' => 'active',
        'last_login' => '2024-01-15 09:15:00',
        'created' => '2023-01-15 10:00:00',
        'two_fa_enabled' => true,
        'permissions' => ['staff_management', 'user_management'],
        'documents' => [
            ['name' => 'Employment Contract', 'type' => 'contract', 'date' => '2023-01-15', 'status' => 'signed'],
            ['name' => 'NDA Agreement', 'type' => 'legal', 'date' => '2023-01-15', 'status' => 'signed'],
            ['name' => 'HR Policies Manual', 'type' => 'reference', 'date' => '2023-02-01', 'status' => 'current']
        ]
    ]
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
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
}

.staff-details-full {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
}

.detail-section {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.detail-section-title {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

.action-btn.documents {
    background: #6366f1;
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

.documents-list {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 1rem;
}

.document-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    margin-bottom: 0.5rem;
}

.document-info {
    flex: 1;
}

.document-name {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.document-meta {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.document-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-signed, .status-completed, .status-current {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.document-actions {
    display: flex;
    gap: 0.5rem;
}

.document-btn {
    padding: 0.25rem 0.5rem;
    border: none;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.document-btn.view {
    background: var(--primary-color);
    color: white;
}

.document-btn.delete {
    background: rgba(239, 68, 68, 0.9);
    color: white;
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
                            <h3><?php echo htmlspecialchars($staff['full_name']); ?></h3>
                            <p class="preferred-name">Preferred: <?php echo htmlspecialchars($staff['preferred_name']); ?></p>
                            <p><strong><?php echo htmlspecialchars($staff['role']); ?></strong></p>
                            <p><em><?php echo htmlspecialchars($staff['department'] ?? 'Not specified'); ?></em></p>
                        </div>
                    </div>
                    
                    <div class="staff-details-full">
                        <div class="detail-section">
                            <div class="detail-section-title">Basic Information</div>
                            <div class="staff-details">
                                <div class="detail-item">
                                    <div class="detail-label">Staff ID</div>
                                    <?php echo htmlspecialchars($staff['staff_id']); ?>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Manager</div>
                                    <?php echo $staff['manager'] ? htmlspecialchars($staff['manager']) : 'None'; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="detail-section-title">Contact Information</div>
                            <div class="staff-details">
                                <div class="detail-item">
                                    <div class="detail-label">Nexi Email</div>
                                    <?php echo htmlspecialchars($staff['nexi_email']); ?>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Private Email</div>
                                    <?php echo htmlspecialchars($staff['private_email']); ?>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Phone</div>
                                    <?php echo htmlspecialchars($staff['phone_number']); ?>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Discord</div>
                                    @<?php echo htmlspecialchars($staff['discord_username']); ?> (<?php echo htmlspecialchars($staff['discord_id']); ?>)
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="detail-section-title">Personal Information</div>
                            <div class="staff-details">
                                <div class="detail-item">
                                    <div class="detail-label">Nationality</div>
                                    <?php echo htmlspecialchars($staff['nationality']); ?>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Country</div>
                                    <?php echo htmlspecialchars($staff['country']); ?>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Date of Birth</div>
                                    <?php echo date('M j, Y', strtotime($staff['date_of_birth'])); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="detail-section-title">Employment Details</div>
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
                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($staff)); ?>)" class="action-btn edit">
                            Edit Info
                        </button>
                        <button onclick="openDocumentsModal(<?php echo htmlspecialchars(json_encode($staff)); ?>)" class="action-btn documents">
                            Documents (<?php echo count($staff['documents']); ?>)
                        </button>
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

<!-- Edit Staff Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Staff Information</h2>
            <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form method="POST" action="?action=update_staff">
            <input type="hidden" id="edit_staff_id" name="staff_id">
            <div class="form-group">
                <label for="edit_full_name" class="form-label">Full Name</label>
                <input type="text" id="edit_full_name" name="full_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_preferred_name" class="form-label">Preferred Name</label>
                <input type="text" id="edit_preferred_name" name="preferred_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_discord_username" class="form-label">Discord Username</label>
                <input type="text" id="edit_discord_username" name="discord_username" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_discord_id" class="form-label">Discord ID</label>
                <input type="text" id="edit_discord_id" name="discord_id" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_role" class="form-label">Job Title</label>
                <input type="text" id="edit_role" name="role" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_department" class="form-label">Department</label>
                <input type="text" id="edit_department" name="department" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_manager" class="form-label">Manager</label>
                <input type="text" id="edit_manager" name="manager" class="form-input">
            </div>
            <div class="form-group">
                <label for="edit_nexi_email" class="form-label">Nexi Email</label>
                <input type="email" id="edit_nexi_email" name="nexi_email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_private_email" class="form-label">Private Email</label>
                <input type="email" id="edit_private_email" name="private_email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_phone_number" class="form-label">Phone Number</label>
                <input type="tel" id="edit_phone_number" name="phone_number" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_nationality" class="form-label">Nationality</label>
                <input type="text" id="edit_nationality" name="nationality" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_country" class="form-label">Country</label>
                <input type="text" id="edit_country" name="country" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="edit_date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" id="edit_date_of_birth" name="date_of_birth" class="form-input" required>
            </div>
            <div class="form-actions">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Information</button>
            </div>
        </form>
    </div>
</div>

<!-- Documents Modal -->
<div id="documentsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Staff Documents</h2>
            <button class="close-btn" onclick="closeModal('documentsModal')">&times;</button>
        </div>
        <div id="documentsContent">
            <!-- Documents will be populated by JavaScript -->
        </div>
        <div class="form-actions">
            <button onclick="openAddDocumentModal()" class="btn btn-primary">Add Document</button>
            <button type="button" onclick="closeModal('documentsModal')" class="btn btn-secondary">Close</button>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div id="addDocumentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Document</h2>
            <button class="close-btn" onclick="closeModal('addDocumentModal')">&times;</button>
        </div>
        <form method="POST" action="?action=add_document" enctype="multipart/form-data">
            <input type="hidden" id="doc_staff_id" name="staff_id">
            <div class="form-group">
                <label for="doc_name" class="form-label">Document Name</label>
                <input type="text" id="doc_name" name="document_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="doc_type" class="form-label">Document Type</label>
                <select id="doc_type" name="document_type" class="form-select" required>
                    <option value="contract">Contract</option>
                    <option value="legal">Legal</option>
                    <option value="training">Training</option>
                    <option value="review">Review</option>
                    <option value="reference">Reference</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="doc_file" class="form-label">Document File</label>
                <input type="file" id="doc_file" name="document_file" class="form-input" accept=".pdf,.doc,.docx,.jpg,.png">
            </div>
            <div class="form-actions">
                <button type="button" onclick="closeModal('addDocumentModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload Document</button>
            </div>
        </form>
    </div>
</div>

<script>
function openInviteModal() {
    document.getElementById('inviteModal').style.display = 'block';
}

function openEditModal(staff) {
    document.getElementById('edit_staff_id').value = staff.id;
    document.getElementById('edit_full_name').value = staff.full_name;
    document.getElementById('edit_preferred_name').value = staff.preferred_name;
    document.getElementById('edit_discord_username').value = staff.discord_username;
    document.getElementById('edit_discord_id').value = staff.discord_id;
    document.getElementById('edit_role').value = staff.role;
    document.getElementById('edit_department').value = staff.department;
    document.getElementById('edit_manager').value = staff.manager || '';
    document.getElementById('edit_nexi_email').value = staff.nexi_email;
    document.getElementById('edit_private_email').value = staff.private_email;
    document.getElementById('edit_phone_number').value = staff.phone_number;
    document.getElementById('edit_nationality').value = staff.nationality;
    document.getElementById('edit_country').value = staff.country;
    document.getElementById('edit_date_of_birth').value = staff.date_of_birth;
    
    document.getElementById('editModal').style.display = 'block';
}

function openDocumentsModal(staff) {
    const documentsContent = document.getElementById('documentsContent');
    let html = '<div class="documents-list">';
    
    if (staff.documents && staff.documents.length > 0) {
        staff.documents.forEach(doc => {
            html += `
                <div class="document-item">
                    <div class="document-info">
                        <div class="document-name">${doc.name}</div>
                        <div class="document-meta">
                            Type: ${doc.type} | Date: ${doc.date}
                        </div>
                    </div>
                    <div class="document-status status-${doc.status}">
                        ${doc.status}
                    </div>
                    <div class="document-actions">
                        <button onclick="viewDocument('${doc.name}')" class="document-btn view">View</button>
                        <button onclick="deleteDocument('${staff.id}', '${doc.name}')" class="document-btn delete">Delete</button>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<p style="color: var(--text-secondary); text-align: center; padding: 2rem;">No documents found for this staff member.</p>';
    }
    
    html += '</div>';
    documentsContent.innerHTML = html;
    
    // Store staff ID for adding new documents
    document.getElementById('doc_staff_id').value = staff.id;
    
    document.getElementById('documentsModal').style.display = 'block';
}

function openAddDocumentModal() {
    document.getElementById('addDocumentModal').style.display = 'block';
}

function viewDocument(docName) {
    alert('Viewing document: ' + docName + '\n\nIn a real implementation, this would open or download the document.');
}

function deleteDocument(staffId, docName) {
    if (confirm('Are you sure you want to delete the document "' + docName + '"?')) {
        alert('Document deleted successfully!\n\nIn a real implementation, this would remove the document from the database.');
        // In a real app, you'd make an AJAX call to delete the document
    }
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

function openEditModal(staff) {
    document.getElementById('edit_staff_id').value = staff.id;
    document.getElementById('edit_full_name').value = staff.full_name;
    document.getElementById('edit_preferred_name').value = staff.preferred_name;
    document.getElementById('edit_discord_username').value = staff.discord_username;
    document.getElementById('edit_discord_id').value = staff.discord_id;
    document.getElementById('edit_role').value = staff.role;
    document.getElementById('edit_department').value = staff.department;
    document.getElementById('edit_manager').value = staff.manager;
    document.getElementById('edit_nexi_email').value = staff.nexi_email;
    document.getElementById('edit_private_email').value = staff.private_email;
    document.getElementById('edit_phone_number').value = staff.phone_number;
    document.getElementById('edit_nationality').value = staff.nationality;
    document.getElementById('edit_country').value = staff.country;
    document.getElementById('edit_date_of_birth').value = staff.date_of_birth.split('T')[0]; // Format date
    
    document.getElementById('editModal').style.display = 'block';
}

function openDocumentsModal(staff) {
    document.getElementById('documentsContent').innerHTML = ''; // Clear existing content
    
    // Simulate loading documents (in real app, fetch from server)
    staff.documents.forEach(doc => {
        const div = document.createElement('div');
        div.className = 'document-item';
        div.innerHTML = `
            <div class="document-info">
                <div class="document-name">${doc.name}</div>
                <div class="document-meta">
                    <span class="document-type">${doc.type}</span> | 
                    <span class="document-date">${doc.date}</span> | 
                    <span class="document-status">${doc.status}</span>
                </div>
            </div>
            <div class="document-actions">
                <button class="document-btn view">View</button>
                <button class="document-btn delete">Delete</button>
            </div>
        `;
        document.getElementById('documentsContent').appendChild(div);
    });
    
    document.getElementById('documentsModal').style.display = 'block';
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
