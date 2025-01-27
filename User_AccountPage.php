<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | Mirror Your World </title>
    <link rel="stylesheet" href="Style/User_AccountPageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
</head>

<body>

<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);

if (!$isLoggedIn) {
    // If not logged in, redirect to the login page
    header('Location: User_LoginPage.php');
    exit;
}

// Fetch user data from the session
$user_id = $_SESSION['user_id']; // Assuming the session stores 'user_id'
$username = $_SESSION['username'];

// Include database connection file
include 'dbconnect.php';

// Fetch additional user information from the database
$query = "SELECT first_name, last_name, email, company_name, contact_number, username, profile_picture FROM userstbl WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id); // 'i' indicates integer type for 'user_id'
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    // Fetch the user data from the result
    $user = $result->fetch_assoc();
} else {
    // If no user found, redirect to login page
    header('Location: User_LoginPage.php');
    exit;
}

// Handle form submission to update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update Basic Information (First Name, Last Name, Email, Company Name, Contact Number)
    if (isset($_POST['update_basic_info'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $company_name = $_POST['company_name'];
        $contact_number = $_POST['contact_number'];

        // Update query for basic information
        $update_query = "UPDATE userstbl SET first_name = ?, last_name = ?, email = ?, company_name = ?, contact_number = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('sssssi', $first_name, $last_name, $email, $company_name, $contact_number, $user_id);

        if ($stmt->execute()) {
            // If update successful, refresh the page to reflect changes
            header('Location: User_AccountPage.php');
            exit;
        } else {
            // Handle error
            echo "Error updating record: " . $conn->error;
        }
    }


    // Update Username
    if (isset($_POST['update_username'])) {
        $new_username = $_POST['new_username'];

        // Update query for username
        $update_username_query = "UPDATE userstbl SET username = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_username_query);
        $stmt->bind_param('si', $new_username, $user_id);

        if ($stmt->execute()) {
            // If update successful, refresh the page to reflect changes
            header('Location: User_AccountPage.php');
            exit;
        } else {
            echo "Error updating username: " . $conn->error;
        }
    }

    // Update Password
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            // Check if current password is correct
            $password_check_query = "SELECT password FROM userstbl WHERE user_id = ?";
            $stmt = $conn->prepare($password_check_query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->bind_result($stored_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($current_password, $stored_password)) {
                // Update query for password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password_query = "UPDATE userstbl SET password = ? WHERE user_id = ?";
                $stmt = $conn->prepare($update_password_query);
                $stmt->bind_param('si', $hashed_password, $user_id);

                if ($stmt->execute()) {
                    // If update successful, refresh the page to reflect changes
                    header('Location: User_AccountPage.php');
                    exit;
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "Current password is incorrect!";
            }
        } else {
            echo "New passwords do not match!";
        }
    }

    // Handle Profile Picture Update (if necessary)
    if (isset($_FILES['profile_picture'])) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "uploads/"; // Specify your target directory
        $target_file = $target_dir . basename($profile_picture);
        $upload_ok = 1;

        // Check file size (limit to 2MB)
        if ($_FILES["profile_picture"]["size"] > 2000000) {
            echo "Sorry, your file is too large.";
            $upload_ok = 0;
        }

        // Allow certain file formats
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
            echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
            $upload_ok = 0;
        }

        // Check if upload is allowed
        if ($upload_ok == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update query for profile picture
                $update_picture_query = "UPDATE userstbl SET profile_picture = ? WHERE user_id = ?";
                $stmt = $conn->prepare($update_picture_query);
                $stmt->bind_param('si', $target_file, $user_id);

                if ($stmt->execute()) {
                    // If update successful, refresh the page to reflect changes
                    header('Location: User_AccountPage.php');
                    exit;
                } else {
                    echo "Error updating profile picture: " . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>

    <div class="navbar">
        <a href="User_Homepage.php">About</a>
        <a href="User_Contactpage.php">Contact</a>
        <a href="User_Showcase.php">Showcase</a>
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
                <a href="User_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Account</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s; ">
                <a href="User_ActiveBookings.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Active Bookings</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="User_LogoutProcess.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
            </li>
        </ul>
    </div>

    <a href="User_InquiryPage.php">
        <img src="Assets/icon_FAQ.png" alt="Inquiry Icon" class="inquiry-icon" />
    </a>

<div class="dashboard-container">
    <!-- Sidebar (Options) -->
    <div class="sidebar">
        <h3>Account Settings</h3>
        <a href="#about-me">About Me</a>
        <a href="#change-profile-picture">Change Profile Picture</a>
        <a href="#change-basic-info">Change Basic Information</a>
        <a href="#change-address">Change Address</a>
        <a href="#change-username">Change Username</a>
        <a href="#change-password">Change Password</a>
    </div>

        <div class="content">
            <div class="section" id="about-me">
                <h2>Personal Information</h2>
                <div class="about-me-info">
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Image" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <h3><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></h3>
                        <h3><strong>Full Name:</strong> <?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></h3>
                        <h3><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></h3>
                        <h3><strong>Company Name:</strong> <?php echo htmlspecialchars($user['company_name']); ?></h3>
                        <h3><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contact_number']); ?></h3>
                    </div>
                </div>
            </div>

            <div class="section" id="change-profile-picture">
                <h2>Change Profile Picture</h2>
                <form action="User_AccountPage.php" method="POST" enctype="multipart/form-data">
                    <label for="profile-picture">Upload New Picture</label>
                    <input type="file" id="profile-picture" name="profile_picture">
                    <button type="submit" name="update_profile_picture">Update Picture</button>
                </form>
            </div>

            <div class="section" id="change-basic-info">
            <h2>Change Basic Information</h2>
            <form action="User_AccountPage.php" method="POST">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">

                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

                <button type="submit">Update Information</button>
            </form>
        </div>

        <!-- Change Address -->
        <div class="section" id="change-address">
            <h2>Change Address</h2>
            <form>
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="123 Main St, Springfield">

                <button type="submit">Update Address</button>
            </form>
        </div>

            <div class="section" id="change-username">
                <h2>Change Username</h2>
                <form action="User_AccountPage.php" method="POST">
                    <label for="new_username">New Username</label>
                    <input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($user['username']); ?>">

                    <button type="submit" name="update_username">Update Username</button>
                </form>
            </div>

            <div class="section" id="change-password">
                <h2>Change Password</h2>
                <form action="User_AccountPage.php" method="POST">
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
