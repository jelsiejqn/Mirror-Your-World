<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP | Mirror Your World</title>
    <link rel="stylesheet" href="Style/Required.css">
</head>
<body>

<center>
    <div class="otp-container">
        <form class="otpForm" method="POST" action="User_VerifyOTP.php">
            <h2>Enter OTP</h2>
            <p>Enter the OTP sent to your email.</p>

            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
            
            <input type="text" name="otp" placeholder="Enter OTP" required>
            
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</center>

</body>
</html>
