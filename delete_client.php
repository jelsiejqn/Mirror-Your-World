<?php
require "dbconnect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM clientstbl WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        error_log("Delete client error: " . $stmt->error);
        echo "Error";
    }
    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
?>