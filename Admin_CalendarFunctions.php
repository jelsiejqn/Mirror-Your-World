<?php
// backend logic for block, unblock, and update
// Admin_CalendarFunctions.php (or similar file)
include 'dbconnect.php';

header('Content-Type: application/json');

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $date = $_POST['date'];

    if ($action === 'block') {
        // Block the date (e.g., add to a blocked_dates table)
        $sql = "INSERT INTO blocked_dates (blocked_date) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Date blocked']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error blocking date']);
        }
        $stmt->close();
    } elseif ($action === 'unblock') {
        // Unblock the date (e.g., remove from blocked_dates table)
        $sql = "DELETE FROM blocked_dates WHERE blocked_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Date unblocked']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error unblocking date']);
        }
        $stmt->close();
    } elseif ($action === 'update') {
        // Update calendar data (e.g., save blocked dates)
        // You'll need to define how you're storing calendar data
        // For example, you could store blocked dates in a JSON string
        $blockedDates = $_POST['blocked_dates']; // Assuming you're sending an array of dates

        // Example: Store blocked dates in a database column
        $blockedDatesJson = json_encode($blockedDates);
        $sql = "UPDATE calendar_settings SET blocked_dates = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $blockedDatesJson);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Calendar updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating calendar']);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    echo json_encode(['error' => 'Action not provided']);
}

$conn->close();
