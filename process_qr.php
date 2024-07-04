<?php
// Define database connection (assuming 'connect.php' and 'stopback.php' handle this)
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the QR code data from the form
    $qrData = trim($_POST['qrData']);

    // Check if qrData is not empty
    if (!empty($qrData)) {
        // Initialize variables to store parsed data
        $appointmentId = null;

        // Parse QR code data to extract relevant information
        // Modify parsing logic based on your actual QR code format
        // Example: Assuming QR data contains 'Appointment ID: [ID]' in a line
        $lines = explode("\n", $qrData);
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

            // Query to retrieve appointment details from database
            $sql = "SELECT cd.*, ca.service_id, ca.promo_id, ca.client_date, ca.start_time, ca.end_time, ca.status
                    FROM client_details cd
                    JOIN client_appointment ca ON cd.appointment_id = ca.appointment_id
                    WHERE cd.appointment_id = '$appointmentId'";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                // If data found, fetch and prepare response
                $row = $result->fetch_assoc();

                // Extract service IDs and promo IDs
                $serviceIds = json_decode($row['service_id']);
                $promoIds = json_decode($row['promo_id']);

                // Initialize arrays to store service names and promo details
                $serviceNames = [];
                $promoDetails = [];

                // Fetch service names for each service ID
                if (!empty($serviceIds)) {
                    $serviceIdsStr = implode("','", array_map([$conn, 'real_escape_string'], $serviceIds));
                    $serviceSql = "SELECT service_id, service_name FROM services WHERE service_id IN ('$serviceIdsStr')";
                    $serviceResult = $conn->query($serviceSql);
                    while ($serviceRow = $serviceResult->fetch_assoc()) {
                        $serviceNames[] = $serviceRow['service_name'];
                    }
                }

                // If service names are empty, add "No service availed"
                if (empty($serviceNames)) {
                    $serviceNames[] = "No service availed";
                }

                // Fetch promo details for each promo ID
                if (!empty($promoIds)) {
                    $promoIdsStr = implode("','", array_map([$conn, 'real_escape_string'], $promoIds));
                    $promoSql = "SELECT promo_id, promo_details FROM promo WHERE promo_id IN ('$promoIdsStr')";
                    $promoResult = $conn->query($promoSql);
                    while ($promoRow = $promoResult->fetch_assoc()) {
                        $promoDetails[] = $promoRow['promo_details'];
                    }
                }

                // If promo details are empty, add "No promo availed"
                if (empty($promoDetails)) {
                    $promoDetails[] = "No promo availed";
                }

                $startTime = new DateTime($row['start_time']);
                $formattedStartTime = $startTime->format('g:i A');

                $endTime = new DateTime($row['end_time']);
                $formattedEndTime = $endTime->format('g:i A');

                $clientNotes = !empty($row['client_notes']) ? $row['client_notes'] : 'No notes';

                // Prepare response
                $response = [
                    'success' => true,
                    'message' => 'QR data processed successfully.',
                    'appointment_id' => $row['appointment_id'],
                    'client_name' => $row['client_name'],
                    'client_contactno' => $row['client_contactno'],
                    'no_of_companions' => $row['no_of_companions'],
                    'client_date' => $row['client_date'],
                    'service_id' => $row['service_id'],
                    'service_names' => implode(', ', $serviceNames),
                    'promo_id' => $row['promo_id'],
                    'promo_details' => implode(', ', $promoDetails),
                    'start_time' => $formattedStartTime,
                    'end_time' => $formattedEndTime,
                    'client_notes' => $clientNotes,
                    'status' => $row['status']
                ];
            } else {
                // If no data found for given appointment ID
                $response = ['error' => 'No appointment found with ID: ' . $appointmentId];
            }
        } else {
            // If no appointment ID found in QR data
            $response = ['error' => 'Appointment ID not found in QR data.'];
        }
    } else {
        // If no QR data provided in POST request
        $response = ['error' => 'No QR data provided.'];
    }

    // Set response header to JSON format
    header('Content-Type: application/json');
    // Output JSON response
    echo json_encode($response);

    // Close database connection
    $conn->close();
}
?>
