<?php
session_start();
include 'dbconnect.php';
require 'User_EmailAPI.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if user is logged in, NOT admin
if (!isset($_SESSION['user_id'])) {
    header("Location: User_LoginPage.php"); // redirect to USER login page if not logged in
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    // Fetch appointment and user details
    $query = "
        SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
        FROM appointmentstbl a
        JOIN userstbl u ON a.user_id = u.user_id
        WHERE a.appointment_id = ? AND a.user_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $_SESSION['user_id']); // Make sure the user can only cancel their own appointment
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Update status to Cancelled
        $update_query = "UPDATE appointmentstbl SET status = 'Cancelled', cancellation_reason = ? WHERE appointment_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $reason, $appointment_id);

        if ($update_stmt->execute()) {
            // Send email to the user
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_username;
                $mail->Password = $smtp_password;
                $mail->SMTPSecure = $smtp_secure;
                $mail->Port = $smtp_port;

                $mail->setFrom($smtp_sender, 'Mirror Your World');
                $mail->addAddress($row['email'], $row['first_name'] . ' ' . $row['last_name']);

                $formatted_date = date('M d Y', strtotime($row['appointment_date']));
                $formatted_time = date('h:i A', strtotime($row['appointment_time']));

                $mail->isHTML(true);
                $mail->Subject = 'Your Appointment Has Been Cancelled';
                $mail->Body = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <h2>Appointment Cancellation Notice</h2>
                    <p>Dear " . htmlspecialchars($row['first_name']) . ",</p>
                    <p>We regret to inform you that your appointment scheduled on <strong>$formatted_date</strong> at <strong>$formatted_time</strong> has been cancelled.</p>
                    <p><strong>Reason:</strong> " . htmlspecialchars($reason) . "</p>
                    <p>We apologize for any inconvenience this may cause.</p>
                    <p>Best regards,<br><strong>Mirror Your World Team</strong></p>
                </body>
                </html>
                ";
                $mail->AltBody = "Your appointment on $formatted_date at $formatted_time has been cancelled. Reason: $reason.";

                $mail->send();

                $_SESSION['swal_title'] = "Success!";
                $_SESSION['swal_text'] = "Appointment cancelled and email notification sent.";
                $_SESSION['swal_icon'] = "success";
            } catch (Exception $e) {
                $_SESSION['swal_title'] = "Warning!";
                $_SESSION['swal_text'] = "Appointment cancelled but email failed to send: " . $mail->ErrorInfo;
                $_SESSION['swal_icon'] = "warning";
            }
        } else {
            $_SESSION['swal_title'] = "Error!";
            $_SESSION['swal_text'] = "Failed to cancel appointment.";
            $_SESSION['swal_icon'] = "error";
        }

        $update_stmt->close();
    } else {
        $_SESSION['swal_title'] = "Error!";
        $_SESSION['swal_text'] = "Appointment not found or unauthorized.";
        $_SESSION['swal_icon'] = "error";
    }

    $stmt->close();
    $conn->close();

    // Redirect to user's Active Bookings page
    header("Location: User_ActiveBookings.php");
    exit;
} else {
    // Direct access (no POST)
    header("Location: User_ActiveBookings.php");
    exit;
}
