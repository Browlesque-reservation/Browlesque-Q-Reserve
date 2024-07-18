<?php
define('INCLUDED', true); // If needed

require_once('connect.php'); // Include your database connection script

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve appointment_id and status from the POST data
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Prepare and execute SQL UPDATE statement to update the status
        $query = "UPDATE client_appointment SET status = '$status' WHERE appointment_id = $appointment_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // If the status is 'Cancelled', archive the appointment
            if ($status === 'Cancelled') {
                // Archive data from client_details table
                $query1 = "SELECT * FROM client_details WHERE appointment_id = ?";
                $stmt1 = mysqli_prepare($conn, $query1);
                mysqli_stmt_bind_param($stmt1, "i", $appointment_id);
                mysqli_stmt_execute($stmt1);
                $result1 = mysqli_stmt_get_result($stmt1);
                $row1 = mysqli_fetch_assoc($result1);

                // Archive data from client_appointment table
                $query2 = "SELECT * FROM client_appointment WHERE appointment_id = ?";
                $stmt2 = mysqli_prepare($conn, $query2);
                mysqli_stmt_bind_param($stmt2, "i", $appointment_id);
                mysqli_stmt_execute($stmt2);
                $result2 = mysqli_stmt_get_result($stmt2);
                $row2 = mysqli_fetch_assoc($result2);

                if ($row1 && $row2) {
                    // Insert into archive_appointments table
                    $insertQuery = "INSERT INTO archive_appointments (client_id, client_name, client_email, client_contactno, rejection_detail, appointment_id, service_id, promo_id, client_date, start_time, end_time, terms_conditions, image_path, image_type, status) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmtInsert = mysqli_prepare($conn, $insertQuery);
                    mysqli_stmt_bind_param($stmtInsert, "issssisssssssss", $row1['client_id'], $row1['client_name'], $row1['client_email'], $row1['client_contactno'], $row1['rejection_detail'], $row2['appointment_id'], $row2['service_id'], $row2['promo_id'], $row2['client_date'], $row2['start_time'], $row2['end_time'], $row2['terms_conditions'], $row2['image_path'], $row2['image_type'], $row2['status']);
                    $insertResult = mysqli_stmt_execute($stmtInsert);

                    if (!$insertResult) {
                        throw new Exception("Error inserting data into archive_appointments table. Appointment ID: " . $appointment_id);
                    }

                    // Delete from client_details table
                    $deleteQuery1 = "DELETE FROM client_details WHERE appointment_id = ?";
                    $stmtDelete1 = mysqli_prepare($conn, $deleteQuery1);
                    mysqli_stmt_bind_param($stmtDelete1, "i", $appointment_id);
                    $deleteResult1 = mysqli_stmt_execute($stmtDelete1);

                    if (!$deleteResult1) {
                        throw new Exception("Error deleting data from client_details table. Appointment ID: " . $appointment_id);
                    }

                    // Delete from client_appointment table
                    $deleteQuery2 = "DELETE FROM client_appointment WHERE appointment_id = ?";
                    $stmtDelete2 = mysqli_prepare($conn, $deleteQuery2);
                    mysqli_stmt_bind_param($stmtDelete2, "i", $appointment_id);
                    $deleteResult2 = mysqli_stmt_execute($stmtDelete2);

                    if (!$deleteResult2) {
                        throw new Exception("Error deleting data from client_appointment table. Appointment ID: " . $appointment_id);
                    }
                } else {
                    throw new Exception("Data not found for appointment ID: " . $appointment_id);
                }
            }

            // Commit transaction
            mysqli_commit($conn);
            // Return a success message
            echo json_encode(array("success" => true));
        } else {
            throw new Exception("Error updating data");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        // Return an error message
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
    } finally {
        // Close the database connection
        mysqli_close($conn);
    }
} else {
    // If the request method is not POST, return an error message
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
