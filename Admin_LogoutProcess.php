<?php
session_start();

// Check if the admin is logged in
if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])) {
    $admin_id = $_SESSION['admin_id'];

    // Include the database connection
    include 'dbconnect.php';

    // Prepare the query to insert into logstbl
    $action_type = 'Admin Logout';
    $log_query = "INSERT INTO logstbl (user_id, action_type, action_timestamp) VALUES (?, ?, NOW())";
    $log_stmt = $conn->prepare($log_query);
    $log_stmt->bind_param('is', $admin_id, $action_type);

    // Execute the log query
    $log_stmt->execute();
    $log_stmt->close();

    // Destroy the session
    session_destroy();

    // Redirect to the admin login page with a logout flag
    header('Location: Admin_LoginPage.php?logout=true');
    exit;
} else {
    // If no admin is logged in, redirect to login page
    header('Location: Admin_LoginPage.php');
    exit;
}
?>
