<?php
session_start();

require_once('connect.php');

 $sql1 = "SELECT * FROM admin_login";
 $result1 = mysqli_query($conn, $sql1);

if(isset($_SESSION["admin_email"])) {
  $admin_email = $_SESSION['admin_email'];
  $query = "SELECT * FROM admin_login WHERE admin_email='$admin_email'";
  $result = mysqli_query($conn, $query);
  $user = mysqli_fetch_assoc($result);
  $admin_id = $user['admin_id'];
  $admin_name = $user['admin_name'];
  $admin_email = $user['admin_email'];
    } else {
    header("location:index.php");}
  ?>