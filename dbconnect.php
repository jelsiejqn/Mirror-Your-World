<?php

$servername = "localhost";
$user = "root";
$password = "";
$dbname = "mirroryourworlddb";

// Using mysqli for one part of the application
$conn = new mysqli($servername, $user, $password, $dbname);

// Check if the mysqli connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Using PDO for another part of the application
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
