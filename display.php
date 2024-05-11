<?php
    require_once('connect.php'); // Include your database connection script

    // Retrieve services from the database
    $query_services = "SELECT service_id, service_name FROM services";
    $result_services = mysqli_query($conn, $query_services);

    // Create an associative array to store service IDs and names
    $services = array();
    while ($row = mysqli_fetch_assoc($result_services)) {
        $services[$row['service_id']] = $row['service_name'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $clientDate = $_POST['client_date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $clientName = $_POST['client_name'];
        $clientContactNo = $_POST['client_contactno'];
        $noOfCompanions = $_POST['no_of_companions'];
        $clientNotes = $_POST['client_notes'];
        $termsConditions = isset($_POST['terms_conditions']) ? $_POST['terms_conditions'] : 0;

        // Check if service_id and promo_id are set in $_POST
        if (isset($_POST['service_id']) && isset($_POST['promo_id'])) {
            // Get selected service IDs and promo IDs
            $serviceIDs = implode(',', $_POST['service_id']);
            $promoIDs = implode(',', $_POST['promo_id']);

            // Prepare and execute SQL INSERT statement for client_appointment
            $query = "INSERT INTO client_appointment (service_id, promo_id, client_date, start_time, end_time, terms_conditions, status) 
                      VALUES ('$serviceIDs', '$promoIDs', '$clientDate', '$startTime', '$endTime', '$termsConditions', 'Pending')";
            $result = mysqli_query($conn, $query);
            
            // Get the last inserted appointment ID
            $lastAppointmentID = mysqli_insert_id($conn);

            // Prepare and execute SQL INSERT statement for client_details
            $query_details = "INSERT INTO client_details (client_name, client_contactno, no_of_companions, client_notes, appointment_id) 
                              VALUES ('$clientName', '$clientContactNo', '$noOfCompanions', '$clientNotes', '$lastAppointmentID')";
            $result_details = mysqli_query($conn, $query_details);
            
            if (!$result || !$result_details) {
                // Handle the error, if any
                echo "Error: " . mysqli_error($conn);
            }
        }

        header("Location: Index.php");
    } else {
        echo "Form data not submitted!";
    }
?>

<!-- Display services -->
<ul>
    <?php foreach ($services as $serviceID => $serviceName): ?>
        <li><?php echo $serviceName; ?> (ID: <?php echo $serviceID; ?>)</li>
    <?php endforeach; ?>
</ul>