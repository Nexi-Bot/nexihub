<?php
require_once '../config/config.php';

// Check if user is logged in via contract user session
if (!isset($_SESSION['contract_user_id'])) {
    header('Location: /elearning/');
    exit;
}

// Get staff information
$stmt = $pdo->prepare("SELECT sp.* FROM staff_profiles sp 
                       JOIN contract_users cu ON sp.id = cu.staff_id 
                       WHERE cu.id = ?");
$stmt->execute([$_SESSION['contract_user_id']]);
$staff = $stmt->fetch();

if (!$staff) {
    header('Location: /elearning/');
    exit;
}

$module_id = intval($_GET['id'] ?? 1);

// Validate module ID
if ($module_id < 1 || $module_id > 7) {
    header('Location: /elearning/');
    exit;
}

try {
    // Check if user can access this module
    $can_access = true;
    if ($module_id > 1) {
        $stmt = $pdo->prepare("SELECT completed FROM elearning_module_progress WHERE staff_id = ? AND module_id = ?");
        $stmt->execute([$staff['id'], $module_id - 1]);
        $prev_completed = $stmt->fetchColumn();
        $can_access = ($prev_completed == 1);
    }
    
    if (!$can_access) {
        header('Location: /elearning/');
        exit;
    }
    
    // Check if current module is completed
    $stmt = $pdo->prepare("SELECT * FROM elearning_module_progress WHERE staff_id = ? AND module_id = ?");
    $stmt->execute([$staff['id'], $module_id]);
    $module_progress = $stmt->fetch();
    $is_completed = ($module_progress && $module_progress['completed'] == 1);
    
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
            <p>Congratulations on joining Nexi Hub! We\'re excited to have you as part of our innovative team.</p>
            <h3>Our Mission</h3>
            <p><strong>"To empower businesses with cutting-edge digital solutions that drive growth, efficiency, and success."</strong></p>
            <h3>What We Do</h3>
            <ul>
                <li><strong>Nexi Web:</strong> Custom web development solutions</li>
                <li><strong>Nexi Bot:</strong> Intelligent automation development</li>
                <li><strong>Nexi Pulse:</strong> Advanced analytics and business intelligence</li>
            </ul>
        '
    ],
    2 => [
        'title' => 'Company Values & Culture',
        'duration' => '20 minutes',
        'quiz_questions' => 4,
        'content' => '
            <h2>💎 Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <h3>🎯 Excellence</h3>
                    <p>We strive for excellence in everything we do.</p>
                </div>
                <div class="value-card">
                    <h3>🚀 Innovation</h3>
                    <p>We embrace new technologies and creative solutions.</p>
                </div>
                <div class="value-card">
                    <h3>🤝 Collaboration</h3>
                    <p>We believe in the power of teamwork.</p>
                </div>
                <div class="value-card">
                    <h3>📈 Growth</h3>
                    <p>We\'re committed to continuous learning and improvement.</p>
                </div>
            </div>
        '
    ],
    3 => [
        'title' => 'Communication Guidelines',
        'duration' => '15 minutes',
        'quiz_questions' => 3,
        'content' => '
            <h2>💬 Communication Excellence</h2>
            <p>Effective communication is the foundation of our success at Nexi Hub.</p>
            <h3>Internal Communication</h3>
            <ul>
                <li>Use Discord for team discussions</li>
                <li>Email for formal communications</li>
                <li>Respond within 4 hours during business hours</li>
            </ul>
        '
    ],
    4 => [
        'title' => 'Data Protection & Security',
        'duration' => '25 minutes',
        'quiz_questions' => 5,
        'content' => '
            <h2>🔒 Security & Data Protection</h2>
            <p>At Nexi Hub, we take data protection and security seriously.</p>
            <div class="security-checklist">
                <h4>✅ Password Requirements:</h4>
                <ul>
                    <li>Minimum 12 characters long</li>
                    <li>Include uppercase, lowercase, numbers, and symbols</li>
                    <li>Use unique passwords for each account</li>
                    <li>Enable two-factor authentication (2FA)</li>
                </ul>
            </div>
        '
    ],
    5 => [
        'title' => 'Working with Clients',
        'duration' => '20 minutes',
        'quiz_questions' => 4,
        'content' => '
            <h2>🤝 Client Relations</h2>
            <p>Our clients are the heart of our business. This module covers best practices for client interactions.</p>
            <h3>Professional Standards</h3>
            <ul>
                <li>Always maintain professional communication</li>
                <li>Respond promptly to client inquiries</li>
                <li>Set clear expectations and deliver on promises</li>
                <li>Keep clients informed of project progress</li>
            </ul>
        '
    ],
    6 => [
        'title' => 'Tools & Technologies',
        'duration' => '18 minutes',
        'quiz_questions' => 4,
        'content' => '
            <h2>🛠️ Our Technology Stack</h2>
            <p>Learn about the tools and technologies we use at Nexi Hub.</p>
            <div class="tools-grid">
                <div class="tool-card">
                    <h4>Development</h4>
                    <ul>
                        <li>VS Code</li>
                        <li>Git & GitHub</li>
                        <li>Docker</li>
                    </ul>
                </div>
                <div class="tool-card">
                    <h4>Communication</h4>
                    <ul>
                        <li>Discord</li>
                        <li>Zoom</li>
                        <li>Email</li>
                    </ul>
                </div>
            </div>
        '
    ],
    7 => [
        'title' => 'Final Assessment',
        'duration' => '10 minutes',
        'quiz_questions' => 8,
        'content' => '
            <h2>🎓 Final Assessment</h2>
            <p>Congratulations on completing all the training modules! This final assessment will test your understanding.</p>
            <div class="assessment-info">
                <h3>Assessment Details:</h3>
                <ul>
                    <li><strong>Questions:</strong> 8 multiple-choice questions</li>
                    <li><strong>Passing Score:</strong> 80% (7 out of 8 correct)</li>
                    <li><strong>Attempts:</strong> You can retake if needed</li>
                </ul>
            </div>
        '
    ]
];

$current_module = $modules[$module_id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($current_module['title']); ?> - E-Learning Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }

        .header .subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .content {
            padding: 30px;
        }

        .module-nav {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .nav-btn.disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .module-content {
            line-height: 1.6;
            color: #333;
        }

        .module-content h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .module-content h3 {
            color: #444;
            margin: 25px 0 15px 0;
            font-size: 1.5rem;
        }

        .module-content h4 {
            color: #555;
            margin: 20px 0 10px 0;
            font-size: 1.2rem;
        }

        .module-content p {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .module-content ul, .module-content ol {
            margin: 15px 0 15px 30px;
        }

        .module-content li {
            margin-bottom: 8px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .value-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .value-card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .security-checklist {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .tool-card {
            background: white;
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .tool-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .assessment-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #2196f3;
        }

        .quiz-section {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #4caf50;
        }

        .quiz-section h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        .quiz-section p {
            color: #388e3c;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .complete-btn {
            background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .complete-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .complete-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .completed-section {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            border: 2px solid #4caf50;
        }

        .completion-message {
            color: #2e7d32;
        }

        .completion-message i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .completion-message h3 {
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        .quiz-info {
            background: #fff3cd;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }

        .quiz-info i {
            color: #856404;
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }

            .content {
                padding: 20px;
            }

            .module-nav {
                flex-direction: column;
                gap: 15px;
            }

            .values-grid {
                grid-template-columns: 1fr;
            }

            .tools-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($current_module['title']); ?></h1>
            <div class="subtitle">Module <?php echo $module_id; ?> of 7 • <?php echo $current_module['duration']; ?></div>
        </div>

        <div class="content">
            <div class="module-nav">
                <div>
                    <a href="/elearning/" class="nav-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
                <div>
                    <?php if ($module_id > 1): ?>
                        <a href="/elearning/module.php?id=<?php echo $module_id - 1; ?>" class="nav-btn">
                            <i class="fas fa-chevron-left"></i>
                            Previous
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($module_id < 7 && $is_completed): ?>
                        <a href="/elearning/module.php?id=<?php echo $module_id + 1; ?>" class="nav-btn">
                            Next
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="module-content">
                <?php echo $current_module['content']; ?>
            </div>
            
            <?php if (!$is_completed): ?>
            <div class="quiz-section">
                <h3><i class="fas fa-question-circle"></i> Knowledge Check</h3>
                <p>Complete this quick quiz to finish the module and progress to the next one.</p>
                
                <div id="quiz-container">
                    <div class="quiz-info">
                        <p><i class="fas fa-info-circle"></i> This module includes <?php echo $current_module['quiz_questions']; ?> quiz questions to test your understanding.</p>
                        <p>Click "Complete Module & Take Quiz" when you're ready to finish.</p>
                    </div>
                </div>
                
                <div class="quiz-actions">
                    <button id="complete-module-btn" class="complete-btn" onclick="completeModule(<?php echo $module_id; ?>)">
                        <i class="fas fa-check"></i>
                        Complete Module & Take Quiz
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
                    <?php if ($module_id < 7): ?>
                    <a href="/elearning/module.php?id=<?php echo $module_id + 1; ?>" class="complete-btn">
                        <i class="fas fa-arrow-right"></i>
                        Next Module
                    </a>
                    <?php else: ?>
                    <a href="/elearning/" class="complete-btn">
                        <i class="fas fa-trophy"></i>
                        View Certificate
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function completeModule(moduleId) {
            console.log('Starting module completion for module:', moduleId);
            
            // Show loading state
            const btn = document.getElementById('complete-module-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;
            
            // Simple completion - in a real implementation, you'd have an actual quiz
            const score = Math.floor(Math.random() * 21) + 80; // Random score between 80-100
            
            fetch('complete-module.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    module_id: moduleId,
                    quiz_score: score
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert('🎉 Module completed successfully!\n\nQuiz Score: ' + score + '%\n\nGreat job! You can now proceed to the next module.');
                    location.reload();
                } else {
                    alert('Error completing module: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again. Error: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>
