<?php
require('vendor/setasign/fpdf/fpdf.php');

class AppointmentPDF extends FPDF {
    // Page header
    function Header() {
        // Logo
        $this->Image('Assets/icon_Logo.png', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'Mirror Your World', 0, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Check what type of document is requested
if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $appointment_id = $_GET['id'];
    
    // Connect to database
    include 'dbconnect.php';
    
    if ($type == 'details') {
        generateAppointmentDetails($conn, $appointment_id);
    } elseif ($type == 'receipt') {
        generateAppointmentReceipt($conn, $appointment_id);
    } elseif ($type == 'history') {
        generateBookingHistory($conn);
    }
    
    $conn->close();
}

function generateAppointmentDetails($conn, $appointment_id) {
    // Get appointment details
    $query = "
        SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
        FROM appointmentstbl a
        JOIN userstbl u ON a.user_id = u.user_id
        WHERE a.appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
        
        // Create PDF
        $pdf = new AppointmentPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Appointment Details', 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);
        
        // Appointment information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Appointment ID:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['appointment_id'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Date:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, date('F d, Y', strtotime($appointment['appointment_date'])), 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Time:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, date('h:i A', strtotime($appointment['appointment_time'])), 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Consultation Type:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['consultation_type'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Location:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, $appointment['address'], 0);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Status:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['status'], 0, 1);
        
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Client Information', 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Name:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['first_name'] . ' ' . $appointment['last_name'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Email:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['email'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Contact Number:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['contact_number'], 0, 1);
        
        // Output PDF
        $pdf->Output('D', 'Appointment_Details_' . $appointment_id . '.pdf');
    } else {
        echo "Appointment not found.";
    }
    $stmt->close();
}

function generateAppointmentReceipt($conn, $appointment_id) {
    // Get appointment details
    $query = "
        SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
        FROM appointmentstbl a
        JOIN userstbl u ON a.user_id = u.user_id
        WHERE a.appointment_id = ? AND a.status = 'Completed'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
        
        // Create PDF
        $pdf = new AppointmentPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Appointment Receipt', 0, 1, 'C');
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Receipt #: R-' . sprintf('%06d', $appointment['appointment_id']), 0, 1, 'R');
        $pdf->Cell(0, 10, 'Date Issued: ' . date('F d, Y'), 0, 1, 'R');
        
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(10);
        
        // Client information
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Client Information', 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Name:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['first_name'] . ' ' . $appointment['last_name'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Email:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['email'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Contact:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['contact_number'], 0, 1);
        
        $pdf->Ln(10);
        
        // Service information
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Service Details', 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Service Type:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $appointment['consultation_type'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Date of Service:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, date('F d, Y', strtotime($appointment['appointment_date'])), 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Time of Service:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, date('h:i A', strtotime($appointment['appointment_time'])), 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Location:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, $appointment['address'], 0);
        
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'This receipt confirms that the above service was completed successfully.', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Thank you for choosing Mirror Your World!', 0, 1, 'C');
        
        // Output PDF
        $pdf->Output('D', 'Service_Receipt_' . $appointment_id . '.pdf');
    } else {
        echo "Completed appointment not found.";
    }
    $stmt->close();
}

function generateBookingHistory($conn) {
    // Get user ID from session
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in.";
        return;
    }
    $user_id = $_SESSION['user_id'];
    
    // Get user information
    $query = "SELECT * FROM userstbl WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Get all appointments for this user
    $query = "
        SELECT * FROM appointmentstbl 
        WHERE user_id = ?
        ORDER BY appointment_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $appointments = $stmt->get_result();
    $stmt->close();
    
    if ($appointments->num_rows > 0) {
        // Create PDF
        $pdf = new AppointmentPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Booking History Report', 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Generated on: ' . date('F d, Y'), 0, 1, 'R');
        
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Client Information', 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Name:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $user['first_name'] . ' ' . $user['last_name'], 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Email:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $user['email'], 0, 1);
        
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Appointment History', 0, 1);
        
        // Table header
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(20, 10, 'ID', 1, 0, 'C', true);
        $pdf->Cell(35, 10, 'Date', 1, 0, 'C', true);
        $pdf->Cell(35, 10, 'Time', 1, 0, 'C', true);
        $pdf->Cell(50, 10, 'Type', 1, 0, 'C', true);
        $pdf->Cell(50, 10, 'Status', 1, 1, 'C', true);
        
        // Table content
        $pdf->SetFont('Arial', '', 10);
        while ($row = $appointments->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['appointment_id'], 1, 0, 'C');
            $pdf->Cell(35, 10, date('M d, Y', strtotime($row['appointment_date'])), 1, 0, 'C');
            $pdf->Cell(35, 10, date('h:i A', strtotime($row['appointment_time'])), 1, 0, 'C');
            $pdf->Cell(50, 10, $row['consultation_type'], 1, 0, 'L');
            $pdf->Cell(50, 10, $row['status'], 1, 1, 'C');
        }
        
        // Summary statistics
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Summary', 0, 1);
        
        // Count by status
        $query = "
            SELECT status, COUNT(*) as count 
            FROM appointmentstbl 
            WHERE user_id = ? 
            GROUP BY status";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $statusCounts = $stmt->get_result();
        $stmt->close();
        
        $pdf->SetFont('Arial', '', 11);
        while ($row = $statusCounts->fetch_assoc()) {
            $pdf->Cell(60, 8, $row['status'] . ' appointments:', 0);
            $pdf->Cell(0, 8, $row['count'], 0, 1);
        }
        
        // Output PDF
        $pdf->Output('D', 'Booking_History_Report.pdf');
    } else {
        echo "No appointments found.";
    }
}
?>