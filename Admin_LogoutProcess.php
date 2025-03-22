<?php
session_start();

// Check if the admin is logged in
if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])) {
    $admin_id = $_SESSION['admin_id'];

    // Include the database connection
    include 'dbconnect.php';


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
