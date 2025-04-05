<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Document</title>
</head>
<body>
    
</body>
</html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Document</title>
</head>
<body>
    
</body>
</html>

<?php
session_start();
include 'dbconnect.php';
require 'User_EmailAPI.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['admin_id'])) {
    header("Location: Admin_LoginPage.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    // First, get the appointment and user details
    $query = "
        SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
        FROM appointmentstbl a
        JOIN userstbl u ON a.user_id = u.user_id
        WHERE a.appointment_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Update the appointment status and save cancellation reason
        $update_query = "UPDATE appointmentstbl SET status = 'Cancelled', cancellation_reason = ? WHERE appointment_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $reason, $appointment_id);
        
        if ($update_stmt->execute()) {
            // Send email notification to client
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host     = $smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_username;
                $mail->Password = $smtp_password;
                $mail->SMTPSecure = $smtp_secure;
                $mail->Port     = $smtp_port;

                $mail->setFrom($smtp_sender, 'Mirror Your World');
                $mail->addAddress($row['email'], $row['first_name'] . ' ' . $row['last_name']);

                $formatted_date = date('M d Y', strtotime($row['appointment_date']));
                $formatted_time = date('h:i A', strtotime($row['appointment_time']));

                $mail->isHTML(true);
                $mail->Subject = 'Your Appointment Has Been Cancelled';
                
                // Email template with CSS styling
                $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body {
                            font-family: 'Arial', sans-serif;
                            line-height: 1.6;
                            color: #333333;
                            max-width: 600px;
                            margin: 0 auto;
                        }
                        .email-container {
                            border: 1px solid #e0e0e0;
                            border-radius: 5px;
                            padding: 20px;
                            background-color: #ffffff;
                        }
                        .header {
                            text-align: center;
                            padding-bottom: 15px;
                            border-bottom: 2px solid #f0f0f0;
                            margin-bottom: 20px;
                        }
                        .header h2 {
                            color: #d9534f;
                            margin: 0;
                            padding: 0;
                        }
                        .content {
                            padding: 0 15px;
                        }
                        .appointment-details {
                            background-color: #f9f9f9;
                            padding: 15px;
                            border-left: 4px solid #d9534f;
                            margin: 20px 0;
                        }
                        .reason {
                            padding: 10px 15px;
                            background-color: #fcf8e3;
                            border-left: 4px solid #f0ad4e;
                            margin-bottom: 20px;
                        }
                        .footer {
                            text-align: center;
                            margin-top: 30px;
                            padding-top: 15px;
                            border-top: 1px solid #f0f0f0;
                            font-size: 0.9em;
                            color: #777777;
                        }
                        .company-name {
                            font-weight: bold;
                            color: #333333;
                        }
                        .btn-reschedule {
                            display: inline-block;
                            padding: 10px 20px;
                            margin: 15px 0;
                            background-color: #5bc0de;
                            color: white;
                            text-decoration: none;
                            border-radius: 4px;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='header'>
                            <h2>Appointment Cancellation Notice</h2>
                        </div>
                        <div class='content'>
                            <p>Dear " . htmlspecialchars($row['first_name']) . ",</p>
                            
                            <p>We regret to inform you that your appointment has been cancelled.</p>
                            
                            <div class='appointment-details'>
                                <p><strong>Date:</strong> " . htmlspecialchars($formatted_date) . "</p>
                                <p><strong>Time:</strong> " . htmlspecialchars($formatted_time) . "</p>
                            </div>
                            
                            <div class='reason'>
                                <p><strong>Reason for Cancellation:</strong> " . htmlspecialchars($reason) . "</p>
                            </div>
                            
                            <p>We apologize for any inconvenience this may cause. Please feel free to reschedule your appointment at your earliest convenience by visiting our website.</p>
                            
                            
                            <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
                        </div>
                        <div class='footer'>
                            <p>Best regards,<br><span class='company-name'>Mirror Your World Team</span></p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                // Plain text alternative
                $mail->AltBody = "
                    Dear " . $row['first_name'] . ",
                    
                    We regret to inform you that your appointment scheduled for " . $formatted_date . " at " . $formatted_time . " has been cancelled.
                    
                    Reason for Cancellation: " . $reason . "
                    
                    We apologize for any inconvenience this may cause. Please feel free to reschedule your appointment at your earliest convenience.
                    
                    If you have any questions or concerns, please don't hesitate to contact us.
                    
                    Best regards,
                    Mirror Your World Team
                ";

                $mail->send();
                
                // Store success message for SweetAlert
                $_SESSION['swal_title'] = "Success!";
                $_SESSION['swal_text'] = "Appointment cancelled successfully. Email notification sent to customer.";
                $_SESSION['swal_icon'] = "success";
            } catch (Exception $e) {
                // Store warning message for SweetAlert
                $_SESSION['swal_title'] = "Warning";
                $_SESSION['swal_text'] = "Appointment cancelled, but there was an error sending the email: " . $mail->ErrorInfo;
                $_SESSION['swal_icon'] = "warning";
            }
        } else {
            // Store error message for SweetAlert
            $_SESSION['swal_title'] = "Error!";
            $_SESSION['swal_text'] = "Error cancelling appointment.";
            $_SESSION['swal_icon'] = "error";
        }
        
        $update_stmt->close();
    } else {
        // Store error message for SweetAlert
        $_SESSION['swal_title'] = "Error!";
        $_SESSION['swal_text'] = "Appointment not found.";
        $_SESSION['swal_icon'] = "error";
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect back to the appointments page
    header("Location: Admin_AppointmentsPage.php");
    exit;
} else {
    // If someone tries to access this file directly without submitting the form
    header("Location: Admin_AppointmentsPage.php");
    exit;
}
?>