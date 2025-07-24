<?php
require_once __DIR__ . '/../config/config.php';
requireAuth();

$page_title = "Nexi Hub E-Learning Portal";
$page_description = "Complete your onboarding and training modules";

// Get staff profile
try {
    $stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE id = ?");
    $stmt->execute([$_SESSION['staff_id']]);
    $staff_profile = $stmt->fetch();
    
    if (!$staff_profile) {
        throw new Exception("Staff profile not found");
    }
} catch (Exception $e) {
    die("Error loading profile: " . $e->getMessage());
}

// Get E-Learning progress
try {
    // Get completed modules for this staff member
    $stmt = $pdo->prepare("SELECT module_id, completed_at, quiz_score FROM elearning_progress WHERE staff_id = ? ORDER BY module_id");
    $stmt->execute([$_SESSION['staff_id']]);
    $completed_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create progress array
    $progress_data = [];
    foreach ($completed_modules as $module) {
        $progress_data[$module['module_id']] = [
            'completed' => true,
            'completed_at' => $module['completed_at'],
            'quiz_score' => $module['quiz_score']
        ];
    }
    
    // Calculate progress
    $total_modules = 5;
    $completed_count = count($completed_modules);
    $progress_percent = ($completed_count / $total_modules) * 100;
    $all_completed = $completed_count >= $total_modules;
    
    // Update staff profile status if needed
    if ($completed_count > 0 && !$staff_profile['elearning_status']) {
        $stmt = $pdo->prepare("UPDATE staff_profiles SET elearning_status = 'In Progress' WHERE id = ?");
        $stmt->execute([$_SESSION['staff_id']]);
        $staff_profile['elearning_status'] = 'In Progress';
    }
    
} catch (Exception $e) {
    error_log("E-Learning progress error: " . $e->getMessage());
    $progress_data = [];
    $completed_count = 0;
    $progress_percent = 0;
    $all_completed = false;
}

// Module definitions
$modules = [
    1 => [
        'title' => 'Welcome to Nexi',
        'description' => 'Introduction to Nexi Hub, our mission, and your role',
        'duration' => '15 minutes',
        'icon' => 'fas fa-rocket'
    ],
    2 => [
        'title' => 'Company Values & Culture',
        'description' => 'Understanding our core values and work culture',
        'duration' => '20 minutes',
        'icon' => 'fas fa-heart'
    ],
    3 => [
        'title' => 'Communication Guidelines',
        'description' => 'How we communicate internally and with clients',
        'duration' => '15 minutes',
        'icon' => 'fas fa-comments'
    ],
    4 => [
        'title' => 'Data Protection & Security',
        'description' => 'Essential security practices and data protection',
        'duration' => '25 minutes',
        'icon' => 'fas fa-shield-alt'
    ],
    5 => [
        'title' => 'Final Assessment',
        'description' => 'Test your knowledge and complete your training',
        'duration' => '10 minutes',
        'icon' => 'fas fa-graduation-cap'
    ]
];

include __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="/elearning/assets/elearning.css">

<div class="elearning-container">
    <div class="elearning-header">
        <div class="container">
            <div class="header-content">
                <div class="user-info">
                    <div class="avatar">
                        <?php echo strtoupper(substr($staff_profile['preferred_name'] ?: $staff_profile['full_name'], 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <h1>Welcome, <?php echo htmlspecialchars($staff_profile['preferred_name'] ?: explode(' ', $staff_profile['full_name'])[0]); ?>!</h1>
                        <p><?php echo htmlspecialchars($staff_profile['job_title'] ?: 'Team Member'); ?></p>
                    </div>
                </div>
                
                <div class="progress-summary">
                    <div class="progress-circle">
                        <svg viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" stroke="#e5e7eb" stroke-width="6" fill="none"/>
                            <circle cx="50" cy="50" r="45" stroke="#3b82f6" stroke-width="6" fill="none"
                                    stroke-dasharray="<?php echo $progress_percent * 2.83; ?> 283"
                                    stroke-dashoffset="0" stroke-linecap="round" transform="rotate(-90 50 50)"/>
                        </svg>
                        <span class="progress-text"><?php echo round($progress_percent); ?>%</span>
                    </div>
                    <div class="progress-details">
                        <h3>Progress</h3>
                        <p><?php echo $completed_count; ?> of <?php echo $total_modules; ?> modules completed</p>
                        <?php if ($all_completed): ?>
                        <p class="status-completed">Training Complete!</p>
                        <?php elseif ($completed_count > 0): ?>
                        <p class="status-progress">In Progress</p>
                        <?php else: ?>
                        <p class="status-not-started">Not Started</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($all_completed): ?>
        <div class="completion-banner">
            <div class="banner-content">
                <i class="fas fa-trophy"></i>
                <div>
                    <h3>Congratulations!</h3>
                    <p>You have successfully completed all training modules. Download your certificate below.</p>
                </div>
                <a href="/elearning/certificate.php" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Download Certificate
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="modules-grid">
            <?php foreach ($modules as $module_id => $module): ?>
            <div class="module-card <?php echo isset($progress_data[$module_id]) ? 'completed' : ($module_id == 1 || isset($progress_data[$module_id - 1]) ? 'available' : 'locked'); ?>">
                <div class="module-header">
                    <div class="module-icon">
                        <i class="<?php echo $module['icon']; ?>"></i>
                    </div>
                    <div class="module-status">
                        <?php if (isset($progress_data[$module_id])): ?>
                        <i class="fas fa-check-circle completed-icon"></i>
                        <?php elseif ($module_id == 1 || isset($progress_data[$module_id - 1])): ?>
                        <i class="fas fa-play-circle available-icon"></i>
                        <?php else: ?>
                        <i class="fas fa-lock locked-icon"></i>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="module-content">
                    <h3><?php echo htmlspecialchars($module['title']); ?></h3>
                    <p><?php echo htmlspecialchars($module['description']); ?></p>
                    <div class="module-meta">
                        <span class="duration">
                            <i class="fas fa-clock"></i>
                            <?php echo $module['duration']; ?>
                        </span>
                        <?php if (isset($progress_data[$module_id])): ?>
                        <span class="score">
                            <i class="fas fa-star"></i>
                            <?php echo $progress_data[$module_id]['quiz_score']; ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="module-actions">
                    <?php if (isset($progress_data[$module_id])): ?>
                    <a href="/elearning/module.php?id=<?php echo $module_id; ?>" class="btn btn-outline">
                        <i class="fas fa-eye"></i> Review
                    </a>
                    <span class="completed-date">
                        Completed <?php echo date('M j, Y', strtotime($progress_data[$module_id]['completed_at'])); ?>
                    </span>
                    <?php elseif ($module_id == 1 || isset($progress_data[$module_id - 1])): ?>
                    <a href="/elearning/module.php?id=<?php echo $module_id; ?>" class="btn btn-primary">
                        <i class="fas fa-play"></i> Start Module
                    </a>
                    <?php else: ?>
                    <span class="locked-message">
                        Complete previous module to unlock
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="help-section">
            <div class="help-card">
                <h3><i class="fas fa-question-circle"></i> Need Help?</h3>
                <p>If you encounter any issues with the training modules or have questions about the content, please contact HR.</p>
                <a href="mailto:hr@nexihub.uk" class="btn btn-outline">Contact HR</a>
            </div>
        </div>
    </div>
</div>

<script src="/elearning/assets/elearning.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
