<?php
// admin_invoice_pdf.php
session_start();
require 'dbconnect.php';
require_once 'vendor/autoload.php'; // Make sure you have TCPDF installed via Composer

// Check for admin authentication
if (!isset($_SESSION['admin_id'])) {
    die('Session is missing. Please log in again.');
}

// Check if invoice ID is provided
if (!isset($_GET['invoice_id']) && !isset($_POST['user_id'])) {
    die('Invoice ID or User ID is required');
}

// Get invoice data
if (isset($_GET['invoice_id'])) {
    // Fetch existing invoice
    $invoice_id = intval($_GET['invoice_id']);
    $stmt = $conn->prepare("
        SELECT i.*, u.first_name, u.last_name, u.email, u.contact_number
        FROM invoicestbl i
        JOIN userstbl u ON i.user_id = u.user_id
        WHERE i.invoice_id = ?
    ");
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die('Invoice not found');
    }
    
    $invoice = $result->fetch_assoc();
    
    // Get appointment details
    $user_id = $invoice['user_id'];
    $stmt = $conn->prepare("
        SELECT * FROM appointmentstbl 
        WHERE user_id = ? AND status = 'Completed'
        ORDER BY appointment_date DESC LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $appointment_result = $stmt->get_result();
    $appointment = $appointment_result->fetch_assoc();
    
} else {
    // Generate new invoice on the fly
    $user_id = intval($_POST['user_id']);
    $total_cost = floatval($_POST['total_cost']);
    $notes = $_POST['notes'] ?? '';
    $tax_discount = floatval($_POST['tax_discount'] ?? 0);
    
    // Get user details
    $stmt = $conn->prepare("SELECT * FROM userstbl WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die('User not found');
    }
    
    $user = $result->fetch_assoc();
    
    // Get appointment details
    $stmt = $conn->prepare("
        SELECT * FROM appointmentstbl 
        WHERE user_id = ? AND status = 'Completed'
        ORDER BY appointment_date DESC LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $appointment_result = $stmt->get_result();
    $appointment = $appointment_result->fetch_assoc();
    
    // Create an invoice record in the database
    $stmt = $conn->prepare("INSERT INTO invoicestbl (user_id, total_cost, notes, tax_discount, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("idsd", $user_id, $total_cost, $notes, $tax_discount);
    $stmt->execute();
    $invoice_id = $conn->insert_id;
    
    // Combine data for the PDF
    $invoice = [
        'invoice_id' => $invoice_id,
        'user_id' => $user_id,
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'contact_number' => $user['contact_number'],
        'total_cost' => $total_cost,
        'notes' => $notes,
        'tax_discount' => $tax_discount,
        'created_at' => date('Y-m-d H:i:s')
    ];
}

// Create new PDF document
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, 'Mirror Your World', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->SetFont('helvetica', 'I', 12);
        $this->Cell(0, 10, 'Official Invoice', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(15);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Mirror Your World - Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Initialize PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Mirror Your World');
$pdf->SetAuthor('Mirror Your World Admin');
$pdf->SetTitle('Invoice #' . $invoice_id);
$pdf->SetSubject('Customer Invoice');

// Set default header and footer data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Invoice number and date
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Invoice #' . $invoice_id, 0, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 10, 'Date: ' . date('F d, Y', strtotime($invoice['created_at'] ?? date('Y-m-d H:i:s'))), 0, 1);
$pdf->Ln(5);

// Customer details
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Customer Details:', 0, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(40, 7, 'Name:', 0, 0);
$pdf->Cell(0, 7, $invoice['first_name'] . ' ' . $invoice['last_name'], 0, 1);
$pdf->Cell(40, 7, 'Email:', 0, 0);
$pdf->Cell(0, 7, $invoice['email'], 0, 1);
$pdf->Cell(40, 7, 'Contact:', 0, 0);
$pdf->Cell(0, 7, $invoice['contact_number'], 0, 1);
$pdf->Ln(5);

// Service details
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Service Details:', 0, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(40, 7, 'Service Type:', 0, 0);
$pdf->Cell(0, 7, $appointment['consultation_type'] ?? 'Consultation', 0, 1);
$pdf->Cell(40, 7, 'Date:', 0, 0);
$pdf->Cell(0, 7, date('F d, Y', strtotime($appointment['appointment_date'] ?? date('Y-m-d'))), 0, 1);
$pdf->Cell(40, 7, 'Time:', 0, 0);
$pdf->Cell(0, 7, date('h:i A', strtotime($appointment['appointment_time'] ?? '00:00:00')), 0, 1);
$pdf->Cell(40, 7, 'Location:', 0, 0);
$pdf->Cell(0, 7, $appointment['address'] ?? 'Office Location', 0, 1);
$pdf->Ln(5);

// Cost breakdown
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Cost Breakdown:', 0, 1);
$pdf->Ln(2);

// Create table
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(100, 10, 'Description', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Amount', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 11);
// Service cost
$pdf->Cell(100, 10, 'Service: ' . ($appointment['consultation_type'] ?? 'Consultation'), 1, 0, 'L');
$baseAmount = $invoice['total_cost'];
$taxDiscount = $invoice['tax_discount'];
$finalAmount = $baseAmount * (1 + ($taxDiscount / 100));

$pdf->Cell(40, 10, '$' . number_format($baseAmount, 2), 1, 1, 'R');

// Tax/Discount
if ($taxDiscount != 0) {
    $taxDiscountAmount = $baseAmount * ($taxDiscount / 100);
    $pdf->Cell(100, 10, ($taxDiscount > 0 ? 'Tax' : 'Discount') . ' (' . abs($taxDiscount) . '%)', 1, 0, 'L');
    $pdf->Cell(40, 10, '$' . number_format($taxDiscountAmount, 2), 1, 1, 'R');
}

// Total
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(100, 10, 'Total', 1, 0, 'L', 1);
$pdf->Cell(40, 10, '$' . number_format($finalAmount, 2), 1, 1, 'R', 1);
$pdf->Ln(5);

// Notes
if (!empty($invoice['notes'])) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Notes:', 0, 1);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 7, $invoice['notes'], 0, 'L');
    $pdf->Ln(5);
}

// Payment instructions
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Payment Information:', 0, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 7, 'Payment due within 30 days of receipt.', 0, 1);
$pdf->Cell(0, 7, 'Please include invoice number with payment.', 0, 1);
$pdf->Ln(5);

// Thank you message
$pdf->SetFont('helvetica', 'I', 11);
$pdf->Cell(0, 10, 'Thank you for choosing Mirror Your World!', 0, 1, 'C');

// Close and output PDF
$pdfFileName = 'Invoice_' . $invoice_id . '_' . date('Ymd') . '.pdf';
$pdf->Output($pdfFileName, 'D'); // D for download, I for inline browser display
exit;