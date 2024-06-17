<?php
define('INCLUDED', true);

require_once('connect.php');

$query = "SELECT * FROM archive_appointments";
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
