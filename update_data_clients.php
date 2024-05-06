<?php
define('INCLUDED', true); // If needed

require_once('connect.php'); // Include your database connection script

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve appointment_id and status from the POST data
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Prepare and execute SQL UPDATE statement to update the status
    $query = "UPDATE client_appointment SET status = '$status' WHERE appointment_id = $appointment_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // If the query was successful, return a success message
        echo json_encode(array("success" => true));
    } else {
        // If there was an error, return an error message
        echo json_encode(array("success" => false, "message" => "Error updating data"));
    }
} else {
    // If the request method is not POST, return an error message
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

mysqli_close($conn); // Close the database connection
?>
