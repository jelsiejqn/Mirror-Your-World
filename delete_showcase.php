<?php
require "dbconnect.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the existing record to get the image path
    $sql = "SELECT image_path FROM showcasetbl WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Delete the record
        $delete_sql = "DELETE FROM showcasetbl WHERE id = $id";
        
        if ($conn->query($delete_sql) === TRUE) {
            // Delete the image file
            if (file_exists($row['image_path'])) {
                unlink($row['image_path']);
            }
            header("Location: Admin_ShowcasePage.php"); // Redirect after deletion
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Record not found.";
    }
} else {
    echo "Invalid request.";
}
?>
