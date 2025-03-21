<?php
include "dbconnect.php";
require 'User_EmailAPI.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $sql = "SELECT * FROM userstbl WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["email" => $email]);
    $count = $stmt->rowCount();

    if ($count > 0) {

        $otp = rand(100000, 999999);

        $sql_update_statement = "UPDATE userstbl SET otp = :otp WHERE email = :email";
        $statement = $pdo->prepare($sql_update_statement);
        $statement->execute(["otp" => $otp, "email" => $email]);

        try {
            sendOTPEmail($email, $otp);

            echo "<script>alert('OTP sent successfully! Check your email.');</script>";
            header("Location: User_VerifyOTP.php?email=$email");
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Email not found! Please enter a registered email.');</script>";
        header("Location: User_RequestOtpPage.php");
        exit();
    }
}
