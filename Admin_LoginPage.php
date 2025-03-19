<?php
session_start(); // Ensure session starts at the very top
require 'dbconnect.php'; 

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        // Prepare and execute SQL query
        $query = "SELECT * FROM adminstbl WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            // Ensure the correct column name for password hash
            if (password_verify($password, $admin["password_hash"])) { 
                session_regenerate_id(true); // Prevent session fixation

                $_SESSION["admin_id"] = $admin["admin_id"];
                $_SESSION["admin_username"] = $admin["username"];

                header("Location: Admin_AppointmentsPage.php");
                exit();
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "Admin not found.";
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/Admin_LoginPageCSS.css" />
</head>
<body>

    <!-- Required -->

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
                <a href="Admin_SignupPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Sign-Up</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="Admin_LoginPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Login</a>
            </li>
        </ul>
    </div>
    
    <div class="BGhome-container">
        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">

        <center>
            <!-- Main Login Form -->
            <div class="loginDiv">

                <form class="loginForm" method="POST" action="Admin_LoginPage.php">

                    <div class="txt_Title">
                        <br> <br>
                        <h2 class="txt_MYW"> Mirror Your World. </h2>
                        <h4 class="txt_Desc"> Welcome back, Admin! </h4>
                    </div>

                    <div class="input-field">
                        <label> <img src="Assets/icon_Profile.png" class="field-icon"> </label>
                        <input id="username" name="username" type="text" placeholder="Username" required>
                    </div>

                    <div class="input-field">
                        <label> <img src="Assets/icon_lock.png" class="field-icon"> </label>
                        <input id="password" name="password" type="password" placeholder="Password" required>
                    </div>

                    <button class="btn_login" type="submit" name="action" id="login">
                        Login
                    </button>
                    <p class="existingacc"> Can't Login? Contact your higher-up. </p>

                    <?php if (!empty($error_message)) { ?>
                        <div class="error-message" style="color: red;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>

                    </br></br>


                </form>

            </div>

        </center>

    </div>

    <!-- Required -->
     <!-- Include SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Check if the logout flag is set in the URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('logout')) {
        Swal.fire({
            icon: 'success',
            title: 'Logged Out',
            text: 'You have successfully logged out.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            // Remove the logout flag from URL
            window.location.href = 'Admin_LoginPage.php';
        });
    }
</script>


</body>


</html>
