<?php
require_once __DIR__ . '/../config/config.php';

// Delete session from database
if (isset($_SESSION['session_token'])) {
    $stmt = $pdo->prepare("DELETE FROM staff_sessions WHERE session_token = ?");
    $stmt->execute([$_SESSION['session_token']]);
}

// Clear all session data
session_destroy();

// Redirect to login
redirectTo('/staff/login');
?>
