<?php
require "dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $company_name = htmlspecialchars($_POST['company_name']);
    $date = $_POST['date'];
    $description = htmlspecialchars($_POST['description']);
    
    // Handle file upload if a new image is provided
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $update_sql = "UPDATE showcasetbl SET company_name='$company_name', date='$date', description='$description', image_path='$target_file' WHERE id=$id";
    } else {
        $update_sql = "UPDATE showcasetbl SET company_name='$company_name', date='$date', description='$description' WHERE id=$id";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
