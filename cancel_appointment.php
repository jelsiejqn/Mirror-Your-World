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
include 'dbconnect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['appointment_id']) || !isset($_POST['reason'])) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>';
    echo 'Swal.fire({';
    echo '  icon: "error",';
    echo '  title: "Error",';
    echo '  text: "Missing required information.",';
    echo '}).then(() => {';
    echo '  window.location.href = "User_ActiveBookings.php";';
    echo '});';
    echo '</script>';
    exit;
}

$appointment_id = $_POST['appointment_id'];
$cancellation_reason = $_POST['reason'];

$query = "UPDATE appointmentstbl SET status = 'Cancelled', cancellation_reason = ? WHERE appointment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $cancellation_reason, $appointment_id);
$result = $stmt->execute();

echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

if ($result) {
    echo '<script>';
    echo 'Swal.fire({';
    echo '  icon: "success",';
    echo '  title: "Success",';
    echo '  text: "Appointment cancelled successfully.",';
    echo '}).then(() => {';
    echo '  window.location.href = "User_ActiveBookings.php";';
    echo '});';
    echo '</script>';
} else {
    echo '<script>';
    echo 'Swal.fire({';
    echo '  icon: "error",';
    echo '  title: "Error",';
    echo '  text: "Failed to cancel appointment: ' . $conn->error . '",';
    echo '}).then(() => {';
    echo '  window.location.href = "User_ActiveBookings.php";';
    echo '});';
    echo '</script>';
}

$stmt->close();
$conn->close();
?>