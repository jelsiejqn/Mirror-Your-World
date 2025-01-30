<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    
</body>
</html>

<?php
include "dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false
            }).then(() => {
                window.location.href='User_ResetPassword.php?email=$email';
            });
        </script>";
        exit();
    }

    // Hash the new password for security
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Retrieve user_id from userstbl based on email
        $user_query = "SELECT user_id FROM userstbl WHERE email = :email";
        $user_stmt = $pdo->prepare($user_query);
        $user_stmt->execute(["email" => $email]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("User not found.");
        }

        $user_id = $user['user_id'];

        // Update password in userstbl
        $update_sql = "UPDATE userstbl SET password = :password, otp = NULL WHERE email = :email";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute(["password" => $hashed_password, "email" => $email]);

        // Log action in logstbl
        $log_sql = "INSERT INTO logstbl (user_id, action_type, action_timestamp) 
                    VALUES (:user_id, 'Password Update', NOW())";
        $log_stmt = $pdo->prepare($log_sql);
        $log_stmt->execute(["user_id" => $user_id]);

        // Commit transaction
        $pdo->commit();

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Your password has been updated. Please log in.',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false
            }).then(() => {
                window.location.href='User_LoginPage.php';
            });
        </script>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred: " . addslashes($e->getMessage()) . "',
                confirmButtonColor: '#d33',
                allowOutsideClick: false
            }).then(() => {
                window.location.href='User_ResetPassword.php?email=$email';
            });
        </script>";
    }
}
?>
