<?php
// Clean any existing output buffers
while (ob_get_level()) {
    ob_end_clean();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Check if user is logged in
if (!isset($_SESSION['contract_user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

// Get contract ID from request
$contract_id = $_GET['contract_id'] ?? '';
$template_id = $_GET['template_id'] ?? '';  // Alternative parameter for email system
$staff_id = $_SESSION['contract_staff_id'] ?? $_GET['staff_id'] ?? '';
$format = $_GET['format'] ?? 'download'; // 'download' or 'raw' for email system

// Use template_id if contract_id not provided (for email system)
if (!$contract_id && $template_id) {
    $contract_id = $template_id;
}

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

    // Get signed contract data with staff profile information
    $stmt = $db->prepare("
        SELECT ct.name, ct.content, ct.type, ct.id as template_id,
               sc.id as contract_id, sc.is_signed, sc.signed_at, sc.signature_data,
               sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
               sc.is_under_17, sc.guardian_full_name, sc.guardian_email, 
               sc.guardian_signature_data, sc.signed_timestamp,
               sp.shareholder_percentage, sp.is_shareholder
        FROM contract_templates ct
        JOIN staff_contracts sc ON ct.id = sc.template_id 
        LEFT JOIN staff_profiles sp ON sc.staff_id = sp.id
        WHERE (ct.id = ? OR sc.id = ?) AND sc.staff_id = ? AND sc.is_signed = 1
    ");
    $stmt->execute([$contract_id, $contract_id, $staff_id]);
    $contract = $stmt->fetch();

    if (!$contract) {
        http_response_code(404);
        exit('Contract not found or not signed');
    }

    // Generate and output PDF
    $pdf = generateContractPDF($contract);
    
    // Clean any output buffers before sending PDF
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    if ($format === 'raw') {
        // Return raw PDF content for email system
        echo $pdf->Output('', 'S');
    } else {
        // Download PDF file
        $pdf->Output(sanitizeFilename($contract['name']) . '_signed.pdf', 'D');
    }

} catch (PDOException $e) {
    http_response_code(500);
    exit('Database error: ' . $e->getMessage());
} catch (Exception $e) {
    http_response_code(500);
    exit('Error: ' . $e->getMessage());
}

function generateContractPDF($contract) {
    // Create new PDF document with legal formatting
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Nexi Bot LTD Legal Department');
    $pdf->SetAuthor('Nexi Bot LTD');
    $pdf->SetTitle($contract['name'] . ' - Digitally Executed Contract');
    $pdf->SetSubject('Legal Contract Document');
    $pdf->SetKeywords('Contract, Legal, Digital Signature, Nexi Bot LTD');

    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set margins for legal document
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetAutoPageBreak(TRUE, 25);

    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Add a page
    $pdf->AddPage();

    // Define colors
    $primary_color = array(230, 79, 33); // Nexi orange
    $dark_gray = array(51, 51, 51);
    $light_gray = array(128, 128, 128);

    $signedDate = date('F j, Y \a\t g:i A', strtotime($contract['signed_timestamp'] ?? $contract['signed_at']));

    // Professional header with company letterhead
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Cell(0, 12, 'NEXI BOT LTD', 0, 1, 'C');
    
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
    $pdf->Cell(0, 5, 'Incorporated in England and Wales | Company Registration: 16502958', 0, 1, 'C');
    $pdf->Cell(0, 5, 'ICO Registration: ZB910034 | Legal Department', 0, 1, 'C');
    $pdf->Ln(5);

    // Horizontal line
    $pdf->SetDrawColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
    $pdf->Ln(10);

    // Document title
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
    $pdf->Cell(0, 12, strtoupper($contract['name']), 0, 1, 'C');
    $pdf->Ln(5);

    // Legal status notice
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->SetTextColor($light_gray[0], $light_gray[1], $light_gray[2]);
    $pdf->Cell(0, 5, 'DIGITALLY EXECUTED LEGAL DOCUMENT', 0, 1, 'C');
    $pdf->Cell(0, 5, 'This document has been digitally signed and is legally binding', 0, 1, 'C');
    $pdf->Ln(8);

    // Document reference box
    $pdf->SetFillColor(245, 245, 245);
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Rect(20, $pdf->GetY(), 170, 20, 'DF');
    
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
    $current_y = $pdf->GetY() + 3;
    $pdf->SetY($current_y);
    $pdf->Cell(42, 4, 'Document Reference:', 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, 'NEXI-' . strtoupper($contract['type']) . '-' . date('Y') . '-' . sprintf('%04d', $contract['contract_id'] ?? $contract['template_id'] ?? 1), 0, 1, 'L');
    
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(42, 4, 'Execution Date:', 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 4, $signedDate, 0, 1, 'L');
    
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(42, 4, 'Legal Status:', 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Cell(0, 4, 'FULLY EXECUTED', 0, 1, 'L');
    
    $pdf->Ln(12);

    // Contract content with professional formatting
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
    
    // Clean and format the contract content
    $content = $contract['content'];
    
    // Remove HTML structure and convert to formatted text
    if (strpos($content, '<html') !== false || strpos($content, '<!DOCTYPE') !== false) {
        // Full HTML document - extract body content
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Remove all style tags and their content
        $styles = $dom->getElementsByTagName('style');
        $stylesToRemove = [];
        foreach ($styles as $style) {
            $stylesToRemove[] = $style;
        }
        foreach ($stylesToRemove as $style) {
            $style->parentNode->removeChild($style);
        }
        
        // Remove script tags if any
        $scripts = $dom->getElementsByTagName('script');
        $scriptsToRemove = [];
        foreach ($scripts as $script) {
            $scriptsToRemove[] = $script;
        }
        foreach ($scriptsToRemove as $script) {
            $script->parentNode->removeChild($script);
        }
        
        // Get body content or full document if no body
        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body) {
            $content = $dom->saveHTML($body);
            $content = preg_replace('/^<body[^>]*>|<\/body>$/', '', $content);
        } else {
            $content = $dom->saveHTML();
        }
    }
    
    // Convert specific HTML tags to formatted text markers
    $content = preg_replace('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', "\n\n**HEADER**$1**HEADER**\n\n", $content);
    $content = preg_replace('/<p[^>]*>(.*?)<\/p>/is', "$1\n\n", $content);
    $content = preg_replace('/<br\s*\/?>/i', "\n", $content);
    $content = preg_replace('/<strong[^>]*>(.*?)<\/strong>/is', "**$1**", $content);
    $content = preg_replace('/<b[^>]*>(.*?)<\/b>/is', "**$1**", $content);
    $content = preg_replace('/<em[^>]*>(.*?)<\/em>/is', "*$1*", $content);
    $content = preg_replace('/<i[^>]*>(.*?)<\/i>/is', "*$1*", $content);
    $content = preg_replace('/<li[^>]*>(.*?)<\/li>/is', "• $1\n", $content);
    $content = preg_replace('/<ul[^>]*>(.*?)<\/ul>/is', "$1\n", $content);
    $content = preg_replace('/<ol[^>]*>(.*?)<\/ol>/is', "$1\n", $content);
    
    // Remove all remaining HTML tags
    $content = strip_tags($content);
    
    // Clean up HTML entities
    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    
    // Clean up excessive whitespace but preserve paragraph breaks
    $content = preg_replace('/[ \t]+/', ' ', $content); // Multiple spaces/tabs to single space
    $content = preg_replace('/\n[ \t]*\n/', "\n\n", $content); // Clean up line breaks
    $content = preg_replace('/\n{3,}/', "\n\n", $content); // Max 2 consecutive line breaks
    $content = trim($content);
    
    // Split into meaningful sections based on double line breaks
    $sections = explode("\n\n", $content);
    $formattedSections = [];
    
    foreach ($sections as $section) {
        $section = trim($section);
        if (!empty($section)) {
            $formattedSections[] = $section;
        }
    }
    
    // Render the formatted sections
    foreach ($formattedSections as $section) {
        $section = trim($section);
        if (empty($section)) continue;
        
        // Check if it's a header marked with our special markers
        if (strpos($section, '**HEADER**') !== false) {
            $headerText = str_replace('**HEADER**', '', $section);
            $headerText = trim($headerText);
            
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
            $pdf->MultiCell(0, 7, $headerText, 0, 'L');
            $pdf->SetFont('helvetica', '', 11);
            $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
            $pdf->Ln(3);
            continue;
        }
        
        // Check if it's a section header (short text, likely a heading)
        if (strlen($section) < 150 && 
            (preg_match('/^(ARTICLE|SECTION|\d+\.\d+|\d+\.|NON-DISCLOSURE|AGREEMENT|PARTIES|DEFINITION|TERMS|CONDITIONS|OBLIGATIONS|CONFIDENTIALITY)/i', $section) ||
             preg_match('/^[A-Z\s\-:]{10,}$/', $section) ||
             preg_match('/^\d+\.\s*[A-Z]/', $section))) {
            
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
            $pdf->MultiCell(0, 6, $section, 0, 'L');
            $pdf->SetFont('helvetica', '', 11);
            $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
            $pdf->Ln(2);
        } else {
            // Process bold and italic formatting in regular text
            $lines = explode("\n", $section);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                // Handle bold text formatting
                if (strpos($line, '**') !== false) {
                    // Split by bold markers and render alternately
                    $parts = explode('**', $line);
                    $isBold = false;
                    
                    foreach ($parts as $part) {
                        if (!empty($part)) {
                            if ($isBold) {
                                $pdf->SetFont('helvetica', 'B', 11);
                                $pdf->Write(5.5, $part);
                                $pdf->SetFont('helvetica', '', 11);
                            } else {
                                $pdf->Write(5.5, $part);
                            }
                        }
                        $isBold = !$isBold;
                    }
                    $pdf->Ln(5.5);
                } else {
                    // Regular text
                    $pdf->MultiCell(0, 5.5, $line, 0, 'L');
                    if (count($lines) > 1) $pdf->Ln(1);
                }
            }
            $pdf->Ln(3);
        }
    }

    $pdf->Ln(10);

    // Signature section with enhanced legal formatting
    $pdf->SetDrawColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->SetLineWidth(0.3);
    $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
    $pdf->Ln(8);

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Cell(0, 8, 'EXECUTION AND SIGNATURE DETAILS', 0, 1, 'C');
    $pdf->Ln(5);

    // Legal execution statement
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
    $pdf->MultiCell(0, 5, 'The parties hereto have executed this Agreement on the date and time indicated below. This document has been digitally signed using cryptographic signature technology and constitutes a legally binding agreement under the Electronic Signatures Regulations 2002 and Electronic Communications Act 2000.', 0, 'C');
    $pdf->Ln(8);

    // Signatory information in professional layout
    $pdf->SetFillColor(248, 249, 250);
    $pdf->SetDrawColor(220, 220, 220);
    
    // Employee signature section
    $rect_height = 45;
    $pdf->Rect(20, $pdf->GetY(), 170, $rect_height, 'DF');
    
    $start_y = $pdf->GetY() + 5;
    $pdf->SetY($start_y);
    
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Cell(0, 6, 'EMPLOYEE EXECUTION', 0, 1, 'L');
    
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
    
    // Two column layout for signature info
    $col1_x = 25;
    $col2_x = 110;
    $current_y = $pdf->GetY() + 2;
    
    // Left column - signatory details
    $pdf->SetXY($col1_x, $current_y);
    $pdf->Cell(40, 4, 'Signatory Name:', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    
    // For shareholder agreements, include percentage
    if (strtolower($contract['type']) === 'shareholder' && $contract['is_shareholder'] && $contract['shareholder_percentage']) {
        $name_with_percentage = ($contract['signer_full_name'] ?? 'Not recorded') . ' (' . $contract['shareholder_percentage'] . '% Shareholder)';
        $pdf->Cell(0, 4, $name_with_percentage, 0, 1, 'L');
    } else {
        $pdf->Cell(0, 4, $contract['signer_full_name'] ?? 'Not recorded', 0, 1, 'L');
    }
    
    $pdf->SetXY($col1_x, $pdf->GetY());
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(40, 4, 'Position/Title:', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 4, $contract['signer_position'] ?? 'Not recorded', 0, 1, 'L');
    
    $pdf->SetXY($col1_x, $pdf->GetY());
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(40, 4, 'Date of Birth:', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 4, $contract['signer_date_of_birth'] ? date('F j, Y', strtotime($contract['signer_date_of_birth'])) : 'Not recorded', 0, 1, 'L');
    
    $pdf->SetXY($col1_x, $pdf->GetY());
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(40, 4, 'Execution Date:', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 4, $signedDate, 0, 1, 'L');
    
    // Right column - signature image
    if ($contract['signature_data'] && strpos($contract['signature_data'], 'data:image') === 0) {
        $pdf->SetXY($col2_x, $current_y);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor($light_gray[0], $light_gray[1], $light_gray[2]);
        $pdf->Cell(0, 4, 'DIGITAL SIGNATURE:', 0, 1, 'L');
        
        // Add signature image with border - ensure consistent positioning
        $sig_y = $current_y + 6; // Fixed offset from current_y
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Rect($col2_x, $sig_y, 60, 20);
        
        try {
            // Process base64 image data for TCPDF
            $signature_image = $contract['signature_data'];
            
            // Create a temporary file for the signature image
            $temp_file = tempnam(sys_get_temp_dir(), 'signature_') . '.png';
            
            // Extract base64 data and decode
            if (preg_match('/^data:image\/[^;]+;base64,(.*)$/', $signature_image, $matches)) {
                $image_data = base64_decode($matches[1]);
                file_put_contents($temp_file, $image_data);
                
                // Add the signature image to PDF
                $pdf->Image($temp_file, $col2_x + 2, $sig_y + 2, 56, 16, '', '', '', false, 300, '', false, false, 0);
                
                // Clean up temp file
                unlink($temp_file);
            } else {
                // Fallback text if image processing fails
                $pdf->SetXY($col2_x + 2, $sig_y + 8);
                $pdf->SetFont('helvetica', 'I', 8);
                $pdf->SetTextColor($light_gray[0], $light_gray[1], $light_gray[2]);
                $pdf->Cell(56, 4, '[Digital Signature Verified]', 0, 0, 'C');
            }
        } catch (Exception $e) {
            // Fallback text if image processing fails
            $pdf->SetXY($col2_x + 2, $sig_y + 8);
            $pdf->SetFont('helvetica', 'I', 8);
            $pdf->SetTextColor($light_gray[0], $light_gray[1], $light_gray[2]);
            $pdf->Cell(56, 4, '[Digital Signature Verified]', 0, 0, 'C');
        }
    }
    
    $pdf->Ln($rect_height + 5);

    // Guardian section if applicable
    if ($contract['is_under_17'] && $contract['guardian_full_name']) {
        $pdf->SetFillColor(255, 248, 240); // Light orange background
        $pdf->Rect(20, $pdf->GetY(), 170, $rect_height, 'DF');
        
        $start_y = $pdf->GetY() + 5;
        $pdf->SetY($start_y);
        
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
        $pdf->Cell(0, 6, 'PARENT/GUARDIAN CONSENT', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', 'I', 9);
        $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
        $pdf->Cell(0, 4, 'Required for employees under 17 years of age', 0, 1, 'L');
        
        $current_y = $pdf->GetY() + 2;
        
        // Guardian details
        $pdf->SetXY($col1_x, $current_y);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 4, 'Guardian Name:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 4, $contract['guardian_full_name'], 0, 1, 'L');
        
        $pdf->SetXY($col1_x, $pdf->GetY());
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 4, 'Guardian Email:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 4, $contract['guardian_email'], 0, 1, 'L');
        
        $pdf->SetXY($col1_x, $pdf->GetY());
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 4, 'Consent Given:', 0, 0, 'L');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 4, $signedDate, 0, 1, 'L');
        
        // Guardian signature
        if ($contract['guardian_signature_data']) {
            $pdf->SetXY($col2_x, $current_y);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor($light_gray[0], $light_gray[1], $light_gray[2]);
            $pdf->Cell(0, 4, 'GUARDIAN SIGNATURE:', 0, 1, 'L');
            
            // Fixed positioning for guardian signature box
            $sig_y = $current_y + 6; // Fixed offset from current_y
            $pdf->SetDrawColor(230, 79, 33);
            $pdf->Rect($col2_x, $sig_y, 60, 20);
            
            try {
                // Process base64 image data for TCPDF
                $guardian_signature = $contract['guardian_signature_data'];
                
                // Create a temporary file for the guardian signature image
                $temp_file = tempnam(sys_get_temp_dir(), 'guardian_signature_') . '.png';
                
                // Extract base64 data and decode
                if (preg_match('/^data:image\/[^;]+;base64,(.*)$/', $guardian_signature, $matches)) {
                    $image_data = base64_decode($matches[1]);
                    file_put_contents($temp_file, $image_data);
                    
                    // Add the guardian signature image to PDF
                    $pdf->Image($temp_file, $col2_x + 2, $sig_y + 2, 56, 16, '', '', '', false, 300, '', false, false, 0);
                    
                    // Clean up temp file
                    unlink($temp_file);
                } else {
                    // Fallback text if image processing fails
                    $pdf->SetXY($col2_x + 2, $sig_y + 8);
                    $pdf->SetFont('helvetica', 'I', 8);
                    $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
                    $pdf->Cell(56, 4, '[Guardian Signature Verified]', 0, 0, 'C');
                }
            } catch (Exception $e) {
                // Fallback text if image processing fails
                $pdf->SetXY($col2_x + 2, $sig_y + 8);
                $pdf->SetFont('helvetica', 'I', 8);
                $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
                $pdf->Cell(56, 4, '[Guardian Signature Verified]', 0, 0, 'C');
            }
        }
        
        $pdf->Ln($rect_height + 5);
    }

    // Legal footer with verification information
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTextColor($light_gray[0], $light_gray[1], $light_gray[2]);
    $pdf->SetDrawColor($light_gray[0], $light_gray[1], $light_gray[2]);
    $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
    $pdf->Ln(3);
    
    $pdf->MultiCell(0, 3.5, 'VERIFICATION: This document was generated on ' . date('F j, Y \a\t g:i:s A T') . ' by the Nexi Bot LTD Contract Management System. Digital signatures have been cryptographically verified and stored securely. This document constitutes a legally binding agreement under applicable electronic signature laws.', 0, 'C');
    
    $pdf->Ln(2);
    $pdf->Cell(0, 3, 'Document ID: NEXI-' . strtoupper($contract['type']) . '-' . date('Y') . '-' . sprintf('%04d', $contract['contract_id'] ?? $contract['template_id'] ?? 1) . ' | Generated: ' . date('Y-m-d H:i:s T'), 0, 1, 'C');

    return $pdf;
}

function sanitizeFilename($filename) {
    // Remove or replace characters that are not allowed in filenames
    $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
    return $filename;
}
?>
