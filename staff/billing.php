<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "Billing & Payments";
$page_description = "Monitor transactions and manage subscriptions";

// Handle actions
$action = $_GET['action'] ?? '';
$message = '';

if ($_POST && $action) {
    switch($action) {
        case 'refund_payment':
            $paymentId = $_POST['payment_id'];
            // In a real app, you'd process the refund via Stripe
            $message = "Refund processed successfully!";
            break;
            
        case 'cancel_subscription':
            $subscriptionId = $_POST['subscription_id'];
            // In a real app, you'd cancel the subscription via Stripe
            $message = "Subscription cancelled successfully!";
            break;
    }
}

// Sample billing data (in a real app, this would come from Stripe)
$transactions = [
    ['id' => 'pi_1234567890', 'customer' => 'john@example.com', 'amount' => 999, 'currency' => 'GBP', 'status' => 'succeeded', 'date' => '2024-01-15 14:30:00', 'description' => 'Premium Subscription'],
    ['id' => 'pi_0987654321', 'customer' => 'sarah@example.com', 'amount' => 1999, 'currency' => 'GBP', 'status' => 'succeeded', 'date' => '2024-01-14 09:15:00', 'description' => 'Business Subscription'],
    ['id' => 'pi_1122334455', 'customer' => 'mike@example.com', 'amount' => 999, 'currency' => 'GBP', 'status' => 'failed', 'date' => '2024-01-13 16:45:00', 'description' => 'Premium Subscription'],
    ['id' => 'pi_5544332211', 'customer' => 'emma@example.com', 'amount' => 1999, 'currency' => 'GBP', 'status' => 'refunded', 'date' => '2024-01-12 11:20:00', 'description' => 'Business Subscription'],
    ['id' => 'pi_9988776655', 'customer' => 'alex@example.com', 'amount' => 999, 'currency' => 'GBP', 'status' => 'succeeded', 'date' => '2024-01-11 19:30:00', 'description' => 'Premium Subscription'],
];

$subscriptions = [
    ['id' => 'sub_1234567890', 'customer' => 'john@example.com', 'plan' => 'Premium Monthly', 'amount' => 999, 'status' => 'active', 'next_billing' => '2024-02-15', 'created' => '2024-01-15'],
    ['id' => 'sub_0987654321', 'customer' => 'sarah@example.com', 'plan' => 'Business Monthly', 'amount' => 1999, 'status' => 'active', 'next_billing' => '2024-02-14', 'created' => '2023-11-22'],
    ['id' => 'sub_1122334455', 'customer' => 'emma@example.com', 'plan' => 'Business Annual', 'amount' => 19999, 'status' => 'cancelled', 'next_billing' => null, 'created' => '2024-01-01'],
    ['id' => 'sub_5544332211', 'customer' => 'alex@example.com', 'plan' => 'Premium Annual', 'amount' => 9999, 'status' => 'past_due', 'next_billing' => '2024-01-20', 'created' => '2023-12-10'],
];

// Filter transactions
$filterStatus = $_GET['status'] ?? '';
$filterCustomer = $_GET['customer'] ?? '';

if ($filterStatus || $filterCustomer) {
    $transactions = array_filter($transactions, function($transaction) use ($filterStatus, $filterCustomer) {
        $matchesStatus = empty($filterStatus) || $transaction['status'] === $filterStatus;
        $matchesCustomer = empty($filterCustomer) || stripos($transaction['customer'], $filterCustomer) !== false;
        
        return $matchesStatus && $matchesCustomer;
    });
}

include __DIR__ . '/../includes/header.php';
?>

<style>
.billing-management {
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
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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

.tabs-container {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
}

.tabs-header {
    display: flex;
    background: var(--background-dark);
}

.tab-button {
    flex: 1;
    padding: 1rem 2rem;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
}

.tab-button.active {
    color: var(--text-primary);
    border-bottom-color: var(--primary-color);
    background: var(--background-light);
}

.tab-content {
    padding: 2rem;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

.filters-section {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
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

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: var(--background-dark);
    color: var(--text-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-secondary);
}

.data-table tr:hover {
    background: var(--background-dark);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-succeeded {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-failed {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.status-refunded {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.status-active {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-cancelled {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.status-past_due {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
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

.action-btn.refund {
    background: rgba(239, 68, 68, 0.9);
    color: white;
}

.action-btn.cancel {
    background: rgba(239, 68, 68, 0.9);
    color: white;
}

.action-btn.view {
    background: var(--primary-color);
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.amount {
    font-weight: 600;
    color: var(--text-primary);
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
    
    .tabs-header {
        flex-direction: column;
    }
    
    .data-table-container {
        overflow-x: auto;
    }
}
</style>

<div class="billing-management">
    <div class="container">
        <div class="page-header">
            <div class="header-content">
                <h1 class="header-title">Billing & Payments</h1>
                <a href="/staff/dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            
            <?php if ($message): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">£<?php echo number_format(array_sum(array_column(array_filter($transactions, fn($t) => $t['status'] === 'succeeded'), 'amount')) / 100, 2); ?></span>
                    <span class="stat-label">Total Revenue</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($transactions, fn($t) => $t['status'] === 'succeeded')); ?></span>
                    <span class="stat-label">Successful Payments</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($subscriptions, fn($s) => $s['status'] === 'active')); ?></span>
                    <span class="stat-label">Active Subscriptions</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo count(array_filter($transactions, fn($t) => $t['status'] === 'failed')); ?></span>
                    <span class="stat-label">Failed Payments</span>
                </div>
            </div>
        </div>

        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-button active" onclick="switchTab('transactions')">Transactions</button>
                <button class="tab-button" onclick="switchTab('subscriptions')">Subscriptions</button>
            </div>
            
            <div class="tab-content">
                <!-- Transactions Tab -->
                <div id="transactions-tab" class="tab-pane active">
                    <form method="GET" class="filters-section">
                        <input type="hidden" name="tab" value="transactions">
                        <div class="filter-group">
                            <label for="customer">Customer Email</label>
                            <input type="text" id="customer" name="customer" class="filter-input" 
                                   placeholder="Search by email..." value="<?php echo htmlspecialchars($filterCustomer); ?>">
                        </div>
                        <div class="filter-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="filter-select">
                                <option value="">All Statuses</option>
                                <option value="succeeded" <?php echo $filterStatus === 'succeeded' ? 'selected' : ''; ?>>Succeeded</option>
                                <option value="failed" <?php echo $filterStatus === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                <option value="refunded" <?php echo $filterStatus === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                            </select>
                        </div>
                        <button type="submit" class="filter-btn">Filter</button>
                    </form>
                    
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><code><?php echo htmlspecialchars($transaction['id']); ?></code></td>
                                        <td><?php echo htmlspecialchars($transaction['customer']); ?></td>
                                        <td class="amount">£<?php echo number_format($transaction['amount'] / 100, 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $transaction['status']; ?>">
                                                <?php echo ucfirst($transaction['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($transaction['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="#" class="action-btn view">View</a>
                                                <?php if ($transaction['status'] === 'succeeded'): ?>
                                                    <button onclick="refundPayment('<?php echo $transaction['id']; ?>')" class="action-btn refund">Refund</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Subscriptions Tab -->
                <div id="subscriptions-tab" class="tab-pane">
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Subscription ID</th>
                                    <th>Customer</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Next Billing</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subscriptions as $subscription): ?>
                                    <tr>
                                        <td><code><?php echo htmlspecialchars($subscription['id']); ?></code></td>
                                        <td><?php echo htmlspecialchars($subscription['customer']); ?></td>
                                        <td><?php echo htmlspecialchars($subscription['plan']); ?></td>
                                        <td class="amount">£<?php echo number_format($subscription['amount'] / 100, 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $subscription['status']; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $subscription['status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $subscription['next_billing'] ? date('M j, Y', strtotime($subscription['next_billing'])) : 'N/A'; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($subscription['created'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="#" class="action-btn view">View</a>
                                                <?php if ($subscription['status'] === 'active'): ?>
                                                    <button onclick="cancelSubscription('<?php echo $subscription['id']; ?>')" class="action-btn cancel">Cancel</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab pane
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

function refundPayment(paymentId) {
    if (confirm('Are you sure you want to refund this payment?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?action=refund_payment';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'payment_id';
        input.value = paymentId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelSubscription(subscriptionId) {
    if (confirm('Are you sure you want to cancel this subscription?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '?action=cancel_subscription';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'subscription_id';
        input.value = subscriptionId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
