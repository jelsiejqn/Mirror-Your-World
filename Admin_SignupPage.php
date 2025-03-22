<?php
require 'dbconnect.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Check if username or email already exists
        $checkQuery = "SELECT * FROM adminstbl WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username or Email already taken!";
        } else {
            // Insert new admin into the database
            $query = "INSERT INTO adminstbl (first_name, last_name, username, email, password_hash, created_at) 
                      VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $first_name, $last_name, $username, $email, $password_hash);

            if ($stmt->execute()) {
                header("Location: Admin_LoginPage.php?success=1"); // Redirect to login page
                exit();
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup | Mirror Your World</title>

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

                <form class="loginForm" method="POST" action="Admin_SignupPage.php">
                    <div class="txt_Title">
                        <br> <br>
                        <h2 class="txt_MYW"> Mirror Your World. </h2>
                        <h4 class="txt_Desc"> Welcome! </h4>
                    </div>

                    <div class="input-field">
                        <label><img src="Assets/icon_Profile.png" class="field-icon"></label>
                        <input id="first_name" name="first_name" type="text" placeholder="First Name" required>
                    </div>

                    <div class="input-field">
                        <label><img src="Assets/icon_Profile.png" class="field-icon"></label>
                        <input id="last_name" name="last_name" type="text" placeholder="Last Name" required>
                    </div>

                    <div class="input-field">
                        <label><img src="Assets/icon_Profile.png" class="field-icon"></label>
                        <input id="username" name="username" type="text" placeholder="Username" required>
                    </div>

                    <div class="input-field">
                        <label><img src="Assets/icon_Profile.png" class="field-icon"></label>
                        <input id="email" name="email" type="email" placeholder="Email" required>
                    </div>

                    <div class="input-field">
                        <label><img src="Assets/icon_lock.png" class="field-icon"></label>
                        <input id="password" name="password" type="password" placeholder="Password" required>
                    </div>

                    <div class="input-field">
                        <label><img src="Assets/icon_lock.png" class="field-icon"></label>
                        <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" required>
                    </div>

                    <button class="btn_login" type="submit" name="action">
                        Sign-Up
                    </button>

                    <p class="existingacc"> Already an admin? <a href="Admin_LoginPage.php" class="a1"> Login</a> </p>

                    <br /><br />

                    <!-- Display error message -->
                    <?php if (!empty($error_message)) { ?>
                        <div class="error-message" style="color: red;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>
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