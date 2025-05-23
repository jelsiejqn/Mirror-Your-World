<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request OTP | Mirror Your World</title>
    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_ForgotPasswordPageCSS.css" />
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

    <div class="profile-container" style="position: fixed; top: 10px; right: 20px; z-index: 1000; border-radius: 20px;">
        <button class="btn dropdown-trigger" data-target="dropdown1" style="border-radius: 20px; padding: 0; background-color: transparent; border: none; cursor: pointer;" onclick="toggleDropdown()">
            <img src="Assets/icon_Profile.png" class="iconProfile" alt="Profile Icon" width="40px" height="40px" style="width: 25px; height: 25px; object-fit: cover; cursor: pointer; transition: filter 0.3s ease;" onmouseover="this.style.filter='invert(1)';" onmouseout="this.style.filter='invert(0)';" />
        </button>

        <br />
        <ul id="dropdown1" class="dropdown-content" style="transition: 0.3s; display: none; position: absolute; top: 60px; right: 0; background-color: white; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); width: 200px; padding: 0; margin: 0;">
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="User_LoginPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Login</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="User_SignupPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Sign Up</a>
            </li>
        </ul>
    </div>

    <div class="BGhome-container">
        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">

        <center>
            <!-- Request OTP Form -->
            <div class="requestOtpDiv">

                <form class="requestOtpForm" method="POST" action="User_SendOTP.php">

                    <div class="txt_Title">
                        <br> <br>
                        <img src="Assets/icon_Logo.png" alt="Logo" style="width: 30px">
                        <h2 class="txt_MYW"> Mirror Your World. </h2>
                        <h6 class="txt_Desc"> Request OTP for Password Reset </h4>
                    </div>

                    <div class="input-field">
                        <!-- <label> <img src="Assets/icon_Email.png" class="field-icon"> </label> -->
                        <input id="email" name="email" type="email" placeholder="Enter your email" required>
                    </div>

                    <button class="btn_login" type="submit" name="action" id="requestOtp">
                        Request OTP
                    </button>

                    <br> <br>
                    <a href="User_LoginPage.php" class="backtologin"> Back to Login. </a>

                    <br /><br />
                </form>

            </div>
        </center>
    </div>

</body>

<script>
    // Function to toggle the visibility of the dropdown content
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdown1');
        // Toggle the display of the dropdown menu
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Close the dropdown if the user clicks anywhere outside the dropdown
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-trigger') && !event.target.matches('.dropdown-trigger img')) {
            var dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(function(dropdown) {
                dropdown.style.display = 'none'; // Close the dropdown
            });
        }
    };
</script>


</html>