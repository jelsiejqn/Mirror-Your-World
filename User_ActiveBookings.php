<?php
session_start();
include 'dbconnect.php'; // Include your database connection file

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: User_LoginPage.php');
    exit;
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch active and cancelled bookings for the logged-in user
$query = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, 
                 a.consultation_type, a.address, 
                 u.first_name, u.last_name, u.email, u.contact_number,
                 a.is_cancelled, a.cancellation_reason
          FROM appointmentstbl a 
          JOIN timeslotstbl t ON a.appointment_date = t.appointment_date 
                             AND a.appointment_time = t.appointment_time 
          JOIN userstbl u ON a.user_id = u.user_id 
          WHERE a.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Categorize bookings
$activeBookings = [];
$cancelledBookings = [];

while ($row = $result->fetch_assoc()) {
    if ($row['is_cancelled'] == 1) {
        $cancelledBookings[] = $row;
    } else {
        $activeBookings[] = $row;
    }
}
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
        <a href="#" onclick="showContent('past-bookings')">Past Bookings</a>
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


        <?php if (!empty($activeBookings)): ?>
            <?php foreach ($activeBookings as $row): ?>
                <table class="booking-container">
                    <tr>
                        <td class="td-date"><h1><?= htmlspecialchars($row['appointment_date']) ?></h1></td>
                        <td class="td-details">
                            <h5>Consultation Type: <?= htmlspecialchars($row['consultation_type']) ?></h5>
                            <h5>Time of Appointment: <?= htmlspecialchars($row['appointment_time']) ?></h5>
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



        <!-- Past Bookings -->
        <div class="section" id="past-bookings" style="display: none;">
            <h2>Past Bookings</h2>


            <table class = "sortby-container">
            <tr>
                <td> <img src = "Assets/icon_sortBy.png" class = "sortby-icon"> </td>
                <td> <h4> Sort by: Most Recent </h4> </td>
                
            </tr>
            </table>

            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025</h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Name: Dionne Blacer  </h5>
                    <h5> Email: hello@gmail.com  </h5>
                    <h5> Contact Number: 09153628520  </h5>
                </td>

                <td class = "td-buttons"> 
                    <img src ="Assets/icon_Check.png" class = "completed-icon">
                </td>
                
            </tr>
            </table>

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
                    <td class="td-date"><h1><?= htmlspecialchars($row['appointment_date']) ?></h1></td>
                        <td class="td-details">
                            <h5>Consultation Type: <?= htmlspecialchars($row['consultation_type']) ?></h5>
                            <h5>Time of Appointment: <?= htmlspecialchars($row['appointment_time']) ?></h5>
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
            <label for="reason">Reason for Cancellation</label>
            <textarea name="reason" id="reason" required></textarea>
            <br>
            <br>
            <button type="submit" class="confirm-btn">Confirm Cancellation</button>
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

.popup h2 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #333;
}

textarea {
    width: 100%;
    height: 80px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none;
    font-size: 14px;
}

.confirm-btn {
    background-color: #FF5C5C;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 50%;
    font-size: 16px;
}

.confirm-btn:hover {
    background-color: #cc4b4b;
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