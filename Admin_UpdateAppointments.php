<?php
include 'dbconnect.php';

// Automatically update appointment statuses
$conn->query("UPDATE appointmentstbl 
SET status = 'Confirmed' 
WHERE status = 'Pending' 
AND appointment_date = CURDATE() 
AND appointment_time <= DATE_SUB(NOW(), INTERVAL 12 HOUR)");

$conn->query("UPDATE appointmentstbl 
SET status = 'Completed' 
WHERE status = 'Confirmed' 
AND appointment_date < CURDATE()");

// Redirect back to the admin page with a success message
header("Location: Admin_AppointmentsPage.php?status=success");
exit;
?>