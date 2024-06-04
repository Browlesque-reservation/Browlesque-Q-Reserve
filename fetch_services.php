<?php
// Include your database connection script
define('INCLUDED', true);
require_once('connect.php');

// Fetch services from the database
$query = "SELECT service_id, service_name FROM services";
$result = mysqli_query($conn, $query);

$services = [];
while ($row = mysqli_fetch_assoc($result)) {
    $services[] = $row;
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($services);

// Close the connection
mysqli_free_result($result);
mysqli_close($conn);
?>
