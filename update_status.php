<?php
define('INCLUDED', true);
define('APP_LOADED', true);
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Prepare the SQL statement
    $sql = "UPDATE client_appointment SET status = 'Confirmed' WHERE appointment_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $appointment_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error executing query']);
        }

        // Close statement
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing query']);
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
