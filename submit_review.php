<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

</body>
</html>
<?php
require "dbconnect.php";

session_start(); // Start the session to access user ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentId = $_POST['appointment_id'];
    $reviewerFirstName = $_POST['reviewer_first_name'];
    $reviewerLastName = $_POST['reviewer_last_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $userId = $_SESSION['user_id']; // Get user ID from session

    // Check if a review already exists for this appointment
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM reviewstbl WHERE appointment_id = ?");
    $checkStmt->bind_param("i", $appointmentId);
    $checkStmt->execute();
    $checkStmt->bind_result($reviewCount);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($reviewCount > 0) {
        // A review already exists
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Review Already Exists!',
                text: 'A review has already been submitted for this appointment.',
            }).then(function() {
                window.location.href = 'User_ActiveBookings.php'; // Redirect after SweetAlert
            });
        </script>";
    } else {
        // No review exists, proceed with insertion
        $stmt = $conn->prepare("INSERT INTO reviewstbl (appointment_id, user_id, reviewer_first_name, reviewer_last_name, rating, comment) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiss", $appointmentId, $userId, $reviewerFirstName, $reviewerLastName, $rating, $comment);

        if ($stmt->execute()) {
            // Use SweetAlert for success message
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Review submitted successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'User_ActiveBookings.php'; // Redirect after SweetAlert
                });
            </script>";

            exit();
        } else {
            // Use SweetAlert for error message and redirect
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '" . $stmt->error . "',
                }).then(function() {
                    window.location.href = 'User_ActiveBookings.php'; // Redirect after SweetAlert
                });
            </script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>