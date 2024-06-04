<?php
define('INCLUDED', true);
require_once('connect.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT antecedents, consequents, support, confidence, lift, conviction FROM association_rules";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Antecedents</th><th>Consequents</th><th>Support</th><th>Confidence</th><th>Lift</th><th>Conviction</th></tr>";
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["antecedents"]. "</td><td>" . $row["consequents"]. "</td><td>" . $row["support"]. "</td><td>" . $row["confidence"]. "</td><td>" . $row["lift"]. "</td><td>" . $row["conviction"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>
