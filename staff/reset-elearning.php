<?php
require_once '../config/config.php';

// Check if user is logged in and has admin access
if (!isset($_SESSION['staff_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get staff info to check admin status
$stmt = $pdo->prepare("SELECT role FROM staff_profiles WHERE id = ?");
$stmt->execute([$_SESSION['staff_id']]);
$staff = $stmt->fetch();

if (!$staff || $staff['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $staff_id = $data['staff_id'] ?? null;
    
    if (!$staff_id) {
        echo json_encode(['success' => false, 'message' => 'Staff ID required']);
        exit;
    }
    
    try {
        // Reset E-Learning progress for the staff member
        $stmt = $pdo->prepare("DELETE FROM elearning_progress WHERE staff_id = ?");
        $stmt->execute([$staff_id]);
        
        // Update staff profile E-Learning status to null
        $stmt = $pdo->prepare("UPDATE staff_profiles SET elearning_status = NULL WHERE id = ?");
        $stmt->execute([$staff_id]);
        
        echo json_encode(['success' => true, 'message' => 'E-Learning progress reset successfully']);
    } catch (Exception $e) {
        error_log("Error resetting E-Learning progress: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error resetting progress']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
