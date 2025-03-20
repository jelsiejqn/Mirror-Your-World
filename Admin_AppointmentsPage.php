<?php
session_start(); // Ensure session starts before any output
include 'dbconnect.php';

// Debugging: Check if the session is active
if (!isset($_SESSION['admin_id'])) {
    die('Session is missing. Please log in again.');
}

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
$cancelledBookings = $conn->query($query);

// Debugging 
// echo isset($_SESSION['admin_id']) ? 'Session is active' : 'Session is missing';

$conn->close();
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments | Mirror Your World</title>
    <link rel="stylesheet" href="Style/Admin_AppointmentsPageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/Calendar.css" />
    <link rel="stylesheet" href="Style/Invoice.css" />
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
            <h2 class="calntime-title">Calendar Availability
                <br>
                <p class="calntime-description"> Please block off unavailable dates. </p>
            </h2>
            <center>
                <div id="calntime-datepicker"></div>
                <div class="calntime-buttons">
                    <button id="calntime-block">Block Date</button>
                    <button id="calntime-unblock">Unblock Date</button>
                    <button id="calntime-update">Update</button>
                </div>
            </center>
        </div>
    </div>

    <div class="profile-container" style="position: fixed; top: 10px; right: 20px; z-index: 1000; border-radius: 20px;">
        <button class="btn dropdown-trigger" data-target="dropdown1" style="border-radius: 20px; padding: 0; background-color: transparent; border: none; cursor: pointer;" onclick="toggleDropdown()">
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

    <!-- Required -->
    <div class="dashboard-container">
        <!-- Sidebar (Options) -->
        <div class="sidebar">
            <h3>Dashboard</h3>
            <a href="#" onclick="showContent('active-bookings')">Pending Appointments</a>
            <a href="#" onclick="showContent('past-bookings')">Confirmed Appointments</a>
            <a href="#" onclick="showContent('completed')">Completed Appointments</a>
            <a href="#" onclick="showContent('cancelled')">Cancelled</a>
        </div>

        <!-- Main Content (Forms to edit) -->
        <div class="content">
            <!-- Active Bookings -->
            <div class="section" id="active-bookings">
                <h2>Pending Appointments</h2>
                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDropdown" onchange="sortBookings()">
                                <option value="recent">Sort by</option>
                                <option value="recent">Most Recent</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <center>
                    <?php if ($pending_appointments->num_rows > 0): ?>
                        <?php while ($row = $pending_appointments->fetch_assoc()): ?>
                            <table class="booking-container">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1>
                                    </td>
                                    <td class="td-details">
                                        <h5> Type: <?php echo $row['consultation_type']; ?> <br>
                                            Time: <?= date('h:i A', strtotime($row['appointment_time'])) ?> <br>
                                            Site of Appointment: <br> <?php echo $row['address']; ?></h5>
                                    </td>
                                    <td class="td-booker">
                                        <h5>Name: <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?> <br>
                                            Email: <?php echo $row['email']; ?> <br>
                                            Contact: <?php echo $row['contact_number']; ?> </h5>
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
                        <h5>No pending bookings.</h5>
                    <?php endif; ?>
                </center>
            </div>

            <!-- Confirmed Bookings -->
            <div class="section" id="past-bookings" style="display: none;">
                <h2>Confirmed Appointments</h2>
                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDropdown" onchange="sortBookings()">
                                <option value="recent">Sort by</option>
                                <option value="recent">Most Recent</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <center>

                    <?php if ($confirmed_appointments && $confirmed_appointments->num_rows > 0): ?>
                        <?php while ($row = $confirmed_appointments->fetch_assoc()): ?>
                            <table class="booking-container">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo date('M d Y', strtotime($row['appointment_date'])); ?></h1>
                                    </td>
                                    <td class="td-details">
                                        <h5>Type: <?php echo htmlspecialchars($row['consultation_type']); ?> <br>
                                            Time: <?= date('h:i A', strtotime($row['appointment_time'])) ?><br>
                                            Site of Appointment: <br> <?php echo htmlspecialchars($row['address']); ?></h5>
                                    </td>
                                    <td class="td-booker">
                                        <h5>Name: <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?><br>
                                            Email: <?= htmlspecialchars($row['email']) ?><br>
                                            Contact: <?= htmlspecialchars($row['contact_number']) ?></h5>
                                    </td>
                                    <td class="td-buttons">
                                        <img src="Assets/icon_check.png" class="completed-icon">
                                    </td>
                                </tr>
                            </table>

                            <br>


                        <?php endwhile; ?>


                    <?php else: ?>
                        <h5>No confirmed bookings.</h5>
                    <?php endif; ?>

                </center>
            </div>


            <!-- Completed Bookings !!! HINDI PA NAPAPALITAN YUNG PHP!!! -->
            <div class="section" id="completed" style="display: none;">
                <h2>Completed</h2>
                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDropdown" onchange="sortBookings()">
                                <option value="recent">Sort by</option>
                                <option value="recent">Most Recent</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <center>
                    <table class="booking-container">

                        <tr>
                            <td class="td-date">
                                <h1> Nov 6 2003 </h1>
                            </td>
                            <td class="td-details">
                                <h5>Consultation Type: Acrylic <br>
                                    Time: 2pm <br>
                                    Site of Appointment: <br> Makati City</h5>
                            </td>
                            <td class="td-booker">
                                <h5>Name: Pwet <br>
                                    Email: rararara <br>
                                    Contact Number: rararaar </h5>
                            </td>
                            <td class="td-buttons">
                                <button class="btn-invoice-create" id="btn-create-invoice">Create Invoice</button>
                            </td>
                        </tr>
                    </table>
                </center>
            </div>





            <!-- Cancelled -->
            <div class="section" id="cancelled" style="display: none;">
                <h2>Cancelled Appointments</h2>
                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDropdown" onchange="sortBookings()">
                                <option value="recent">Sort by</option>
                                <option value="recent">Most Recent</option>
                                <option value="oldest">Oldest</option>
                            </select>
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
                                        <h5>Consultation Type: <?= htmlspecialchars($row['consultation_type']) ?><br>
                                            Time: <?= date('h:i A', strtotime($row['appointment_time'])) ?><br>
                                            Site of Appointment: <br> <?= htmlspecialchars($row['address']) ?></h5>
                                    </td>
                                    <td class="td-booker">
                                        <h5>Name: <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?><br>
                                            Email: <?php echo $row['email']; ?><br>
                                            Contact Number: <?php echo $row['contact_number']; ?><br>
                                            Reason for Cancellation: <?= htmlspecialchars($row['cancellation_reason']) ?></h5>
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
        </div>
    </div>

    <div id="cancelPopup" class="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="closeCancelPopup()">&times;</span>
            <h1 class="cancellation-title">Mirror Your World <br> Cancellation of Appointment</h1>
            <form id="cancelForm" method="POST" action="cancel_appointment.php">
                <input type="hidden" name="appointment_id" id="appointmentId">
                <hr>
                <label for="reason" class="radio-option"> Select a Reason for <br> Cancellation </label><br> <br>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="reason" value="Material Supply Issue" required>
                        <span>Material Supply Issue</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="reason" value="Weather Conditions" required>
                        <span>Weather Conditions</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="reason" value="Technical Issues" required>
                        <span>Technical Issues</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="reason" value="Other" required>
                        <span>Other</span>
                    </label>
                </div>
                <button type="submit" class="confirm-cancel-btn">
                    <h5 class="txt-confirm-cancel">Confirm Cancellation</h5>
                </button>
            </form>
        </div>
    </div>

    <!-- Modal for Invoice Creation -->
    <div class="modal-invoice" id="modal-invoice">
        <div class="modal-invoice-content">
            <span class="modal-invoice-close" id="btn-close-modal">&times;</span>

            <!-- Modal Title -->
            <br>
            <h1 class="invoice-title">Mirror Your World <br>
                <p class="invoice-sub">Create an Invoice for a Client <br> ------------------------------ Receipt ------------------------------</p>
                </h2>


                <!-- Pre-filled Information -->
                <div class="invoice-info">

                    <label for="user-name">Client:</label>
                    <input type="text" id="user-name" value="John Doe" disabled>

                    <label for="user-email">Email:</label>
                    <input type="email" id="user-email" value="johndoe@example.com" disabled>

                    <label for="user-contact">Contact:</label>
                    <input type="text" id="user-contact" value="123-456-7890" disabled>

                    <label for="service-type">Service Type:</label>
                    <input type="text" id="service-type" value="Glass Installation" disabled>

                    <label for="consultation-date">Consultation Date:</label>
                    <input type="date" id="consultation-date" value="2025-03-15" disabled>
                </div>

                <!-- Manual Inputs by Admin -->
                <div class="invoice-manual-inputs">
                    <label for="total-cost">Total Cost ($):</label>
                    <input type="number" id="total-cost" placeholder="Enter total cost" required>

                    <label for="invoice-notes">Notes/Description:</label>
                    <textarea id="invoice-notes" placeholder="Add any notes"></textarea>

                    <label for="tax-discount">Taxes/Discounts:</label>
                    <input type="number" id="tax-discount" placeholder="Enter tax/discount rate">
                </div>

                <!-- Action Buttons -->

                <div class="invoice-modal-actions">
                    <button class="btn-invoice-cancel" id="btn-cancel-invoice">Cancel</button>
                    <button class="btn-invoice-send" id="btn-send-invoice">Send</button>
                </div> <br>
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
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
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
            font-size: 1vw;
            cursor: pointer;
        }

        .radio-option input[type="radio"] {
            accent-color: rgb(178, 80, 80);
            transform: scale(1.2);
        }

        .popup h2 {
            font-size: 3vw;
            margin-bottom: 15px;
            color: #333;
        }

        .reason-txt {
            font-size: 1.5vw;
            text-align: left;
        }

        .close-btn {
            position: fixed;
            top: 0;
            right: 15;
            font-size: 2.5vw;
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



    // Get modal and buttons INVOICE!!!
    var modal = document.getElementById("modal-invoice");
    var btnCreateInvoice = document.getElementById("btn-create-invoice");
    var btnCloseModal = document.getElementById("btn-close-modal");
    var btnCancelInvoice = document.getElementById("btn-cancel-invoice");
    var btnSendInvoice = document.getElementById("btn-send-invoice");

    // Open the modal when the 'Create Invoice' button is clicked
    btnCreateInvoice.onclick = function() {
        modal.style.display = "block";
    }

    // Close the modal when the 'X' is clicked
    btnCloseModal.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when the 'Cancel' button is clicked
    btnCancelInvoice.onclick = function() {
        modal.style.display = "none";
    }

    // Action when the 'Send' button is clicked
    btnSendInvoice.onclick = function() {
        var totalCost = document.getElementById("total-cost").value;
        var notes = document.getElementById("invoice-notes").value;
        var taxDiscount = document.getElementById("tax-discount").value;

        // Do something with the data (e.g., generate the invoice, send email, etc.)
        console.log("Invoice Generated with Total Cost:", totalCost);
        console.log("Notes:", notes);
        console.log("Tax/Discount:", taxDiscount);

        // Close the modal after sending
        modal.style.display = "none";
    }

    //Sort Booking

    function sortBookings() {
        var sortByValue = document.getElementById('sortByDropdown').value;
        window.location.href = window.location.pathname + "?sort=" + sortByValue;
    }
</script>

</html>