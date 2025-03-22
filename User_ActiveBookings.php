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

// Get the current date for comparison purposes
$current_date = date('Y-m-d H:i:s'); // Current date and time

// Fetch pending (cancellable) appointments for the logged-in user
$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Pending' AND a.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pendingBookings = $stmt->get_result();
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
$confirmedBookings = $stmt->get_result();
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

// Fetch past (completed) appointments for the logged-in user
$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number, u.user_id
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Completed' AND a.user_id = ? AND a.appointment_date < ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_date);
$stmt->execute();
$pastBookings = $stmt->get_result();
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

    <div class="navbar">
        <a href="User_Homepage.php">About</a>
        <a href="User_InquiryPage.php">FAQ</a>
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

    <div class="dashboard-container">
        <div class="sidebar">
            <h3>My Appointments</h3>
            <a href="#" onclick="showContent('pending')">Pending Bookings</a>
            <a href="#" onclick="showContent('active-bookings')">Confirmed Bookings</a>
            <a href="#" onclick="showContent('past-bookings')">Completed Bookings</a>
            <a href="#" onclick="showContent('cancelled')">Cancelled</a>
        </div>

        <div class="content">


            <div class="section" id="pending">
                <h2>Pending Bookings</h2>

                <table class="sortby-container">
                    <tr>
                        <td><img src="Assets/icon_sortBy.png" class="sortby-icon"></td>
                        <td>
                            <h4>Sort by: Most Recent</h4>
                        </td>
                    </tr>
                </table>

                <center>
                    <?php if ($pendingBookings->num_rows > 0): ?>
                        <?php while ($row = $pendingBookings->fetch_assoc()): ?>
                            <table class="booking-container">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1>
                                    </td>
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
                            <br>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <h5>No pending bookings found.</h5>
                    <?php endif; ?>
                </center>
            </div>


            <!-- Confirmed Bookings -->
            <div class="section" id="active-bookings">
                <h2>Confirmed Bookings</h2>

                <table class="sortby-container">
                    <tr>
                        <td><img src="Assets/icon_sortBy.png" class="sortby-icon"></td>
                        <td>
                            <h4>Sort by: Most Recent</h4>
                        </td>
                    </tr>
                </table>

                <center>
                    <?php if ($confirmedBookings->num_rows > 0): ?>
                        <?php while ($row = $confirmedBookings->fetch_assoc()): ?>
                            <table class="booking-container">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1>
                                    </td>
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
                                </tr>
                            </table>
                            <br>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <h5>No confirmed bookings found.</h5>
                    <?php endif; ?>
                </center>
            </div>


            <!-- Past Bookings -->
            <div class="section" id="past-bookings" style="display: none;">
                <h2>Completed Bookings</h2>
                <table class="sortby-container">
                    <tr>
                        <td><img src="Assets/icon_sortBy.png" class="sortby-icon"></td>
                        <td>
                            <h4>Sort by: Most Recent</h4>
                        </td>
                    </tr>
                </table>

                <center>
    <table class="booking-container">
        <?php if ($pastBookings->num_rows > 0): ?>
            <?php while ($row = $pastBookings->fetch_assoc()): ?>
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
                    <button class="review-button"
                            data-appointment-id="<?= $row['appointment_id'] ?>"
                            data-user-id="<?= $row['user_id'] ?>"
                            data-first-name="<?= htmlspecialchars($row['first_name']) ?>"
                            data-last-name="<?= htmlspecialchars($row['last_name']) ?>">
                        Review
                    </button>
                </td>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <h5>No past bookings found.</h5>
        <?php endif; ?>
    </table>
</center>
            </div>

            <div id="reviewModal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); justify-content: center; align-items: center;">
                <div style="background-color: #fff; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px;">
                    <span class="close" style="float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
                    <h2>Add Review</h2>
                    <form id="reviewForm" method="post" action="submit_review.php">
                        <input type="hidden" id="appointmentId" name="appointment_id" value="">
                        <input type="hidden" id="userId" name="user_id" value="">
                        <input type="hidden" id="reviewerFirstName" name="reviewer_first_name" value="">
                        <input type="hidden" id="reviewerLastName" name="reviewer_last_name" value="">
                        
                        <div style="margin-bottom: 15px;">
                            <label for="rating" style="display: block;">Rating (1-5):</label><br>
                            <input type="number" id="rating" name="rating" min="1" max="5" required style="width: 100%;">
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <label for="comment" style="display: block;">Comment:</label><br>
                            <textarea id="comment" name="comment" rows="5" required style="width: 100%;"></textarea>
                        </div>
                        
                        <input type="submit" value="Submit Review" style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; width: 100%;">
                    </form>
                </div>
            </div>


            <!-- Cancelled -->
            <div class="section" id="cancelled" style="display: none;">
                <h2>Cancelled</h2>

                <table class="sortby-container">
                    <tr>
                        <td> <img src="Assets/icon_sortBy.png" class="sortby-icon"> </td>
                        <td>
                            <h4> Sort by: Most Recent </h4>
                        </td>
                    </tr>
                </table>

                <center>
                    <?php if (!empty($cancelledBookings)): ?>
                        <?php foreach ($cancelledBookings as $row): ?>
                            <table class="booking-container">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1>
                                    </td>
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

                        <button type="submit" class="">
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

    document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('reviewModal');
    var buttons = document.querySelectorAll('.review-button');
    var span = document.getElementsByClassName('close')[0];

    console.log("Modal display on load:", modal.style.display); // Check initial display

    buttons.forEach(function(button) {
        button.onclick = function() {
            console.log("Review button clicked"); // Check if button is clicked
            document.getElementById('appointmentId').value = this.dataset.appointmentId;
            document.getElementById('reviewerFirstName').value = this.dataset.firstName;
            document.getElementById('reviewerLastName').value = this.dataset.lastName;
            modal.style.display = 'block';
            console.log("Modal display after button click:", modal.style.display); // Check display after click
        };
    });

    span.onclick = function() {
        console.log("Close button clicked"); // Check if close is clicked
        modal.style.display = 'none';
        console.log("Modal display after close:", modal.style.display); // Check display after close
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            console.log("Clicked outside modal"); // Check if clicked outside
            modal.style.display = 'none';
            console.log("Modal display after outside click:", modal.style.display); // Check display after outside click
        }
    };
});

</script>


</html>