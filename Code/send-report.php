<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "utils/connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive and decode the JSON data
    $postData = json_decode(file_get_contents('php://input'), true);

    // Check if the data contains what you need
    if (
        isset($postData['reportID']) &&
        isset($postData['pId']) &&
        isset($postData['dId'])
    ) {
       

        $reportID = $postData['reportID'];
        $pId = $postData['pId'];
        $dId = $postData['dId'];

        // You should retrieve the manager_id value from your session or another source
        $managerId = $_SESSION['id'];

        // Get the present timestamp with seconds set to "00"
        $timestamp = strtotime(date('Y-m-d H:i:00'));

        $sql = "INSERT INTO `send_report` (test_report_id, patient_id, doctor_id, manager_id, date) 
    VALUES (?, ?, ?, ?, FROM_UNIXTIME(?))";
        header('Content-Type: application/json; charset=utf-8');

        // Use prepared statements to avoid SQL injection
        $stmt = $mysqli->prepare($sql);

        // Adjust the parameter binding format to require_once all placeholders
        $stmt->bind_param("iiiis", $reportID, $pId, $dId, $managerId, $timestamp);

        // Execute the query and handle errors
        if ($stmt->execute()) {
            // Entry added successfully
            echo json_encode(['success' => true, 'message' => 'Entry added to send-report table']);
        } else {
            // Database error occurred
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $mysqli->error]);
        }

        $stmt->close();
    } else {
        // Handle other HTTP methods or requests as needed
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
}
