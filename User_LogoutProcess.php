<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    // Log out the user
    $user_id = $_SESSION['user_id'];

    // Include the database connection
    include 'dbconnect.php';

    // Prepare the query to insert into logstbl
    $action_type = 'Logout';
    $log_query = "INSERT INTO logstbl (user_id, action_type, action_timestamp) VALUES (?, ?, NOW())";
    $log_stmt = $conn->prepare($log_query);
    $log_stmt->bind_param('is', $user_id, $action_type);
    
    // Execute the log query
    $log_stmt->execute();
    $log_stmt->close();

    // Destroy the session
    session_destroy();

    // Redirect to the homepage with a logout flag
    header('Location: User_Homepage.php?logout=true');
    exit;
} else {
    // If no user is logged in, redirect to the homepage
    header('Location: User_Homepage.php');
    exit;
}
?>
