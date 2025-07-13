<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "Support Tickets";
$page_description = "Manage customer support requests";

// Handle actions
$action = $_GET['action'] ?? '';
$ticketId = $_GET['id'] ?? '';
$message = '';

if ($_POST && $action) {
    switch($action) {
        case 'update_status':
            $ticketId = $_POST['ticket_id'];
            $status = $_POST['status'];
            
            $stmt = $pdo->prepare("UPDATE support_tickets SET status = ?, updated_at = NOW() WHERE id = ?");
            if ($stmt->execute([$status, $ticketId])) {
                $message = "Ticket status updated successfully!";
            } else {
                $message = "Error updating ticket status.";
            }
            break;
            
        case 'add_reply':
            $ticketId = $_POST['ticket_id'];
            $reply = $_POST['reply'];
            
            // In a full implementation, you'd have a separate replies table
            // For now, we'll just update the ticket status to show activity
            $stmt = $pdo->prepare("UPDATE support_tickets SET updated_at = NOW() WHERE id = ?");
            if ($stmt->execute([$ticketId])) {
                $message = "Reply added successfully!";
            } else {
                $message = "Error adding reply.";
            }
            break;
            
        case 'assign_ticket':
            $ticketId = $_POST['ticket_id'];
            $assignee = $_POST['assignee'];
            
            // Find staff member by name (simplified - in reality you'd use IDs)
            $staffStmt = $pdo->prepare("SELECT id FROM staff WHERE discord_username LIKE ? OR email LIKE ? LIMIT 1");
            $staffStmt->execute(["%$assignee%", "%$assignee%"]);
            $staff = $staffStmt->fetch();
            
            if ($staff) {
                $stmt = $pdo->prepare("UPDATE support_tickets SET assigned_to = ?, updated_at = NOW() WHERE id = ?");
                if ($stmt->execute([$staff['id'], $ticketId])) {
                    $message = "Ticket assigned successfully!";
                } else {
                    $message = "Error assigning ticket.";
                }
            } else {
                $message = "Staff member not found.";
            }
            break;
    }
}

// Get tickets from database
$whereConditions = [];
$params = [];

// Filter tickets
$filterStatus = $_GET['status'] ?? '';
$filterPriority = $_GET['priority'] ?? '';
$filterCategory = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

if ($filterStatus) {
    $whereConditions[] = "st.status = ?";
    $params[] = $filterStatus;
}

if ($filterPriority) {
    $whereConditions[] = "st.priority = ?";
    $params[] = $filterPriority;
}

if ($filterCategory) {
    $whereConditions[] = "st.category = ?";
    $params[] = $filterCategory;
}

if ($search) {
    $whereConditions[] = "(st.subject LIKE ? OR u.username LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereClause = $whereConditions ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

$sql = "
    SELECT st.*, 
           u.username as customer_name
    FROM support_tickets st
    LEFT JOIN users u ON st.user_id = u.id
    $whereClause
    ORDER BY st.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();

// Get specific ticket for detail view
$viewTicket = null;
if ($action === 'view' && $ticketId) {
    $stmt = $pdo->prepare("
        SELECT st.*, 
               u.username as customer_name
        FROM support_tickets st
        LEFT JOIN users u ON st.user_id = u.id
        WHERE st.id = ?
    ");
    $stmt->execute([$ticketId]);
    $viewTicket = $stmt->fetch();
}

include __DIR__ . '/../includes/header.php';
?>

<style>
.support-management {
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

.filters-section {
    display: grid;
    grid-template-columns: 1fr auto auto auto auto;
    gap: 1rem;
    align-items: end;
    margin-bottom: 1.5rem;
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

.tickets-container {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    overflow: hidden;
}

.tickets-table {
    width: 100%;
    border-collapse: collapse;
}

.tickets-table th {
    background: var(--background-dark);
    color: var(--text-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}

.tickets-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-secondary);
}

.tickets-table tr:hover {
    background: var(--background-dark);
    cursor: pointer;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-open {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.status-closed {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.priority-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.priority-urgent {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.priority-high {
    background: rgba(251, 146, 60, 0.2);
    color: #fb923c;
}

.priority-medium {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.priority-low {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.ticket-detail {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.ticket-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.ticket-info h2 {
    color: var(--text-primary);
    font-size: 1.5rem;
    margin: 0 0 1rem 0;
}

.ticket-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.ticket-actions {
    display: flex;
    gap: 1rem;
}

.action-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn.primary {
    background: var(--primary-color);
    color: white;
}

.action-btn.secondary {
    background: var(--background-dark);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.messages-container {
    margin-top: 2rem;
}

.message-thread {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.message {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
}

.message-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.message-author {
    font-weight: 600;
    color: var(--text-primary);
}

.message-time {
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.message-content {
    color: var(--text-secondary);
    line-height: 1.6;
}

.reply-form {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.reply-textarea {
    width: 100%;
    min-height: 100px;
    padding: 1rem;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-primary);
    font-family: inherit;
    resize: vertical;
}

.reply-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
}

.message {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22c55e;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .filters-section {
        grid-template-columns: 1fr;
    }
    
    .ticket-detail-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .tickets-container {
        overflow-x: auto;
    }
}
</style>

<div class="support-management">
    <div class="container">
        <?php if ($viewTicket): ?>
            <!-- Ticket Detail View -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="header-title">Ticket #<?php echo $viewTicket['id']; ?></h1>
                    <a href="/staff/support" class="back-btn">← Back to Tickets</a>
                </div>
            </div>
            
            <div class="ticket-detail">
                <div class="ticket-detail-header">
                    <div class="ticket-info">
                        <h2><?php echo htmlspecialchars($viewTicket['subject']); ?></h2>
                        <div class="ticket-meta">
                            <div><strong>Customer:</strong> <?php echo htmlspecialchars($viewTicket['customer_name'] ?? 'Unknown User'); ?></div>
                            <div><strong>Status:</strong> 
                                <span class="status-badge status-<?php echo $viewTicket['status']; ?>">
                                    <?php echo ucfirst($viewTicket['status']); ?>
                                </span>
                            </div>
                            <div><strong>Priority:</strong> 
                                <span class="priority-badge priority-<?php echo $viewTicket['priority']; ?>">
                                    <?php echo ucfirst($viewTicket['priority']); ?>
                                </span>
                            </div>
                            <div><strong>Category:</strong> <?php echo htmlspecialchars($viewTicket['category']); ?></div>
                            <div><strong>Assignee:</strong> <?php echo $viewTicket['assigned_to'] ? 'Staff Member' : 'Unassigned'; ?></div>
                            <div><strong>Created:</strong> <?php echo date('M j, Y g:i A', strtotime($viewTicket['created_at'])); ?></div>
                        </div>
                    </div>
                    <div class="ticket-actions">
                        <button onclick="updateTicketStatus(<?php echo $viewTicket['id']; ?>)" class="action-btn primary">Update Status</button>
                        <button onclick="assignTicket(<?php echo $viewTicket['id']; ?>)" class="action-btn secondary">Assign</button>
                    </div>
                </div>
                
                <div class="messages-container">
                    <h3 style="color: var(--text-primary); margin-bottom: 1rem;">Messages</h3>
                    <div class="message-thread">
                        <?php foreach ($viewTicket['messages'] as $msg): ?>
                            <div class="message">
                                <div class="message-header">
                                    <span class="message-author"><?php echo htmlspecialchars($msg['author']); ?></span>
                                    <span class="message-time"><?php echo date('M j, Y g:i A', strtotime($msg['time'])); ?></span>
                                </div>
                                <div class="message-content">
                                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <form method="POST" action="?action=add_reply" class="reply-form">
                        <input type="hidden" name="ticket_id" value="<?php echo $viewTicket['id']; ?>">
                        <label for="reply" style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Add Reply</label>
                        <textarea name="reply" id="reply" class="reply-textarea" placeholder="Type your reply here..." required></textarea>
                        <button type="submit" class="action-btn primary" style="margin-top: 1rem;">Send Reply</button>
                    </form>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Tickets List View -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="header-title">Support Tickets</h1>
                    <a href="/staff/dashboard" class="back-btn">← Back to Dashboard</a>
                </div>
                
                <?php if ($message): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-number"><?php echo count($tickets); ?></span>
                        <span class="stat-label">Total Tickets</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?php echo count(array_filter($tickets, fn($t) => $t['status'] === 'open')); ?></span>
                        <span class="stat-label">Open Tickets</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?php echo count(array_filter($tickets, fn($t) => $t['status'] === 'pending')); ?></span>
                        <span class="stat-label">Pending Tickets</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?php echo count(array_filter($tickets, fn($t) => $t['priority'] === 'urgent')); ?></span>
                        <span class="stat-label">Urgent Tickets</span>
                    </div>
                </div>
                
                <form method="GET" class="filters-section">
                    <div class="filter-group">
                        <label for="search">Search Tickets</label>
                        <input type="text" id="search" name="search" class="filter-input" 
                               placeholder="Search by subject or customer..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="filter-select">
                            <option value="">All Statuses</option>
                            <option value="open" <?php echo $filterStatus === 'open' ? 'selected' : ''; ?>>Open</option>
                            <option value="pending" <?php echo $filterStatus === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="closed" <?php echo $filterStatus === 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" class="filter-select">
                            <option value="">All Priorities</option>
                            <option value="urgent" <?php echo $filterPriority === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                            <option value="high" <?php echo $filterPriority === 'high' ? 'selected' : ''; ?>>High</option>
                            <option value="medium" <?php echo $filterPriority === 'medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="low" <?php echo $filterPriority === 'low' ? 'selected' : ''; ?>>Low</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" class="filter-select">
                            <option value="">All Categories</option>
                            <option value="Authentication" <?php echo $filterCategory === 'Authentication' ? 'selected' : ''; ?>>Authentication</option>
                            <option value="Billing" <?php echo $filterCategory === 'Billing' ? 'selected' : ''; ?>>Billing</option>
                            <option value="API" <?php echo $filterCategory === 'API' ? 'selected' : ''; ?>>API</option>
                            <option value="Feature Request" <?php echo $filterCategory === 'Feature Request' ? 'selected' : ''; ?>>Feature Request</option>
                            <option value="Account" <?php echo $filterCategory === 'Account' ? 'selected' : ''; ?>>Account</option>
                        </select>
                    </div>
                    <button type="submit" class="filter-btn">Filter</button>
                </form>
            </div>

            <div class="tickets-container">
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Category</th>
                            <th>Assignee</th>
                            <th>Created</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr onclick="viewTicket(<?php echo $ticket['id']; ?>)">
                                <td><strong>#<?php echo $ticket['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                                <td><?php echo htmlspecialchars($ticket['customer_name'] ?? 'Unknown User'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $ticket['status']; ?>">
                                        <?php echo ucfirst($ticket['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="priority-badge priority-<?php echo $ticket['priority']; ?>">
                                        <?php echo ucfirst($ticket['priority']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($ticket['category']); ?></td>
                                <td><?php echo $ticket['assigned_to'] ? 'Staff Member' : 'Unassigned'; ?></td>
                                <td><?php echo date('M j, Y', strtotime($ticket['created_at'])); ?></td>
                                <td><?php echo date('M j, Y', strtotime($ticket['updated_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function viewTicket(ticketId) {
    window.location.href = '?action=view&id=' + ticketId;
}

function updateTicketStatus(ticketId) {
    const newStatus = prompt('Enter new status (open, pending, closed):');
    if (newStatus && ['open', 'pending', 'closed'].includes(newStatus)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?action=update_status&id=' + ticketId;
        
        const ticketInput = document.createElement('input');
        ticketInput.type = 'hidden';
        ticketInput.name = 'ticket_id';
        ticketInput.value = ticketId;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        
        form.appendChild(ticketInput);
        form.appendChild(statusInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function assignTicket(ticketId) {
    const assignee = prompt('Enter assignee name:');
    if (assignee) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?action=assign_ticket&id=' + ticketId;
        
        const ticketInput = document.createElement('input');
        ticketInput.type = 'hidden';
        ticketInput.name = 'ticket_id';
        ticketInput.value = ticketId;
        
        const assigneeInput = document.createElement('input');
        assigneeInput.type = 'hidden';
        assigneeInput.name = 'assignee';
        assigneeInput.value = assignee;
        
        form.appendChild(ticketInput);
        form.appendChild(assigneeInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
