<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "User Management";
$page_description = "Manage customer accounts and subscriptions";

// Handle actions
$action = $_GET['action'] ?? '';
$userId = $_GET['id'] ?? '';
$message = '';

if ($_POST && $action) {
    switch($action) {
        case 'update_user':
            $userId = $_POST['user_id'];
            $email = $_POST['email'];
            $subscription = $_POST['subscription'];
            $status = $_POST['status'];
            
            // In a real app, you'd update the user in the database
            $message = "User updated successfully!";
            break;
            
        case 'suspend_user':
            // In a real app, you'd suspend the user
            $message = "User suspended successfully!";
            break;
            
        case 'delete_user':
            // In a real app, you'd delete the user
            $message = "User deleted successfully!";
            break;
    }
}

// Sample user data (in a real app, this would come from your database)
$users = [
    ['id' => 1, 'email' => 'john@example.com', 'username' => 'john_doe', 'subscription' => 'Premium', 'status' => 'Active', 'joined' => '2023-10-15', 'last_login' => '2024-01-15 14:30:00'],
    ['id' => 2, 'email' => 'sarah@example.com', 'username' => 'sarah_smith', 'subscription' => 'Free', 'status' => 'Active', 'joined' => '2023-11-22', 'last_login' => '2024-01-14 09:15:00'],
    ['id' => 3, 'email' => 'mike@example.com', 'username' => 'mike_jones', 'subscription' => 'Premium', 'status' => 'Suspended', 'joined' => '2023-09-03', 'last_login' => '2024-01-10 16:45:00'],
    ['id' => 4, 'email' => 'emma@example.com', 'username' => 'emma_wilson', 'subscription' => 'Business', 'status' => 'Active', 'joined' => '2024-01-01', 'last_login' => '2024-01-15 11:20:00'],
    ['id' => 5, 'email' => 'alex@example.com', 'username' => 'alex_brown', 'subscription' => 'Free', 'status' => 'Active', 'joined' => '2023-12-10', 'last_login' => '2024-01-13 19:30:00'],
];

// Filter and search
$search = $_GET['search'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$filterSubscription = $_GET['subscription'] ?? '';

if ($search || $filterStatus || $filterSubscription) {
    $users = array_filter($users, function($user) use ($search, $filterStatus, $filterSubscription) {
        $matchesSearch = empty($search) || 
            stripos($user['email'], $search) !== false || 
            stripos($user['username'], $search) !== false;
        
        $matchesStatus = empty($filterStatus) || $user['status'] === $filterStatus;
        $matchesSubscription = empty($filterSubscription) || $user['subscription'] === $filterSubscription;
        
        return $matchesSearch && $matchesStatus && $matchesSubscription;
    });
}

include __DIR__ . '/../includes/header.php';
?>

<style>
.user-management {
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

.filters-section {
    display: grid;
    grid-template-columns: 1fr auto auto auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 600;
}

.filter-input, .filter-select {
    padding: 0.75rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
}

.filter-btn {
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.users-table-container {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    margin-top: 2rem;
    overflow: hidden;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background: var(--background-dark);
    color: var(--text-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}

.users-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-secondary);
}

.users-table tr:hover {
    background: var(--background-dark);
}

.status-badge {
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

.status-suspended {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.subscription-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.subscription-free {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.subscription-premium {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.subscription-business {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
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

.action-btn.suspend {
    background: rgba(239, 68, 68, 0.9);
    color: white;
}

.action-btn.delete {
    background: rgba(239, 68, 68, 0.9);
    color: white;
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
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

@media (max-width: 768px) {
    .filters-section {
        grid-template-columns: 1fr;
    }
    
    .users-table-container {
        overflow-x: auto;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<div class="user-management">
    <div class="container">
        <div class="page-header">
            <div class="header-content">
                <h1 class="header-title">User Management</h1>
                <a href="/staff/dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            
            <?php if ($message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number"><?php echo count($users); ?></span>
                    <span class="stat-label">Total Users</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($users, fn($u) => $u['status'] === 'Active')); ?></span>
                    <span class="stat-label">Active Users</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($users, fn($u) => $u['subscription'] === 'Premium')); ?></span>
                    <span class="stat-label">Premium Users</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($users, fn($u) => $u['subscription'] === 'Business')); ?></span>
                    <span class="stat-label">Business Users</span>
                </div>
            </div>
            
            <form method="GET" class="filters-section">
                <div class="filter-group">
                    <label for="search">Search Users</label>
                    <input type="text" id="search" name="search" class="filter-input" 
                           placeholder="Search by email or username..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        <option value="Active" <?php echo $filterStatus === 'Active' ? 'selected' : ''; ?>>Active</option>
                        <option value="Suspended" <?php echo $filterStatus === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="subscription">Subscription</label>
                    <select id="subscription" name="subscription" class="filter-select">
                        <option value="">All Subscriptions</option>
                        <option value="Free" <?php echo $filterSubscription === 'Free' ? 'selected' : ''; ?>>Free</option>
                        <option value="Premium" <?php echo $filterSubscription === 'Premium' ? 'selected' : ''; ?>>Premium</option>
                        <option value="Business" <?php echo $filterSubscription === 'Business' ? 'selected' : ''; ?>>Business</option>
                    </select>
                </div>
                <button type="submit" class="filter-btn">Filter</button>
            </form>
        </div>

        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Subscription</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($user['username']); ?></strong><br>
                                <small>ID: <?php echo $user['id']; ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="subscription-badge subscription-<?php echo strtolower($user['subscription']); ?>">
                                    <?php echo $user['subscription']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($user['status']); ?>">
                                    <?php echo $user['status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['joined'])); ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($user['last_login'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?action=edit&id=<?php echo $user['id']; ?>" class="action-btn edit">Edit</a>
                                    <?php if ($user['status'] === 'Active'): ?>
                                        <button onclick="suspendUser(<?php echo $user['id']; ?>)" class="action-btn suspend">Suspend</button>
                                    <?php endif; ?>
                                    <button onclick="deleteUser(<?php echo $user['id']; ?>)" class="action-btn delete">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function suspendUser(userId) {
    if (confirm('Are you sure you want to suspend this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?action=suspend_user';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_id';
        input.value = userId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?action=delete_user';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_id';
        input.value = userId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
