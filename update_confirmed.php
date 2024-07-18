<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentId = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Sanitize input to prevent SQL injection
    $appointmentId = $conn->real_escape_string($appointmentId);
    $status = $conn->real_escape_string($status);

    // Update the status in the database
    $sql = "UPDATE client_appointment SET status = '$status' WHERE appointment_id = '$appointmentId'";
    if ($conn->query($sql) === TRUE) {
        $response = ['success' => true, 'message' => 'Status updated successfully.'];
    } else {
        $response = ['success' => false, 'message' => 'Error updating status: ' . $conn->error];
    }

    // Set response header to JSON format
    header('Content-Type: application/json');
    // Output JSON response
    echo json_encode($response);

    // Close database connection
    $conn->close();
}
?>
