<?php
if (!defined('INCLUDED')) {
  // If not included, redirect to an error page or any other page you prefer
  header("Location: index.php");
  exit;
}

$servername = "localhost";
$username = "u155023598_browlesque";
$password = "Browlesque_cavite_123";
$database = "u155023598_browlesque_db";

$conn = new mysqli($servername, $username, $password, $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
