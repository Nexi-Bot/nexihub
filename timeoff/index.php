<?php
session_start();
require_once '../config/config.php';

// Check if user is logged in via contract user session
if (!isset($_SESSION['contract_user_id'])) {
    header('Location: login.php');
    exit;
}

// Get staff information
$stmt = $pdo->prepare("SELECT sp.*, cu.email FROM staff_profiles sp 
                       JOIN contract_users cu ON sp.id = cu.staff_id 
                       WHERE cu.id = ?");
$stmt->execute([$_SESSION['contract_user_id']]);
$staff = $stmt->fetch();

if (!$staff) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'submit_request':
                    $start_date = $_POST['start_date'];
                    $end_date = $_POST['end_date'];
                    $type = $_POST['type'];
                    $reason = $_POST['reason'];
                    
                    // Calculate days
                    $start = new DateTime($start_date);
                    $end = new DateTime($end_date);
                    $days_requested = $start->diff($end)->days + 1;
                    
                    // Check if user has enough balance for vacation days
                    if ($type === 'vacation' && $days_requested > $staff['time_off_balance']) {
                        $message = 'Insufficient time off balance. You have ' . $staff['time_off_balance'] . ' days available.';
                        $message_type = 'error';
                    } else {
                        // Insert request
                        $stmt = $pdo->prepare("INSERT INTO time_off_requests (staff_id, start_date, end_date, type, reason, days_requested, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
                        $stmt->execute([$staff['id'], $start_date, $end_date, $type, $reason, $days_requested]);
                        $request_id = $pdo->lastInsertId();
                        
                        // Log action
                        $stmt = $pdo->prepare("INSERT INTO time_off_audit_log (request_id, staff_id, action, details, created_at) VALUES (?, ?, 'submitted', ?, NOW())");
                        $stmt->execute([$request_id, $staff['id'], "Request submitted for $days_requested days ($type)"]);
                        
                        // Send email notification to HR
                        sendNotificationEmail('request_submitted', $staff, [
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'type' => $type,
                            'reason' => $reason,
                            'days_requested' => $days_requested
                        ]);
                        
                        $message = 'Time off request submitted successfully!';
                        $message_type = 'success';
                    }
                    break;
                    
                case 'approve_request':
                    if ($staff['role'] === 'HR' || $staff['role'] === 'Admin') {
                        $request_id = $_POST['request_id'];
                        
                        // Get request details
                        $stmt = $pdo->prepare("SELECT tor.*, sp.full_name, sp.time_off_balance FROM time_off_requests tor JOIN staff_profiles sp ON tor.staff_id = sp.id WHERE tor.id = ?");
                        $stmt->execute([$request_id]);
                        $request = $stmt->fetch();
                        
                        if ($request && $request['status'] === 'pending') {
                            // Approve request
                            $stmt = $pdo->prepare("UPDATE time_off_requests SET status = 'approved', approved_by = ?, approved_at = NOW() WHERE id = ?");
                            $stmt->execute([$staff['id'], $request_id]);
                            
                            // Deduct from balance if vacation
                            if ($request['type'] === 'vacation') {
                                $new_balance = max(0, $request['time_off_balance'] - $request['days_requested']);
                                $stmt = $pdo->prepare("UPDATE staff_profiles SET time_off_balance = ? WHERE id = ?");
                                $stmt->execute([$new_balance, $request['staff_id']]);
                            }
                            
                            // Log action
                            $stmt = $pdo->prepare("INSERT INTO time_off_audit_log (request_id, staff_id, action, details, created_at) VALUES (?, ?, 'approved', ?, NOW())");
                            $stmt->execute([$request_id, $staff['id'], "Request approved by " . $staff['full_name']]);
                            
                            // Send email notification
                            sendNotificationEmail('request_approved', $request, [
                                'approved_by' => $staff['full_name']
                            ]);
                            
                            $message = 'Request approved successfully!';
                            $message_type = 'success';
                        }
                    }
                    break;
                    
                case 'deny_request':
                    if ($staff['role'] === 'HR' || $staff['role'] === 'Admin') {
                        $request_id = $_POST['request_id'];
                        $denial_reason = $_POST['denial_reason'];
                        
                        // Get request details
                        $stmt = $pdo->prepare("SELECT tor.*, sp.full_name FROM time_off_requests tor JOIN staff_profiles sp ON tor.staff_id = sp.id WHERE tor.id = ?");
                        $stmt->execute([$request_id]);
                        $request = $stmt->fetch();
                        
                        if ($request && $request['status'] === 'pending') {
                            // Deny request
                            $stmt = $pdo->prepare("UPDATE time_off_requests SET status = 'denied', approved_by = ?, approved_at = NOW(), denial_reason = ? WHERE id = ?");
                            $stmt->execute([$staff['id'], $denial_reason, $request_id]);
                            
                            // Log action
                            $stmt = $pdo->prepare("INSERT INTO time_off_audit_log (request_id, staff_id, action, details, created_at) VALUES (?, ?, 'denied', ?, NOW())");
                            $stmt->execute([$request_id, $staff['id'], "Request denied by " . $staff['full_name'] . ": " . $denial_reason]);
                            
                            // Send email notification
                            sendNotificationEmail('request_denied', $request, [
                                'denied_by' => $staff['full_name'],
                                'denial_reason' => $denial_reason
                            ]);
                            
                            $message = 'Request denied successfully!';
                            $message_type = 'success';
                        }
                    }
                    break;
            }
        }
    } catch (Exception $e) {
        error_log("Time off error: " . $e->getMessage());
        $message = 'An error occurred. Please try again.';
        $message_type = 'error';
    }
}

// Get user's requests
$stmt = $pdo->prepare("SELECT * FROM time_off_requests WHERE staff_id = ? ORDER BY created_at DESC");
$stmt->execute([$staff['id']]);
$user_requests = $stmt->fetchAll();

// Get all requests for HR/Admin
$all_requests = [];
if ($staff['job_title'] === 'HR' || $staff['job_title'] === 'Admin') {
    $stmt = $pdo->prepare("SELECT tor.*, sp.full_name, sp.nexi_email FROM time_off_requests tor JOIN staff_profiles sp ON tor.staff_id = sp.id ORDER BY tor.created_at DESC");
    $stmt->execute();
    $all_requests = $stmt->fetchAll();
}

// Email notification function
function sendNotificationEmail($type, $data, $extra = []) {
    $to = '';
    $subject = '';
    $message = '';
    
    switch ($type) {
        case 'request_submitted':
            $to = 'hr@nexihub.com'; // HR email
            $subject = 'New Time Off Request - ' . $data['full_name'];
            $message = "A new time off request has been submitted:\n\n";
            $message .= "Employee: " . $data['full_name'] . "\n";
            $message .= "Type: " . ucfirst($extra['type']) . "\n";
            $message .= "Dates: " . $extra['start_date'] . " to " . $extra['end_date'] . "\n";
            $message .= "Days: " . $extra['days_requested'] . "\n";
            $message .= "Reason: " . $extra['reason'] . "\n\n";
            $message .= "Please log in to the Time Off portal to review and approve/deny this request.";
            break;
            
        case 'request_approved':
            $to = $data['nexi_email'] ?? 'staff@nexihub.com';
            $subject = 'Time Off Request Approved';
            $message = "Your time off request has been approved!\n\n";
            $message .= "Type: " . ucfirst($data['type']) . "\n";
            $message .= "Dates: " . $data['start_date'] . " to " . $data['end_date'] . "\n";
            $message .= "Days: " . $data['days_requested'] . "\n";
            $message .= "Approved by: " . $extra['approved_by'] . "\n\n";
            $message .= "Enjoy your time off!";
            break;
            
        case 'request_denied':
            $to = $data['nexi_email'] ?? 'staff@nexihub.com';
            $subject = 'Time Off Request Denied';
            $message = "Your time off request has been denied.\n\n";
            $message .= "Type: " . ucfirst($data['type']) . "\n";
            $message .= "Dates: " . $data['start_date'] . " to " . $data['end_date'] . "\n";
            $message .= "Days: " . $data['days_requested'] . "\n";
            $message .= "Denied by: " . $extra['denied_by'] . "\n";
            $message .= "Reason: " . $extra['denial_reason'] . "\n\n";
            $message .= "Please contact HR if you have any questions.";
            break;
    }
    
    // In a real application, you would send actual emails here
    // For now, we'll just log it
    error_log("Email notification [$type]: To: $to, Subject: $subject");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Off Portal - NexiHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e64f21 0%, #ff6b35 100%);
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
            background: linear-gradient(135deg, #e64f21 0%, #ff6b35 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .user-info {
            background: rgba(255,255,255,0.1);
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content {
            padding: 30px;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #eee;
            margin-bottom: 30px;
        }

        .tab {
            padding: 15px 25px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            font-weight: 500;
            color: #666;
            transition: all 0.3s ease;
        }

        .tab.active {
            color: #e64f21;
            border-bottom: 3px solid #e64f21;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #e64f21;
        }

        .btn {
            background: linear-gradient(135deg, #e64f21 0%, #ff6b35 100%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn.btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .btn.btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }

        .btn.btn-sm {
            padding: 8px 15px;
            font-size: 14px;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .requests-table th,
        .requests-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .requests-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-denied {
            background: #f8d7da;
            color: #721c24;
        }

        .balance-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .balance-number {
            font-size: 3rem;
            font-weight: bold;
            color: #1976d2;
            margin: 10px 0;
        }

        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .col {
            flex: 1;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            text-decoration: none;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Time Off Portal</h1>
            <div class="user-info">
                <div>
                    <strong><?php echo htmlspecialchars($staff['full_name']); ?></strong>
                    <span>(<?php echo htmlspecialchars($staff['job_title']); ?>)</span>
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="content">
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="balance-info">
                <h3>Your Time Off Balance</h3>
                <div class="balance-number"><?php echo $staff['time_off_balance']; ?></div>
                <p>Days Available</p>
            </div>

            <div class="tabs">
                <button class="tab active" onclick="showTab('submit')">Submit Request</button>
                <button class="tab" onclick="showTab('my-requests')">My Requests</button>
                <?php if ($staff['job_title'] === 'HR' || $staff['job_title'] === 'Admin'): ?>
                    <button class="tab" onclick="showTab('manage')">Manage Requests</button>
                <?php endif; ?>
            </div>

            <!-- Submit Request Tab -->
            <div id="submit" class="tab-content active">
                <h3>Submit Time Off Request</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="submit_request">
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" name="type" required>
                            <option value="">Select type...</option>
                            <option value="vacation">Vacation</option>
                            <option value="sick">Sick Leave</option>
                            <option value="personal">Personal</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea id="reason" name="reason" rows="4" placeholder="Please provide details about your request..." required></textarea>
                    </div>

                    <button type="submit" class="btn">Submit Request</button>
                </form>
            </div>

            <!-- My Requests Tab -->
            <div id="my-requests" class="tab-content">
                <h3>My Time Off Requests</h3>
                <?php if (empty($user_requests)): ?>
                    <p>No requests found.</p>
                <?php else: ?>
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_requests as $request): ?>
                                <tr>
                                    <td><?php echo ucfirst(htmlspecialchars($request['type'])); ?></td>
                                    <td><?php echo htmlspecialchars($request['start_date']) . ' to ' . htmlspecialchars($request['end_date']); ?></td>
                                    <td><?php echo $request['days_requested']; ?></td>
                                    <td><span class="status-badge status-<?php echo $request['status']; ?>"><?php echo ucfirst($request['status']); ?></span></td>
                                    <td><?php echo date('M j, Y', strtotime($request['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($request['reason']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Manage Requests Tab (HR/Admin only) -->
            <?php if ($staff['job_title'] === 'HR' || $staff['job_title'] === 'Admin'): ?>
                <div id="manage" class="tab-content">
                    <h3>Manage All Requests</h3>
                    <?php if (empty($all_requests)): ?>
                        <p>No requests found.</p>
                    <?php else: ?>
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_requests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['full_name']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($request['type'])); ?></td>
                                        <td><?php echo htmlspecialchars($request['start_date']) . ' to ' . htmlspecialchars($request['end_date']); ?></td>
                                        <td><?php echo $request['days_requested']; ?></td>
                                        <td><span class="status-badge status-<?php echo $request['status']; ?>"><?php echo ucfirst($request['status']); ?></span></td>
                                        <td><?php echo date('M j, Y', strtotime($request['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars($request['reason']); ?></td>
                                        <td>
                                            <?php if ($request['status'] === 'pending'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="action" value="approve_request">
                                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                </form>
                                                <button class="btn btn-danger btn-sm" onclick="showDenyModal(<?php echo $request['id']; ?>)">Deny</button>
                                            <?php else: ?>
                                                <span class="status-badge status-<?php echo $request['status']; ?>">
                                                    <?php echo ucfirst($request['status']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Deny Modal -->
    <div id="denyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDenyModal()">&times;</span>
            <h3>Deny Request</h3>
            <form method="POST" id="denyForm">
                <input type="hidden" name="action" value="deny_request">
                <input type="hidden" name="request_id" id="denyRequestId">
                
                <div class="form-group">
                    <label for="denial_reason">Reason for Denial</label>
                    <textarea id="denial_reason" name="denial_reason" rows="4" placeholder="Please provide a reason for denying this request..." required></textarea>
                </div>
                
                <button type="submit" class="btn btn-danger">Deny Request</button>
                <button type="button" class="btn" onclick="closeDenyModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }

        function showDenyModal(requestId) {
            document.getElementById('denyRequestId').value = requestId;
            document.getElementById('denyModal').style.display = 'block';
        }

        function closeDenyModal() {
            document.getElementById('denyModal').style.display = 'none';
            document.getElementById('denial_reason').value = '';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('denyModal');
            if (event.target == modal) {
                closeDenyModal();
            }
        }

        // Auto-calculate end date based on start date and type
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDateInput = document.getElementById('end_date');
            endDateInput.min = this.value;
            
            // If end date is before start date, reset it
            if (endDateInput.value && new Date(endDateInput.value) < startDate) {
                endDateInput.value = this.value;
            }
        });
    </script>
</body>
</html>
