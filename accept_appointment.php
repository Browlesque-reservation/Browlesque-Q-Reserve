<?php
require 'vendors/autoload.php'; // Ensure this path is correct
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to handle JSON response
function sendResponse($success, $message = '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientName = $_POST['client_name'];
    $clientEmail = $_POST['client_email'];
    $appointmentId = $_POST['appointment_id'];

    // Generate QR code
    $text = "Appointment ID: " . $appointmentId . "\n";
    $text .= "Client Name: " . $clientName . "\n";
    $text .= "Client Email: " . $clientEmail . "\n";
    $qrCode = QrCode::create($text)
                        ->setSize(250)
                        ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);

    // Directory to save QR codes
    $qrCodeDir = 'qrcodes/';

    // Ensure directory exists or create it
    if (!file_exists($qrCodeDir) && !is_dir($qrCodeDir)) {
        mkdir($qrCodeDir, 0755, true);
    }

    // Prepare PNG writer
    $writer = new PngWriter();

    // Save QR code to file
    $qrCodePath = $qrCodeDir . $appointmentId . '.png';
    $writer->write($qrCode)->saveToFile($qrCodePath);

    // Send email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'browlesquebacoorbranch@gmail.com'; // Your Gmail address
        $mail->Password = 'ohrk idmk sulk wdlq'; // Your Gmail app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('browlesquebacoorbranch@gmail.com', 'Browlesque Cavite'); 
        $mail->addAddress($clientEmail);

        // Attach QR code image
        $mail->addAttachment($qrCodePath, 'qr_code.png');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Appointment Confirmation';
        $mail->Body = "Dear $clientName, <br> <br>
                       Your appointment has been confirmed. Please find the QR code attached. <br>
                       Best regards,<br>
                       Browlesque Team";
        
        // Send email
        $mail->send();

        $pdo = new PDO('mysql:host=127.0.0.1;dbname=browlesque', 'root', '');
        $stmt = $pdo->prepare('UPDATE client_appointment SET status = :status WHERE appointment_id = :appointment_id');
        $stmt->execute(['status' => 'Pending', 'appointment_id' => $appointmentId]);

        // Log success
        error_log('Email sent successfully to ' . $clientEmail);
        
        // Respond with success
        sendResponse(true);
    } catch (Exception $e) {
        // Log the error message for debugging
        error_log('Mail error: ' . $mail->ErrorInfo);
        
        // Respond with error message
        sendResponse(false, $mail->ErrorInfo);
    }
}
?>
