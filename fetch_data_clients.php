<?php
define('INCLUDED', true);

require_once('connect.php');

$query = "SELECT a.*, d.client_name, d.client_contactno, d.no_of_companions, d.client_notes 
          FROM client_appointment AS a 
          INNER JOIN client_details AS d ON a.appointment_id = d.appointment_id";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

mysqli_free_result($result);
mysqli_close($conn);
?>
