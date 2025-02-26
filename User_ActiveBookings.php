<?php
session_start();
include 'dbconnect.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: User_LoginPage.php');
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch pending appointments for the logged-in user
$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Pending' AND a.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$activeBookings = $stmt->get_result();
$stmt->close();

// Fetch confirmed appointments for the logged-in user
$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Confirmed' AND a.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$confirmed_appointments = $stmt->get_result();
$stmt->close();

// Fetch cancelled appointments for the logged-in user
$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Cancelled' AND a.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cancelledBookings = $stmt->get_result();
$stmt->close();

$conn->close();
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Bookings | Mirror Your World </title>


    <link rel="stylesheet" href="Style/User_ActiveBookingsCSS.css" />
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
        <h3>My Appointments</h3>
        <a href="#" onclick="showContent('active-bookings')">Active Bookings</a>
        <a href="#" onclick="showContent('past-bookings')">Confirmed and Past Bookings</a>
        <a href="#" onclick="showContent('cancelled')">Cancelled</a>
    </div>

    <!-- Main Content (Forms to edit) -->
    <div class="content">

        <!-- Active Bookings -->
        <div class="section" id="active-bookings">
    <h2>Active Bookings</h2>
    
    <table class="sortby-container">
        <tr>
            <td> <img src="Assets/icon_sortBy.png" class="sortby-icon"> </td>
            <td> <h4> Sort by: Most Recent </h4> </td>
        </tr>
    </table>

<center>
        <?php if (!empty($activeBookings)): ?>
            <?php foreach ($activeBookings as $row): ?>
                <table class="booking-container">
                    <tr>
                    <td class="td-date"><h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1></td>
                        <td class="td-details">
                            <h5>Consultation Type: <?= htmlspecialchars($row['consultation_type']) ?></h5>
                            <h5>Time of Appointment: <?= date('h:i A', strtotime($row['appointment_time'])) ?></h5>
                            <h5>Site of Appointment: <?= htmlspecialchars($row['address']) ?></h5>
                        </td>
                        <td class="td-booker">
                            <h5>Name: <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h5>
                            <h5>Email: <?= htmlspecialchars($row['email']) ?></h5>
                            <h5>Contact Number: <?= htmlspecialchars($row['contact_number']) ?></h5>
                        </td>
                        <td class="td-buttons">
                            <button class="cancel-btn" onclick="openCancelPopup('<?= htmlspecialchars($row['appointment_id']) ?>')">
                                <h5 class="txt-cancel">Cancel</h5>
                            </button>
                        </td>
                    </tr>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <h5>No active bookings found.</h5>
        <?php endif; ?>
</div>
</center>


 <!-- Past Bookings -->
<div class="section" id="past-bookings" style="display: none;">
    <h2>Confirmed Appointments</h2>
    <table class="sortby-container">
        <tr>
            <td><img src="Assets/icon_sortBy.png" class="sortby-icon"></td>
            <td><h4>Sort by: Most Recent</h4></td>
        </tr>
    </table>

    <center>
    <table class="booking-container">
        <?php if ($confirmed_appointments && $confirmed_appointments->num_rows > 0): ?>
            <?php while ($row = $confirmed_appointments->fetch_assoc()): ?>
            <tr>
                <td class="td-date">
                    <h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1>
                </td>
                <td class="td-details">
                    <h5>Consultation Type: <?php echo htmlspecialchars($row['consultation_type']); ?></h5>
                    <h5>Time of Appointment: <?php echo htmlspecialchars($row['appointment_time']); ?></h5>
                    <h5>Site of Appointment: <?php echo htmlspecialchars($row['address']); ?></h5>
                </td>
                <td class="td-booker">
                    <h5>Name: <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h5>
                    <h5>Email: <?= htmlspecialchars($row['email']) ?></h5>
                    <h5>Contact Number: <?= htmlspecialchars($row['contact_number']) ?></h5>
                </td>
                <td class="td-buttons">
                    <img src="Assets/icon_check.png" class="completed-icon">
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;"><h5>No confirmed appointments.</h5></td>
            </tr>
        <?php endif; ?>
    </table>
    </center>
</div>



        <!-- Cancelled -->
        <div class="section" id="cancelled" style="display: none;">
    <h2>Cancelled</h2>

    <table class="sortby-container">
        <tr>
            <td> <img src="Assets/icon_sortBy.png" class="sortby-icon"> </td>
            <td> <h4> Sort by: Most Recent </h4> </td>
        </tr>
    </table>

    <center>
        <?php if (!empty($cancelledBookings)): ?>
            <?php foreach ($cancelledBookings as $row): ?>
                <table class="booking-container">
                    <tr>
                    <td class="td-date"><h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1></td>
                        <td class="td-details">
                            <h5>Consultation Type: <?= htmlspecialchars($row['consultation_type']) ?></h5>
                            <h5>Time of Appointment: <?= date('h:i A', strtotime($row['appointment_time'])) ?></h5>
                            <h5>Site of Appointment: <?= htmlspecialchars($row['address']) ?></h5>
                        </td>
                        <td class="td-booker">
                            <h5>Reason for Cancellation</h5>
                            <h5><?= htmlspecialchars($row['cancellation_reason']) ?></h5>
                        </td>
                        <td class="td-buttons">
                            <img src="Assets/icon_X.png" class="completed-icon">
                        </td>
                    </tr>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <h5>No cancelled bookings found.</h5>
        <?php endif; ?>
    </center>
</div>


<div id="cancelPopup" class="popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closeCancelPopup()">&times;</span>
        <h2>Cancel Appointment</h2>
        <form id="cancelForm" method="POST" action="cancel_appointment.php">
            <input type="hidden" name="appointment_id" id="appointmentId">
            <label for="reason">Reason for Cancellation</label><br>
            
            <div class="radio-group">
                <label class="radio-option">
                    <input type="radio" name="reason" value="Personal Reasons" required>
                    <span>Personal Reasons</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="reason" value="Scheduling Conflict" required>
                    <span>Scheduling Conflict</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="reason" value="Health Issues" required>
                    <span>Health Issues</span>
                </label>

                <label class="radio-option">
                    <input type="radio" name="reason" value="Other" required>
                    <span>Other</span>
                </label>
            </div>

            <button type="submit" class="cancel-btn">
                <h5 class="txt-cancel">Confirm Cancellation</h5>
            </button>
        </form>
    </div>
</div>

<style>

.popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 20px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    z-index: 1000;
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.popup-content {
    text-align: center;
    position: relative;
}

.radio-group {
    display: flex;
    flex-direction: column;
    align-items: start;
    margin-top: 10px;
    gap: 10px;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    cursor: pointer;
}

.radio-option input[type="radio"] {
    accent-color: #FF5C5C; 
    transform: scale(1.2);
}

.popup h2 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #333;
}


.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 22px;
    cursor: pointer;
    color: #777;
    transition: color 0.3s;
}

.close-btn:hover {
    color: #333;
}

</style>

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

// Function to show content when a sidebar link is clicked
function showContent(sectionId) {
    // Hide all sections
    var sections = document.querySelectorAll('.section');
    sections.forEach(function(section) {
        section.style.display = 'none';
    });

    // Show the clicked section
    var activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.style.display = 'block';
    }
}
function openCancelPopup(appointmentId) {
    document.getElementById('appointmentId').value = appointmentId;
    let popup = document.getElementById('cancelPopup');
    popup.style.display = 'block';
    popup.style.opacity = '1';
    popup.style.transform = 'translate(-50%, -50%) scale(1)';
}

function closeCancelPopup() {
    let popup = document.getElementById('cancelPopup');
    popup.style.opacity = '0';
    popup.style.transform = 'translate(-50%, -50%) scale(0.9)';
    setTimeout(() => {
        popup.style.display = 'none';
    }, 200);
}

   
</script>
    

</html>