<?php
// Define database connection
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the QR code data from the form
    $qrData = trim($_POST['qrData']);

    // Check if qrData is not empty
    if (!empty($qrData)) {
        // Split the QR code data into lines
        $lines = explode("\n", $qrData);

        // Initialize variables to store parsed data
        $appointmentId = null;

        // Parse each line to extract relevant information
        foreach ($lines as $line) {
            if (strpos($line, 'Appointment ID:') !== false) {
                $appointmentId = trim(str_replace('Appointment ID:', '', $line));
                break; // Once we find appointment ID, no need to continue
            }
        }

        // Check if appointment ID was found
        if ($appointmentId) {

            // Sanitize appointmentId to prevent SQL injection
            $appointmentId = $conn->real_escape_string($appointmentId);

            $sql = "SELECT cd.*, ca.service_id, ca.promo_id, ca.client_date, ca.start_time, ca.end_time
                    FROM client_details cd
                    JOIN client_appointment ca ON cd.appointment_id = ca.appointment_id
                    WHERE cd.appointment_id = '$appointmentId'";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                // If data found, fetch and display it
                $row = $result->fetch_assoc();
                $dbAppointmentId = $row['appointment_id'];
                $dbClientName = $row['client_name'];
                $dbClientContactNo = $row['client_contactno'];
                $dbNoOfCompanions = $row['no_of_companions'];
                $dbDateOfAppointment = $row['client_date'];
                $dbServices = $row['service_id'];
                $dbPromos = $row['promo_id'];
                $dbStartTime = $row['start_time'];
                $dbEndTime = $row['end_time'];

                // Output the retrieved data
                echo "Appointment ID: " . $dbAppointmentId . "<br>";
                echo "Client Name: " . $dbClientName . "<br>";
                echo "Client Contact No: " . $dbClientContactNo . "<br>";
                echo "Number of Companions: " . $dbNoOfCompanions . "<br>";
                echo "Date of Appointment: " . $dbDateOfAppointment . "<br>";
                echo "Services: " . json_encode($dbServices) . "<br>";
                echo "Promos: " . json_encode($dbPromos) . "<br>";
                echo "Start Time of Appointment: " . $dbStartTime . "<br>";
                echo "End Time of Appointment: " . $dbEndTime . "<br>";

                // Example: Save the data to a file
                file_put_contents('qr_data.txt', $qrData . PHP_EOL, FILE_APPEND);
            } else {
                echo "No appointment found with ID: " . $appointmentId;
            }

            $conn->close();
        } else {
            echo "Appointment ID not found in QR data.";
        }
    } else {
        echo "No QR data provided.";
    }
}
?>
