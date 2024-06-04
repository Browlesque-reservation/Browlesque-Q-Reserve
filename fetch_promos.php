<?php
// Include your database connection script
define('INCLUDED', true);
require_once('connect.php');

// Fetch promos from the database
$query = "SELECT promo_id, promo_details FROM promo";
$result = mysqli_query($conn, $query);

$promos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $promos[] = $row;
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($promos);

// Close the connection
mysqli_free_result($result);
mysqli_close($conn);
?>
