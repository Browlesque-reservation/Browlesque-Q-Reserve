<?php
session_start();

define('INCLUDED', true);
require_once('connect.php');
require "vendors/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

// Check if appointment_id is provided in the URL
if (isset($_GET['appointment_id'])) {
    $appointmentId = intval($_GET['appointment_id']); 

    // Retrieve data from the database based on the provided appointment_id
    $query = "SELECT a.appointment_id, c.client_name, c.client_contactno, a.client_date, a.service_id, a.promo_id, a.start_time, a.end_time
              FROM client_appointment a
              INNER JOIN client_details c ON a.appointment_id = c.appointment_id
              WHERE a.appointment_id = $appointmentId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch data from the result set
        $row = mysqli_fetch_assoc($result);
        
        // Construct text for the QR code
        $text = "Appointment ID: " . $row['appointment_id'] . "\n";
        $text .= "Client Name: " . $row['client_name'] . "\n";
        $text .= "Client Contact No: " . $row['client_contactno'] . "\n";
        $text .= "Date of Appointment: " . $row['client_date'] . "\n";
        $text .= "Services: " . $row['service_id'] . "\n";
        $text .= "Promos: " . $row['promo_id'] . "\n";
        $text .= "Start Time of Appointment: " . $row['start_time'] . "\n";
        $text .= "End Time of Appointment: " . $row['end_time'];
        // Add more fields as needed
        
        // Create QR code
        $qr_code = QrCode::create($text)
                            ->setSize(250)
                            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
        
        // Prepare PNG writer
        $writer = new PngWriter;
        
        // Write QR code to PNG image
        $result = $writer->write($qr_code);
        
        // Set the appropriate content type
        header("Content-Type: " . $result->getMimeType());
        
        // Output the QR code image
        echo $result->getString();

        // Unset the session variable after accessing the confirmation page to prevent reuse
        // unset($_SESSION['appointment_submitted']);
    } else {
        echo "No data found in the database for the specified appointment ID!";
    }
} else {
    echo "No appointment ID provided in the URL!";
}
?>
