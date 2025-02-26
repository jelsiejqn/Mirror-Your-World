<?php
include 'dbconnect.php';


// Check if the update button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Automatically update appointment statuses
    $conn->query("UPDATE appointmentstbl SET status = 'Confirmed' WHERE status = 'Pending' AND appointment_date <= NOW() - INTERVAL 1 DAY");
    $conn->query("UPDATE appointmentstbl SET status = 'Completed' WHERE appointment_date < NOW() AND status != 'Completed'");


    // Redirect back to the admin page with a success message
    header("Location: Admin_AppointmentsPage.php?status=success");
    exit;
}


// Fetch booked appointments with user details
$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Pending'";
$pending_appointments = $conn->query($query);


$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Confirmed'";
$confirmed_appointments = $conn->query($query);


$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Cancelled'";
$cancelled_appointments = $conn->query($query);


$conn->close();


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments | Mirror Your World </title>


    <link rel="stylesheet" href="Style/Admin_AppointmentsPageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/Calendar.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


</head>
<body>
    
   <!-- Required -->

   <div class="navbar">
        <a href="Admin_ShowcasePage.php">Showcase</a>
        <a href="Admin_AppointmentsPage.php">Appointments</a>
        <a href="Admin_FAQPage.php">FAQ</a>
        <a href="Admin_ReviewsPage.php">Reviews</a>
        
    </div>

   <!-- Calendar -->

   <div class="logo">
        <img src="Assets/icon_calendar.png" class="calntime-cal_img" alt="calendar" style="width: 30px; cursor: pointer;">
    </div>

    <div id="calntime-modal" class="calntime-modal" style="display: none;">
        <div class="calntime-modal-content">
            <span class="calntime-close">&times;</span>
            <h2 class = "calntime-title">Calendar Availability
                <br> <p class = "calntime-description"> Please block off unavailable dates. </p> 

            </h2>
            
            <center>
            <div id="calntime-datepicker"></div>
            <div class="calntime-buttons">
                <button id="calntime-block">Block Date</button>
                <button id="calntime-unblock">Unblock Date</button>
                <button id="calntime-update">Update</button>
            </div>
        </div>
    </div>
   

     <!-- Calendar -->


    <div class="profile-container" style="position: fixed; top: 10px; right: 20px; z-index: 1000; border-radius: 20px;">
        <button class="btn dropdown-trigger" data-target="dropdown1" style=" border-radius: 20px; padding: 0; background-color: transparent; border: none; cursor: pointer;" onclick="toggleDropdown()">
            <img src="Assets/icon_Profile.png" class="iconProfile" alt="Profile Icon" width="40px" height="40px" style="width: 25px; height: 25px; object-fit: cover; cursor: pointer; transition: filter 0.3s ease;" onmouseover="this.style.filter='invert(1)';" onmouseout="this.style.filter='invert(0)';" />
        </button>
        
        <br />
        <ul id="dropdown1" class="dropdown-content" style="transition: 0.3s; display: none; position: absolute; top: 60px; right: 0; background-color: white; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); width: 200px; padding: 0; margin: 0;">
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="Admin_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Account</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s; ">
                <a href="Admin_LoginPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Required -->


    <div class="dashboard-container">
    <!-- Sidebar (Options) -->
    <div class="sidebar">
        <h3>Dashboard</h3>
        <a href="#" onclick="showContent('active-bookings')">Pending Appointments</a>
        <a href="#" onclick="showContent('past-bookings')">Confirmed Appointments</a>
        <a href="#" onclick="showContent('cancelled')">Cancelled </a>
    </div>

    <!-- Main Content (Forms to edit) -->
    <div class="content">

        <!-- Active Bookings -->
        <div class="section" id="active-bookings">
            <h2>Pending Appointments</h2> 
            <!-- Yung 12 hours pagitan na pwede silamagcancel both client and user -->
            
            <table class = "sortby-container">
            <tr>
                <td> <img src = "Assets/icon_sortBy.png" class = "sortby-icon"> </td>
                <td> <h4> Sort by: Most Recent </h4> </td>
                
            </tr>
            </table>

            <center>
    <?php while ($row = $pending_appointments->fetch_assoc()): ?>
    <table class="booking-container">
        <tr>
            <td class="td-date"><h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1></td>
            <td class="td-details">
                <h5>Consultation Type: <?php echo $row['consultation_type']; ?></h5>
                <h5>Time of Appointment: <?php echo $row['appointment_time']; ?></h5>
                <h5>Site of Appointment: <?php echo $row['address']; ?></h5>
            </td>
            <td class="td-booker">
                <h5>Name: <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h5>
                <h5>Email: <?php echo $row['email']; ?></h5>
                <h5>Contact Number: <?php echo $row['contact_number']; ?></h5>
            </td>
            <td class="td-buttons">
                <button class="cancel-btn" onclick="openModal()">
                    <h5 class="txt-cancel"> Cancel </h5>
                </button>
            </td>
            </center>
        </tr>

    </table>

    <br> 
    <?php endwhile; ?>

<div id="cancelModal" class="modal">
    <div class="modal-content">
        <h3>Please select the reason for cancellation:</h3>
        <form id="cancelReasonForm">
            <label>
                <input name="reason" type="radio" value="Scheduling Conflicts" />
                Scheduling Conflicts
            </label><br>
            <label>
                <input name="reason" type="radio" value="Material Requested Not Available" />
                Material Requested Not Available
            </label><br>
            <label>
                <input name="reason" type="radio" value="Project out of Scope" />
                Project out of Scope
            </label><br>
            <label>
                <input name="reason" type="radio" value="Client needs to Rebook" />
                Client needs to Rebook
            </label><br>
        </form>
        <div class="modal-buttons">
            <button class="submit-btn" onclick="submitCancel()">Submit</button>
            <button class="close-btn" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>
                
            </tr>
            </table>  
            

        </div>

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
    <h2>Cancelled Appointments</h2>
    <table class="sortby-container">
        <tr>
            <td><img src="Assets/icon_sortBy.png" class="sortby-icon"></td>
            <td><h4>Sort by: Most Recent</h4></td>
        </tr>
    </table>


    <center>
    <table class="booking-container">
        <?php while ($row = $cancelled_appointments->fetch_assoc()): ?>
            <tr>
            <td class="td-date"><h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1></td>
            <td class="td-details">
                <h5>Consultation Type: <?php echo $row['consultation_type']; ?></h5>
                <h5>Time of Appointment: <?php echo $row['appointment_time']; ?></h5>
                <h5>Site of Appointment: <?php echo $row['address']; ?></h5>
            </td>
            <td class="td-booker">
                <h5>Name: <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h5>
                <h5>Email: <?php echo $row['email']; ?></h5>
                <h5>Contact Number: <?php echo $row['contact_number']; ?></h5>
                <h5><?php echo "Reason of Cancellation: " . $row['cancellation_reason']; ?></h5>
            </td>
            <td class="td-buttons">
                <img src="Assets/icon_X.png" class="completed-icon">
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </center>
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

function openModal() {
        document.getElementById('cancelModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('cancelModal').classList.remove('show');
    }

    function submitCancel() {
        const selectedReason = document.querySelector('input[name="reason"]:checked');
        if (selectedReason) {
            alert('Cancellation reason submitted: ' + selectedReason.value);
            closeModal();
        } else {
            alert('Please select a reason before submitting.');
        }
    }

     // Calendar

     document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("calntime-modal");
            const img = document.querySelector(".calntime-cal_img");
            const closeBtn = document.querySelector(".calntime-close");
            
            flatpickr("#calntime-datepicker", {
                inline: true,
                enableTime: false,
                dateFormat: "Y-m-d"
            });
            
            img.onclick = function() {
                modal.style.display = "flex";
            }
            
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }
            
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
   
</script>
    

</html>