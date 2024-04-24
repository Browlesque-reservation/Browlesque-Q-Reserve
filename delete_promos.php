<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['admin_email'])) {
    if(isset($_POST['promo_ids']) && is_array($_POST['promo_ids'])) {
        $promo_ids = $_POST['promo_ids'];
        $placeholders = implode(',', array_fill(0, count($promo_ids), '?'));
        
        $stmt = $conn->prepare("DELETE FROM promo WHERE promo_id IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($promo_ids)), ...$promo_ids);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>
