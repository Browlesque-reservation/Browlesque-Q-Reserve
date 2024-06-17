<?php
// PHP script (archive_appointments.php)
session_start();
define('INCLUDED', true);
define('APP_LOADED', true);
require_once('connect.php');

if (isset($_SESSION['admin_email'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_ids'])) {
        $restoreIds = $_POST['restore_ids'];

        // Begin transaction
        mysqli_begin_transaction($conn);

        try {
            foreach ($restoreIds as $restoreId) {
                // Fetch data from the archive table
                $query1 = "SELECT * FROM archive_appointments WHERE archive_id = ?";
                $stmt1 = mysqli_prepare($conn, $query1);
                mysqli_stmt_bind_param($stmt1, "i", $restoreId);
                mysqli_stmt_execute($stmt1);
                $result1 = mysqli_stmt_get_result($stmt1);
                $row1 = mysqli_fetch_assoc($result1);

                if ($row1) {
                    // Insert into client_appointments table first
                    $insertQuery1 = "INSERT INTO client_appointment (appointment_id, service_id, promo_id, client_date, start_time, end_time, terms_conditions, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmtInsert1 = mysqli_prepare($conn, $insertQuery1);
                    mysqli_stmt_bind_param($stmtInsert1, "isssssss", $row1['appointment_id'], $row1['service_id'], $row1['promo_id'], $row1['client_date'], $row1['start_time'], $row1['end_time'], $row1['terms_conditions'], $row1['status']);
                    $insertResult1 = mysqli_stmt_execute($stmtInsert1);

                    if (!$insertResult1) {
                        throw new Exception("Error inserting data into client_appointment table. Appointment ID: " . $restoreId);
                    }

                    // Then insert into client_details table
                    $insertQuery2 = "INSERT INTO client_details (client_id, client_name, client_contactno, no_of_companions, client_notes, appointment_id) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmtInsert2 = mysqli_prepare($conn, $insertQuery2);
                    mysqli_stmt_bind_param($stmtInsert2, "issisi", $row1['client_id'], $row1['client_name'], $row1['client_contactno'], $row1['no_of_companions'], $row1['client_notes'], $row1['appointment_id']);
                    $insertResult2 = mysqli_stmt_execute($stmtInsert2);

                    if (!$insertResult2) {
                        throw new Exception("Error inserting data into client_details table. Appointment ID: " . $restoreId);
                    }

                    // Delete from archive_appointments table
                    $deleteQuery = "DELETE FROM archive_appointments WHERE archive_id = ?";
                    $stmtDelete = mysqli_prepare($conn, $deleteQuery);
                    mysqli_stmt_bind_param($stmtDelete, "i", $restoreId);
                    $deleteResult = mysqli_stmt_execute($stmtDelete);

                    if (!$deleteResult) {
                        throw new Exception("Error deleting data from archive_appointments table. Archive ID: " . $restoreId);
                    }
                } else {
                    throw new Exception("Data not found for archive ID: " . $restoreId);
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
