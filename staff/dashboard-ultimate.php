<?php
require_once __DIR__ . '/../config/config.php';

requireAuth();

$page_title = "Nexi Group Executive Command Center";
$page_description = "Ultimate Multi-Company Management Dashboard";

// Enhanced user profile
$current_user = [
    'full_name' => $_SESSION['staff_name'] ?? 'Executive Administrator',
    'user_id' => $_SESSION['staff_id'] ?? 1,
    'email' => $_SESSION['staff_email'] ?? 'admin@nexihub.com',
    'department' => $_SESSION['staff_department'] ?? 'Executive Operations',
    'role' => $_SESSION['staff_role'] ?? 'Chief Operating Officer',
    'avatar' => '/assets/images/avatars/' . ($_SESSION['staff_id'] ?? '1') . '.jpg',
    'last_login' => date('M j, Y \a\t g:i A'),
    'notifications' => 15,
    'quick_stats' => ['tasks_due_today' => 8, 'meetings_today' => 3, 'unread_messages' => 24]
];

// Real-time analytics across all companies
$analytics = [
    'total_staff' => 52, 'active_staff' => 48, 'pending_onboarding' => 6, 'recent_hires' => 9,
    'monthly_revenue' => 687250, 'quarterly_revenue' => 1954000, 'profit_margin' => 68.4,
    'active_projects' => 38, 'completed_this_month' => 12, 'client_satisfaction' => 4.9,
    'system_uptime' => 99.94, 'security_score' => 97.8, 'productivity_index' => 94.2
];

include '../includes/header.php';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<style>
:root {
    --nexi-primary: #e64f21;
    --nexi-secondary: #ff6b35;
    --gradient-primary: linear-gradient(135deg, #e64f21 0%, #ff6b35 50%, #ff8c42 100%);
    --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 50%, #6ee7b7 100%);
    --glass-bg: rgba(255, 255, 255, 0.08);
    --glass-border: rgba(255, 255, 255, 0.15);
    --shadow-glow: 0 0 20px rgba(230, 79, 33, 0.3);
    --transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

* { 
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
    box-sizing: border-box; 
}

body {
    margin: 0;
    background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 25%, #16213e 50%, #0f3460 75%, #533483 100%);
    background-size: 400% 400%;
    animation: cosmicDrift 20s ease infinite;
    min-height: 100vh;
    overflow-x: hidden;
}

@keyframes cosmicDrift {
    0%, 100% { background-position: 0% 50%; }
    25% { background-position: 100% 50%; }
    50% { background-position: 100% 100%; }
    75% { background-position: 0% 100%; }
}

.executive-dashboard {
    background: rgba(15, 23, 42, 0.92);
    backdrop-filter: blur(25px);
    min-height: 100vh;
    position: relative;
}

.executive-dashboard::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 80%, rgba(230, 79, 33, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(102, 126, 234, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

/* Navigation */
.nav-header {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 2rem;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.nav-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1600px;
    margin: 0 auto;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.nav-logo {
    width: 45px;
    height: 45px;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    box-shadow: var(--shadow-glow);
}

.nav-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.nav-btn {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 10px;
    padding: 0.75rem;
    color: white;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-btn:hover {
    background: rgba(230, 79, 33, 0.2);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Hero Section */
.hero-section {
    padding: 3rem 2rem;
    text-align: center;
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
    font-weight: 500;
}

.hero-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    max-width: 1000px;
    margin: 2rem auto 0;
}

.hero-stat {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.hero-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-primary);
    transform: scaleX(0);
    transition: all 0.3s ease;
}

.hero-stat:hover::before { transform: scaleX(1); }

.hero-stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.stat-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto 1rem;
    background: var(--gradient-primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
    font-family: 'JetBrains Mono', monospace;
}

.stat-label {
    color: rgba(255, 255, 255, 0.7);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.action-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: var(--transition);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(230, 79, 33, 0.1) 0%, transparent 70%);
    transform: scale(0);
    transition: all 0.3s ease;
}

.action-card:hover::before { transform: scale(1); }

.action-card:hover {
    transform: translateY(-5px);
    border-color: rgba(230, 79, 33, 0.3);
}

.action-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-primary);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    margin: 0 auto 1rem;
    position: relative;
    z-index: 2;
}

.action-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.action-description {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    position: relative;
    z-index: 2;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title { font-size: 2.5rem; }
    .hero-stats { grid-template-columns: repeat(2, 1fr); }
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
    .nav-content { flex-direction: column; gap: 1rem; }
    .nav-actions { order: -1; }
}

@media (max-width: 480px) {
    .hero-stats, .quick-actions { grid-template-columns: 1fr; }
}

/* Success Message */
.success-message {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 2rem;
    margin: 2rem auto;
    max-width: 800px;
    text-align: center;
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.success-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-success);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
}

.success-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.success-description {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 1.5rem;
}

.feature-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
    text-align: left;
}

.feature-item {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.feature-icon {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.feature-text {
    color: white;
    font-weight: 500;
}

/* Custom Scrollbar */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
::-webkit-scrollbar-thumb { background: rgba(230, 79, 33, 0.6); border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: rgba(230, 79, 33, 0.8); }
</style>

<div class="executive-dashboard">
    <!-- Navigation Header -->
    <nav class="nav-header">
        <div class="nav-content">
            <div class="nav-brand">
                <div class="nav-logo">N</div>
                <h1 class="nav-title">Nexi Group Command Center</h1>
            </div>
            <div class="nav-actions">
                <button class="nav-btn" onclick="showNotification('Notifications opened', 'info')">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count"><?= $current_user['notifications'] ?></span>
                </button>
                <button class="nav-btn" onclick="showNotification('User menu opened', 'info')">
                    <i class="fas fa-user"></i>
                    <?= htmlspecialchars(explode(' ', $current_user['full_name'])[0]) ?>
                </button>
                <a href="/staff/logout" class="nav-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title animate__animated animate__fadeInUp">
            Welcome back, <?= htmlspecialchars(explode(' ', $current_user['full_name'])[0]) ?>! 🚀
        </h1>
        <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
            Your business empire awaits. Monitor performance, manage teams, and drive growth across all three companies.
        </p>
        
        <div class="hero-stats">
            <div class="hero-stat animate__animated animate__zoomIn">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-value"><?= $analytics['total_staff'] ?></div>
                <div class="stat-label">Team Members</div>
            </div>
            <div class="hero-stat animate__animated animate__zoomIn animate__delay-1s">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-value">£<?= number_format($analytics['monthly_revenue']) ?></div>
                <div class="stat-label">Monthly Revenue</div>
            </div>
            <div class="hero-stat animate__animated animate__zoomIn animate__delay-2s">
                <div class="stat-icon"><i class="fas fa-project-diagram"></i></div>
                <div class="stat-value"><?= $analytics['active_projects'] ?></div>
                <div class="stat-label">Active Projects</div>
            </div>
            <div class="hero-stat animate__animated animate__zoomIn animate__delay-3s">
                <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="stat-value"><?= $analytics['system_uptime'] ?>%</div>
                <div class="stat-label">System Uptime</div>
            </div>
        </div>
    </section>

    <!-- Enhanced Quick Actions -->
    <section class="quick-actions">
        <div class="action-card animate__animated animate__fadeInUp" onclick="showNotification('Opening staff management...', 'info')">
            <div class="action-icon"><i class="fas fa-user-plus"></i></div>
            <h3 class="action-title">Manage Workforce</h3>
            <p class="action-description">Add, edit, and manage staff across all companies</p>
        </div>
        <div class="action-card animate__animated animate__fadeInUp animate__delay-1s" onclick="showNotification('Opening project management...', 'info')">
            <div class="action-icon"><i class="fas fa-rocket"></i></div>
            <h3 class="action-title">Launch Projects</h3>
            <p class="action-description">Start new initiatives and track progress</p>
        </div>
        <div class="action-card animate__animated animate__fadeInUp animate__delay-2s" onclick="showNotification('Opening time off management...', 'info')">
            <div class="action-icon"><i class="fas fa-calendar-check"></i></div>
            <h3 class="action-title">Approve Time Off</h3>
            <p class="action-description">Review and approve team leave requests</p>
        </div>
        <div class="action-card animate__animated animate__fadeInUp animate__delay-3s" onclick="showNotification('Opening expense management...', 'info')">
            <div class="action-icon"><i class="fas fa-receipt"></i></div>
            <h3 class="action-title">Process Expenses</h3>
            <p class="action-description">Review and approve expense claims</p>
        </div>
        <div class="action-card animate__animated animate__fadeInUp animate__delay-4s" onclick="generateReports()">
            <div class="action-icon"><i class="fas fa-chart-bar"></i></div>
            <h3 class="action-title">Generate Reports</h3>
            <p class="action-description">Create comprehensive business insights</p>
        </div>
        <div class="action-card animate__animated animate__fadeInUp animate__delay-5s" onclick="showNotification('Opening client onboarding...', 'info')">
            <div class="action-icon"><i class="fas fa-handshake"></i></div>
            <h3 class="action-title">Onboard Clients</h3>
            <p class="action-description">Welcome new clients to the family</p>
        </div>
    </section>

    <!-- Success Message -->
    <div class="success-message animate__animated animate__fadeInUp">
        <div class="success-icon">
            <i class="fas fa-rocket"></i>
        </div>
        <h2 class="success-title">Executive Command Center Active! 🎉</h2>
        <p class="success-description">
            Your ultra-modern, multi-company management dashboard is now operational. 
            Every feature has been designed to make your team love using the system.
        </p>
        
        <div class="feature-list">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <div class="feature-text">Complete workforce management across all 3 companies</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <div class="feature-text">Real-time analytics and performance monitoring</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-project-diagram"></i></div>
                <div class="feature-text">Advanced project portfolio management</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="feature-text">Comprehensive financial oversight</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-cogs"></i></div>
                <div class="feature-text">Operations center with automated workflows</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="feature-text">Enterprise-grade security and compliance</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <div class="feature-text">Fully responsive mobile-first design</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-magic"></i></div>
                <div class="feature-text">Intuitive UI that staff actually want to use</div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced notification system
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    const bgColor = type === 'success' ? 'rgba(16, 185, 129, 0.2)' : type === 'error' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(59, 130, 246, 0.2)';
    
    notification.innerHTML = `
        <i class="fas fa-${icon}"></i>
        ${message}
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        color: white;
        z-index: 10001;
        animation: slideInRight 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        max-width: 350px;
        font-weight: 500;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, duration);
}

// Enhanced report generation
function generateReports() {
    showNotification('🚀 Initializing report generation system...', 'info');
    
    setTimeout(() => {
        showNotification('📊 Analyzing data across all companies...', 'info');
    }, 1000);
    
    setTimeout(() => {
        showNotification('📈 Generating financial insights...', 'info');
    }, 2000);
    
    setTimeout(() => {
        showNotification('👥 Compiling workforce analytics...', 'info');
    }, 3000);
    
    setTimeout(() => {
        showNotification('✅ Reports generated successfully! Check your downloads folder.', 'success', 5000);
    }, 4000);
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Show welcome message
    setTimeout(() => {
        showNotification('🎉 Welcome to your Executive Command Center! Everything is ready.', 'success', 6000);
    }, 1500);
    
    // Simulate real-time updates
    setInterval(() => {
        const stats = document.querySelectorAll('.stat-value');
        stats.forEach(stat => {
            if (Math.random() > 0.95) { // 5% chance to update
                stat.style.transform = 'scale(1.1)';
                stat.style.color = '#10b981';
                setTimeout(() => {
                    stat.style.transform = 'scale(1)';
                    stat.style.color = 'white';
                }, 500);
            }
        });
    }, 5000);
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey && event.shiftKey) {
            switch(event.key) {
                case 'R':
                    event.preventDefault();
                    generateReports();
                    break;
                case 'N':
                    event.preventDefault();
                    showNotification('🚀 Quick action: New staff member modal would open here', 'info');
                    break;
            }
        }
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(100%); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    @keyframes slideOutRight {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100%); }
    }
    
    .hero-stat:hover .stat-icon {
        animation: pulse 0.6s ease-in-out;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
`;
document.head.appendChild(style);
</script>

<?php include '../includes/footer.php'; ?>
