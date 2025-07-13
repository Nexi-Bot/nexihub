<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "System Health";
$page_description = "Monitor system status and performance";

// Simulate system metrics (in a real app, this would come from actual monitoring)
$systemMetrics = [
    'uptime' => 99.87,
    'avg_response_time' => 127,
    'cpu_usage' => 23.5,
    'memory_usage' => 68.2,
    'disk_usage' => 45.8,
    'active_users' => 1247,
    'requests_per_minute' => 15429,
    'error_rate' => 0.12
];

$services = [
    [
        'name' => 'Main Website',
        'url' => 'https://nexihub.uk',
        'status' => 'operational',
        'response_time' => 145,
        'uptime' => 99.9,
        'last_check' => '2024-01-15 16:30:00'
    ],
    [
        'name' => 'API Gateway',
        'url' => 'https://api.nexihub.uk',
        'status' => 'operational',
        'response_time' => 89,
        'uptime' => 99.8,
        'last_check' => '2024-01-15 16:30:00'
    ],
    [
        'name' => 'Staff Portal',
        'url' => 'https://staff.nexihub.uk',
        'status' => 'operational',
        'response_time' => 156,
        'uptime' => 99.7,
        'last_check' => '2024-01-15 16:30:00'
    ],
    [
        'name' => 'Database',
        'url' => 'Internal',
        'status' => 'operational',
        'response_time' => 12,
        'uptime' => 99.95,
        'last_check' => '2024-01-15 16:30:00'
    ],
    [
        'name' => 'CDN',
        'url' => 'https://cdn.nexihub.uk',
        'status' => 'degraded',
        'response_time' => 245,
        'uptime' => 98.5,
        'last_check' => '2024-01-15 16:30:00'
    ],
    [
        'name' => 'Email Service',
        'url' => 'SMTP Server',
        'status' => 'operational',
        'response_time' => 234,
        'uptime' => 99.6,
        'last_check' => '2024-01-15 16:30:00'
    ]
];

$recentLogs = [
    ['timestamp' => '2024-01-15 16:25:32', 'level' => 'info', 'service' => 'API', 'message' => 'Database connection pool optimized'],
    ['timestamp' => '2024-01-15 16:20:15', 'level' => 'warning', 'service' => 'CDN', 'message' => 'High response times detected on edge servers'],
    ['timestamp' => '2024-01-15 16:15:45', 'level' => 'info', 'service' => 'Website', 'message' => 'Cache cleared successfully'],
    ['timestamp' => '2024-01-15 16:10:22', 'level' => 'error', 'service' => 'Email', 'message' => 'Failed to send notification email (retry successful)'],
    ['timestamp' => '2024-01-15 16:05:18', 'level' => 'info', 'service' => 'Database', 'message' => 'Backup completed successfully'],
    ['timestamp' => '2024-01-15 16:00:12', 'level' => 'info', 'service' => 'API', 'message' => 'Rate limiting updated for premium users'],
];

$alerts = [
    ['severity' => 'warning', 'service' => 'CDN', 'message' => 'Response times above normal threshold', 'time' => '2024-01-15 16:20:00'],
    ['severity' => 'info', 'service' => 'System', 'message' => 'Scheduled maintenance in 2 hours', 'time' => '2024-01-15 15:30:00'],
];

include __DIR__ . '/../includes/header.php';
?>

<style>
.system-health {
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

.status-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.status-operational {
    background: #22c55e;
}

.status-degraded {
    background: #fbbf24;
}

.status-down {
    background: #ef4444;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.metric-value {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.metric-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.metric-change {
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.metric-up {
    color: #22c55e;
}

.metric-down {
    color: #ef4444;
}

.section-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.section-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.section-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.section-title {
    color: var(--text-primary);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 1.5rem 0;
}

.services-grid {
    display: grid;
    gap: 1rem;
}

.service-item {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.service-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.service-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.service-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.1rem;
}

.service-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.service-operational {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.service-degraded {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.service-down {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.service-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    font-size: 0.9rem;
}

.service-metric {
    text-align: center;
    color: var(--text-secondary);
}

.service-metric-value {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.alerts-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.alert-item {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    border-left: 4px solid;
}

.alert-warning {
    border-left-color: #fbbf24;
}

.alert-error {
    border-left-color: #ef4444;
}

.alert-info {
    border-left-color: #3b82f6;
}

.alert-message {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.alert-details {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.logs-container {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.logs-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.logs-table {
    width: 100%;
    border-collapse: collapse;
}

.logs-table th {
    background: var(--background-dark);
    color: var(--text-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}

.logs-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-secondary);
}

.log-level {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.log-info {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.log-warning {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.log-error {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.refresh-btn {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    padding: 1rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.refresh-btn:hover {
    background: var(--secondary-color);
    transform: scale(1.1);
}

@media (max-width: 768px) {
    .section-grid {
        grid-template-columns: 1fr;
    }
    
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .service-metrics {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="system-health">
    <div class="container">
        <div class="page-header">
            <div class="header-content">
                <h1 class="header-title">System Health</h1>
                <div class="status-indicator">
                    <span class="status-dot status-operational"></span>
                    <span style="color: var(--text-primary);">All Systems Operational</span>
                </div>
                <a href="/staff/dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
        </div>

        <!-- System Metrics -->
        <div class="metrics-grid">
            <div class="metric-card">
                <span class="metric-value"><?php echo $systemMetrics['uptime']; ?>%</span>
                <span class="metric-label">Uptime (30 days)</span>
                <div class="metric-change metric-up">+0.02% vs last month</div>
            </div>
            <div class="metric-card">
                <span class="metric-value"><?php echo $systemMetrics['avg_response_time']; ?>ms</span>
                <span class="metric-label">Avg Response Time</span>
                <div class="metric-change metric-down">-15ms vs last hour</div>
            </div>
            <div class="metric-card">
                <span class="metric-value"><?php echo $systemMetrics['cpu_usage']; ?>%</span>
                <span class="metric-label">CPU Usage</span>
                <div class="metric-change metric-up">+2.1% vs last hour</div>
            </div>
            <div class="metric-card">
                <span class="metric-value"><?php echo $systemMetrics['memory_usage']; ?>%</span>
                <span class="metric-label">Memory Usage</span>
                <div class="metric-change metric-up">+1.5% vs last hour</div>
            </div>
            <div class="metric-card">
                <span class="metric-value"><?php echo number_format($systemMetrics['active_users']); ?></span>
                <span class="metric-label">Active Users</span>
                <div class="metric-change metric-up">+127 vs last hour</div>
            </div>
            <div class="metric-card">
                <span class="metric-value"><?php echo number_format($systemMetrics['requests_per_minute']); ?></span>
                <span class="metric-label">Requests/Min</span>
                <div class="metric-change metric-up">+245 vs last hour</div>
            </div>
        </div>

        <!-- Services and Alerts -->
        <div class="section-grid">
            <div class="section-card">
                <h2 class="section-title">Service Status</h2>
                <div class="services-grid">
                    <?php foreach ($services as $service): ?>
                        <div class="service-item">
                            <div class="service-header">
                                <span class="service-name"><?php echo htmlspecialchars($service['name']); ?></span>
                                <span class="service-status service-<?php echo $service['status']; ?>">
                                    <?php echo ucfirst($service['status']); ?>
                                </span>
                            </div>
                            <div class="service-metrics">
                                <div class="service-metric">
                                    <span class="service-metric-value"><?php echo $service['response_time']; ?>ms</span>
                                    <span>Response Time</span>
                                </div>
                                <div class="service-metric">
                                    <span class="service-metric-value"><?php echo $service['uptime']; ?>%</span>
                                    <span>Uptime</span>
                                </div>
                                <div class="service-metric">
                                    <span class="service-metric-value"><?php echo date('H:i', strtotime($service['last_check'])); ?></span>
                                    <span>Last Check</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="section-card">
                <h2 class="section-title">Active Alerts</h2>
                <div class="alerts-list">
                    <?php foreach ($alerts as $alert): ?>
                        <div class="alert-item alert-<?php echo $alert['severity']; ?>">
                            <div class="alert-message"><?php echo htmlspecialchars($alert['message']); ?></div>
                            <div class="alert-details">
                                <strong><?php echo htmlspecialchars($alert['service']); ?></strong> • 
                                <?php echo date('M j, Y g:i A', strtotime($alert['time'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="logs-container">
            <h2 class="section-title">Recent System Logs</h2>
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Level</th>
                        <th>Service</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td><?php echo date('M j, Y H:i:s', strtotime($log['timestamp'])); ?></td>
                            <td>
                                <span class="log-level log-<?php echo $log['level']; ?>">
                                    <?php echo ucfirst($log['level']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($log['service']); ?></td>
                            <td><?php echo htmlspecialchars($log['message']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button class="refresh-btn" onclick="refreshData()" title="Refresh Data">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
                <path d="M17.65,6.35C16.2,4.9 14.21,4 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20C15.73,20 18.84,17.45 19.73,14H17.65C16.83,16.33 14.61,18 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6C13.66,6 15.14,6.69 16.22,7.78L13,11H20V4L17.65,6.35Z"/>
            </svg>
        </button>
    </div>
</div>

<script>
function refreshData() {
    // Add spinning animation
    const btn = document.querySelector('.refresh-btn svg');
    btn.style.animation = 'spin 1s linear infinite';
    
    // Simulate refresh (in a real app, this would make an AJAX call)
    setTimeout(() => {
        btn.style.animation = '';
        location.reload();
    }, 1000);
}

// Auto-refresh every 30 seconds
setInterval(() => {
    refreshData();
}, 30000);

// Add CSS for spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
