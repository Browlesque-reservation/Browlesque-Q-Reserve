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

    // Update rejection details in client_details table
    $stmt = $conn->prepare("UPDATE client_details SET rejection_detail = ? WHERE appointment_id = ?");
    $stmt->bind_param("si", $rejectionDetails, $appointmentId);

    // Update status in client_appointment table
    $stmt2 = $conn->prepare("UPDATE client_appointment SET status = 'Rejected' WHERE appointment_id = ?");
    $stmt2->bind_param("i", $appointmentId);

    // Execute the updates
    $update1 = $stmt->execute();
    $update2 = $stmt2->execute();

    // Check if both updates were successful
    if ($update1 && $update2) {
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
        // Output error message if database updates fail
        echo json_encode(array('status' => 'error', 'message' => 'Error updating database.'));
    }

    // Close statements and database connection
    $stmt->close();
    $stmt2->close();
    $conn->close();
}
?>
