<?php
require_once '../config/config.php';
requireAuth();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    // Get staff info
    $stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE id = ?");
    $stmt->execute([$_SESSION['staff_id']]);
    $staff = $stmt->fetch();
    
    if (!$staff) {
        echo json_encode(['success' => false, 'message' => 'Staff not found']);
        exit;
    }
    
    if (isset($data['action']) && $data['action'] === 'cancel') {
        // Cancel request
        $requestId = intval($data['request_id'] ?? 0);
        
        // Verify request belongs to user and is pending
        $stmt = $pdo->prepare("SELECT * FROM time_off_requests WHERE id = ? AND staff_id = ? AND status = 'Pending'");
        $stmt->execute([$requestId, $_SESSION['staff_id']]);
        $request = $stmt->fetch();
        
        if (!$request) {
            echo json_encode(['success' => false, 'message' => 'Request not found or cannot be cancelled']);
            exit;
        }
        
        // Update request to cancelled
        $stmt = $pdo->prepare("UPDATE time_off_requests SET status = 'Cancelled', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$requestId]);
        
        // Log audit
        $stmt = $pdo->prepare("
            INSERT INTO time_off_audit (request_id, action, performed_by, previous_status, new_status, ip_address, user_agent) 
            VALUES (?, 'Cancelled', ?, 'Pending', 'Cancelled', ?, ?)
        ");
        $stmt->execute([
            $requestId,
            $_SESSION['staff_id'],
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        // Send email notification
        sendTimeOffEmail($staff, $request, 'cancelled');
        
        echo json_encode(['success' => true, 'message' => 'Request cancelled successfully']);
        exit;
    }
    
    // Create new request
    $requestType = trim($data['request_type'] ?? '');
    $reason = trim($data['reason'] ?? '');
    $dateFrom = $data['date_from'] ?? '';
    $dateTo = $data['date_to'] ?? '';
    $notes = trim($data['notes'] ?? '');
    $emergencyContact = trim($data['emergency_contact'] ?? '');
    $coverArrangements = trim($data['cover_arrangements'] ?? '');
    
    // Validation
    if (empty($requestType) || empty($reason) || empty($dateFrom) || empty($dateTo)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }
    
    $validTypes = ['Holiday', 'Sick Leave', 'Personal Leave', 'Bereavement', 'Maternity/Paternity', 'Other'];
    if (!in_array($requestType, $validTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request type']);
        exit;
    }
    
    // Validate dates
    $fromDate = new DateTime($dateFrom);
    $toDate = new DateTime($dateTo);
    $today = new DateTime();
    
    if ($fromDate < $today->setTime(0, 0, 0)) {
        echo json_encode(['success' => false, 'message' => 'Start date cannot be in the past']);
        exit;
    }
    
    if ($toDate < $fromDate) {
        echo json_encode(['success' => false, 'message' => 'End date cannot be before start date']);
        exit;
    }
    
    // Calculate business days
    $daysRequested = calculateBusinessDays($fromDate, $toDate);
    
    if ($daysRequested <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid date range']);
        exit;
    }
    
    // Check for overlapping requests
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM time_off_requests 
        WHERE staff_id = ? 
        AND status IN ('Pending', 'Approved')
        AND (
            (date_from <= ? AND date_to >= ?) OR
            (date_from <= ? AND date_to >= ?) OR
            (date_from >= ? AND date_to <= ?)
        )
    ");
    $stmt->execute([
        $_SESSION['staff_id'],
        $dateFrom, $dateFrom,
        $dateTo, $dateTo,
        $dateFrom, $dateTo
    ]);
    
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'You already have a request for overlapping dates']);
        exit;
    }
    
    // Insert request
    $stmt = $pdo->prepare("
        INSERT INTO time_off_requests (
            staff_id, request_type, reason, date_from, date_to, days_requested,
            notes, emergency_contact, cover_arrangements
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['staff_id'],
        $requestType,
        $reason,
        $dateFrom,
        $dateTo,
        $daysRequested,
        $notes,
        $emergencyContact,
        $coverArrangements
    ]);
    
    $requestId = $pdo->lastInsertId();
    
    // Log audit
    $stmt = $pdo->prepare("
        INSERT INTO time_off_audit (request_id, action, performed_by, new_status, ip_address, user_agent) 
        VALUES (?, 'Created', ?, 'Pending', ?, ?)
    ");
    $stmt->execute([
        $requestId,
        $_SESSION['staff_id'],
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    
    // Get the full request for email
    $stmt = $pdo->prepare("SELECT * FROM time_off_requests WHERE id = ?");
    $stmt->execute([$requestId]);
    $request = $stmt->fetch();
    
    // Send email notifications
    sendTimeOffEmail($staff, $request, 'submitted');
    
    echo json_encode([
        'success' => true,
        'message' => 'Time off request submitted successfully',
        'request_id' => $requestId
    ]);
    
} catch (Exception $e) {
    error_log("Time off request error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
}

function calculateBusinessDays($startDate, $endDate) {
    $days = 0;
    $currentDate = clone $startDate;
    
    while ($currentDate <= $endDate) {
        $dayOfWeek = $currentDate->format('w');
        if ($dayOfWeek != 0 && $dayOfWeek != 6) { // Not Sunday (0) or Saturday (6)
            $days++;
        }
        $currentDate->add(new DateInterval('P1D'));
    }
    
    return $days;
}

function sendTimeOffEmail($staff, $request, $action) {
    $staffEmail = $staff['nexi_email'] ?? $staff['private_email'];
    $hrEmail = 'hr@nexihub.uk';
    
    if (!$staffEmail) return;
    
    $subject = '';
    $message = '';
    
    switch ($action) {
        case 'submitted':
            $subject = 'Time Off Request Submitted - ' . $request['request_type'];
            $message = generateSubmittedEmail($staff, $request);
            break;
        case 'approved':
            $subject = 'Time Off Request Approved - ' . $request['request_type'];
            $message = generateApprovedEmail($staff, $request);
            break;
        case 'declined':
            $subject = 'Time Off Request Declined - ' . $request['request_type'];
            $message = generateDeclinedEmail($staff, $request);
            break;
        case 'cancelled':
            $subject = 'Time Off Request Cancelled - ' . $request['request_type'];
            $message = generateCancelledEmail($staff, $request);
            break;
    }
    
    $headers = [
        'From' => 'Nexi Hub HR <noreply@nexihub.uk>',
        'Reply-To' => 'hr@nexihub.uk',
        'Content-Type' => 'text/html; charset=UTF-8',
        'X-Mailer' => 'Nexi Hub Time Off System'
    ];
    
    $headerString = '';
    foreach ($headers as $key => $value) {
        $headerString .= "$key: $value\r\n";
    }
    
    // Send to staff member
    mail($staffEmail, $subject, $message, $headerString);
    
    // Send to HR (if different action context)
    if ($action === 'submitted') {
        $hrSubject = 'New Time Off Request - ' . $staff['full_name'];
        $hrMessage = generateHRNotificationEmail($staff, $request);
        mail($hrEmail, $hrSubject, $hrMessage, $headerString);
    } else {
        mail($hrEmail, $subject . ' - ' . $staff['full_name'], $message, $headerString);
    }
}

function generateSubmittedEmail($staff, $request) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 20px; }
            .footer { background: #e9ecef; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
            .detail { margin: 10px 0; }
            .label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Time Off Request Submitted</h2>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($staff['full_name']) . ",</p>
                <p>Your time off request has been submitted successfully and is now pending approval.</p>
                
                <div class='detail'><span class='label'>Request Type:</span> " . htmlspecialchars($request['request_type']) . "</div>
                <div class='detail'><span class='label'>Dates:</span> " . date('M j, Y', strtotime($request['date_from'])) . " - " . date('M j, Y', strtotime($request['date_to'])) . "</div>
                <div class='detail'><span class='label'>Days Requested:</span> " . $request['days_requested'] . " days</div>
                <div class='detail'><span class='label'>Reason:</span> " . htmlspecialchars($request['reason']) . "</div>
                
                <p>Your request will be reviewed by the HR team. You will receive an email notification once a decision is made.</p>
                <p>You can view the status of your request in the <a href='" . SITE_URL . "/timeoff/'>Time Off Portal</a>.</p>
            </div>
            <div class='footer'>
                <p>Best regards,<br>Nexi Hub HR Team</p>
            </div>
        </div>
    </body>
    </html>";
}

function generateHRNotificationEmail($staff, $request) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 20px; }
            .footer { background: #e9ecef; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
            .detail { margin: 10px 0; }
            .label { font-weight: bold; }
            .action-btn { background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Time Off Request - Action Required</h2>
            </div>
            <div class='content'>
                <p>A new time off request has been submitted and requires your attention.</p>
                
                <div class='detail'><span class='label'>Staff Member:</span> " . htmlspecialchars($staff['full_name']) . "</div>
                <div class='detail'><span class='label'>Department:</span> " . htmlspecialchars($staff['department'] ?? 'N/A') . "</div>
                <div class='detail'><span class='label'>Request Type:</span> " . htmlspecialchars($request['request_type']) . "</div>
                <div class='detail'><span class='label'>Dates:</span> " . date('M j, Y', strtotime($request['date_from'])) . " - " . date('M j, Y', strtotime($request['date_to'])) . "</div>
                <div class='detail'><span class='label'>Days Requested:</span> " . $request['days_requested'] . " days</div>
                <div class='detail'><span class='label'>Reason:</span> " . htmlspecialchars($request['reason']) . "</div>
                
                " . ($request['notes'] ? "<div class='detail'><span class='label'>Notes:</span> " . htmlspecialchars($request['notes']) . "</div>" : "") . "
                " . ($request['emergency_contact'] ? "<div class='detail'><span class='label'>Emergency Contact:</span> " . htmlspecialchars($request['emergency_contact']) . "</div>" : "") . "
                " . ($request['cover_arrangements'] ? "<div class='detail'><span class='label'>Cover Arrangements:</span> " . htmlspecialchars($request['cover_arrangements']) . "</div>" : "") . "
                
                <p style='margin-top: 20px;'>
                    <a href='" . SITE_URL . "/staff/dashboard' class='action-btn'>Review in Dashboard</a>
                </p>
            </div>
            <div class='footer'>
                <p>Nexi Hub HR System</p>
            </div>
        </div>
    </body>
    </html>";
}

function generateApprovedEmail($staff, $request) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 20px; }
            .footer { background: #e9ecef; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
            .detail { margin: 10px 0; }
            .label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>✅ Time Off Request Approved</h2>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($staff['full_name']) . ",</p>
                <p><strong>Great news!</strong> Your time off request has been approved.</p>
                
                <div class='detail'><span class='label'>Request Type:</span> " . htmlspecialchars($request['request_type']) . "</div>
                <div class='detail'><span class='label'>Dates:</span> " . date('M j, Y', strtotime($request['date_from'])) . " - " . date('M j, Y', strtotime($request['date_to'])) . "</div>
                <div class='detail'><span class='label'>Days Approved:</span> " . $request['days_requested'] . " days</div>
                
                " . ($request['decision_notes'] ? "<div class='detail'><span class='label'>Notes:</span> " . htmlspecialchars($request['decision_notes']) . "</div>" : "") . "
                
                <p>Your time off has been deducted from your annual balance. Enjoy your time off!</p>
                <p>View your updated balance in the <a href='" . SITE_URL . "/timeoff/'>Time Off Portal</a>.</p>
            </div>
            <div class='footer'>
                <p>Best regards,<br>Nexi Hub HR Team</p>
            </div>
        </div>
    </body>
    </html>";
}

function generateDeclinedEmail($staff, $request) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 20px; }
            .footer { background: #e9ecef; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
            .detail { margin: 10px 0; }
            .label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>❌ Time Off Request Declined</h2>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($staff['full_name']) . ",</p>
                <p>Unfortunately, your time off request has been declined.</p>
                
                <div class='detail'><span class='label'>Request Type:</span> " . htmlspecialchars($request['request_type']) . "</div>
                <div class='detail'><span class='label'>Dates:</span> " . date('M j, Y', strtotime($request['date_from'])) . " - " . date('M j, Y', strtotime($request['date_to'])) . "</div>
                <div class='detail'><span class='label'>Days Requested:</span> " . $request['days_requested'] . " days</div>
                
                " . ($request['decision_notes'] ? "<div class='detail'><span class='label'>Reason for Decline:</span> " . htmlspecialchars($request['decision_notes']) . "</div>" : "") . "
                
                <p>If you have any questions about this decision, please contact the HR team.</p>
                <p>You can submit a new request in the <a href='" . SITE_URL . "/timeoff/'>Time Off Portal</a>.</p>
            </div>
            <div class='footer'>
                <p>Best regards,<br>Nexi Hub HR Team</p>
            </div>
        </div>
    </body>
    </html>";
}

function generateCancelledEmail($staff, $request) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #6b7280, #4b5563); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
            .content { background: #f8f9fa; padding: 20px; }
            .footer { background: #e9ecef; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
            .detail { margin: 10px 0; }
            .label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Time Off Request Cancelled</h2>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($staff['full_name']) . ",</p>
                <p>Your time off request has been cancelled.</p>
                
                <div class='detail'><span class='label'>Request Type:</span> " . htmlspecialchars($request['request_type']) . "</div>
                <div class='detail'><span class='label'>Dates:</span> " . date('M j, Y', strtotime($request['date_from'])) . " - " . date('M j, Y', strtotime($request['date_to'])) . "</div>
                <div class='detail'><span class='label'>Days:</span> " . $request['days_requested'] . " days</div>
                
                <p>If you need time off for these dates, please submit a new request.</p>
                <p>Visit the <a href='" . SITE_URL . "/timeoff/'>Time Off Portal</a> to make a new request.</p>
            </div>
            <div class='footer'>
                <p>Best regards,<br>Nexi Hub HR Team</p>
            </div>
        </div>
    </body>
    </html>";
}
?>
