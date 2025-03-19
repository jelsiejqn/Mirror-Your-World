<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
</body>
</html>

<?php
require "dbconnect.php"; // Database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-desc'])) {

    // Check if required form fields are set
    $client_name = isset($_POST['client-name']) ? mysqli_real_escape_string($conn, $_POST['client-name']) : '';
    $client_role = isset($_POST['client-role']) ? mysqli_real_escape_string($conn, $_POST['client-role']) : '';
    $desc = isset($_POST['desc']) ? mysqli_real_escape_string($conn, $_POST['desc']) : '';

    // Check if required fields are empty
    if (empty($client_name) || empty($client_role) || empty($desc)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Missing Data',
                text: 'Please fill in all required fields.'
            });
        </script>";
        exit();
    }

    // Handle file upload
    $image_path = '';
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $target_dir = "uploads/"; // Folder where images will be saved
        
        // Create the directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'There was an error uploading your file.'
                });
            </script>";
            exit();
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Missing Image',
                text: 'Please upload an image.'
            });
        </script>";
        exit();
    }

    // Insert data into the client table
    $sql = "INSERT INTO clientstbl (client_name, client_role, description, image_path) 
                VALUES ('$client_name', '$client_role', '$desc', '$image_path')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            window.location.href = 'Admin_ShowcasePage.php'; // Redirect back to Admin_ShowcasePage.php
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: 'Error adding client: " . addslashes($conn->error) . "'
            });
        </script>";
    }
}

$conn->close();
?>