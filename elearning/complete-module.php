<?php
require_once '../config/config.php';

header('Content-Type: application/json');

// Check if user is logged in via contract user session
if (!isset($_SESSION['contract_user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

// Get staff information
$stmt = $pdo->prepare("SELECT sp.* FROM staff_profiles sp 
                       JOIN contract_users cu ON sp.id = cu.staff_id 
                       WHERE cu.id = ?");
$stmt->execute([$_SESSION['contract_user_id']]);
$staff = $stmt->fetch();

if (!$staff) {
    echo json_encode(['success' => false, 'message' => 'Staff not found']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$module_id = intval($data['module_id'] ?? 0);
$quiz_score = intval($data['quiz_score'] ?? 80);

if ($module_id < 1 || $module_id > 7) {
    echo json_encode(['success' => false, 'message' => 'Invalid module ID']);
    exit;
}

try {
    // Record module completion 
    $stmt = $pdo->prepare("
        INSERT INTO elearning_module_progress (staff_id, module_id, completed, completed_at, quiz_score) 
        VALUES (?, ?, 1, NOW(), ?)
        ON DUPLICATE KEY UPDATE completed = 1, completed_at = NOW(), quiz_score = VALUES(quiz_score)
    ");
    $stmt->execute([$staff['id'], $module_id, $quiz_score]);
    
    // Check how many modules completed
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM elearning_module_progress WHERE staff_id = ? AND completed = 1");
    $stmt->execute([$staff['id']]);
    $completed_count = $stmt->fetchColumn();
    
    $total_modules = 7;
    $all_completed = $completed_count >= $total_modules;
    
    // Update staff profile status
    if ($all_completed) {
        $stmt = $pdo->prepare("UPDATE staff_profiles SET elearning_status = 'Completed' WHERE id = ?");
        $stmt->execute([$staff['id']]);
    } else {
        $stmt = $pdo->prepare("UPDATE staff_profiles SET elearning_status = 'In Progress' WHERE id = ?");
        $stmt->execute([$staff['id']]);
    }
    
    $response = [
        'success' => true,
        'message' => 'Module completed successfully',
        'completed_modules' => $completed_count,
        'total_modules' => $total_modules,
        'all_completed' => $all_completed,
        'progress_percent' => round(($completed_count / $total_modules) * 100)
    ];
    
    if (!$all_completed) {
        $response['next_module'] = $module_id + 1;
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("E-Learning completion error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
