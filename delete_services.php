<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['admin_email'])) {
    if(isset($_POST['service_ids']) && is_array($_POST['service_ids'])) {
        $service_ids = $_POST['service_ids'];
        $placeholders = implode(',', array_fill(0, count($service_ids), '?'));
        
        $stmt = $conn->prepare("DELETE FROM services WHERE service_id IN ($placeholders)");
        $stmt->bind_param(str_repeat('i', count($service_ids)), ...$service_ids);
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
