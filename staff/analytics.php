<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "Analytics";
$page_description = "Platform usage statistics and insights";

// Sample analytics data (in a real app, this would come from your analytics system)
$timeRange = $_GET['range'] ?? '7d';

$analyticsData = [
    'overview' => [
        'total_users' => 1247,
        'active_users' => 892,
        'page_views' => 48329,
        'sessions' => 12847,
        'bounce_rate' => 24.5,
        'avg_session_duration' => 345, // seconds
        'conversion_rate' => 3.2,
        'revenue' => 24750 // pence
    ],
    'traffic_sources' => [
        ['source' => 'Direct', 'visitors' => 4521, 'percentage' => 35.2],
        ['source' => 'Google Search', 'visitors' => 3247, 'percentage' => 25.3],
        ['source' => 'Social Media', 'visitors' => 2103, 'percentage' => 16.4],
        ['source' => 'Referrals', 'visitors' => 1845, 'percentage' => 14.4],
        ['source' => 'Email', 'visitors' => 1131, 'percentage' => 8.8],
    ],
    'popular_pages' => [
        ['page' => '/dashboard', 'views' => 8521, 'unique_views' => 3247],
        ['page' => '/api/docs', 'views' => 6234, 'unique_views' => 2891],
        ['page' => '/pricing', 'views' => 4567, 'unique_views' => 3421],
        ['page' => '/features', 'views' => 3892, 'unique_views' => 2156],
        ['page' => '/contact', 'views' => 2145, 'unique_views' => 1834],
    ],
    'devices' => [
        ['device' => 'Desktop', 'sessions' => 7821, 'percentage' => 60.8],
        ['device' => 'Mobile', 'sessions' => 3947, 'percentage' => 30.7],
        ['device' => 'Tablet', 'sessions' => 1079, 'percentage' => 8.4],
    ],
    'browsers' => [
        ['browser' => 'Chrome', 'sessions' => 8934, 'percentage' => 69.5],
        ['browser' => 'Safari', 'sessions' => 2156, 'percentage' => 16.8],
        ['browser' => 'Firefox', 'sessions' => 1234, 'percentage' => 9.6],
        ['browser' => 'Edge', 'sessions' => 523, 'percentage' => 4.1],
    ],
    'geographical' => [
        ['country' => 'United Kingdom', 'users' => 8934, 'percentage' => 69.5],
        ['country' => 'United States', 'users' => 2156, 'percentage' => 16.8],
        ['country' => 'Germany', 'users' => 834, 'percentage' => 6.5],
        ['country' => 'France', 'users' => 523, 'percentage' => 4.1],
        ['country' => 'Canada', 'users' => 400, 'percentage' => 3.1],
    ],
    'daily_stats' => [
        ['date' => '2024-01-09', 'users' => 156, 'sessions' => 234, 'page_views' => 1205],
        ['date' => '2024-01-10', 'users' => 189, 'sessions' => 278, 'page_views' => 1434],
        ['date' => '2024-01-11', 'users' => 234, 'sessions' => 345, 'page_views' => 1789],
        ['date' => '2024-01-12', 'users' => 198, 'sessions' => 289, 'page_views' => 1567],
        ['date' => '2024-01-13', 'users' => 267, 'sessions' => 398, 'page_views' => 2103],
        ['date' => '2024-01-14', 'users' => 223, 'sessions' => 334, 'page_views' => 1876],
        ['date' => '2024-01-15', 'users' => 245, 'sessions' => 367, 'page_views' => 1945],
    ]
];

include __DIR__ . '/../includes/header.php';
?>

<style>
.analytics-dashboard {
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

.header-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.time-range-selector {
    display: flex;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    overflow: hidden;
}

.time-range-btn {
    padding: 0.5rem 1rem;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.time-range-btn.active {
    background: var(--primary-color);
    color: white;
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

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.overview-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.overview-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.overview-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.overview-value {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.overview-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.overview-change {
    font-size: 0.8rem;
    margin-top: 0.5rem;
    font-weight: 600;
}

.change-positive {
    color: #22c55e;
}

.change-negative {
    color: #ef4444;
}

.analytics-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.analytics-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.analytics-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.card-title {
    color: var(--text-primary);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 1.5rem 0;
}

.chart-container {
    height: 300px;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-style: italic;
    position: relative;
}

.chart-placeholder {
    text-align: center;
}

.data-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.data-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 1rem;
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
}

.data-item-info {
    flex: 1;
}

.data-item-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.data-item-value {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.data-item-percentage {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1.1rem;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--border-color);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 3px;
    transition: width 0.3s ease;
}

.full-width-card {
    grid-column: 1 / -1;
}

.table-container {
    overflow-x: auto;
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

.export-btn {
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: white;
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

.export-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(230, 79, 33, 0.3);
}

@media (max-width: 768px) {
    .analytics-grid {
        grid-template-columns: 1fr;
    }
    
    .overview-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .header-controls {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .time-range-selector {
        width: 100%;
    }
    
    .time-range-btn {
        flex: 1;
    }
}
</style>

<div class="analytics-dashboard">
    <div class="container">
        <div class="page-header">
            <div class="header-content">
                <h1 class="header-title">Analytics Dashboard</h1>
                <div class="header-controls">
                    <div class="time-range-selector">
                        <button class="time-range-btn <?php echo $timeRange === '24h' ? 'active' : ''; ?>" onclick="changeTimeRange('24h')">24h</button>
                        <button class="time-range-btn <?php echo $timeRange === '7d' ? 'active' : ''; ?>" onclick="changeTimeRange('7d')">7d</button>
                        <button class="time-range-btn <?php echo $timeRange === '30d' ? 'active' : ''; ?>" onclick="changeTimeRange('30d')">30d</button>
                        <button class="time-range-btn <?php echo $timeRange === '90d' ? 'active' : ''; ?>" onclick="changeTimeRange('90d')">90d</button>
                    </div>
                    <a href="/staff/dashboard" class="back-btn">← Dashboard</a>
                </div>
            </div>
        </div>

        <!-- Overview Metrics -->
        <div class="overview-grid">
            <div class="overview-card">
                <span class="overview-value"><?php echo number_format($analyticsData['overview']['total_users']); ?></span>
                <span class="overview-label">Total Users</span>
                <div class="overview-change change-positive">+12.5% vs last period</div>
            </div>
            <div class="overview-card">
                <span class="overview-value"><?php echo number_format($analyticsData['overview']['active_users']); ?></span>
                <span class="overview-label">Active Users</span>
                <div class="overview-change change-positive">+8.3% vs last period</div>
            </div>
            <div class="overview-card">
                <span class="overview-value"><?php echo number_format($analyticsData['overview']['page_views']); ?></span>
                <span class="overview-label">Page Views</span>
                <div class="overview-change change-positive">+15.7% vs last period</div>
            </div>
            <div class="overview-card">
                <span class="overview-value"><?php echo number_format($analyticsData['overview']['sessions']); ?></span>
                <span class="overview-label">Sessions</span>
                <div class="overview-change change-positive">+9.2% vs last period</div>
            </div>
            <div class="overview-card">
                <span class="overview-value"><?php echo $analyticsData['overview']['bounce_rate']; ?>%</span>
                <span class="overview-label">Bounce Rate</span>
                <div class="overview-change change-negative">-2.1% vs last period</div>
            </div>
            <div class="overview-card">
                <span class="overview-value"><?php echo gmdate('i:s', $analyticsData['overview']['avg_session_duration']); ?></span>
                <span class="overview-label">Avg Session</span>
                <div class="overview-change change-positive">+0:45 vs last period</div>
            </div>
            <div class="overview-card">
                <span class="overview-value"><?php echo $analyticsData['overview']['conversion_rate']; ?>%</span>
                <span class="overview-label">Conversion Rate</span>
                <div class="overview-change change-positive">+0.8% vs last period</div>
            </div>
            <div class="overview-card">
                <span class="overview-value">£<?php echo number_format($analyticsData['overview']['revenue'] / 100, 2); ?></span>
                <span class="overview-label">Revenue</span>
                <div class="overview-change change-positive">+18.4% vs last period</div>
            </div>
        </div>

        <!-- Charts and Lists -->
        <div class="analytics-grid">
            <!-- Traffic Trend Chart -->
            <div class="analytics-card">
                <h2 class="card-title">Traffic Trend</h2>
                <div class="chart-container">
                    <div class="chart-placeholder">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width: 48px; height: 48px; opacity: 0.5;">
                            <path d="M16,6L18.29,8.29L13.41,13.17L9.41,9.17L2,16.59L3.41,18L9.41,12L13.41,16L19.71,9.71L22,12V6H16Z"/>
                        </svg>
                        <p>Interactive chart would appear here<br>
                        <small>(Integration with Chart.js or similar)</small></p>
                    </div>
                </div>
            </div>

            <!-- Traffic Sources -->
            <div class="analytics-card">
                <h2 class="card-title">Traffic Sources</h2>
                <div class="data-list">
                    <?php foreach ($analyticsData['traffic_sources'] as $source): ?>
                        <div class="data-item">
                            <div class="data-item-info">
                                <div class="data-item-name"><?php echo htmlspecialchars($source['source']); ?></div>
                                <div class="data-item-value"><?php echo number_format($source['visitors']); ?> visitors</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $source['percentage']; ?>%"></div>
                                </div>
                            </div>
                            <div class="data-item-percentage"><?php echo $source['percentage']; ?>%</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Device & Browser Analytics -->
        <div class="analytics-grid">
            <div class="analytics-card">
                <h2 class="card-title">Device Types</h2>
                <div class="data-list">
                    <?php foreach ($analyticsData['devices'] as $device): ?>
                        <div class="data-item">
                            <div class="data-item-info">
                                <div class="data-item-name"><?php echo htmlspecialchars($device['device']); ?></div>
                                <div class="data-item-value"><?php echo number_format($device['sessions']); ?> sessions</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $device['percentage']; ?>%"></div>
                                </div>
                            </div>
                            <div class="data-item-percentage"><?php echo $device['percentage']; ?>%</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="analytics-card">
                <h2 class="card-title">Browsers</h2>
                <div class="data-list">
                    <?php foreach ($analyticsData['browsers'] as $browser): ?>
                        <div class="data-item">
                            <div class="data-item-info">
                                <div class="data-item-name"><?php echo htmlspecialchars($browser['browser']); ?></div>
                                <div class="data-item-value"><?php echo number_format($browser['sessions']); ?> sessions</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $browser['percentage']; ?>%"></div>
                                </div>
                            </div>
                            <div class="data-item-percentage"><?php echo $browser['percentage']; ?>%</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Tables -->
        <div class="analytics-grid">
            <div class="analytics-card">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 class="card-title" style="margin: 0;">Popular Pages</h2>
                    <a href="#" class="export-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width: 16px; height: 16px;">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                        Export
                    </a>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Page Views</th>
                                <th>Unique Views</th>
                                <th>% of Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($analyticsData['popular_pages'] as $page): ?>
                                <tr>
                                    <td><code><?php echo htmlspecialchars($page['page']); ?></code></td>
                                    <td><?php echo number_format($page['views']); ?></td>
                                    <td><?php echo number_format($page['unique_views']); ?></td>
                                    <td><?php echo round(($page['views'] / $analyticsData['overview']['page_views']) * 100, 1); ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="analytics-card">
                <h2 class="card-title">Geographic Distribution</h2>
                <div class="data-list">
                    <?php foreach ($analyticsData['geographical'] as $country): ?>
                        <div class="data-item">
                            <div class="data-item-info">
                                <div class="data-item-name"><?php echo htmlspecialchars($country['country']); ?></div>
                                <div class="data-item-value"><?php echo number_format($country['users']); ?> users</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $country['percentage']; ?>%"></div>
                                </div>
                            </div>
                            <div class="data-item-percentage"><?php echo $country['percentage']; ?>%</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Daily Stats Table -->
        <div class="analytics-card full-width-card">
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1.5rem;">
                <h2 class="card-title" style="margin: 0;">Daily Statistics</h2>
                <a href="#" class="export-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width: 16px; height: 16px;">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Export CSV
                </a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Users</th>
                            <th>Sessions</th>
                            <th>Page Views</th>
                            <th>Pages/Session</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($analyticsData['daily_stats'] as $i => $day): ?>
                            <tr>
                                <td><?php echo date('M j, Y', strtotime($day['date'])); ?></td>
                                <td><?php echo number_format($day['users']); ?></td>
                                <td><?php echo number_format($day['sessions']); ?></td>
                                <td><?php echo number_format($day['page_views']); ?></td>
                                <td><?php echo round($day['page_views'] / $day['sessions'], 1); ?></td>
                                <td>
                                    <?php if ($i > 0): ?>
                                        <?php $prevDay = $analyticsData['daily_stats'][$i-1]; ?>
                                        <?php $change = (($day['users'] - $prevDay['users']) / $prevDay['users']) * 100; ?>
                                        <span class="<?php echo $change >= 0 ? 'change-positive' : 'change-negative'; ?>">
                                            <?php echo ($change >= 0 ? '+' : '') . round($change, 1); ?>%
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function changeTimeRange(range) {
    const url = new URL(window.location);
    url.searchParams.set('range', range);
    window.location.href = url.toString();
}

// Simulate real-time updates
setInterval(() => {
    // In a real app, this would fetch updated data via AJAX
    console.log('Analytics data would be refreshed here');
}, 60000); // Update every minute
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
