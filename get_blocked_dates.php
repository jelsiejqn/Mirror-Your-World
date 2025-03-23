<?php
include 'dbconnect.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT blocked_date FROM blocked_dates ORDER BY blocked_date";
    $result = $conn->query($sql);
    
    if ($result) {
        $dates = [];
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row['blocked_date'];
        }
        
        echo json_encode([
            'success' => true,
            'dates' => $dates
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error retrieving blocked dates: ' . $conn->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Exception: ' . $e->getMessage()
    ]);
}

$conn->close();