<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');
header('Content-Type: application/json');


if (isset($_SESSION['admin_email'])) {
// Fetch data for chart
$sql = "SELECT service_id FROM client_appointment";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    // Initialize array to hold service counts
    $serviceCounts = [];

    // Loop through results
    while ($row = $result->fetch_assoc()) {
        $serviceIds = json_decode($row['service_id']);
        if (is_array($serviceIds)) {
            foreach ($serviceIds as $serviceId) {
                if (!isset($serviceCounts[$serviceId])) {
                    $serviceCounts[$serviceId] = 0;
                }
                $serviceCounts[$serviceId]++;
            }
        }
    }

    // Fetch service names
    $serviceNames = [];
    $sql = "SELECT service_id, service_name FROM services";
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $serviceNames[$row['service_id']] = $row['service_name'];
        }

        // Prepare data for the chart
        $data2 = [];
        foreach ($serviceCounts as $serviceId => $count) {
            $data2[] = ['service' => $serviceNames[$serviceId], 'count' => $count];
        }

        // Output JSON
        echo json_encode($data2);
    } else {
        echo "Error: Unable to fetch service names";
    }
} else {
    echo "Error: Unable to fetch data from client_appointment table";
}

// Close connection
$conn->close();
} else {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "Unauthorized"]);
}
?>