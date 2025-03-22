<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | Mirror Your World </title>
    <link rel="stylesheet" href="Style/User_AccountPageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>


    <?php
    session_start();

    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
        header('Location: Admin_LoginPage.php');
        exit;
    }

    include 'dbconnect.php';
    $admin_id = $_SESSION['admin_id'];

    $query = "SELECT first_name, last_name, email, username FROM adminstbl WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    function showAlert($message)
    {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '$message',
            confirmButtonText: 'OK'
        }).then(() => { window.location.href = 'Admin_AccountPage.php'; });
    </script>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_basic_info'])) {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];

            $update_query = "UPDATE adminstbl SET first_name = ?, last_name = ?, email = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param('sssi', $first_name, $last_name, $email, $admin_id);
            if ($stmt->execute()) showAlert('Your information has been updated successfully.');
        }

        if (isset($_POST['update_username'])) {
            $new_username = $_POST['new_username'];
            $update_username_query = "UPDATE adminstbl SET username = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($update_username_query);
            $stmt->bind_param('si', $new_username, $admin_id);
            if ($stmt->execute()) {
                $_SESSION['admin_username'] = $new_username;
                showAlert('Your username has been updated successfully.');
            }
        }

        if (isset($_POST['update_password'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                $password_check_query = "SELECT password_hash FROM adminstbl WHERE admin_id = ?";
                $stmt = $conn->prepare($password_check_query);
                $stmt->bind_param('i', $admin_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $admin = $result->fetch_assoc();

                if ($admin && password_verify($current_password, $admin['password_hash'])) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_password_query = "UPDATE adminstbl SET password_hash = ? WHERE admin_id = ?";
                    $stmt = $conn->prepare($update_password_query);
                    $stmt->bind_param('si', $hashed_password, $admin_id);
                    if ($stmt->execute()) showAlert('Your password has been updated successfully.');
                } else {
                    echo "<script>Swal.fire('Error', 'Current password is incorrect!', 'error');</script>";
                }
            } else {
                echo "<script>Swal.fire('Error', 'New passwords do not match!', 'error');</script>";
            }
        }

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
            $profile_picture = $_FILES['profile_picture']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($profile_picture);
            $upload_ok = 1;
            $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if ($_FILES["profile_picture"]["size"] > 2000000) {
                echo "<script>Swal.fire('Error', 'File is too large.', 'error');</script>";
                $upload_ok = 0;
            }
            if (!in_array($image_file_type, ["jpg", "png", "jpeg"])) {
                echo "<script>Swal.fire('Error', 'Only JPG, JPEG, and PNG files are allowed.', 'error');</script>";
                $upload_ok = 0;
            }
            if ($upload_ok && move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $update_picture_query = "UPDATE adminstbl SET profile_picture = ? WHERE admin_id = ?";
                $stmt = $conn->prepare($update_picture_query);
                $stmt->bind_param('si', $target_file, $admin_id);
                if ($stmt->execute()) showAlert('Your profile picture has been updated successfully.');
            }
        }
    }
    ?>


    <div class="navbar">
        <a href="Admin_ShowcasePage.php">Showcase</a>
        <a href="Admin_AppointmentsPage.php">Appointments</a>
        <!-- <a href="Admin_FAQPage.php">FAQ</a> -->
        <a href="Admin_ReviewsPage.php">Reviews</a>
    </div>

    <div class="logo">
        <img src="Assets/icon_Logo.png" alt="Logo" style="width: 30px">
    </div>

    <div class="profile-container" style="position: fixed; top: 10px; right: 20px; z-index: 1000; border-radius: 20px;">
        <button class="btn dropdown-trigger" data-target="dropdown1" style=" border-radius: 20px; padding: 0; background-color: transparent; border: none; cursor: pointer;" onclick="toggleDropdown()">
            <img src="Assets/icon_Profile.png" class="iconProfile" alt="Profile Icon" width="40px" height="40px" style="width: 25px; height: 25px; object-fit: cover; cursor: pointer; transition: filter 0.3s ease;" onmouseover="this.style.filter='invert(1)';" onmouseout="this.style.filter='invert(0)';" />
        </button>

        <br />
        <ul id="dropdown1" class="dropdown-content" style="transition: 0.3s; display: none; position: absolute; top: 60px; right: 0; background-color: white; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); width: 200px; padding: 0; margin: 0;">
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="Admin_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Account</a>
            </li>
            <form method="POST" action="Admin_LoginPage.php">
                <li style="list-style: none; margin: 0; padding: 10px;">
                    <a href="Admin_LogoutProcess.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
                </li>
            </form>
        </ul>
    </div>


    <div class="dashboard-container">
        <!-- Sidebar (Options) -->
        <div class="sidebar">
            <h3>Account Settings</h3>
            <a href="#about-me">Account Information</a>
            <!-- <a href="#change-profile-picture">Change Profile Picture</a> -->
            <a href="#change-basic-info">Change Basic Information</a>
            <a href="#change-username">Change Username</a>
            <a href="#change-password">Change Password</a>
        </div>

        <div class="content">
            <div class="section" id="about-me">
                <h2>Personal Information</h2>
                <div class="about-me-info">
                    <!-- <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Image" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;"> -->
                    <div>
                        <h3><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></h3>
                        <h3><strong>Full Name:</strong> <?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></h3>
                        <h3><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></h3>
                    </div>
                </div>
            </div>

            <!-- <div class="section" id="change-profile-picture">
                <h2>Change Profile Picture</h2>
                <form action="User_AccountPage.php" method="POST" enctype="multipart/form-data">
                    <label for="profile-picture">Upload New Picture</label>
                    <input type="file" id="profile-picture" name="profile_picture">
                    <button type="submit" name="update_profile_picture">Update Picture</button>
                </form>
            </div> -->

            <div class="section" id="change-basic-info">
                <h2>Change Basic Information</h2>
                <form action="Admin_AccountPage.php" method="POST">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">

                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

                    <button type="submit" name="update_basic_info">Update Information</button>
                </form>
            </div>


            <div class="section" id="change-username">
                <h2>Change Username</h2>
                <form action="Admin_AccountPage.php" method="POST">
                    <label for="new_username">New Username</label>
                    <input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($user['username']); ?>">

                    <button type="submit" name="update_username">Update Username</button>
                </form>
            </div>

            <div class="section" id="change-password">
                <h2>Change Password</h2>
                <form action="Admin_AccountPage.php" method="POST">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">

                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">

                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">

                    <button type="submit" name="update_password">Update Password</button>
                </form>
            </div>
        </div>
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
        // Check if the click is outside the dropdown or icon
        if (!event.target.matches('.dropdown-trigger') && !event.target.matches('.dropdown-trigger img')) {
            var dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(function(dropdown) {
                dropdown.style.display = 'none'; // Close the dropdown
            });
        }
    };
</script>

</html>