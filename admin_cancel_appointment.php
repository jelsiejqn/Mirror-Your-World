<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Document</title>
</head>
<body>
    
</body>
</html>

<?php
session_start();
include 'dbconnect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $reason = $_POST['reason'];

    if (!empty($appointment_id) && !empty($reason)) {
        // Modify query to update both cancellation reason and status
        $query = "UPDATE appointmentstbl SET cancellation_reason = ?, is_cancelled = 1, status = 'Cancelled' WHERE appointment_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $reason, $appointment_id);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Appointment cancelled successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'Admin_ActiveBookings.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Error cancelling appointment!',
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Warning!',
                text: 'Please provide a reason for cancellation.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>
