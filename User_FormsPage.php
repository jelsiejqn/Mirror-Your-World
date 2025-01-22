<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: User_LoginPage.php');
    exit;
}

// Fetch user details from the database
include 'dbconnect.php';
$query = "SELECT first_name, last_name, email FROM userstbl WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user) {
    header('Location: User_LoginPage.php');
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: User_LoginPage.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $consultation_type = $_POST['consultation-type'];
    $appointment_date = $_POST['appointment-date'];
    $appointment_time = $_POST['time'];
    $special_requests = $_POST['request'];

    // Validate input
    if (empty($consultation_type) || empty($appointment_date) || empty($appointment_time)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    // Prepare the query to insert consultation data
    $query = "INSERT INTO consultationstbl (user_id, consultation_type, appointment_date, appointment_time, special_requests) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issss', $user_id, $consultation_type, $appointment_date, $appointment_time, $special_requests);

    // Execute and handle success/error
    if ($stmt->execute()) {
        // Log the action in the logstbl
        $action_type = 'Consultation Submission';
        $log_query = "INSERT INTO logstbl (user_id, action_type, action_timestamp) VALUES (?, ?, NOW())";
        $log_stmt = $conn->prepare($log_query);
        $log_stmt->bind_param('is', $user_id, $action_type);
        $log_stmt->execute();
        $log_stmt->close();

        // Confirmation message and redirect
        echo "<script>
                alert('Your consultation request has been submitted successfully!');
                window.location.href = 'User_Homepage.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to submit your consultation request. Please try again later.');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment | Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_FormsPageCSS.css" />
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
            <a href="User_ViewProfile.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">View Profile</a>
        </li>
        <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
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

<!-- Required -->
<center> 
    <div class="BGhome-container">
        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">
        <div class="txt_Tite">
            <br><br><br>
            <h2 class="txt_MYW"> Mirror Your World. </h2>
            <h4 class="txt_Desc"> Appointment and Consultation </h4>
        </div>
    </div>
</center>

<div class="form-container">
    <form action="User_FormsPage.php" method="POST">

        <!-- Full Name -->
        <div class="name-fields">
            <div class="input-wrapper1">
                <label for="full-name">Full Name</label>
                <p id="full-name" style="font-weight: bold;">
                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                </p>
            </div>
        </div>

        <!-- Email -->
        <div class="input-wrapper">
            <label for="email">Email</label>
            <p id="email" style="font-weight: bold;">
                <?= htmlspecialchars($user['email']) ?>
            </p>
        </div>

        <!-- Consultation Type -->
        <div class="consultation-fields">
            <div class="input-wrapper2">
                <label for="consultation-type">Consultation Type</label>
                <select id="consultation-type" name="consultation-type" required>
                    <option value="" disabled selected style="color: gray">Select Type</option>
                    <option value="aluminum">Aluminum</option>
                    <option value="glass">Glass</option>
                    <option value="aluminum-and-glass">Aluminum and Glass</option>
                </select>
            </div>

            <!-- Appointment Date -->
            <div class="input-wrapper2">
                <label for="appointment-date">Date of Appointment</label>
                <input type="date" id="appointment-date" name="appointment-date" required>
            </div>

            <!-- Appointment Time -->
            <div class="input-wrapper2">
                <label for="time">Time of Appointment</label>
                <select id="time" name="time" required>
                    <option value="" disabled selected>Select Time</option>
                    <option value="9:00 AM">9:00 AM</option>
                    <option value="12:00 PM">12:00 PM</option>
                    <option value="3:00 PM">3:00 PM</option>
                    <option value="6:00 PM">6:00 PM</option>
                </select>
            </div>
        </div>

        <!-- Special Requests -->
        <div class="request-field">
            <div class="input-wrapper">
                <label for="request">Special Requests</label>
                <input type="text" id="request" name="request" placeholder="e.g., Can you accommodate [ ] size?" required>
            </div>
        </div>

        <!-- Buttons -->
        <div class="form-buttons">
            <button type="submit" class="btn-submit">Request Consultation</button>
            <button type="reset" class="btn-clear">Clear Form</button>
        </div>
    </form>
</div>

<script>
// Function to toggle the visibility of the dropdown content
function toggleDropdown() {
    var dropdown = document.getElementById('dropdown1');
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
            dropdown.style.display = 'none';
        });
    }
};
</script>

</body>
</html>
