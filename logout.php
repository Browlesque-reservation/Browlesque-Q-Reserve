<?php
session_start();
session_unset();
$_SESSION['message'] = "You are now logged out";
header("Location: index.php");
?>