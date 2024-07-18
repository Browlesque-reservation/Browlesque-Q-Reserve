<?php
define('INCLUDED', true);
define('APP_LOADED', true);
session_start();
require_once 'connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendResponse($success, $message = '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = $_POST['appointmentId'];
    $rejectionDetails = $_POST['rejectionDetails'];
    $clientEmail = $_POST['clientEmail'];
    $clientName = $_POST['clientName'];

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Update rejection details in client_details table
        $stmt = $conn->prepare("UPDATE client_details SET rejection_detail = ? WHERE appointment_id = ?");
        $stmt->bind_param("si", $rejectionDetails, $appointmentId);
        $update1 = $stmt->execute();

        // Update status in client_appointment table
        $stmt2 = $conn->prepare("UPDATE client_appointment SET status = 'Rejected' WHERE appointment_id = ?");
        $stmt2->bind_param("i", $appointmentId);
        $update2 = $stmt2->execute();

        // Check if both updates were successful
        if ($update1 && $update2) {
            // Archive data from client_details table
            $query1 = "SELECT * FROM client_details WHERE appointment_id = ?";
            $stmt1 = $conn->prepare($query1);
            $stmt1->bind_param("i", $appointmentId);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $row1 = $result1->fetch_assoc();

            // Archive data from client_appointment table
            $query2 = "SELECT * FROM client_appointment WHERE appointment_id = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("i", $appointmentId);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();

            if ($row1 && $row2) {
                // Insert into archive_appointments table
                $insertQuery = "INSERT INTO archive_appointments (client_id, client_name, client_email, client_contactno, rejection_detail, appointment_id, service_id, promo_id, client_date, start_time, end_time, terms_conditions, image_path, image_type, status) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $insertQuery);
                mysqli_stmt_bind_param($stmtInsert, "issssisssssssss", $row1['client_id'], $row1['client_name'], $row1['client_email'], $row1['client_contactno'], $row1['rejection_detail'], $row2['appointment_id'], $row2['service_id'], $row2['promo_id'], $row2['client_date'], $row2['start_time'], $row2['end_time'], $row2['terms_conditions'], $row2['image_path'], $row2['image_type'], $row2['status']);
                $insertResult = mysqli_stmt_execute($stmtInsert);

                if (!$insertResult) {
                    throw new Exception("Error inserting data into archive_appointments table. Appointment ID: " . $appointmentId);
                }

                // Delete from client_details table
                $deleteQuery1 = "DELETE FROM client_details WHERE appointment_id = ?";
                $stmtDelete1 = $conn->prepare($deleteQuery1);
                $stmtDelete1->bind_param("i", $appointmentId);
                $deleteResult1 = $stmtDelete1->execute();

                if (!$deleteResult1) {
                    throw new Exception("Error deleting data from client_details table. Appointment ID: " . $appointmentId);
                }

                // Delete from client_appointment table
                $deleteQuery2 = "DELETE FROM client_appointment WHERE appointment_id = ?";
                $stmtDelete2 = $conn->prepare($deleteQuery2);
                $stmtDelete2->bind_param("i", $appointmentId);
                $deleteResult2 = $stmtDelete2->execute();

                if (!$deleteResult2) {
                    throw new Exception("Error deleting data from client_appointment table. Appointment ID: " . $appointmentId);
                }
            } else {
                throw new Exception("Data not found for appointment ID: " . $appointmentId);
            }

            // Commit transaction
            mysqli_commit($conn);

            // Send email notification
            $mail = new PHPMailer(true);
            try {
                // SMTP settings for Gmail
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'browlesquebacoorbranch@gmail.com';
                $mail->Password = 'ohrk idmk sulk wdlq';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Sender and recipient
                $mail->setFrom('browlesquebacoorbranch@gmail.com', 'Browlesque Cavite');
                $mail->addAddress($clientEmail);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = "Appointment Rejection";
                $mail->Body = "Dear $clientName, <br><br>
                               Details of rejection: $rejectionDetails <br><br>
                               Best regards,<br>
                               Browlesque Team";

                // Send email
                $mail->send();

                error_log('Email sent successfully to ' . $clientEmail);
                sendResponse(true);
            } catch (Exception $e) {
                error_log('Mail error: ' . $mail->ErrorInfo);
                sendResponse(false, $mail->ErrorInfo);
            }

        } else {
            throw new Exception("Error updating database.");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        sendResponse(false, $e->getMessage());
    } finally {
        // Close statements and database connection
        $stmt->close();
        $stmt2->close();
        $conn->close();
    }
}
?>