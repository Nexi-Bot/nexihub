<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Check if user is logged in via E-Learning portal
if (!isset($_SESSION['contract_user_id']) || !isset($_SESSION['staff_id'])) {
    header('Location: ./login');
    exit;
}

try {
    // Get staff profile and progress
    $stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE id = ?");
    $stmt->execute([$_SESSION['staff_id']]);
    $staff_profile = $stmt->fetch();
    
    $stmt = $pdo->prepare("SELECT * FROM elearning_progress WHERE staff_id = ? AND status = 'completed'");
    $stmt->execute([$_SESSION['staff_id']]);
    $progress = $stmt->fetch();
    
    if (!$staff_profile || !$progress) {
        die("Certificate not available. Please complete all training modules first.");
    }
    
    // Create PDF
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
    
    // Document properties
    $pdf->SetCreator('Nexi Hub E-Learning System');
    $pdf->SetAuthor('Nexi Hub');
    $pdf->SetTitle('E-Learning Completion Certificate');
    $pdf->SetSubject('Training Completion Certificate');
    
    // Remove header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Add page
    $pdf->AddPage();
    
    // Colors
    $primary_color = [102, 126, 234]; // #667eea
    $secondary_color = [118, 75, 162]; // #764ba2
    $dark_color = [30, 41, 59]; // #1e293b
    $gold_color = [255, 193, 7]; // #ffc107
    
    // Background gradient effect
    $pdf->SetFillColor(248, 249, 250);
    $pdf->Rect(0, 0, 297, 210, 'F');
    
    // Certificate border
    $pdf->SetLineWidth(3);
    $pdf->SetDrawColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Rect(15, 15, 267, 180, 'D');
    
    // Inner border
    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor($secondary_color[0], $secondary_color[1], $secondary_color[2]);
    $pdf->Rect(20, 20, 257, 170, 'D');
    
    // Header decoration
    $pdf->SetFillColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Rect(20, 20, 257, 25, 'F');
    
    // Company logo placeholder / icon
    $pdf->SetXY(40, 30);
    $pdf->SetFont('helvetica', 'B', 24);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(0, 15, '🎓', 0, 1, 'L');
    
    // Company name
    $pdf->SetXY(70, 30);
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(0, 15, 'NEXI HUB', 0, 1, 'L');
    
    // Certificate title
    $pdf->SetXY(20, 60);
    $pdf->SetFont('helvetica', 'B', 32);
    $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Cell(257, 20, 'CERTIFICATE OF COMPLETION', 0, 1, 'C');
    
    // Decorative line
    $pdf->SetLineWidth(2);
    $pdf->SetDrawColor($gold_color[0], $gold_color[1], $gold_color[2]);
    $pdf->Line(80, 85, 217, 85);
    
    // Certificate text
    $pdf->SetXY(20, 95);
    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetTextColor($dark_color[0], $dark_color[1], $dark_color[2]);
    $pdf->Cell(257, 10, 'This is to certify that', 0, 1, 'C');
    
    // Staff name
    $pdf->SetXY(20, 110);
    $pdf->SetFont('helvetica', 'B', 24);
    $pdf->SetTextColor($secondary_color[0], $secondary_color[1], $secondary_color[2]);
    $staff_name = $staff_profile['full_name'];
    $pdf->Cell(257, 15, strtoupper($staff_name), 0, 1, 'C');
    
    // Completion text
    $pdf->SetXY(20, 130);
    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetTextColor($dark_color[0], $dark_color[1], $dark_color[2]);
    $pdf->Cell(257, 8, 'has successfully completed the comprehensive', 0, 1, 'C');
    
    $pdf->SetXY(20, 140);
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor($primary_color[0], $primary_color[1], $primary_color[2]);
    $pdf->Cell(257, 10, 'NEXI HUB E-LEARNING TRAINING PROGRAM', 0, 1, 'C');
    
    // Completion details
    $completion_date = date('F j, Y', strtotime($progress['completed_at']));
    $pdf->SetXY(20, 155);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetTextColor($dark_color[0], $dark_color[1], $dark_color[2]);
    $pdf->Cell(257, 8, 'demonstrating proficiency in company policies, procedures, and professional standards', 0, 1, 'C');
    
    // Date and signatures section
    $pdf->SetXY(50, 175);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(60, 6, 'Date of Completion:', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 6, $completion_date, 0, 1, 'L');
    
    // Certificate ID
    $certificate_id = 'NEXI-' . str_pad($_SESSION['staff_id'], 4, '0', STR_PAD_LEFT) . '-' . date('Y', strtotime($progress['completed_at']));
    $pdf->SetXY(180, 175);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(60, 6, 'Certificate ID:', 0, 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 6, $certificate_id, 0, 1, 'L');
    
    // Digital signature placeholder
    $pdf->SetXY(200, 155);
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->SetTextColor($secondary_color[0], $secondary_color[1], $secondary_color[2]);
    $pdf->Cell(77, 6, 'Digitally Verified', 0, 1, 'C');
    
    // Authority signature
    $pdf->SetXY(200, 165);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetTextColor($dark_color[0], $dark_color[1], $dark_color[2]);
    $pdf->Cell(77, 6, 'Nexi Hub Training Authority', 0, 1, 'C');
    
    // Achievement badge
    $pdf->SetFillColor($gold_color[0], $gold_color[1], $gold_color[2]);
    $pdf->Circle(240, 120, 15, 0, 360, 'F');
    $pdf->SetXY(225, 115);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(30, 10, '★ CERTIFIED ★', 0, 1, 'C');
    
    // Verification info
    $pdf->SetXY(20, 185);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(257, 5, 'This certificate was generated on ' . date('F j, Y \a\t g:i A T') . ' and is digitally verified.', 0, 1, 'C');
    
    // Mark certificate as generated in database
    $stmt = $pdo->prepare("UPDATE elearning_progress SET certificate_generated = TRUE WHERE staff_id = ?");
    $stmt->execute([$_SESSION['staff_id']]);
    
    // Output PDF
    $filename = 'Nexi_Hub_Training_Certificate_' . str_replace(' ', '_', $staff_name) . '.pdf';
    $pdf->Output($filename, 'D');
    
} catch (Exception $e) {
    die("Error generating certificate: " . $e->getMessage());
}
