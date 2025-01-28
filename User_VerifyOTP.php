<?php
include "dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Check OTP
    $sql = "SELECT * FROM userstbl WHERE email = :email AND otp = :otp";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["email" => $email, "otp" => $otp]);
    $count = $stmt->rowCount();

    if ($count > 0) {
        // OTP is correct, redirect to password reset page
        echo "<script>alert('OTP Verified! Set your new password.');</script>";
        echo "<script>window.location.href='User_ResetPassword.php?email=$email';</script>";
    } else {
        echo "<script>alert('Invalid OTP! Try again.');</script>";
        echo "<script>window.location.href='User_EnterOtpPage.php?email=$email';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="otp-container">
        <h2>Enter OTP</h2>
        <form action="User_VerifyOTP.php" method="POST">
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
            
            <label for="otp">Enter OTP:</label>
            <input type="text" name="otp" id="otp" required>

            <button type="submit">Verify OTP</button>
        </form>
    </div>

</body>
</html>
