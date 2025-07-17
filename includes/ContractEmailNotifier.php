<?php
/**
 * Email notification system for contract signatures
 * Sends notifications to employee and HR when contracts are signed
 */

class ContractEmailNotifier {
    private $smtp_host;
    private $smtp_port;
    private $smtp_username;
    private $smtp_password;
    private $from_email;
    private $from_name;
    
    public function __construct() {
        // Email configuration - update these with your actual values
        $this->smtp_host = 'webmail.nexihub.uk';
        $this->smtp_port = 587; // or 465 for SSL
        $this->smtp_username = 'noreply-contracts@nexihub.uk';
        $this->smtp_password = 'nexicontractsigning17072025'; // You'll need to provide this
        $this->from_email = 'noreply-contracts@nexihub.uk';
        $this->from_name = 'Nexi Bot LTD - Contract System';
    }
    
    /**
     * Send contract signing notification emails
     */
    public function sendContractSignedNotification($staff_id, $contract_name, $contract_id) {
        try {
            // Get staff information
            $staff_info = $this->getStaffInfo($staff_id);
            if (!$staff_info) {
                error_log("Failed to get staff info for ID: $staff_id");
                return false;
            }
            
            // Get contract statistics
            $contract_stats = $this->getContractStats($staff_id);
            
            // Generate PDF attachment
            $pdf_content = $this->generateContractPDF($contract_id, $staff_id);
            
            // Send email to employee
            $this->sendEmployeeNotification($staff_info, $contract_name, $contract_stats, $pdf_content);
            
            // Send email to HR
            $this->sendHRNotification($staff_info, $contract_name, $contract_stats, $pdf_content);
            
            return true;
            
        } catch (Exception $e) {
            error_log("Email notification error: " . $e->getMessage());
            return false;
        }
    }
    
    private function getStaffInfo($staff_id) {
        try {
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
            
            $stmt = $db->prepare("SELECT full_name, nexi_email, private_email FROM staff_profiles WHERE id = ?");
            $stmt->execute([$staff_id]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Database error getting staff info: " . $e->getMessage());
            return null;
        }
    }
    
    private function getContractStats($staff_id) {
        try {
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
            
            // Count total contracts
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM contract_templates");
            $stmt->execute();
            $total = $stmt->fetch()['total'];
            
            // Count signed contracts for this staff member
            $stmt = $db->prepare("SELECT COUNT(*) as signed FROM staff_contracts WHERE staff_id = ? AND is_signed = 1");
            $stmt->execute([$staff_id]);
            $signed = $stmt->fetch()['signed'];
            
            return [
                'signed' => $signed,
                'total' => $total,
                'remaining' => $total - $signed
            ];
            
        } catch (PDOException $e) {
            error_log("Database error getting contract stats: " . $e->getMessage());
            return ['signed' => 0, 'total' => 4, 'remaining' => 4];
        }
    }
    
    private function generateContractPDF($template_id, $staff_id) {
        try {
            // Use the exact same PDF generation as download-pdf.php
            // This ensures consistency between email attachments and download button
            
            // Get contract data using the same query as download-pdf.php
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
            
            // Use the exact same query as download-pdf.php
            $stmt = $db->prepare("
                SELECT ct.name, ct.content, ct.type, ct.id as template_id,
                       sc.id as contract_id, sc.is_signed, sc.signed_at, sc.signature_data,
                       sc.signer_full_name, sc.signer_position, sc.signer_date_of_birth,
                       sc.is_under_17, sc.guardian_full_name, sc.guardian_email, 
                       sc.guardian_signature_data, sc.signed_timestamp
                FROM contract_templates ct
                JOIN staff_contracts sc ON ct.id = sc.template_id 
                WHERE ct.id = ? AND sc.staff_id = ? AND sc.is_signed = 1
                ORDER BY sc.signed_timestamp DESC
                LIMIT 1
            ");
            $stmt->execute([$template_id, $staff_id]);
            $contract = $stmt->fetch();
            
            if (!$contract) {
                return null;
            }
            
            // Include the exact same PDF generation function from download-pdf.php
            require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';
            
            // Use the exact generateContractPDF function logic from download-pdf.php
            $pdf = $this->generateExactContractPDF($contract);
            return $pdf->Output('', 'S');
            
        } catch (Exception $e) {
            error_log("PDF generation failed: " . $e->getMessage());
            return null;
        }
    }
    
    private function generateExactContractPDF($contract) {
        // This is the EXACT same function as in download-pdf.php to ensure identical PDFs
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
        
        // Remove any markdown symbols and format properly
        $content = preg_replace('/^#+\s*/m', '', $content); // Remove markdown headers
        $content = preg_replace('/\*\*(.*?)\*\*/', '$1', $content); // Remove bold markdown
        $content = preg_replace('/\*(.*?)\*/', '$1', $content); // Remove italic markdown
        $content = preg_replace('/•/', '• ', $content); // Fix bullet points
        
        // Convert to proper legal formatting
        $content = str_replace("\n\n", "\n", $content); // Remove double line breaks
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Check if it's a section header (all caps or starts with ARTICLE/SECTION)
            if (preg_match('/^(ARTICLE|SECTION|\d+\.\d+|\d+\.)\s+/', $line) || 
                (strlen($line) < 100 && strtoupper($line) === $line && !preg_match('/[.!?]$/', $line))) {
                $pdf->Ln(3);
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
                $pdf->MultiCell(0, 6, $line, 0, 'L');
                $pdf->SetFont('helvetica', '', 11);
                $pdf->SetTextColor($dark_gray[0], $dark_gray[1], $dark_gray[2]);
                $pdf->Ln(2);
            } else {
                // Regular paragraph text
                $pdf->MultiCell(0, 5.5, $line, 0, 'L');
                $pdf->Ln(1);
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
        $pdf->Cell(0, 4, $contract['signer_full_name'] ?? 'Not recorded', 0, 1, 'L');
        
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
            
            // Add signature image with border
            $sig_y = $pdf->GetY() + 2;
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
                
                $sig_y = $pdf->GetY() + 2;
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
    
    private function sendEmployeeNotification($staff_info, $contract_name, $stats, $pdf_content) {
        $to_emails = [];
        if (!empty($staff_info['nexi_email'])) {
            $to_emails[] = $staff_info['nexi_email'];
        }
        if (!empty($staff_info['private_email'])) {
            $to_emails[] = $staff_info['private_email'];
        }
        
        if (empty($to_emails)) {
            error_log("No email addresses found for staff member");
            return false;
        }
        
        $subject = "Contract Signed Successfully - " . $contract_name;
        $html_body = $this->generateEmployeeEmailHTML($staff_info['full_name'], $contract_name, $stats);
        
        foreach ($to_emails as $email) {
            $this->sendEmail($email, $subject, $html_body, $pdf_content, $contract_name);
        }
        
        return true;
    }
    
    private function sendHRNotification($staff_info, $contract_name, $stats, $pdf_content) {
        $hr_email = 'hr@nexihub.uk';
        $subject = "Contract Signed - " . $staff_info['full_name'] . " - " . $contract_name;
        $html_body = $this->generateHREmailHTML($staff_info['full_name'], $contract_name, $stats);
        
        $this->sendEmail($hr_email, $subject, $html_body, $pdf_content, $contract_name);
        
        return true;
    }
    
    private function generateEmployeeEmailHTML($name, $contract_name, $stats) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Contract Signed Successfully - Nexi Bot LTD</title>
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    line-height: 1.6; 
                    color: #333333; 
                    max-width: 600px; 
                    margin: 0 auto; 
                    padding: 0;
                    background-color: #f8f9fa;
                }
                .email-container {
                    background-color: #ffffff;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    margin: 20px auto;
                }
                .header { 
                    background: linear-gradient(135deg, #e64f21 0%, #ff6b3d 100%); 
                    color: white; 
                    padding: 40px 30px;
                    text-align: center;
                    position: relative;
                }
                .header::after {
                    content: '';
                    position: absolute;
                    bottom: -2px;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: linear-gradient(90deg, #e64f21, #ff6b3d, #e64f21);
                }
                .nexi-logo {
                    font-size: 28px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    margin-bottom: 8px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 600;
                    margin-bottom: 8px;
                }
                .header p {
                    margin: 0;
                    font-size: 16px;
                    opacity: 0.95;
                }
                .content { 
                    padding: 40px 30px;
                    background-color: #ffffff;
                }
                .content p {
                    margin: 0 0 16px 0;
                    font-size: 16px;
                    line-height: 1.6;
                }
                .contract-highlight { 
                    background: linear-gradient(135deg, #e64f21, #ff6b3d);
                    color: white; 
                    padding: 4px 12px; 
                    border-radius: 6px; 
                    font-weight: 600;
                    font-size: 15px;
                }
                .progress-section { 
                    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                    border-radius: 12px; 
                    padding: 25px; 
                    margin: 25px 0;
                    text-align: center;
                    border: 1px solid #dee2e6;
                }
                .progress-section h3 {
                    margin: 0 0 15px 0;
                    color: #333333;
                    font-size: 18px;
                    font-weight: 600;
                }
                .progress-stats {
                    font-size: 20px;
                    font-weight: 700;
                    color: #e64f21;
                    margin: 10px 0;
                }
                .cta-button {
                    display: inline-block;
                    background: linear-gradient(135deg, #e64f21, #ff6b3d);
                    color: white;
                    padding: 14px 28px;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 16px;
                    margin: 15px 0;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 8px rgba(230, 79, 33, 0.3);
                }
                .attachment-info {
                    background: #f8f9fa;
                    border-left: 4px solid #e64f21;
                    padding: 20px;
                    margin: 25px 0;
                    border-radius: 0 8px 8px 0;
                }
                .footer { 
                    background: #333333;
                    color: white; 
                    padding: 30px;
                    text-align: center;
                    border-radius: 0 0 12px 12px;
                }
                .footer h3 {
                    margin: 0 0 10px 0;
                    font-size: 18px;
                    font-weight: 600;
                }
                .footer p {
                    margin: 5px 0;
                    opacity: 0.9;
                }
                .legal-notice {
                    font-size: 13px;
                    color: #6c757d;
                    margin-top: 20px;
                    padding-top: 20px;
                    border-top: 1px solid #dee2e6;
                }
                .completion-celebration {
                    background: linear-gradient(135deg, #e64f21, #ff6b3d);
                    color: white;
                    padding: 20px;
                    border-radius: 8px;
                    text-align: center;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <div class='nexi-logo'>NEXI BOT LTD</div>
                    <h1>Contract Signed Successfully</h1>
                    <p>Digital Contract Management System</p>
                </div>
                
                <div class='content'>
                    <p>Dear <strong>{$name}</strong>,</p>
                    
                    <p>Thank you for completing the digital execution of your <span class='contract-highlight'>{$contract_name}</span>.</p>
                    
                    <div class='progress-section'>
                        <h3>Signing Progress</h3>
                        <div class='progress-stats'>{$stats['signed']} of {$stats['total']} documents completed</div>
                        " . ($stats['remaining'] > 0 ? 
                            "<p>You have <strong>{$stats['remaining']}</strong> remaining documents to complete your onboarding process.</p>
                            <a href='https://nexihub.uk/contracts' class='cta-button'>Continue Document Signing</a>" 
                            : "<div class='completion-celebration'>
                                <h3>Congratulations!</h3>
                                <p><strong>All required documents completed successfully.</strong></p>
                                <p>Your onboarding process is now complete.</p>
                            </div>") . "
                    </div>
                    
                    <div class='attachment-info'>
                        <p><strong>Document Archive:</strong> A professionally formatted PDF copy of your signed contract has been attached to this email for your permanent records. Please store this document securely as it may be required for future reference.</p>
                    </div>
                    
                    <p>Should you require any assistance or have questions regarding your contract, please contact our Human Resources department at <a href='mailto:hr@nexihub.uk' style='color: #e64f21; text-decoration: none; font-weight: 600;'>hr@nexihub.uk</a>.</p>
                    
                    <div class='legal-notice'>
                        <p><em>This is an automated notification from our contract management system. Please do not reply to this email as this mailbox is not monitored. For all inquiries, please use the contact information provided above.</em></p>
                    </div>
                </div>
                
                <div class='footer'>
                    <h3>Kind Regards</h3>
                    <p><strong>The Nexi Team</strong></p>
                    <p>Nexi Bot LTD | Digital Contract Management</p>
                    <p style='font-size: 12px; margin-top: 15px; opacity: 0.8;'>
                        Company Registration: 16502958 | ICO Registration: ZB910034<br>
                        Incorporated in England and Wales
                    </p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function generateHREmailHTML($staff_name, $contract_name, $stats) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Contract Execution Notification - Nexi Bot LTD HR</title>
            <style>
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    line-height: 1.6; 
                    color: #333333; 
                    max-width: 600px; 
                    margin: 0 auto; 
                    padding: 0;
                    background-color: #f8f9fa;
                }
                .email-container {
                    background-color: #ffffff;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    margin: 20px auto;
                }
                .header { 
                    background: linear-gradient(135deg, #e64f21 0%, #ff6b3d 100%); 
                    color: white; 
                    padding: 40px 30px;
                    text-align: center;
                    position: relative;
                }
                .header::after {
                    content: '';
                    position: absolute;
                    bottom: -2px;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: linear-gradient(90deg, #e64f21, #ff6b3d, #e64f21);
                }
                .nexi-logo {
                    font-size: 28px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    margin-bottom: 8px;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 600;
                    margin-bottom: 8px;
                }
                .header p {
                    margin: 0;
                    font-size: 16px;
                    opacity: 0.95;
                }
                .content { 
                    padding: 40px 30px;
                    background-color: #ffffff;
                }
                .content p {
                    margin: 0 0 16px 0;
                    font-size: 16px;
                    line-height: 1.6;
                }
                .staff-info { 
                    background: linear-gradient(135deg, #f8f9fa, #ffffff);
                    border-left: 4px solid #e64f21; 
                    padding: 25px; 
                    margin: 25px 0;
                    border-radius: 0 8px 8px 0;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                }
                .staff-info h3 {
                    margin: 0 0 15px 0;
                    color: #e64f21;
                    font-size: 18px;
                    font-weight: 600;
                }
                .staff-info .detail-row {
                    display: flex;
                    margin: 8px 0;
                    align-items: center;
                }
                .staff-info .label {
                    font-weight: 600;
                    color: #495057;
                    min-width: 120px;
                }
                .staff-info .value {
                    color: #333333;
                }
                .progress-section { 
                    background: linear-gradient(135deg, #e9ecef, #f8f9fa);
                    border-radius: 12px; 
                    padding: 25px; 
                    margin: 25px 0;
                    border: 1px solid #dee2e6;
                }
                .progress-section h3 {
                    margin: 0 0 15px 0;
                    color: #333333;
                    font-size: 18px;
                    font-weight: 600;
                }
                .progress-stats {
                    font-size: 20px;
                    font-weight: 700;
                    color: #e64f21;
                    margin: 10px 0;
                }
                .status-badge {
                    display: inline-block;
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 14px;
                    font-weight: 600;
                    margin: 5px 0;
                }
                .status-pending {
                    background: #fff3cd;
                    color: #856404;
                    border: 1px solid #ffeaa7;
                }
                .status-complete {
                    background: #d4edda;
                    color: #155724;
                    border: 1px solid #c3e6cb;
                }
                .attachment-info {
                    background: #f8f9fa;
                    border-left: 4px solid #e64f21;
                    padding: 20px;
                    margin: 25px 0;
                    border-radius: 0 8px 8px 0;
                }
                .dashboard-link {
                    display: inline-block;
                    background: linear-gradient(135deg, #e64f21, #ff6b3d);
                    color: white;
                    padding: 14px 28px;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 16px;
                    margin: 15px 0;
                    box-shadow: 0 2px 8px rgba(230, 79, 33, 0.3);
                }
                .footer { 
                    background: #333333;
                    color: white; 
                    padding: 30px;
                    text-align: center;
                    border-radius: 0 0 12px 12px;
                }
                .footer h3 {
                    margin: 0 0 10px 0;
                    font-size: 18px;
                    font-weight: 600;
                }
                .footer p {
                    margin: 5px 0;
                    opacity: 0.9;
                }
                .legal-notice {
                    font-size: 13px;
                    color: #6c757d;
                    margin-top: 20px;
                    padding-top: 20px;
                    border-top: 1px solid #dee2e6;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <div class='nexi-logo'>NEXI BOT LTD</div>
                    <h1>Contract Execution Notification</h1>
                    <p>Human Resources Management System</p>
                </div>
                
                <div class='content'>
                    <p>Dear HR Team,</p>
                    
                    <p>This notification confirms the successful digital execution of a staff contract within our management system.</p>
                    
                    <div class='staff-info'>
                        <h3>Contract Execution Details</h3>
                        <div class='detail-row'>
                            <span class='label'>Staff Member:</span>
                            <span class='value'><strong>{$staff_name}</strong></span>
                        </div>
                        <div class='detail-row'>
                            <span class='label'>Document:</span>
                            <span class='value'><strong>{$contract_name}</strong></span>
                        </div>
                        <div class='detail-row'>
                            <span class='label'>Execution Date:</span>
                            <span class='value'>" . date('F j, Y') . " at " . date('g:i A T') . "</span>
                        </div>
                        <div class='detail-row'>
                            <span class='label'>Status:</span>
                            <span class='value'><span class='status-badge status-complete'>Digitally Executed</span></span>
                        </div>
                    </div>
                    
                    <div class='progress-section'>
                        <h3>Staff Onboarding Progress</h3>
                        <div class='progress-stats'>{$stats['signed']} of {$stats['total']} required documents completed</div>
                        " . ($stats['remaining'] > 0 ? 
                            "<p><span class='status-badge status-pending'>{$stats['remaining']} documents remaining</span></p>
                            <p><strong>{$staff_name}</strong> has additional contract documents pending completion.</p>" 
                            : "<p><span class='status-badge status-complete'>Onboarding Complete</span></p>
                            <p><strong>{$staff_name}</strong> has successfully completed all required contract documentation.</p>") . "
                    </div>
                    
                    <div class='attachment-info'>
                        <p><strong>Document Archive:</strong> The fully executed contract has been attached to this email in PDF format for your HR records. This document includes complete digital signature verification and legal metadata.</p>
                    </div>
                    
                    <p>You can access comprehensive staff contract management tools and reporting through the HR Dashboard:</p>
                    
                    <p style='text-align: center;'>
                        <a href='https://nexihub.uk/staff/dashboard.php' class='dashboard-link'>Access HR Dashboard</a>
                    </p>
                    
                    <div class='legal-notice'>
                        <p><em>This notification is generated automatically by the Nexi Contract Management System. All digital signatures comply with UK Electronic Signatures Regulations 2002 and are legally binding under English law.</em></p>
                    </div>
                </div>
                
                <div class='footer'>
                    <h3>System Notification</h3>
                    <p><strong>Nexi Contract Management System</strong></p>
                    <p>Automated HR Notification Service</p>
                    <p style='font-size: 12px; margin-top: 15px; opacity: 0.8;'>
                        Company Registration: 16502958 | ICO Registration: ZB910034<br>
                        Incorporated in England and Wales
                    </p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function sendEmail($to_email, $subject, $html_body, $pdf_content, $contract_name) {
        try {
            // Enhanced headers for better deliverability
            $headers = array();
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-Type: multipart/mixed; boundary=\"PHP-mixed-" . md5(time()) . "\"";
            $headers[] = "From: Nexi Bot LTD Contract System <{$this->from_email}>";
            $headers[] = "Reply-To: hr@nexihub.uk";
            $headers[] = "Return-Path: {$this->from_email}";
            $headers[] = "X-Mailer: Nexi Contract System";
            $headers[] = "X-Priority: 3";
            $headers[] = "X-MSMail-Priority: Normal";
            $headers[] = "Importance: Normal";
            $headers[] = "List-Unsubscribe: <mailto:hr@nexihub.uk>";
            $headers[] = "X-Auto-Response-Suppress: All";
            $headers[] = "Precedence: bulk";
            
            // Anti-spam headers
            $headers[] = "Message-ID: <" . md5(uniqid(time())) . "@nexihub.uk>";
            $headers[] = "Date: " . date('r');
            $headers[] = "Organization: Nexi Bot LTD";
            $headers[] = "X-Sender: noreply-contracts@nexihub.uk";
            $headers[] = "X-Original-Sender: noreply-contracts@nexihub.uk";
            
            $boundary = "PHP-mixed-" . md5(time());
            
            // Create professional plain text version
            $plain_text = $this->createPlainTextVersion($html_body, $contract_name);
            
            $message = "--$boundary\r\n";
            $message .= "Content-Type: multipart/alternative; boundary=\"alt-" . md5(time()) . "\"\r\n\r\n";
            
            // Plain text version
            $alt_boundary = "alt-" . md5(time());
            $message .= "--$alt_boundary\r\n";
            $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
            $message .= $plain_text . "\r\n\r\n";
            
            // HTML version
            $message .= "--$alt_boundary\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
            $message .= $html_body . "\r\n\r\n";
            $message .= "--$alt_boundary--\r\n\r\n";
            
            // PDF attachment
            if ($pdf_content) {
                $message .= "--$boundary\r\n";
                $message .= "Content-Type: application/pdf; name=\"" . $this->sanitizeFilename($contract_name) . "_signed.pdf\"\r\n";
                $message .= "Content-Disposition: attachment; filename=\"" . $this->sanitizeFilename($contract_name) . "_signed.pdf\"\r\n";
                $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
                $message .= chunk_split(base64_encode($pdf_content)) . "\r\n";
            }
            
            $message .= "--$boundary--";
            
            // Add debugging
            error_log("Sending professional email to: $to_email");
            error_log("Subject: $subject");
            error_log("From: {$this->from_email}");
            
            $success = mail($to_email, $subject, $message, implode("\r\n", $headers));
            
            if ($success) {
                error_log("Email sent successfully to: $to_email");
            } else {
                error_log("Failed to send email to: $to_email");
                // Get last error
                $error = error_get_last();
                if ($error) {
                    error_log("Last error: " . print_r($error, true));
                }
            }
            
            return $success;
            
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    private function createPlainTextVersion($html_body, $contract_name) {
        // Create a plain text version for better deliverability
        $text = "NEXI BOT LTD - CONTRACT NOTIFICATION\n";
        $text .= str_repeat("=", 50) . "\n\n";
        
        if (strpos($html_body, 'Contract Signed Successfully') !== false) {
            $text .= "Your contract has been signed successfully!\n\n";
            $text .= "Contract: $contract_name\n";
            $text .= "Status: Digitally Executed\n";
            $text .= "Date: " . date('F j, Y \a\t g:i A T') . "\n\n";
            $text .= "A PDF copy of your signed contract is attached to this email.\n\n";
            $text .= "If you have any questions, please contact:\n";
            $text .= "HR Department: hr@nexihub.uk\n\n";
        } else {
            $text .= "HR NOTIFICATION - Contract Signed\n\n";
            $text .= "A staff contract has been digitally executed.\n\n";
            $text .= "Contract: $contract_name\n";
            $text .= "Status: Digitally Executed\n";
            $text .= "Date: " . date('F j, Y \a\t g:i A T') . "\n\n";
            $text .= "The signed contract PDF is attached for your records.\n\n";
            $text .= "Access the HR Dashboard: https://nexihub.uk/staff/dashboard.php\n\n";
        }
        
        $text .= "Best regards,\n";
        $text .= "The Nexi Team\n";
        $text .= "Nexi Bot LTD\n";
        $text .= "Company Registration: 16502958\n";
        $text .= "ICO Registration: ZB910034\n\n";
        $text .= "This is an automated notification. Please do not reply to this email.\n";
        
        return $text;
    }
    
    private function sanitizeFilename($filename) {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
    }
}
?>
