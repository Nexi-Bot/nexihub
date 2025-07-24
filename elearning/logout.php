<?php
session_start();

// Clear contract user session
unset($_SESSION['contract_user_id']);
unset($_SESSION['contract_user_email']);
unset($_SESSION['contract_staff_id']);

// Redirect to login
header('Location: /elearning/');
exit;
?>
