<?php
// Include config first (which handles session initialization)
require_once __DIR__ . '/../config/config.php';

// FORCE FRESH DATA - PREVENT ALL CACHING
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('ETag: "' . md5(time()) . '"');

// Prevent session timeout for contract system
$_SESSION['LAST_ACTIVITY'] = time();

// Check if user is logged in
if (!isset($_SESSION['contract_user_id'])) {
    header('Location: index.php');
    exit;
}

// Database connection
try {
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db_path = realpath(__DIR__ . "/../database/nexihub.db");
        if (!$db_path || !file_exists($db_path)) {
            throw new Exception("Database file not found at: " . __DIR__ . "/../database/nexihub.db");
        }
        $db = new PDO("sqlite:" . $db_path);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    error_log("Contract Portal Database Error: " . $e->getMessage());
    die("Database connection failed. Please check the system configuration.");
} catch (Exception $e) {
    error_log("Contract Portal Error: " . $e->getMessage());
    die("System error: " . $e->getMessage());
}

// Handle contract signing
if ($_POST['action'] ?? '' === 'sign_contract') {
    $template_id = $_POST['template_id'] ?? '';
    $signature = $_POST['signature'] ?? '';
    $guardian_signature = $_POST['guardian_signature'] ?? '';
    $staff_id = $_SESSION['contract_staff_id'];
    
    if ($template_id && $signature && $staff_id) {
        try {
            // Get staff profile data
            $stmt = $db->prepare("SELECT full_name, job_title, date_of_birth FROM staff_profiles WHERE id = ?");
            $stmt->execute([$staff_id]);
            $staff_profile = $stmt->fetch();
            
            if ($staff_profile) {
                // Calculate age
                $dob = new DateTime($staff_profile['date_of_birth']);
                $today = new DateTime();
                $age = $today->diff($dob)->y;
                $is_under_17 = $age <= 16;
                
                // Check if guardian info is required and provided
                if ($is_under_17) {
                    $guardian_name = $_POST['guardian_name'] ?? '';
                    $guardian_email = $_POST['guardian_email'] ?? '';
                    
                    if (!$guardian_name || !$guardian_email || !$guardian_signature) {
                        $error = "Guardian information and signature are required for signers 16 years or younger.";
                    }
                }
                
                if (!isset($error)) {
                    // Check if already signed
                    $stmt = $db->prepare("SELECT id FROM staff_contracts WHERE staff_id = ? AND template_id = ? AND is_signed = 1");
                    $stmt->execute([$staff_id, $template_id]);
                    
                    if (!$stmt->fetch()) {
                        $signed_timestamp = date('Y-m-d H:i:s');
                        
                        // Update the existing contract with signature data
                        $stmt = $db->prepare("
                            UPDATE staff_contracts SET
                            signed_at = ?,
                            signature_data = ?,
                            is_signed = 1,
                            signer_full_name = ?,
                            signer_position = ?,
                            signer_date_of_birth = ?,
                            is_under_17 = ?,
                            guardian_full_name = ?,
                            guardian_email = ?,
                            guardian_signature_data = ?,
                            signed_timestamp = ?
                            WHERE staff_id = ? AND template_id = ?
                        ");
                        
                        $stmt->execute([
                            $signed_timestamp, 
                            $signature,
                            $staff_profile['full_name'],
                            $staff_profile['job_title'],
                            $staff_profile['date_of_birth'],
                            $is_under_17 ? 1 : 0,
                            $is_under_17 ? ($_POST['guardian_name'] ?? null) : null,
                            $is_under_17 ? ($_POST['guardian_email'] ?? null) : null,
                            $is_under_17 ? $guardian_signature : null,
                            $signed_timestamp,
                            $staff_id, 
                            $template_id
                        ]);
                        
                        // Get contract name for email notification
                        $stmt = $db->prepare("SELECT name FROM contract_templates WHERE id = ?");
                        $stmt->execute([$template_id]);
                        $contract_name = $stmt->fetchColumn();
                        
                        // Send email notifications
                        try {
                            require_once __DIR__ . '/../includes/ContractEmailNotifier.php';
                            $emailNotifier = new ContractEmailNotifier();
                            $emailSent = $emailNotifier->sendContractSignedNotification($staff_id, $contract_name, $template_id);
                            
                            if ($emailSent) {
                                $success = "Contract signed successfully! Email notifications have been sent.";
                            } else {
                                $success = "Contract signed successfully! (Note: Email notifications could not be sent - please check email configuration)";
                            }
                        } catch (Exception $e) {
                            error_log("Email notification error: " . $e->getMessage());
                            $success = "Contract signed successfully! (Note: Email notifications could not be sent)";
                        }
                    } else {
                        $error = "This contract has already been signed.";
                    }
                }
            } else {
                $error = "Staff profile not found.";
            }
        } catch (PDOException $e) {
            $error = "Error signing contract: " . $e->getMessage();
        }
    } else {
        $error = "Please provide your signature.";
    }
}

// Handle logout
if ($_GET['action'] ?? '' === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Get user profile data for auto-filling forms
$user_profile = null;
try {
    $stmt = $db->prepare("SELECT full_name, job_title, date_of_birth FROM staff_profiles WHERE id = ?");
    $stmt->execute([$_SESSION['contract_staff_id']]);
    $user_profile = $stmt->fetch();
    
    if ($user_profile && $user_profile['date_of_birth']) {
        $dob = new DateTime($user_profile['date_of_birth']);
        $today = new DateTime();
        $user_profile['age'] = $today->diff($dob)->y;
        $user_profile['is_under_17'] = $user_profile['age'] <= 16;
    }
} catch (PDOException $e) {
    // Handle error silently for now
}

// Get available contracts - only show contracts assigned to this staff member
// Group by template_id to avoid duplicates and show only the latest record for each contract
$contracts = [];
try {
    $stmt = $db->prepare("
        SELECT ct.*,
               sc.id as contract_record_id,
               sc.is_signed,
               sc.signed_at, sc.signature_data,
               sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
               sc.is_under_17, sc.guardian_full_name, sc.guardian_email,
               sc.guardian_signature_data, sc.signed_timestamp,
               sp.shareholder_percentage, sp.is_shareholder
        FROM contract_templates ct
        INNER JOIN staff_contracts sc ON ct.id = sc.template_id 
        LEFT JOIN staff_profiles sp ON sc.staff_id = sp.id
        WHERE sc.staff_id = ?
        ORDER BY ct.name, sc.is_signed ASC, sc.id DESC
    ");
    $stmt->execute([$_SESSION['contract_staff_id'] ?? 0]);
    $all_contracts = $stmt->fetchAll();
    
    // Group contracts by template_id to avoid showing duplicates
    $contract_groups = [];
    foreach ($all_contracts as $contract) {
        $template_id = $contract['id'];
        
        // Only keep the first record for each template (signed status takes priority due to ORDER BY)
        if (!isset($contract_groups[$template_id])) {
            $contract_groups[$template_id] = $contract;
        }
    }
    
    $contracts = array_values($contract_groups);
} catch (PDOException $e) {
    $error = "Error fetching contracts: " . $e->getMessage();
}

// Handle AJAX requests for contract details
if ($_GET['action'] ?? '' === 'get_contract') {
    header('Content-Type: application/json');
    
    $contract_id = $_GET['contract_id'] ?? '';
    
    if (!$contract_id) {
        echo json_encode(['success' => false, 'message' => 'Contract ID required']);
        exit;
    }
    
    try {
        // Get contract details with signature information
        $stmt = $db->prepare("
            SELECT ct.name, ct.content, ct.type,
                   sc.is_signed, sc.signed_at, sc.signature_data,
                   sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
                   sc.is_under_17, sc.guardian_full_name, sc.guardian_email,
                   sc.guardian_signature_data, sc.signed_timestamp,
                   sp.full_name as staff_name, sp.job_title as staff_position,
                   sp.date_of_birth as staff_dob, sp.shareholder_percentage, sp.is_shareholder
            FROM contract_templates ct
            JOIN staff_contracts sc ON ct.id = sc.template_id 
            JOIN staff_profiles sp ON sc.staff_id = sp.id
            WHERE sc.id = ? AND sc.is_signed = 1
        ");
        $stmt->execute([$contract_id]);
        $contract = $stmt->fetch();
        
        if ($contract) {
            echo json_encode(['success' => true, 'contract' => $contract]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Contract not found or not signed']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    
    exit;
}

$page_title = "Nexi HR Portal - Dashboard";
$page_description = "Review and sign your employment contracts";
include __DIR__ . '/../includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Contract Dashboard</h1>
            <p class="hero-subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['contract_user_email']); ?></p>
            <p class="hero-description">
                Review and digitally sign your employment contracts, NDAs, and company policy documents.
            </p>
            <div class="hero-actions">
                <a href="?action=logout" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16,17V14H9V10H16V7L21,12L16,17M14,2A2,2 0 0,1 16,4V6H14V4H5V20H14V18H16V20A2,2 0 0,1 14,22H5A2,2 0 0,1 3,20V4A2,2 0 0,1 5,2H14Z"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</section>

<?php if (isset($success)): ?>
<section class="content-section">
    <div class="container">
        <div class="success-message">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.41,10.09L6,11.5L11,16.5Z"/>
            </svg>
            <div>
                <h3>Success!</h3>
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (isset($error)): ?>
<section class="content-section">
    <div class="container">
        <div class="error-message">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,7A1,1 0 0,0 11,8V12A1,1 0 0,0 12,13A1,1 0 0,0 13,12V8A1,1 0 0,0 12,7M12,17A1,1 0 0,0 13,16A1,1 0 0,0 12,15A1,1 0 0,0 11,16A1,1 0 0,0 12,17Z"/>
            </svg>
            <div>
                <h3>Error</h3>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="content-section">
    <div class="container">
        <?php if (!empty($contracts)): ?>
            <div class="contracts-overview">
                <h2>Your Contracts</h2>
                <p class="section-description">Review the details of each contract and provide your digital signature to complete the process.</p>
            </div>
            
            <div class="products-grid">
                <?php foreach ($contracts as $contract): ?>
                    <div class="product-card contract-card" data-contract-id="<?php echo $contract['id']; ?>">
                        <div class="product-icon contract-icon-<?php echo strtolower($contract['type']); ?>">
                            <?php 
                            switch(strtolower($contract['type'])) {
                                case 'shareholder':
                                    echo '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M16,6L18.29,8.29L13.41,13.17L9.41,9.17L2,16.59L3.41,18L9.41,12L13.41,16L19.71,9.71L22,12V6H16Z"/></svg>';
                                    break;
                                case 'nda':
                                    echo '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/></svg>';
                                    break;
                                case 'conduct':
                                    echo '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A1,1 0 0,1 11,16A1,1 0 0,1 12,15A1,1 0 0,1 13,16A1,1 0 0,1 12,17M12,7A3,3 0 0,1 15,10C15,11.31 14.17,12.42 13.06,12.81C12.67,12.95 12.5,13.34 12.5,13.75V14H11.5V13.75C11.5,12.9 12.1,12.23 12.94,12.06C13.63,11.92 14,11.27 14,10.5C14,9.67 13.33,9 12.5,9S11,9.67 11,10.5H10A2.5,2.5 0 0,1 12.5,8A2.5,2.5 0 0,1 15,10.5Z"/></svg>';
                                    break;
                                case 'policies':
                                    echo '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>';
                                    break;
                                default:
                                    echo '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>';
                                    break;
                            }
                            ?>
                        </div>
                        <h3 class="product-title">
                            <?php echo htmlspecialchars($contract['name']); ?>
                            <?php if (strtolower($contract['type']) === 'shareholder' && $contract['is_shareholder'] && $contract['shareholder_percentage']): ?>
                                <span class="shareholder-percentage">(<?php echo $contract['shareholder_percentage']; ?>% Share)</span>
                            <?php endif; ?>
                        </h3>
                        
                        <?php if ($contract['is_signed']): ?>
                            <div class="contract-status signed">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.41,10.09L6,11.5L11,16.5Z"/>
                                </svg>
                                <span>Signed on <?php echo date('F j, Y g:i A', strtotime($contract['signed_at'])); ?></span>
                            </div>
                            
                            <p class="product-description">
                                Contract successfully signed and stored securely. You can review the signed document below.
                            </p>
                            
                            <button onclick="viewContract(<?php echo $contract['contract_record_id']; ?>)" class="product-link">
                                View Signed Contract →
                            </button>
                        <?php else: ?>
                            <div class="contract-status pending">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,7A1,1 0 0,0 11,8V12A1,1 0 0,0 12,13A1,1 0 0,0 13,12V8A1,1 0 0,0 12,7M12,17A1,1 0 0,0 13,16A1,1 0 0,0 12,15A1,1 0 0,0 11,16A1,1 0 0,0 12,17Z"/>
                                </svg>
                                <span>Signature Required</span>
                            </div>
                            
                            <p class="product-description">
                                <?php echo nl2br(htmlspecialchars(substr($contract['content'], 0, 120))); ?>...
                            </p>
                            
                            <button onclick="openSigningModal(<?php echo $contract['id']; ?>)" class="product-link">
                                Review & Sign Contract →
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-contracts">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                </div>
                <h3>No Contracts Available</h3>
                <p>There are currently no contracts assigned to your account. Please contact HR if you believe this is an error.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Contract Signing Modal -->
<div id="signingModal" class="modal">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h2 id="modalContractTitle">Contract Details</h2>
            <button class="modal-close" onclick="closeSigningModal()">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                </svg>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="contract-content" id="modalContractContent">
                <!-- Contract content will be populated here -->
            </div>
            
            <div class="signature-section">
                <h3>Digital Signature</h3>
                <p>By signing below, you acknowledge that you have read, understood, and agree to the terms of this contract.</p>
                
                <form method="POST" onsubmit="return submitSignature(this)" id="signatureForm">
                    <input type="hidden" name="action" value="sign_contract">
                    <input type="hidden" name="template_id" id="modalTemplateId">
                    <input type="hidden" name="signature" id="modalSignature">
                    <input type="hidden" name="guardian_signature" id="modalGuardianSignature">
                    
                    <!-- Signer Information -->
                    <div class="signer-info-section">
                        <h4>Signer Information</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Full Legal Name</label>
                                <input type="text" id="signerFullName" value="<?php echo htmlspecialchars($user_profile['full_name'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" id="signerPosition" value="<?php echo htmlspecialchars($user_profile['job_title'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" id="signerDOB" value="<?php echo $user_profile['date_of_birth'] ?? ''; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Guardian Information (shown only if under 17) -->
                    <div class="guardian-info-section" id="guardianSection" style="display: none;">
                        <h4>Parent/Guardian Information</h4>
                        <p class="info-text">As you are 16 years or younger, parent/guardian consent and signature are required.</p>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Parent/Guardian Full Name *</label>
                                <input type="text" name="guardian_name" id="guardianName" required>
                            </div>
                            <div class="form-group">
                                <label>Parent/Guardian Email *</label>
                                <input type="email" name="guardian_email" id="guardianEmail" required>
                            </div>
                        </div>
                        
                        <div class="signature-pad-container">
                            <label>Parent/Guardian Signature *</label>
                            <canvas id="guardianSignaturePad" width="600" height="150"></canvas>
                            <div class="signature-instructions">
                                Parent/Guardian: Draw your signature above using your mouse or touch screen
                            </div>
                            <button type="button" onclick="clearGuardianSignature()" class="btn btn-secondary btn-small">
                                Clear Guardian Signature
                            </button>
                        </div>
                    </div>
                    
                    <!-- Main Signature -->
                    <div class="main-signature-section">
                        <h4>Your Signature</h4>
                        <div class="signature-pad-container">
                            <canvas id="signaturePad" width="600" height="200"></canvas>
                            <div class="signature-instructions">
                                Draw your signature above using your mouse or touch screen
                            </div>
                        </div>
                        
                        <div class="signature-controls">
                            <button type="button" onclick="clearSignature()" class="btn btn-secondary">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                                </svg>
                                Clear Signature
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14.6,16.6L19.2,12L14.6,7.4L13.2,8.8L15.67,11.25H5V12.75H15.67L13.2,15.2L14.6,16.6Z"/>
                                </svg>
                                Sign Contract
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Contract View Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h2 id="viewModalTitle">Signed Contract</h2>
            <div class="modal-header-actions">
                <button onclick="downloadSignedPDF()" class="btn btn-secondary btn-small">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Download PDF
                </button>
                <button class="modal-close" onclick="closeViewModal()">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="modal-body">
            <div class="contract-content" id="viewModalContent">
                <!-- Contract content will be populated here -->
            </div>
            
            <div class="signature-details" id="signatureDetails">
                <!-- Signature details will be populated here -->
            </div>
        </div>
    </div>
</div>

<style>
.contracts-overview {
    text-align: center;
    margin-bottom: 3rem;
}

.contracts-overview h2 {
    color: var(--text-primary);
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
}

.section-description {
    color: var(--text-secondary);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.contract-card {
    position: relative;
}

.contract-icon-shareholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.contract-icon-nda {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.contract-icon-conduct {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.contract-icon-policies {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.contract-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
}

.contract-status.signed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.contract-status.pending {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.contract-status svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.shareholder-percentage {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--secondary-color);
    margin-top: 0.25rem;
}

.empty-contracts {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-secondary);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 2rem;
    background: var(--background-light);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
}

.empty-icon svg {
    width: 40px;
    height: 40px;
}

.empty-contracts h3 {
    color: var(--text-primary);
    font-size: 1.5rem;
    margin: 0 0 1rem 0;
}

.empty-contracts p {
    font-size: 1.1rem;
    max-width: 500px;
    margin: 0 auto;
    line-height: 1.6;
}

.success-message,
.error-message {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
}

.success-message {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.error-message {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.success-message svg,
.error-message svg {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}

.success-message h3,
.error-message h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.success-message p,
.error-message p {
    margin: 0;
    font-size: 0.95rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
}

.modal-content {
    background: var(--background-light);
    margin: 2% auto;
    border-radius: 24px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid var(--border-color);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.large-modal {
    max-width: 1200px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--background-dark);
    border-radius: 24px 24px 0 0;
}

.modal-header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.modal-header h2 {
    color: var(--text-primary);
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.modal-close {
    width: 40px;
    height: 40px;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.modal-close svg {
    width: 20px;
    height: 20px;
}

.modal-body {
    padding: 2rem;
}

.contract-content {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    line-height: 1.8;
    color: var(--text-primary);
    max-height: 300px;
    overflow-y: auto;
}

.signature-section h3 {
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.signature-section p {
    color: var(--text-secondary);
    margin: 0 0 2rem 0;
    line-height: 1.6;
}

.signature-pad-container {
    position: relative;
    margin-bottom: 2rem;
}

#signaturePad {
    border: 2px solid var(--border-color);
    border-radius: 16px;
    background: white;
    cursor: crosshair;
    width: 100%;
    height: 200px;
}

.signature-instructions {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #999;
    font-size: 0.9rem;
    pointer-events: none;
    text-align: center;
}

.signature-controls {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(230, 79, 33, 0.3);
}

.btn-secondary {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: var(--border-color);
}

.btn svg {
    width: 16px;
    height: 16px;
}

.signer-info-section,
.guardian-info-section,
.main-signature-section {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.signer-info-section h4,
.guardian-info-section h4,
.main-signature-section h4 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-group input {
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    background: var(--background-light);
    color: var(--text-primary);
    font-size: 0.9rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
}

.form-group input[readonly] {
    background: var(--background-dark);
    cursor: not-allowed;
    opacity: 0.8;
}

.info-text {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(59, 130, 246, 0.2);
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.guardian-info-section {
    border: 2px solid var(--secondary-color);
    background: linear-gradient(135deg, rgba(230, 79, 33, 0.05), rgba(243, 131, 91, 0.05));
}

#guardianSignaturePad {
    border: 2px solid var(--secondary-color);
    border-radius: 16px;
    background: white;
    cursor: crosshair;
    width: 100%;
    height: 150px;
    margin-top: 0.5rem;
}

.btn-small {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    margin-top: 0.5rem;
}

.signature-details {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    margin-top: 2rem;
}

.signature-details h3 {
    color: var(--text-primary);
    margin: 0 0 2rem 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.signature-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.signature-info-card {
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
}

.signature-info-card h4 {
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.info-value {
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 600;
}

.signature-display {
    padding: 1rem;
    margin-top: 1rem;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.signature-display img {
    max-width: 100%;
    max-height: 120px;
    object-fit: contain;
}
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.signature-box {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
}

.signature-box h5 {
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.signature-image {
    max-width: 100%;
    height: auto;
    border: 1px solid var(--border-color);
    border-radius: 8px;
}

.guardian-signature-box {
    border-color: var(--secondary-color);
    background: linear-gradient(135deg, rgba(230, 79, 33, 0.05), rgba(243, 131, 91, 0.05));
}
</style>

<script>
const contracts = <?php echo json_encode($contracts); ?>;
const userProfile = <?php echo json_encode($user_profile); ?>;
let isDrawing = false;
let guardianIsDrawing = false;
let signaturePad;
let guardianSignaturePad;

function initSignaturePad() {
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    
    // Set canvas size
    canvas.width = canvas.offsetWidth;
    canvas.height = 200;
    
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    
    signaturePad = {
        canvas: canvas,
        ctx: ctx,
        drawing: false,
        isEmpty: true
    };
    
    // Mouse events
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Touch events
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', stopDrawing);
    
    function startDrawing(e) {
        isDrawing = true;
        signaturePad.isEmpty = false;
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.beginPath();
        ctx.moveTo(x, y);
    }
    
    function draw(e) {
        if (!isDrawing) return;
        
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.lineTo(x, y);
        ctx.stroke();
    }
    
    function stopDrawing() {
        isDrawing = false;
    }
    
    function handleTouch(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                        e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }
    
    // Initialize guardian signature pad if visible
    const guardianCanvas = document.getElementById('guardianSignaturePad');
    if (guardianCanvas && document.getElementById('guardianSection').style.display !== 'none') {
        initGuardianSignaturePad();
    }
}

function initGuardianSignaturePad() {
    const canvas = document.getElementById('guardianSignaturePad');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    // Set canvas size
    canvas.width = canvas.offsetWidth;
    canvas.height = 150;
    
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    
    guardianSignaturePad = {
        canvas: canvas,
        ctx: ctx,
        drawing: false,
        isEmpty: true
    };
    
    // Mouse events
    canvas.addEventListener('mousedown', startGuardianDrawing);
    canvas.addEventListener('mousemove', drawGuardian);
    canvas.addEventListener('mouseup', stopGuardianDrawing);
    canvas.addEventListener('mouseout', stopGuardianDrawing);
    
    // Touch events
    canvas.addEventListener('touchstart', handleGuardianTouch);
    canvas.addEventListener('touchmove', handleGuardianTouch);
    canvas.addEventListener('touchend', stopGuardianDrawing);
    
    function startGuardianDrawing(e) {
        guardianIsDrawing = true;
        guardianSignaturePad.isEmpty = false;
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.beginPath();
        ctx.moveTo(x, y);
    }
    
    function drawGuardian(e) {
        if (!guardianIsDrawing) return;
        
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.lineTo(x, y);
        ctx.stroke();
    }
    
    function stopGuardianDrawing() {
        guardianIsDrawing = false;
    }
    
    function handleGuardianTouch(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                        e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }
}

function clearGuardianSignature() {
    if (guardianSignaturePad) {
        guardianSignaturePad.ctx.clearRect(0, 0, guardianSignaturePad.canvas.width, guardianSignaturePad.canvas.height);
        guardianSignaturePad.isEmpty = true;
    }
}

function openSigningModal(contractId) {
    console.log('Opening signing modal for contract:', contractId);
    console.log('Available contracts:', contracts);
    console.log('User profile:', userProfile);
    
    const contract = contracts.find(c => c.id == contractId);
    if (!contract) {
        console.error('Contract not found with ID:', contractId);
        alert('Contract not found. Please refresh the page and try again.');
        return;
    }
    
    console.log('Found contract:', contract);
    
    document.getElementById('modalContractTitle').textContent = contract.name;
    document.getElementById('modalTemplateId').value = contractId;
    
    // Clean and format contract content for display
    let content = contract.content;
    
    // Remove markdown symbols and format properly
    content = content.replace(/^#+\s*/gm, ''); // Remove markdown headers
    content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); // Convert bold markdown
    content = content.replace(/\*(.*?)\*/g, '<em>$1</em>'); // Convert italic markdown
    content = content.replace(/•/g, '•'); // Fix bullet points
    
    // Convert to proper HTML formatting
    const lines = content.split('\n');
    let formattedContent = '';
    
    for (let line of lines) {
        line = line.trim();
        if (!line) continue;
        
        // Check if it's a section header
        if (line.match(/^(ARTICLE|SECTION|\d+\.\d+|\d+\.)\s+/) || 
            (line.length < 100 && line === line.toUpperCase() && !line.match(/[.!?]$/))) {
            formattedContent += `<h4 style="color: var(--primary-color); margin: 1.5rem 0 0.5rem 0; font-size: 1.1rem;">${line}</h4>`;
        } else {
            formattedContent += `<p style="margin: 0.8rem 0; line-height: 1.6;">${line}</p>`;
        }
    }
    
    document.getElementById('modalContractContent').innerHTML = formattedContent;
    
    // Show/hide guardian section based on age
    const guardianSection = document.getElementById('guardianSection');
    if (userProfile && userProfile.is_under_17) {
        guardianSection.style.display = 'block';
        // Make guardian fields required
        document.getElementById('guardianName').required = true;
        document.getElementById('guardianEmail').required = true;
    } else {
        guardianSection.style.display = 'none';
        // Remove required attribute
        document.getElementById('guardianName').required = false;
        document.getElementById('guardianEmail').required = false;
    }
    
    document.getElementById('signingModal').style.display = 'block';
    
    // Initialize signature pad after modal is shown
    setTimeout(() => {
        initSignaturePad();
        if (userProfile && userProfile.is_under_17) {
            setTimeout(initGuardianSignaturePad, 100);
        }
    }, 100);
}

function closeSigningModal() {
    document.getElementById('signingModal').style.display = 'none';
}

function viewContract(contractId) {
    console.log('Viewing contract:', contractId);
    console.log('Available contracts:', contracts);
    
    const contract = contracts.find(c => c.contract_record_id == contractId);
    if (!contract || !contract.is_signed) {
        console.error('Contract not found or not signed:', contractId, contract);
        alert('Signed contract not found. Please refresh the page and try again.');
        return;
    }
    
    console.log('Found signed contract:', contract);
    
    // Set current viewing contract ID for PDF download
    window.currentViewingContractId = contract.id; // Use template ID for PDF download
    
    document.getElementById('viewModalTitle').textContent = contract.name;
    
    // Clean and format contract content for display
    let content = contract.content;
    
    // Remove markdown symbols and format properly
    content = content.replace(/^#+\s*/gm, ''); // Remove markdown headers
    content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); // Convert bold markdown
    content = content.replace(/\*(.*?)\*/g, '<em>$1</em>'); // Convert italic markdown
    content = content.replace(/•/g, '•'); // Fix bullet points
    
    // Convert to proper HTML formatting
    const lines = content.split('\n');
    let formattedContent = '';
    
    for (let line of lines) {
        line = line.trim();
        if (!line) continue;
        
        // Check if it's a section header
        if (line.match(/^(ARTICLE|SECTION|\d+\.\d+|\d+\.)\s+/) || 
            (line.length < 100 && line === line.toUpperCase() && !line.match(/[.!?]$/))) {
            formattedContent += `<h4 style="color: var(--primary-color); margin: 1.5rem 0 0.5rem 0; font-size: 1.1rem;">${line}</h4>`;
        } else {
            formattedContent += `<p style="margin: 0.8rem 0; line-height: 1.6;">${line}</p>`;
        }
    }
    
    document.getElementById('viewModalContent').innerHTML = formattedContent;
    
    // Display signature details
    displaySignatureDetails(contract);
    
    document.getElementById('viewModal').style.display = 'block';
}

function displaySignatureDetails(contract) {
    const signatureDetails = document.getElementById('signatureDetails');
    
    let detailsHTML = '<h3>Signature Details</h3>';
    
    // Employee signature section
    detailsHTML += `
        <div class="signature-info-grid">
            <div class="signature-info-card">
                <h4>Employee Information</h4>
                <div class="info-row">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value">${contract.signer_full_name || contract.staff_name || 'Not recorded'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Position:</span>
                    <span class="info-value">${contract.signer_position || contract.staff_position || 'Not recorded'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Birth:</span>
                    <span class="info-value">${(contract.signer_date_of_birth || contract.staff_dob) ? new Date(contract.signer_date_of_birth || contract.staff_dob).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not recorded'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Age at Signing:</span>
                    <span class="info-value">${contract.is_under_17 ? 'Under 17 (Guardian consent required)' : '17 or older'}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Signed:</span>
                    <span class="info-value">${new Date(contract.signed_timestamp || contract.signed_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit' })}</span>
                </div>
            </div>
            
            <div class="signature-info-card">
                <h4>Employee Signature</h4>
                <div class="signature-display">
                    ${contract.signature_data ? `<img src="${contract.signature_data}" alt="Employee Signature" style="max-width: 100%; height: auto; border: 1px solid var(--border-color); border-radius: 8px; background: white; padding: 10px;">` : '<p style="color: var(--text-secondary); font-style: italic;">No signature data available</p>'}
                </div>
            </div>
        </div>
        
        ${contract.is_under_17 && contract.guardian_full_name ? `
        <div class="signature-info-grid">
            <div class="signature-info-card">
                <h4>Guardian Information</h4>
                <div class="info-row">
                    <span class="info-label">Guardian Name:</span>
                    <span class="info-value">${contract.guardian_full_name}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Guardian Email:</span>
                    <span class="info-value">${contract.guardian_email}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Consent Required:</span>
                    <span class="info-value">Yes (Employee under 17)</span>
                </div>
            </div>
            
            <div class="signature-info-card">
                <h4>Guardian Signature</h4>
                <div class="signature-display">
                    ${contract.guardian_signature_data ? `<img src="${contract.guardian_signature_data}" alt="Guardian Signature" style="max-width: 100%; height: auto; border: 1px solid var(--border-color); border-radius: 8px; background: white; padding: 10px;">` : '<p style="color: var(--text-secondary); font-style: italic;">No guardian signature available</p>'}
                </div>
            </div>
        </div>
        ` : ''}
    `;
    
    signatureDetails.innerHTML = detailsHTML;
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

function clearSignature() {
    if (signaturePad) {
        signaturePad.ctx.clearRect(0, 0, signaturePad.canvas.width, signaturePad.canvas.height);
        signaturePad.isEmpty = true;
    }
}

function submitSignature(form) {
    if (!signaturePad || signaturePad.isEmpty) {
        alert('Please provide your signature before submitting.');
        return false;
    }
    
    // Check if guardian signature is required
    const guardianSection = document.getElementById('guardianSection');
    const isGuardianRequired = guardianSection.style.display !== 'none';
    
    if (isGuardianRequired) {
        // Validate guardian fields
        const guardianName = document.getElementById('guardianName').value.trim();
        const guardianEmail = document.getElementById('guardianEmail').value.trim();
        
        if (!guardianName || !guardianEmail) {
            alert('Please fill in all parent/guardian information.');
            return false;
        }
        
        if (!guardianSignaturePad || guardianSignaturePad.isEmpty) {
            alert('Parent/guardian signature is required for employees under 17.');
            return false;
        }
        
        // Save guardian signature data
        const guardianSignatureData = guardianSignaturePad.canvas.toDataURL();
        document.getElementById('modalGuardianSignature').value = guardianSignatureData;
    }
    
    const signatureData = signaturePad.canvas.toDataURL();
    document.getElementById('modalSignature').value = signatureData;
    
    return true;
}

function downloadSignedPDF() {
    // Get the current contract ID from the modal
    const contractId = window.currentViewingContractId;
    
    if (contractId) {
        window.open(`download-pdf.php?contract_id=${contractId}`, '_blank');
    } else {
        alert('Unable to determine contract ID for PDF generation.');
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const signingModal = document.getElementById('signingModal');
    const viewModal = document.getElementById('viewModal');
    
    if (event.target === signingModal) {
        closeSigningModal();
    } else if (event.target === viewModal) {
        closeViewModal();
    }
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
