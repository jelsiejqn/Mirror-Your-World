<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment | Mirror Your World</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_FormsPageCSS.css" />
</head>

<body>
    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'User_EmailAPI.php';
    include 'dbconnect.php';

    session_start();

    // Redirect to login if not logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: User_LoginPage.php');
        exit;
    }

    $update_query = "UPDATE appointmentstbl 
                 SET status = 'Confirmed' 
                 WHERE status = 'Pending' 
                 AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 24";

    $conn->query($update_query);

    // Fetch user details from the database
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

    // Fetch booked dates and times
    $booked_dates = [];
    $booked_slots = [];

    $query = "SELECT appointment_date, appointment_time FROM timeslotstbl WHERE is_booked = 1";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $booked_dates[] = $row['appointment_date'];
        $booked_slots[$row['appointment_date']][] = $row['appointment_time'];
    }

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $consultation_type = $_POST['consultation-type'];
        $appointment_date = $_POST['appointment-date'];
        $appointment_time = $_POST['time'];
        $special_requests = $_POST['request'];
        $address = $_POST['address'];

        // Validate input
        if (empty($consultation_type) || empty($appointment_date) || empty($appointment_time)) {
            echo "<script>alert('All fields are required!'); window.history.back();</script>";
            exit;
        }

        // Combine date and time for comparison
        $selected_datetime = new DateTime($appointment_date . ' ' . $appointment_time);
        $current_datetime = new DateTime();

        // Check if the selected appointment is at least one day in the future
        if ($selected_datetime < $current_datetime->modify('+1 day')->setTime(0, 0)) {
            echo "<script>alert('You must book at least one day in advance.'); window.history.back();</script>";
            exit;
        }

        // Check if the selected timeslot is already booked
        $check_query = "SELECT * FROM timeslotstbl WHERE appointment_date = ? AND appointment_time = ? AND is_booked = 1";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param('ss', $appointment_date, $appointment_time);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Timeslot Unavailable',
                text: 'This timeslot is already booked. Please choose another.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.history.back();
            });
        </script>";
            exit;
        }

        // Insert the booked timeslot into timeslotstbl
        $insert_timeslot_query = "INSERT INTO timeslotstbl (appointment_date, appointment_time, is_booked) VALUES (?, ?, 1)";
        $insert_timeslot_stmt = $conn->prepare($insert_timeslot_query);
        $insert_timeslot_stmt->bind_param('ss', $appointment_date, $appointment_time);
        $insert_timeslot_stmt->execute();
        $insert_timeslot_stmt->close();

        // Insert into appointmentstbl
        $query = "INSERT INTO appointmentstbl (user_id, consultation_type, appointment_date, appointment_time, address, special_requests, status) 
              VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isssss', $user_id, $consultation_type, $appointment_date, $appointment_time, $address, $special_requests);

        if ($stmt->execute()) {
            // Log the action
            $action_type = 'Appointment Booked Successfully';
            $log_query = "INSERT INTO logstbl (user_id, action_type, action_timestamp) VALUES (?, ?, NOW())";
            $log_stmt = $conn->prepare($log_query);
            $log_stmt->bind_param('is', $user_id, $action_type);
            $log_stmt->execute();
            $log_stmt->close();

            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $smtp_username;
                $mail->Password   = $smtp_password;
                $mail->SMTPSecure = $smtp_secure;
                $mail->Port       = $smtp_port;

                $mail->setFrom($smtp_sender, 'Mirror Your World');
                $mail->addAddress($user['email'], $user['first_name']);

                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body    = "Dear " . htmlspecialchars($user['first_name']) . ",<br><br>Your appointment has been successfully booked for " . htmlspecialchars($appointment_date) . " at " . htmlspecialchars($appointment_time) . ".<br><br>Consultation Type: " . htmlspecialchars($consultation_type) . "<br>Special Requests: " . htmlspecialchars($special_requests) . "<br><br>Thank you for choosing us!<br><br>Best regards,<br>Mirror Your World";

                $mail->send();
                echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Your consultation request has been submitted successfully! A confirmation email has been sent to you.',
            }).then(() => { window.location.href = 'User_Homepage.php'; });
          </script>";
            } catch (Exception $e) {
                echo "<script>
                    alert('Your consultation request has been submitted successfully! However, there was an error sending the confirmation email: {$mail->ErrorInfo}');
                    window.location.href = 'User_Homepage.php';
                  </script>";
            }
        } else {
            echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while booking your appointment. Please try again.',
        }).then(() => { window.history.back(); });
      </script>";
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <!-- Required -->

    <div class="navbar">
        <a href="User_Homepage.php">About</a>
        <a href="User_InquiryPage.php">FAQ</a>
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
                <a href="User_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">View Profile</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="User_ActiveBookings.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">My Appointments</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="User_LogoutProcess.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Required -->
    <center>
        <div class="BGhome-container">
            <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">
            <div class="txt_Tite">
                <br> <br><br> <br>
                <h2 class="txt_MYW"> Mirror Your World. </h2>
                <h4 class="txt_Desc"> Appointment and Consultation </h4>
            </div>
        </div>
    </center>

    <div class="form-container">
        <form action="User_FormsPage.php" method="POST">

            <!-- Full Name -->
            <div class="name-fields">
                <label for="full-name">Full Name</label>
                <p id="full-name" style="font-weight: bold;">
                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                </p>

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
                    <input type="date" id="appointment-date" name="appointment-date" required min="<?= date('Y-m-d'); ?>">
                </div>

                <!-- Appointment Time -->
                <div class="input-wrapper2">
                    <label for="time">Time of Appointment</label>
                    <select id="time" name="time" required>
                        <option value="" disabled selected>Select Time</option>
                        <option value="09:00:00">09:00 AM</option>
                        <option value="12:00:00">12:00 PM</option>
                        <option value="15:00:00">03:00 PM</option>
                        <option value="18:00:00">06:00 PM</option>
                    </select>
                </div>
            </div>

            <!-- Special Requests -->
            <div class="request-field">
                <div class="input-wrapper">
                    <label for="request">Consultation Site</label>
                    <input type="text" class="address1" id="request" name="address" placeholder="Full Address" required>
                </div>
            </div>

            <div class="request-field">
                <div class="input-wrapper">
                    <label for="request">Special Requests</label>
                    <input type="text" id="request" name="request" placeholder="e.g., Can you accommodate [ ] size?">
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
            dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
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

        document.getElementById('appointment-date').addEventListener('change', function() {
            const selectedDate = this.value;
            fetch(`get_available_slots.php?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    const timeSelect = document.getElementById('time');
                    timeSelect.innerHTML = '<option value="" disabled selected>Select Time</option>'; // Reset the dropdown

                    // Populate dropdown with available slots
                    data.available_times.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });

                    // Disable booked slots (optional)
                    data.booked_slots.forEach(slot => {
                        let option = timeSelect.querySelector(`option[value="${slot}"]`);
                        if (option) {
                            option.disabled = true;
                            option.style.color = 'gray';
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching available slots:', error);
                });
        });

        document.querySelector('.btn-submit').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission

            Swal.fire({
                title: 'Confirm Booking',
                text: 'Are you sure you want to request this appointment?',
                icon: 'warning',
                iconColor: '#2a2a2a', // Exclamation point color
                showCancelButton: true,
                confirmButtonColor: '#38853c',
                cancelButtonColor: '#c94747',
                confirmButtonText: 'Yes, confirm it!',
                customClass: {
                    title: 'swal-custom-font',
                    content: 'swal-custom-font',
                    confirmButton: 'swal-custom-font swal-custom-button',
                    cancelButton: 'swal-custom-font swal-custom-button'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').submit(); // Submit the form
                }
            });

            // Add custom font styling and button border radius
            const style = document.createElement('style');
            style.innerHTML = `
   
    .swal-custom-font {
      font-family: louis ;
    }
    .swal-custom-button {
      border-radius: 20px !important;
    }
     .swal-custom-text {
      font-family: louis ;
    }
  `;
            document.head.appendChild(style);
        });
    </script>

</body>

</html>