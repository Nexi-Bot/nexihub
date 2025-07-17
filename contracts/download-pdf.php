<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Check if user is logged in
if (!isset($_SESSION['contract_user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

// Get contract ID from request
$contract_id = $_GET['contract_id'] ?? '';
$staff_id = $_SESSION['contract_staff_id'];

if (!$contract_id || !$staff_id) {
    http_response_code(400);
    exit('Missing required parameters');
}

try {
    // Connect to database
    if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } else {
        $db = new PDO("sqlite:" . __DIR__ . "/../database/nexihub.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Get signed contract data
    $stmt = $db->prepare("
        SELECT ct.name, ct.content, ct.type,
               sc.is_signed, sc.signed_at, sc.signature_data,
               sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
               sc.is_under_17, sc.guardian_full_name, sc.guardian_email, 
               sc.guardian_signature_data, sc.signed_timestamp
        FROM contract_templates ct
        JOIN staff_contracts sc ON ct.id = sc.template_id 
        WHERE ct.id = ? AND sc.staff_id = ? AND sc.is_signed = 1
    ");
    $stmt->execute([$contract_id, $staff_id]);
    $contract = $stmt->fetch();

    if (!$contract) {
        http_response_code(404);
        exit('Contract not found or not signed');
    }

    // Generate and output PDF
    $pdf = generateContractPDF($contract);
    $pdf->Output(sanitizeFilename($contract['name']) . '_signed.pdf', 'D');

} catch (PDOException $e) {
    http_response_code(500);
    exit('Database error: ' . $e->getMessage());
} catch (Exception $e) {
    http_response_code(500);
    exit('Error: ' . $e->getMessage());
}

function generateContractPDF($contract) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Nexi Bot LTD');
    $pdf->SetAuthor('Nexi Bot LTD');
    $pdf->SetTitle($contract['name'] . ' - Signed Contract');
    $pdf->SetSubject('Digital Contract');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'Nexi Bot LTD', "Contract Portal - " . $contract['name'], array(230, 79, 33), array(0, 0, 0));
    $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0));

    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    $signedDate = date('F j, Y g:i A', strtotime($contract['signed_timestamp'] ?? $contract['signed_at']));

    // Contract content
    $html = '<h1 style="color: #e64f21; text-align: center; border-bottom: 2px solid #e64f21; padding-bottom: 10px;">' . 
            htmlspecialchars($contract['name']) . '</h1>';
    
    $html .= '<div style="text-align: center; color: #666; margin-bottom: 20px;">
                <strong>Nexi Bot LTD</strong> | Company Registration: 16502958 | ICO Registration: ZB910034<br>
                Digitally Signed Contract
              </div>';

    $html .= '<div style="margin: 30px 0; line-height: 1.6;">' . nl2br(htmlspecialchars($contract['content'])) . '</div>';

    // Signature section
    $html .= '<div style="border-top: 2px solid #e64f21; padding-top: 20px; margin-top: 30px;">
                <h2 style="color: #e64f21;">Digital Signature Information</h2>';

    $html .= '<table style="width: 100%; margin-bottom: 20px;">
                <tr><td style="font-weight: bold; width: 150px;">Signer Name:</td><td>' . htmlspecialchars($contract['signer_full_name'] ?? 'Not recorded') . '</td></tr>
                <tr><td style="font-weight: bold;">Position:</td><td>' . htmlspecialchars($contract['signer_position'] ?? 'Not recorded') . '</td></tr>
                <tr><td style="font-weight: bold;">Date of Birth:</td><td>' . htmlspecialchars($contract['signer_date_of_birth'] ?? 'Not recorded') . '</td></tr>
                <tr><td style="font-weight: bold;">Signed On:</td><td>' . $signedDate . '</td></tr>
              </table>';

    $html .= '<div style="border: 2px solid #ccc; padding: 15px; margin: 15px 0; text-align: center; background: #f9f9f9;">
                <h3>Employee Signature</h3>';
    
    if ($contract['signature_data']) {
        // For TCPDF, we need to handle base64 images differently
        $signatureImage = $contract['signature_data'];
        if (strpos($signatureImage, 'data:image') === 0) {
            $html .= '<img src="' . $signatureImage . '" style="max-width: 300px; max-height: 100px; border: 1px solid #ddd;">';
        }
    } else {
        $html .= '<p>Signature not available</p>';
    }
    
    $html .= '</div>';

    // Guardian section if applicable
    if ($contract['is_under_17'] && $contract['guardian_full_name']) {
        $html .= '<div style="background: #fff5f5; border: 2px solid #e64f21; padding: 15px; margin: 15px 0; border-radius: 8px;">
                    <h2 style="color: #e64f21;">Parent/Guardian Consent</h2>
                    <p><em>Required for signers 16 years or younger</em></p>';
        
        $html .= '<table style="width: 100%; margin-bottom: 15px;">
                    <tr><td style="font-weight: bold; width: 150px;">Guardian Name:</td><td>' . htmlspecialchars($contract['guardian_full_name']) . '</td></tr>
                    <tr><td style="font-weight: bold;">Guardian Email:</td><td>' . htmlspecialchars($contract['guardian_email']) . '</td></tr>
                    <tr><td style="font-weight: bold;">Consent Given:</td><td>' . $signedDate . '</td></tr>
                  </table>';

        $html .= '<div style="border: 2px solid #ccc; padding: 15px; margin: 15px 0; text-align: center; background: #f9f9f9;">
                    <h3>Parent/Guardian Signature</h3>';
        
        if ($contract['guardian_signature_data']) {
            $guardianSignatureImage = $contract['guardian_signature_data'];
            if (strpos($guardianSignatureImage, 'data:image') === 0) {
                $html .= '<img src="' . $guardianSignatureImage . '" style="max-width: 300px; max-height: 100px; border: 1px solid #ddd;">';
            }
        } else {
            $html .= '<p>Guardian signature not available</p>';
        }
        
        $html .= '</div></div>';
    }

    $html .= '</div>';

    $html .= '<div style="margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ccc; padding-top: 15px;">
                <p>This document was digitally signed and is legally binding.</p>
                <p>Generated on ' . date('F j, Y g:i A') . ' | Nexi Bot LTD</p>
              </div>';

    // Write HTML to PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    return $pdf;
}

function sanitizeFilename($filename) {
    // Remove or replace characters that are not allowed in filenames
    $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
    return $filename;
}
?>
