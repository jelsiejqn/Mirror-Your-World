<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Mirror Your World</title>
    <link rel="stylesheet" href="Style/Required.css">
</head>
<body>

<center>
    <div class="reset-container">
        <form class="resetForm" method="POST" action="User_UpdatePassword.php">
            <h2>Reset Password</h2>
            <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
            
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            
            <button type="submit">Update Password</button>
        </form>
    </div>
</center>

</body>
</html>
