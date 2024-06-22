<?php
define('INCLUDED', true);
require_once('connect.php');

if (isset($_POST['service_id']) && isset($_POST['service_state'])) {
    $service_id = $_POST['service_id'];
    $service_state = $_POST['service_state'];

    // Update the state in the database
    $query = "UPDATE services SET service_state = ? WHERE service_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $service_state, $service_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "error";
}
?>
