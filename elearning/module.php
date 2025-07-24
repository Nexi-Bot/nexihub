<?php
require_once __DIR__ . '/../config/config.php';
requireAuth();

$module_id = intval($_GET['id'] ?? 1);

// Validate module ID
if ($module_id < 1 || $module_id > 5) {
    header('Location: /elearning/');
    exit;
}

// Get staff profile and check module access
try {
    $stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE id = ?");
    $stmt->execute([$_SESSION['staff_id']]);
    $staff_profile = $stmt->fetch();
    
    if (!$staff_profile) {
        throw new Exception("Staff profile not found");
    }
    
    // Check if user can access this module
    // Module 1 is always accessible, others require previous module completion
    $can_access = true;
    if ($module_id > 1) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM elearning_progress WHERE staff_id = ? AND module_id = ?");
        $stmt->execute([$_SESSION['staff_id'], $module_id - 1]);
        $prev_completed = $stmt->fetchColumn() > 0;
        $can_access = $prev_completed;
    }
    
    if (!$can_access) {
        header('Location: /elearning/');
        exit;
    }
    
    // Check if current module is completed
    $stmt = $pdo->prepare("SELECT * FROM elearning_progress WHERE staff_id = ? AND module_id = ?");
    $stmt->execute([$_SESSION['staff_id'], $module_id]);
    $module_progress = $stmt->fetch();
    $is_completed = $module_progress !== false;
    
} catch (Exception $e) {
    die("Error loading progress: " . $e->getMessage());
}

// Define modules content
$modules = [
    1 => [
        'title' => 'Welcome to Nexi Hub',
        'duration' => '15 minutes',
        'quiz_questions' => 3,
        'content' => '
            <h2>🚀 Welcome to the Nexi Hub Family!</h2>
            
            <p>Congratulations on joining Nexi Hub! We\'re excited to have you as part of our innovative team. This training module will introduce you to our company, our mission, and what makes us unique in the industry.</p>
            
            <h3>Our Story</h3>
            <p>Nexi Hub was founded with a simple yet powerful vision: to revolutionize how businesses connect, communicate, and grow in the digital age. We started as a small team of passionate technologists and have grown into a dynamic company serving clients worldwide.</p>
            
            <h3>Our Mission</h3>
            <div class="mission-box">
                <p><strong>"To empower businesses with cutting-edge digital solutions that drive growth, efficiency, and success."</strong></p>
            </div>
            
            <h3>What We Do</h3>
            <ul>
                <li><strong>Nexi Web:</strong> Custom web development and design solutions</li>
                <li><strong>Nexi Bot:</strong> Intelligent automation and chatbot development</li>
                <li><strong>Nexi Pulse:</strong> Advanced analytics and business intelligence</li>
            </ul>
            
            <h3>Your Role Matters</h3>
            <p>Every team member at Nexi Hub plays a crucial role in our success. Whether you\'re in development, design, marketing, or support, your contributions help us deliver exceptional value to our clients and push the boundaries of what\'s possible.</p>
            
            <div class="key-points">
                <h4>Key Takeaways:</h4>
                <ul>
                    <li>Nexi Hub is a technology company focused on digital solutions</li>
                    <li>We serve businesses worldwide with web, bot, and analytics solutions</li>
                    <li>Every team member contributes to our collective success</li>
                    <li>Innovation and excellence are at the core of everything we do</li>
                </ul>
            </div>
        '
    ],
    2 => [
        'title' => 'Company Values & Culture',
        'duration' => '20 minutes',
        'quiz_questions' => 4,
        'content' => '
            <h2>💎 Our Core Values</h2>
            
            <p>At Nexi Hub, our values guide every decision we make and every interaction we have. These principles define who we are and how we work together to achieve our goals.</p>
            
            <div class="values-grid">
                <div class="value-card">
                    <h3>🎯 Excellence</h3>
                    <p>We strive for excellence in everything we do. From code quality to customer service, we never settle for "good enough."</p>
                </div>
                
                <div class="value-card">
                    <h3>🚀 Innovation</h3>
                    <p>We embrace new technologies and creative solutions. Innovation is not just encouraged—it\'s essential to our success.</p>
                </div>
                
                <div class="value-card">
                    <h3>🤝 Collaboration</h3>
                    <p>We believe in the power of teamwork. Great things happen when talented people work together toward a common goal.</p>
                </div>
                
                <div class="value-card">
                    <h3>📈 Growth</h3>
                    <p>We\'re committed to continuous learning and improvement, both as individuals and as a company.</p>
                </div>
            </div>
            
            <h3>Our Work Culture</h3>
            <ul>
                <li><strong>Remote-First:</strong> We support flexible work arrangements that promote work-life balance</li>
                <li><strong>Open Communication:</strong> We encourage honest, transparent communication at all levels</li>
                <li><strong>Learning-Oriented:</strong> We invest in your professional development and growth</li>
                <li><strong>Results-Focused:</strong> We measure success by outcomes, not hours worked</li>
            </ul>
            
            <div class="culture-highlight">
                <h4>Living Our Values Daily</h4>
                <p>Our values aren\'t just words on a wall—they\'re principles we live by. In your day-to-day work, you\'ll see these values in action through our decision-making processes, team interactions, and client relationships.</p>
            </div>
        '
    ],
    3 => [
        'title' => 'Communication Guidelines',
        'duration' => '15 minutes',
        'quiz_questions' => 3,
        'content' => '
            <h2>💬 Communication Excellence</h2>
            
            <p>Effective communication is the foundation of our success at Nexi Hub. This module covers our communication standards, tools, and best practices.</p>
            
            <h3>Internal Communication</h3>
            
            <h4>Discord - Our Primary Platform</h4>
            <ul>
                <li><strong>General channels:</strong> Company announcements and casual conversation</li>
                <li><strong>Project channels:</strong> Specific project discussions and updates</li>
                <li><strong>Department channels:</strong> Team-specific communications</li>
                <li><strong>Direct messages:</strong> One-on-one conversations and private matters</li>
            </ul>
            
            <h4>Email Guidelines</h4>
            <ul>
                <li>Use for formal communications and external correspondence</li>
                <li>Include clear, descriptive subject lines</li>
                <li>Keep messages concise and professional</li>
                <li>Use proper formatting and proofread before sending</li>
            </ul>
            
            <h3>Client Communication</h3>
            
            <div class="communication-standards">
                <h4>Professional Standards:</h4>
                <ul>
                    <li><strong>Response Time:</strong> Acknowledge emails within 4 hours during business hours</li>
                    <li><strong>Tone:</strong> Always professional, helpful, and positive</li>
                    <li><strong>Clarity:</strong> Use clear, jargon-free language</li>
                    <li><strong>Follow-up:</strong> Confirm understanding and next steps</li>
                </ul>
            </div>
            
            <h3>Meeting Etiquette</h3>
            <ul>
                <li>Join meetings on time and come prepared</li>
                <li>Mute when not speaking in larger meetings</li>
                <li>Contribute actively and stay engaged</li>
                <li>Follow up with action items and deadlines</li>
            </ul>
            
            <div class="communication-tip">
                <h4>💡 Pro Tip</h4>
                <p>When in doubt about communication approach, ask yourself: "Is this clear, professional, and helpful?" If yes, you\'re on the right track!</p>
            </div>
        '
    ],
    4 => [
        'title' => 'Data Protection & Security',
        'duration' => '25 minutes',
        'quiz_questions' => 5,
        'content' => '
            <h2>🔒 Security & Data Protection</h2>
            
            <p>At Nexi Hub, we take data protection and security seriously. This module covers essential security practices and our data protection policies.</p>
            
            <h3>Password Security</h3>
            
            <div class="security-checklist">
                <h4>✅ Password Requirements:</h4>
                <ul>
                    <li>Minimum 12 characters long</li>
                    <li>Include uppercase, lowercase, numbers, and symbols</li>
                    <li>Use unique passwords for each account</li>
                    <li>Enable two-factor authentication (2FA) wherever possible</li>
                    <li>Use a password manager</li>
                </ul>
            </div>
            
            <h3>Data Handling</h3>
            
            <h4>Client Data Protection:</h4>
            <ul>
                <li><strong>Confidentiality:</strong> Never share client data without authorization</li>
                <li><strong>Access Control:</strong> Only access data necessary for your role</li>
                <li><strong>Secure Storage:</strong> Use approved cloud storage solutions</li>
                <li><strong>Data Retention:</strong> Follow our data retention policies</li>
            </ul>
            
            <h4>Personal Data (GDPR Compliance):</h4>
            <ul>
                <li>Obtain proper consent before collecting personal data</li>
                <li>Process data only for specified purposes</li>
                <li>Allow individuals to access and correct their data</li>
                <li>Report data breaches within 72 hours</li>
            </ul>
            
            <h3>Common Security Threats</h3>
            
            <div class="threat-warnings">
                <div class="threat-item">
                    <h4>🎣 Phishing Attacks</h4>
                    <p>Be cautious of suspicious emails asking for login credentials or personal information. Always verify the sender\'s identity.</p>
                </div>
                
                <div class="threat-item">
                    <h4>🦠 Malware</h4>
                    <p>Keep your software updated and use reputable antivirus protection. Avoid downloading suspicious files.</p>
                </div>
                
                <div class="threat-item">
                    <h4>📱 Social Engineering</h4>
                    <p>Be wary of unsolicited phone calls or messages requesting sensitive information, even if they claim to be from legitimate sources.</p>
                </div>
            </div>
            
            <h3>Incident Reporting</h3>
            <p>If you suspect a security incident or data breach, immediately contact the IT security team at <strong>security@nexihub.uk</strong></p>
            
            <div class="security-reminder">
                <h4>🛡️ Remember</h4>
                <p>Security is everyone\'s responsibility. Your vigilance helps protect our company, our clients, and your colleagues.</p>
            </div>
        '
    ],
    5 => [
        'title' => 'Final Assessment',
        'duration' => '10 minutes',
        'quiz_questions' => 8,
        'content' => '
            <h2>🎓 Final Assessment</h2>
            
            <p>Congratulations on completing all the training modules! This final assessment will test your understanding of the key concepts covered in your onboarding training.</p>
            
            <div class="assessment-info">
                <h3>Assessment Details:</h3>
                <ul>
                    <li><strong>Questions:</strong> 8 multiple-choice questions</li>
                    <li><strong>Passing Score:</strong> 80% (7 out of 8 correct)</li>
                    <li><strong>Time Limit:</strong> No time limit</li>
                    <li><strong>Attempts:</strong> You can retake the assessment if needed</li>
                </ul>
            </div>
            
            <h3>Topics Covered:</h3>
            <ul>
                <li>Nexi Hub company overview and mission</li>
                <li>Core values and company culture</li>
                <li>Communication guidelines and best practices</li>
                <li>Data protection and security protocols</li>
            </ul>
            
            <div class="success-message">
                <h3>🏆 Upon Successful Completion:</h3>
                <ul>
                    <li>You\'ll receive a completion certificate</li>
                    <li>Your training status will be updated to "Completed"</li>
                    <li>You\'ll have full access to all company resources</li>
                    <li>Welcome to the Nexi Hub team!</li>
                </ul>
            </div>
            
            <p><strong>Take your time and answer thoughtfully. Good luck!</strong></p>
        '
    ]
];

$current_module = $modules[$module_id];
$page_title = $current_module['title'] . " - Nexi Hub E-Learning";

include __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="/elearning/assets/elearning.css">

<div class="module-container">
    <div class="module-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="/elearning/"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>
            
            <div class="module-info">
                <div class="module-meta">
                    <span class="module-number">Module <?php echo $module_id; ?></span>
                    <span class="module-duration">
                        <i class="fas fa-clock"></i>
                        <?php echo $current_module['duration']; ?>
                    </span>
                    <?php if ($is_completed): ?>
                    <span class="completion-badge">
                        <i class="fas fa-check-circle"></i>
                        Completed
                    </span>
                    <?php endif; ?>
                </div>
                <h1><?php echo htmlspecialchars($current_module['title']); ?></h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="module-content">
            <div class="content-section">
                <?php echo $current_module['content']; ?>
            </div>
            
            <?php if (!$is_completed): ?>
            <div class="quiz-section">
                <h3><i class="fas fa-question-circle"></i> Knowledge Check</h3>
                <p>Complete this quick quiz to finish the module.</p>
                
                <div id="quiz-container">
                    <!-- Quiz questions will be loaded by JavaScript -->
                </div>
                
                <div class="quiz-actions">
                    <button id="complete-module-btn" class="btn btn-primary" onclick="completeModule(<?php echo $module_id; ?>)">
                        <i class="fas fa-check"></i>
                        Complete Module
                    </button>
                </div>
            </div>
            <?php else: ?>
            <div class="completed-section">
                <div class="completion-message">
                    <i class="fas fa-check-circle"></i>
                    <h3>Module Completed!</h3>
                    <p>You completed this module on <?php echo date('F j, Y', strtotime($module_progress['completed_at'])); ?></p>
                    <p>Quiz Score: <strong><?php echo $module_progress['quiz_score']; ?>%</strong></p>
                </div>
                
                <div class="navigation-actions">
                    <?php if ($module_id < 5): ?>
                    <a href="/elearning/module.php?id=<?php echo $module_id + 1; ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        Next Module
                    </a>
                    <?php else: ?>
                    <a href="/elearning/" class="btn btn-primary">
                        <i class="fas fa-trophy"></i>
                        View Certificate
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const moduleId = <?php echo $module_id; ?>;
const isCompleted = <?php echo $is_completed ? 'true' : 'false'; ?>;

function completeModule(moduleId) {
    // Simple completion - in a real implementation, you'd have an actual quiz
    const score = Math.floor(Math.random() * 21) + 80; // Random score between 80-100
    
    fetch('/elearning/complete-module.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            module_id: moduleId,
            quiz_score: score
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Module completed successfully! Quiz Score: ' + score + '%');
            location.reload();
        } else {
            alert('Error completing module: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    });
}

// Load quiz questions (simplified for demo)
if (!isCompleted) {
    document.getElementById('quiz-container').innerHTML = `
        <div class="quiz-info">
            <p><i class="fas fa-info-circle"></i> This module includes ${<?php echo $current_module['quiz_questions']; ?>} quiz questions to test your understanding.</p>
            <p>Click "Complete Module" when you're ready to finish and take the quiz.</p>
        </div>
    `;
}
</script>

<script src="/elearning/assets/elearning.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
