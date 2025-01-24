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
$query = "SELECT first_name, last_name, email, company_name,contact_number FROM userstbl WHERE user_id = ?"; // Use 'user_id' instead of 'id'
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

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | Mirror Your World </title>


    <link rel="stylesheet" href="Style/User_AccountPageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />

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
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s; ">
                <a href="User_Homepage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
            </li>
        </ul>
    </div>

    <a href="User_InquiryPage.php">
        <img src="Assets/icon_FAQ.png" alt="Inquiry Icon" class="inquiry-icon" />
    </a>

    <!-- Required -->


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

    <!-- Main Content (Forms to edit) -->
    <div class="content">
        <!-- About Me -->

        <div class="section" id="about-me">
            <h2>Personal Information</h2>
            <div class="about-me-info">
    <img src="Assets/client2.jpg" alt="Profile Image">
    <div>
    <h3><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></h3>
    <h3><strong>Full Name:</strong> <?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></h3>
    <h3><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></h3>
    <h3><strong>Company Name:</strong> <?php echo htmlspecialchars($user['company_name']); ?></h3>
    <h3><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contact_number']); ?></h3>


    </div>
</div>

        </div>

        <!-- Change Profile Picture -->
        <div class="section" id="change-profile-picture">
            <h2>Change Profile Picture</h2>
            <form>
                <label for="profile-picture">Upload New Picture</label>
                <input type="file" id="profile-picture" name="profile-picture">
                <button type="submit">Update Picture</button>
            </form>
        </div>

        <!-- Change Basic Information -->
        <div class="section" id="change-basic-info">
            <h2>Change Basic Information</h2>
            <form>
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="John Doe">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="johndoe@example.com">

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

        <!-- Change Username -->
        <div class="section" id="change-username">
            <h2>Change Username</h2>
            <form>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="john_doe_123">

                <button type="submit">Update Username</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="section" id="change-password">
            <h2>Change Password</h2>
            <form>
                <label for="current-password">Current Password</label>
                <input type="password" id="current-password" name="current-password">

                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password">

                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm-password">

                <button type="submit">Update Password</button>
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