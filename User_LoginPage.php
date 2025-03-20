<?php
require_once 'dbconnect.php';

session_start(); // Start the session to store user session

// Declare error and success message variables
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username exists in the database
    $check_query = "SELECT * FROM userstbl WHERE username = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // User exists, now check the password
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // Log the login action
            $log_query = "INSERT INTO logstbl (user_id, action_type) VALUES (?, 'Login')";
            $log_stmt = mysqli_prepare($conn, $log_query);
            mysqli_stmt_bind_param($log_stmt, "i", $user['user_id']);
            mysqli_stmt_execute($log_stmt);

            // Redirect to user homepage after successful login
            header('Location: User_Homepage.php');
            exit;
        } else {
            $error_message = 'Incorrect password. Please try again';
        }
    } else {
        $error_message = '<h6 class = "error"> No user found with that username. </h6>';
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_LoginPageCSS.css" />
</head>

<body>

    <!-- Required -->

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
            <!-- Main Login Form -->
            <div class="loginDiv">

                <form class="loginForm" method="POST" action="User_LoginPage.php">

                    <div class="txt_Title">
                        <br> <br>
                        <h2 class="txt_MYW"> Mirror Your World. </h2>
                        <h4 class="txt_Desc"> Welcome back! </h4>
                    </div>

                    <div class="input-field">
                        <label> <img src="Assets/icon_Profile.png" class="field-icon"> </label>
                        <input id="username" name="username" type="text" placeholder="Username" required>
                    </div>

                    <div class="input-field">
                        <label> <img src="Assets/icon_lock.png" class="field-icon"> </label>
                        <input id="password" name="password" type="password" placeholder="Password" required>
                    </div>

                    <!-- Forgot password link -->
                    <p class="forgot-password">
                        <a href="User_ForgotPasswordPage.php"> Forgot Password? </a>
                    </p>

                    <button class="btn_login" type="submit" name="action" id="login">
                        Login
                    </button>



                    <p class="existingacc"> Don't have an account? <a href="User_SignupPage.php" class="a1"> Sign Up.</a> </p>

                    <br /><br />
                </form>

                <!-- Display error or success messages -->
                <?php if (!empty($error_message)) { ?>
                    <div class="error-message" style="color: red;">
                        <?php echo $error_message; ?>
                    </div>
                <?php } ?>

            </div>

        </center>

    </div>

    <!-- Required -->

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