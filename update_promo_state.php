<?php
define('INCLUDED', true);
require_once('connect.php');

if (isset($_POST['promo_id']) && isset($_POST['promo_state'])) {
    $promo_id = $_POST['promo_id'];
    $promo_state = $_POST['promo_state'];

    // Update the state in the database
    $query = "UPDATE promo SET promo_state = ? WHERE promo_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $promo_state, $promo_id);
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
