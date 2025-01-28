<?php
include "dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        echo "<script>window.location.href='User_ResetPassword.php?email=$email';</script>";
        exit();
    }

    // Hash the new password for security
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password in the database
    $sql = "UPDATE userstbl SET password = :password, otp = NULL WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["password" => $hashed_password, "email" => $email]);

    echo "<script>alert('Password updated successfully! Please log in.');</script>";
    echo "<script>window.location.href='User_LoginPage.php';</script>";
}
?>
