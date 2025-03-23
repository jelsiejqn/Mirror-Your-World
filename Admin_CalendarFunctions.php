<?php
include 'dbconnect.php';

header('Content-Type: application/json');
error_log(print_r($_POST, true)); // Log POST data for debugging

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $date = $_POST['date'];

    if ($action === 'block') {
        // Validate date format
        if (!DateTime::createFromFormat('Y-m-d', $date)) {
            echo json_encode(['success' => false, 'message' => 'Invalid date format']);
            exit;
        }

        // Block the date
        $sql = "INSERT INTO blocked_dates (blocked_date) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Date blocked']);
        } else {
            if ($stmt->errno === 1062) { // MySQL error code for duplicate entry
                echo json_encode(['success' => false, 'message' => 'Date already blocked']);
            } else {
                error_log("SQL Error: " . $stmt->error); // Log SQL error
                echo json_encode(['success' => false, 'message' => 'Error blocking date']);
            }
        }
        $stmt->close();
    } elseif ($action === 'unblock') {
        // Validate date format
        if (!DateTime::createFromFormat('Y-m-d', $date)) {
            echo json_encode(['success' => false, 'message' => 'Invalid date format']);
            exit;
        }

        // Unblock the date
        $sql = "DELETE FROM blocked_dates WHERE blocked_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Date unblocked']);
        } else {
            error_log("SQL Error: " . $stmt->error); // Log SQL error
            echo json_encode(['success' => false, 'message' => 'Error unblocking date']);
        }
        $stmt->close();
    } elseif ($action === 'update') {
        // Decode blocked_dates JSON string
        $blockedDates = json_decode($_POST['blocked_dates'], true);
        if (!is_array($blockedDates)) {
            echo json_encode(['success' => false, 'message' => 'Invalid blocked dates format']);
            exit;
        }

        // Update calendar data
        $blockedDatesJson = json_encode($blockedDates);
        $sql = "UPDATE calendar_settings SET blocked_dates = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $blockedDatesJson);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Calendar updated']);
        } else {
            error_log("SQL Error: " . $stmt->error); // Log SQL error
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