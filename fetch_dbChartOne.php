<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');
header('Content-Type: application/json');

if (isset($_SESSION['admin_email'])) {

    $sql = "SELECT antecedents, consequents, conviction FROM association_rules";
    $result = $conn->query($sql);

    // Fetch data and prepare JSON
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data1[] = [
                'label' => $row['antecedents'] . ' => ' . $row['consequents'],
                'value' => $row['conviction']
            ];
        }
    }

    echo json_encode($data1);
    
    $conn->close();
} else {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(["error" => "Unauthorized"]);
}
?>
