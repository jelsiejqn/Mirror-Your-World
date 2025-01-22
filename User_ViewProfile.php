<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);

if (!$isLoggedIn) {
    // If not logged in, redirect to the login page
    header('Location: User_LoginPage.php');
    exit;
}

// Fetch user data from the session
$user_id = $_SESSION['user_id']; // Assuming the session stores 'user_id'
$username = $_SESSION['username'];

// Include database connection file
include 'dbconnect.php';

// Fetch additional user information from the database
$query = "SELECT first_name, last_name, email, company_name,contact_number FROM userstbl WHERE user_id = ?"; // Use 'user_id' instead of 'id'
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id); // 'i' indicates integer type for 'user_id'
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    // Fetch the user data from the result
    $user = $result->fetch_assoc();
} else {
    // If no user found, redirect to login page
    header('Location: User_LoginPage.php');
    exit;
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>

    <h1>User Profile</h1>

    <div class="profile-details">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Company Name:</strong> <?php echo htmlspecialchars($user['company_name']); ?></p>
        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contact_number']); ?></p>

        <!-- Back button -->
        <button onclick="goBack()">Back</button>
    </div>

</body>
</html>

<script>
// Function to navigate back to the previous page
function goBack() {
    window.history.back();
}
</script>
