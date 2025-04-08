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

    // Validate inputs
    $validation_error = false;

    // Validate names (letters and spaces only)
    if (!preg_match("/^[a-zA-Z ]+$/", $first_name)) {
        $error_message = "First name should contain only letters and spaces.";
        $validation_error = true;
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $last_name)) {
        $error_message = "Last name should contain only letters and spaces.";
        $validation_error = true;
    }
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
        $validation_error = true;
    }
    // Validate username (alphanumeric and underscore, 4-20 characters)
    elseif (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $username)) {
        $error_message = "Username must be 4-20 characters and contain only letters, numbers, and underscores.";
        $validation_error = true;
    }
    // Validate contact number (exactly 11 digits)
    elseif (!preg_match("/^[0-9]{11}$/", $contact_number)) {
        $error_message = "Contact number should contain exactly 11 digits.";
        $validation_error = true;
    }
    // Validate password strength (at least 8 characters, with at least one uppercase, one lowercase, and one number)
    elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $error_message = "Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, and one number.";
        $validation_error = true;
    }
    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match. Please try again.";
        $validation_error = true;
    }

    // If no validation errors, proceed with registration
    if (!$validation_error) {
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
                    // Set the flag for SweetAlert
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
                        <input id="email" name="email" type="email" placeholder="Email" required>
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

                    <!-- Display error or success messages -->
                    <?php if (!empty($error_message)) { ?>
                        <div class="error-message" style="color: red;">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>

                </form>



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

        // Client-side validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('signupForm');

            form.addEventListener('submit', function(event) {
                let isValid = true;
                const fname = document.getElementById('fname').value.trim();
                const lname = document.getElementById('lname').value.trim();
                const email = document.getElementById('email').value.trim();
                const username = document.getElementById('username').value.trim();
                const contactno = document.getElementById('contactno').value.trim();
                const password = document.getElementById('password').value;
                const confirmpassword = document.getElementById('confirmpassword').value;

                // Clear previous error messages
                const errorDiv = document.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.remove();
                }

                // Validate first name (letters and spaces only)
                if (!/^[a-zA-Z ]+$/.test(fname)) {
                    showError("First name should contain only letters and spaces.");
                    isValid = false;
                }
                // Validate last name (letters and spaces only)
                else if (!/^[a-zA-Z ]+$/.test(lname)) {
                    showError("Last name should contain only letters and spaces.");
                    isValid = false;
                }
                // Validate email format
                else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
                    showError("Please enter a valid email address.");
                    isValid = false;
                }
                // Validate username (alphanumeric and underscore, 4-20 characters)
                else if (!/^[a-zA-Z0-9_]{4,20}$/.test(username)) {
                    showError("Username must be 4-20 characters and contain only letters, numbers, and underscores.");
                    isValid = false;
                }
                // Validate contact number (exactly 11 digits)
                else if (!/^[0-9]{11}$/.test(contactno)) {
                    showError("Contact number should contain exactly 11 digits.");
                    isValid = false;
                }
                // Validate password strength
                else if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(password)) {
                    showError("Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, and one number.");
                    isValid = false;
                }
                // Check if passwords match
                else if (password !== confirmpassword) {
                    showError("Passwords do not match. Please try again.");
                    isValid = false;
                }

                // Prevent form submission if validation fails
                if (!isValid) {
                    event.preventDefault();
                }
            });

            // Function to show error messages
            function showError(message) {
                const loginDiv = document.querySelector('.loginDiv');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.style.color = 'red';
                errorDiv.textContent = message;
                loginDiv.appendChild(errorDiv);
            }
        });

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