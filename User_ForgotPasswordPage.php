<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request OTP | Mirror Your World</title>
    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_LoginPageCSS.css" />
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="User_Homepage.php">About</a>
    <a href="User_InquiryPage.php">FAQ</a>
    <a href="User_Showcase.php">Showcase</a>
</div>

<div class="logo">
    <img src="Assets/icon_Logo.png" alt="Logo" style="width: 30px">
</div>

<div class="BGhome-container">
    <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">

    <center>
        <!-- Request OTP Form -->
        <div class="requestOtpDiv">

            <form class="requestOtpForm" method="POST" action="User_SendOTP.php">

                <div class="txt_Title">
                    <br> <br>
                    <h2 class="txt_MYW"> Mirror Your World. </h2>
                    <h4 class="txt_Desc"> Request OTP for Password Reset </h4>
                </div>

                <div class="input-field">
                    <label> <img src="Assets/icon_Email.png" class="field-icon"> </label>
                    <input id="email" name="email" type="email" placeholder="Enter your email" required>
                </div>

                <button class="btn_login" type="submit" name="action" id="requestOtp">
                    Request OTP
                </button>

                <br /><br />
            </form>

        </div>
    </center>
</div>

</body>
</html>

