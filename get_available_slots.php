<?php
// get_available_slots.php

include 'dbconnect.php'; // Include your database connection file

header('Content-Type: application/json');

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];

    // Fetch all time slots (you'll need to define these)
    $allTimeSlots = [
        '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'
    ];

    // Fetch booked slots for the selected date
    $bookedSlots = [];
    $sql = "SELECT appointment_time FROM appointmentstbl WHERE appointment_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $bookedSlots[] = date('H:i', strtotime($row['appointment_time']));
    }

    $stmt->close();

    // Calculate available slots
    $availableSlots = array_diff($allTimeSlots, $bookedSlots);

    echo json_encode(['available_times' => array_values($availableSlots), 'booked_slots' => $bookedSlots]);
} else {
    echo json_encode(['error' => 'Date not provided']);
}

$conn->close();
?>