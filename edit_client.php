<?php
require "dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $description = $_POST['description'];

    $sql = "UPDATE clientstbl SET client_name = ?, client_role = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $role, $description, $id);

    if ($stmt->execute()) {
        // Handle image upload if needed
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $sql_image = "UPDATE clientstbl SET image_path = ? WHERE id = ?";
                $stmt_image = $conn->prepare($sql_image);
                $stmt_image->bind_param("si", $target_file, $id);
                $stmt_image->execute();
                $stmt_image->close();
            }
        }
        echo "Success";
    } else {
        error_log("Edit client error: " . $stmt->error);
        echo "Error";
    }
    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
?>