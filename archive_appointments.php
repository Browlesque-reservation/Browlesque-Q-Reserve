<?php
// PHP script (archive_appointments.php)
session_start();
define('INCLUDED', true);
define('APP_LOADED', true);
require_once('connect.php');

if (isset($_SESSION['admin_email'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive_ids'])) {
        $archiveIds = $_POST['archive_ids'];

        // Begin transaction
        mysqli_begin_transaction($conn);

        try {
            foreach ($archiveIds as $archiveId) {
                // Archive data from the first table
                $query1 = "SELECT * FROM client_details WHERE appointment_id = ?";
                $stmt1 = mysqli_prepare($conn, $query1);
                mysqli_stmt_bind_param($stmt1, "i", $archiveId);
                mysqli_stmt_execute($stmt1);
                $result1 = mysqli_stmt_get_result($stmt1);
                $row1 = mysqli_fetch_assoc($result1);

                // Archive data from the second table
                $query2 = "SELECT * FROM client_appointment WHERE appointment_id = ?";
                $stmt2 = mysqli_prepare($conn, $query2);
                mysqli_stmt_bind_param($stmt2, "i", $archiveId);
                mysqli_stmt_execute($stmt2);
                $result2 = mysqli_stmt_get_result($stmt2);
                $row2 = mysqli_fetch_assoc($result2);

                if ($row1 && $row2) {
                    // Insert into archive_appointments table
                    $insertQuery = "INSERT INTO archive_appointments (client_id, client_name, client_contactno, no_of_companions, client_notes, appointment_id, service_id, promo_id, client_date, start_time, end_time, terms_conditions, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmtInsert = mysqli_prepare($conn, $insertQuery);
                    mysqli_stmt_bind_param($stmtInsert, "issisisssssss", $row1['client_id'], $row1['client_name'], $row1['client_contactno'], $row1['no_of_companions'], $row1['client_notes'], $row2['appointment_id'], $row2['service_id'], $row2['promo_id'], $row2['client_date'], $row2['start_time'], $row2['end_time'], $row2['terms_conditions'], $row2['status']);
                    $insertResult = mysqli_stmt_execute($stmtInsert);

                    if (!$insertResult) {
                        throw new Exception("Error inserting data into archive_appointments table. Appointment ID: " . $archiveId);
                    }

                    // Delete from client_details table
                    $deleteQuery1 = "DELETE FROM client_details WHERE appointment_id = ?";
                    $stmtDelete1 = mysqli_prepare($conn, $deleteQuery1);
                    mysqli_stmt_bind_param($stmtDelete1, "i", $archiveId);
                    $deleteResult1 = mysqli_stmt_execute($stmtDelete1);

                    if (!$deleteResult1) {
                        throw new Exception("Error deleting data from client_details table. Appointment ID: " . $archiveId);
                    }

                    // Delete from client_appointment table
                    $deleteQuery2 = "DELETE FROM client_appointment WHERE appointment_id = ?";
                    $stmtDelete2 = mysqli_prepare($conn, $deleteQuery2);
                    mysqli_stmt_bind_param($stmtDelete2, "i", $archiveId);
                    $deleteResult2 = mysqli_stmt_execute($stmtDelete2);

                    if (!$deleteResult2) {
                        throw new Exception("Error deleting data from client_appointment table. Appointment ID: " . $archiveId);
                    }
                } else {
                    throw new Exception("Data not found for appointment ID: " . $archiveId);
                }
            }

            // Commit transaction
            mysqli_commit($conn);
            echo "success";
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            $errorMessage = "Error: " . $e->getMessage();
            echo $errorMessage;
            error_log("Error in archive_appointments.php: " . $errorMessage);
        } finally {
            // Close connection
            mysqli_close($conn);
        }
    } else {
        echo "error";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
