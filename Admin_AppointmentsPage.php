<?php
session_start();
include 'dbconnect.php';
require 'User_EmailAPI.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Debugging: Check if the session is active
if (!isset($_SESSION['admin_id'])) {
    die('Session is missing. Please log in again.');
}

// Add this near the beginning of your PHP
$activeSection = isset($_GET['section']) ? $_GET['section'] : 'active-bookings';

// Check if the update button was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) { //check to make sure it is not the invoice form submission
    // Automatically update appointment statuses
    $conn->query("UPDATE appointmentstbl SET status = 'Confirmed' WHERE status = 'Pending' AND appointment_date <= NOW() - INTERVAL 1 DAY");
    $conn->query("UPDATE appointmentstbl SET status = 'Completed' WHERE appointment_date < NOW() AND status != 'Completed'");

    // Redirect back to the admin page with a success message
    header("Location: Admin_AppointmentsPage.php?status=success");
    exit;
}

// Get the sort parameter
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

// Modify the ORDER BY clause in your queries based on the sort parameter
$orderBy = ($sort === 'oldest') ? 'ORDER BY a.appointment_date ASC' : 'ORDER BY a.appointment_date DESC';

// Update all your queries to include the ORDER BY clause
$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Pending'
    $orderBy";
$pending_appointments = $conn->query($query);

$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Confirmed'
    $orderBy";
$confirmed_appointments = $conn->query($query);

$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Completed'
    $orderBy";
$completed_appointments = $conn->query($query);

$query = "
    SELECT a.*, u.first_name, u.last_name, u.email, u.contact_number
    FROM appointmentstbl a
    JOIN userstbl u ON a.user_id = u.user_id
    WHERE a.status = 'Cancelled'
    $orderBy";
$cancelledBookings = $conn->query($query);

// Invoice creation logic (create_invoice.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_invoice_form') {
    // Validate and sanitize input
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $total_cost = mysqli_real_escape_string($conn, $_POST['total_cost']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $tax_discount = mysqli_real_escape_string($conn, $_POST['tax_discount']);

    $sql = "INSERT INTO invoicestbl (user_id, total_cost, notes, tax_discount) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idsd", $user_id, $total_cost, $notes, $tax_discount);

    if ($stmt->execute()) {
        $_SESSION['popup_message'] = "Invoice created successfully!";
        $_SESSION['popup_type'] = "success";

        // Fetch user and appointment data for email
        $user_query = "SELECT email, first_name FROM userstbl WHERE user_id = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        if ($user_row = $user_result->fetch_assoc()) {
            $user_email = $user_row['email'];
            $user_first_name = $user_row['first_name'];

            //Fetch appointment data
            $appointment_query = "SELECT appointment_date, consultation_type, appointment_time FROM appointmentstbl WHERE user_id = ? AND status = 'Completed' ORDER BY appointment_date DESC LIMIT 1";
            $appointment_stmt = $conn->prepare($appointment_query);
            $appointment_stmt->bind_param("i", $user_id);
            $appointment_stmt->execute();
            $appointment_result = $appointment_stmt->get_result();
            if ($appointment_row = $appointment_result->fetch_assoc()) {
                $appointment_date = $appointment_row['appointment_date'];
                $consultation_type = $appointment_row['consultation_type'];
                $appointment_time = $appointment_row['appointment_time'];

                // Send confirmation email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host     = $smtp_host;
                    $mail->SMTPAuth = true;
                    $mail->Username = $smtp_username;
                    $mail->Password = $smtp_password;
                    $mail->SMTPSecure = $smtp_secure;
                    $mail->Port     = $smtp_port;

                    $mail->setFrom($smtp_sender, 'Mirror Your World');
                    $mail->addAddress($user_email, $user_first_name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Invoice Confirmation';
                    $mail->Body    = "Dear " . htmlspecialchars($user_first_name) . ",<br><br>Your invoice has been created, for your appointment on " . htmlspecialchars(date('M d Y', strtotime($appointment_date))) . " at " . htmlspecialchars(date('h:i A', strtotime($appointment_time))) . ".<br><br>Consultation Type: " . htmlspecialchars($consultation_type) . "<br>Total Cost: $" . htmlspecialchars($total_cost) . "<br>Notes: " . htmlspecialchars($notes) . "<br>Taxes/Discounts: " . htmlspecialchars($tax_discount) . "<br><br>Thank you for choosing us!<br><br>Best regards,<br>Mirror Your World";

                    $mail->send();
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Invoice created successfully! A confirmation email has been sent to you.',
                        });
                    </script>";
                } catch (Exception $e) {
                    echo "<script>
                        alert('Invoice created successfully! However, there was an error sending the confirmation email: {$mail->ErrorInfo}');
                    </script>";
                }
            } else {
                echo "<script>alert('Error: Appointment data not found for user.');</script>";
            }
        } else {
            echo "<script>alert('Error: User data not found.');</script>";
        }
    } else {
        $_SESSION['popup_message'] = "Error creating invoice. Please try again.";
        $_SESSION['popup_type'] = "error";
    }

    $stmt->close();
}

$conn->close();

if (isset($_SESSION['popup_message'])) {
    $message = htmlspecialchars($_SESSION['popup_message']);
    $type = $_SESSION['popup_type'];

    echo "<script>
        window.onload = function() {
            alert('" . $message . "');
        };
    </script>";

    unset($_SESSION['popup_message']);
    unset($_SESSION['popup_type']);
}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <!-- Required -->
    <div class="navbar">
        <a href="Admin_ShowcasePage.php">Showcase</a>
        <a href="Admin_AppointmentsPage.php">Appointments</a>
        <!-- <a href="Admin_FAQPage.php">FAQ</a> -->
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
                    <button id="calntime-block" type="button">Block Date</button>
                    <button id="calntime-unblock" type="button">Unblock Date</button>
                    <button id="calntime-update" type="button">Update</button>
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
        <?php 
        if(isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == 1) { 
        ?>
        <li style="list-style: none; margin: 0; padding: 10px;">
            <a href="Admin_SignupPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Add Admin</a>
        </li>
        <?php } ?>
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
                            <select id="sortByDropdown-active" class="sortByDropdown" onchange="sortBookings('active-bookings')">
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Sort by</option>
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Most Recent</option>
                                <option value="oldest" <?php echo ($sort === 'oldest') ? 'selected' : ''; ?>>Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
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
                                            Cancel
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
                            <select id="sortByDropdown-past" class="sortByDropdown" onchange="sortBookings('past-bookings')">
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Sort by</option>
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Most Recent</option>
                                <option value="oldest" <?php echo ($sort === 'oldest') ? 'selected' : ''; ?>>Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
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
                            <select id="sortByDropdown-completed" class="sortByDropdown" onchange="sortBookings('completed')">
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Sort by</option>
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Most Recent</option>
                                <option value="oldest" <?php echo ($sort === 'oldest') ? 'selected' : ''; ?>>Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>

                <center>
                    <?php if ($completed_appointments->num_rows > 0): ?>
                        <?php while ($row = $completed_appointments->fetch_assoc()): ?>
                            <?php
                            $appointment_data = [
                                'user_id' => $row['user_id'],
                                'user_name' => $row['first_name'] . ' ' . $row['last_name'],
                                'user_email' => $row['email'],
                                'user_contact' => $row['contact_number'],
                                'service_type' => $row['consultation_type'],
                                'consultation_date' => $row['appointment_date'],
                            ];
                            $appointment_json = json_encode($appointment_data);
                            ?>
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
                                        <button class="btn-invoice-create" onclick="populateInvoiceModal(<?php echo htmlspecialchars($appointment_json); ?>)">Create Invoice</button>
                                    </td>
                                </tr>
                            </table>
                            <br>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <h5>No past bookings.</h5>
                    <?php endif; ?>
                </center>
            </div>


            <!-- Cancelled -->
            <div class="section" id="cancelled" style="display: none;">
                <h2>Cancelled Appointments</h2>
                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDropdown-cancelled" class="sortByDropdown" onchange="sortBookings('cancelled')">
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Sort by</option>
                                <option value="recent" <?php echo ($sort === 'recent') ? 'selected' : ''; ?>>Most Recent</option>
                                <option value="oldest" <?php echo ($sort === 'oldest') ? 'selected' : ''; ?>>Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>
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
                <center>
                    <input type="hidden" name="appointment_id" id="appointmentId">
                    <hr>
                    <center>
                        <label for="reason" class="radio-option"> Select a Reason for Cancellation </label><br> <br>
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
                            Confirm Cancellation
                        </button>
            </form>
        </div>
    </div>

    <div class="modal-invoice" id="modal-invoice">
        <div class="modal-invoice-content">
            <span class="modal-invoice-close" id="btn-close-modal">&times;</span>
            <br>
            <h1 class="invoice-title">Mirror Your World <br>
                <p class="invoice-sub">Create an Invoice for a Client <br> ------------------------------ Receipt ------------------------------</p>
            </h1>
            <form method="POST" action="Admin_AppointmentsPage.php">
                <div class="invoice-info">
                    <label for="user-name">Client:</label>
                    <input type="text" id="user-name" disabled>
                    <label for="user-email">Email:</label>
                    <input type="email" id="user-email" disabled>
                    <label for="user-contact">Contact:</label>
                    <input type="text" id="user-contact" disabled>
                    <label for="service-type">Service Type:</label>
                    <input type="text" id="service-type" disabled>
                    <label for="consultation-date">Consultation Date:</label>
                    <input type="date" id="consultation-date" disabled>
                </div>
                <div class="invoice-manual-inputs">
                    <label for="total-cost">Total Cost ($):</label>
                    <input type="number" id="total-cost" name="total_cost" placeholder="Enter total cost" required>
                    <label for="invoice-notes">Notes/Description:</label>
                    <textarea id="invoice-notes" name="notes" placeholder="Add any notes"></textarea>
                    <label for="tax-discount">Taxes/Discounts:</label>
                    <input type="number" id="tax-discount" name="tax_discount" placeholder="Enter tax/discount rate">
                </div>
                <div class="invoice-modal-actions">
                    <button type="button" class="btn-invoice-cancel" id="btn-cancel-invoice">Cancel</button>
                    <button type="submit" class="btn-invoice-send" id="btn-send-invoice">Send</button>
                </div><br>
                <input type="hidden" id="invoice-user-id" name="user_id">
                <input type="hidden" name="action" value="create_invoice_form">
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
    var btnCloseModal = document.getElementById("btn-close-modal");
    var btnCancelInvoice = document.getElementById("btn-cancel-invoice");
    var btnSendInvoice = document.getElementById("btn-send-invoice");

    // Close the modal when the 'X' is clicked
    btnCloseModal.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when the 'Back' button is clicked
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

        // Get the currently active section
        var activeSection = '';
        var sections = document.querySelectorAll('.section');
        sections.forEach(function(section) {
            if (section.style.display === 'block') {
                activeSection = section.id;
            }
        });

        // Add the active section as a parameter
        window.location.href = window.location.pathname + "?sort=" + sortByValue + "&section=" + activeSection;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Show the active section based on URL parameter
        var activeSection = "<?php echo $activeSection; ?>";
        if (activeSection) {
            showContent(activeSection);
        }
    });

    function sortBookings(section) {
        // Find the dropdown in the current section
        var sortByValue = document.getElementById('sortByDropdown-' + section.split('-')[0]).value;

        // Redirect with both sort and section parameters
        window.location.href = window.location.pathname + "?sort=" + sortByValue + "&section=" + section;
    }

    function populateInvoiceModal(appointmentData) {
        document.getElementById('user-name').value = appointmentData.user_name;
        document.getElementById('user-email').value = appointmentData.user_email;
        document.getElementById('user-contact').value = appointmentData.user_contact;
        document.getElementById('service-type').value = appointmentData.service_type;
        document.getElementById('consultation-date').value = appointmentData.consultation_date;
        document.getElementById('invoice-user-id').value = appointmentData.user_id;

        document.getElementById('modal-invoice').style.display = "block";
    }

    // Calendar functionality
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("calntime-modal");
        const img = document.querySelector(".calntime-cal_img");
        const closeBtn = document.querySelector(".calntime-close");
        const blockBtn = document.getElementById("calntime-block");
        const unblockBtn = document.getElementById("calntime-unblock");
        const updateBtn = document.getElementById("calntime-update");

        // Store blocked dates
        let blockedDates = [];

        // Initialize calendar
        const calendar = flatpickr("#calntime-datepicker", {
            inline: true,
            enableTime: false,
            dateFormat: "Y-m-d",
            onReady: function() {
                // Load blocked dates from the database
                loadBlockedDates();
            }
        });

        // Function to load blocked dates from the database
        function loadBlockedDates() {
            fetch('get_blocked_dates.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        blockedDates = data.dates;
                        updateCalendarDisplay();
                    } else {
                        console.error("Failed to load blocked dates:", data.message);
                    }
                })
                .catch(error => console.error("Error loading blocked dates:", error));
        }

        // Function to update the calendar display with blocked dates
        function updateCalendarDisplay() {
            // Get all date cells in the calendar
            const dateCells = document.querySelectorAll(".flatpickr-day");

            // Reset all cells
            dateCells.forEach(cell => {
                cell.classList.remove("blocked-date");
            });

            // Mark blocked dates
            blockedDates.forEach(date => {
                dateCells.forEach(cell => {
                    const cellDate = cell.getAttribute("aria-label");
                    if (cellDate && cellDate === formatDate(date)) {
                        cell.classList.add("blocked-date");
                    }
                });
            });
        }

        // Format date to match flatpickr format
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
        }

        // Block date button click
        blockBtn.addEventListener("click", function() {
            const selectedDate = calendar.selectedDates[0];
            if (selectedDate) {
                const formattedDate = formatDateForDB(selectedDate);
                blockDate(formattedDate);
            } else {
                alert("Please select a date to block");
            }
        });

        // Unblock date button click
        unblockBtn.addEventListener("click", function() {
            const selectedDate = calendar.selectedDates[0];
            if (selectedDate) {
                const formattedDate = formatDateForDB(selectedDate);
                unblockDate(formattedDate);
            } else {
                alert("Please select a date to unblock");
            }
        });

        // Update calendar button click
        updateBtn.addEventListener("click", function() {
            // Reload blocked dates from the server
            loadBlockedDates();
            alert("Calendar updated successfully");
        });

        // Format date for database (YYYY-MM-DD)
        function formatDateForDB(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Function to block a date
        function blockDate(date) {
            const formData = new FormData();
            formData.append('action', 'block');
            formData.append('date', date);

            fetch('Admin_CalendarFunctions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add to local blocked dates array if not already there
                        if (!blockedDates.includes(date)) {
                            blockedDates.push(date);
                        }
                        updateCalendarDisplay();
                        alert("Date blocked successfully");
                    } else {
                        alert("Failed to block date: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error blocking date:", error);
                    alert("An error occurred while blocking the date");
                });
        }

        // Function to unblock a date
        function unblockDate(date) {
            const formData = new FormData();
            formData.append('action', 'unblock');
            formData.append('date', date);

            fetch('Admin_CalendarFunctions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove from local blocked dates array
                        blockedDates = blockedDates.filter(d => d !== date);
                        updateCalendarDisplay();
                        alert("Date unblocked successfully");
                    } else {
                        alert("Failed to unblock date: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error unblocking date:", error);
                    alert("An error occurred while unblocking the date");
                });
        }

        // Open modal
        img.onclick = function() {
            modal.style.display = "flex";
            loadBlockedDates(); // Refresh dates when opening
        }

        // Close modal
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
</script>

</html>