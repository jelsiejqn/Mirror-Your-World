<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'User_EmailConfig.php'; // This file contains SMTP credentials

function sendOTPEmail($toEmail, $otp) {
    global $smtp_host, $smtp_username, $smtp_password, $smtp_secure, $smtp_port, $smtp_sender;

    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = $smtp_secure;
        $mail->Port       = $smtp_port;

        // Sender & Recipient
        $mail->setFrom($smtp_sender, 'Mirror Your World');
        $mail->addAddress($toEmail);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP Code is <b>$otp</b>. Please enter this code to reset your password.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        throw new Exception("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>
