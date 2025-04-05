<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'User_EmailConfig.php'; // This file contains SMTP credentials

function sendOTPEmail($toEmail, $otp, $isPasswordResetRequested = true) {
    global $smtp_host, $smtp_username, $smtp_password, $smtp_secure, $smtp_port, $smtp_sender;

    // Check if password reset is requested
    if (!$isPasswordResetRequested) {
        return false; // Ignore email if the user did not request a password reset
    }

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
        $mail->Subject = 'Your OTP Code - Mirror Your World';

        // HTML Email Body with CSS Styling
        $mail->Body    = '
        <html>
        <head>
            <style>
                body {
                    font-family: "Century Gothic", sans-serif;
                    color: #333333;
                    background-color: #f9f9f9;
                    padding: 20px;
                }
                .container {
                    background-color: #ffffff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                h2 {
                    color: #4CAF50;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 16px;
                    margin-bottom: 15px;
                }
                .otp-code {
                    font-size: 22px;
                    color: #E91E63;
                    font-weight: bold;
                }
                .footer {
                    margin-top: 30px;
                    font-size: 14px;
                    color: #777777;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Password Update Request</h2>
                <p>Hello,</p>
                <p>You have requested to update your password. Your One-Time Password (OTP) is:</p>
                <p class="otp-code">' . $otp . '</p>
                <p>Please enter this code to proceed with updating your password. If you did not request a password update, please contact our support team.</p>
                <div class="footer">
                    <p>Regards,</p>
                    <p>The Mirror Your World Team</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        throw new Exception("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>