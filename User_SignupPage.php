<?php
require_once 'dbconnect.php';

// Declare error and success message variables
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['fname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contactno']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirmpassword']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match. Please try again.";
    } else {
        // Check if username or email already exists in the database
        $check_query = "SELECT * FROM userstbl WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username or Email already exists. Please choose another one.";
        } else {
            // Hash the password for secure storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL insert query for user data (using prepared statement)
            $insert_query = "INSERT INTO userstbl (first_name, last_name, email, company_name, username, contact_number, password)
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $last_name, $email, $company_name, $username, $contact_number, $hashed_password);

            // Execute the insert query
            if (mysqli_stmt_execute($stmt)) {
                // Get the user_id of the newly inserted user
                $user_id = mysqli_insert_id($conn);

                // Log the registration action
                $log_query = "INSERT INTO logstbl (user_id, action_type) VALUES (?, 'Register')";
                $stmt = mysqli_prepare($conn, $log_query);
                mysqli_stmt_bind_param($stmt, "i", $user_id);

                // Execute the log query
                if (mysqli_stmt_execute($stmt)) {
                    // Instead of showing a success message in HTML, set the flag for SweetAlert
                    $success_message = "Registration successful!";
                } else {
                    $error_message = "Error logging action: " . mysqli_error($conn);
                }
            } else {
                $error_message = "Error registering user: " . mysqli_error($conn);
            }
        }
        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!-- HTML Form for the User Signup -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Mirror Your World</title>
    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_SignupPageCSS.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <!-- Required -->

  <div class="navbar">
    <a href="User_Homepage.php">About</a>
    <a href="User_Contactpage.php">Contact</a>
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

  <a href="User_InquiryPage.php" class="inquiry-icon">
        <img src="Assets/icon_FAQ.png" alt="Inquiry Icon" class="inquiry-icon1"/>
    </a>


  <div class="BGhome-container">
    <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">

      <center>
  
        <!-- Main Sign Up Form -->
        <div class="loginDiv">
            <form class="loginForm" method="POST" action="User_SignupPage.php">
                <div class="txt_Title">
                    <br> <br>
                    <h4 class="txt_Desc"> Welcome to </h4>
                    <h2 class="txt_MYW"> Mirror Your World! </h2>
                </div>

                <div class="input-field">
                    <label> <img src="Assets/icon_Name.png" class="field-icon"> </label>
                    <input id="fname" name="fname" type="text" placeholder="First Name" required>
                    <input id="lname" name="lname" type="text" placeholder="Last Name" required>
                </div>

                <div class="input-field">
                    <label> <img src="Assets/icon_email.png" class="field-icon"> </label>
                    <input id="email" name="email" type="text" placeholder="Email" required>
                    <input id="company" name="company" type="text" placeholder="Company Name (optional)">
                </div>

                <div class="input-field">
                    <label> <img src="Assets/icon_Profile.png" class="field-icon"> </label>
                    <input id="username" name="username" type="text" placeholder="Username" required>
                    <input id="contactno" name="contactno" type="text" placeholder="Contact No." required>
                </div>

                <div class="input-field">
                    <label> <img src="Assets/icon_lock.png" class="field-icon"> </label>
                    <input id="password" name="password" type="password" placeholder="Password" required>
                    <input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" required>
                </div>

                <button class="btn_login" type="submit" name="action" id="signup"> Sign Up </button>
                <p class="existingacc"> Already have an account? <a href="User_LoginPage.php" class="a1"> Login. </a> </p>
            </form>

            <!-- Display error or success messages -->
            <?php if (!empty($error_message)) { ?>
                <div class="error-message" style="color: red;">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>

        </div>
 
  </div>

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

    <?php if ($success_message) { ?>
        Swal.fire({
            title: 'Registration Successful!',
            text: 'You can now log in to your account.',
            icon: 'success',
            confirmButtonText: 'Go to Login',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = 'User_LoginPage.php';
            }
        });
    <?php } ?>
  </script>

</body>
</html>
