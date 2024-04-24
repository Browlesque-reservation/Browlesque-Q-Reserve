<?php
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_SESSION['admin_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kineme Page</title>
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>

<body>
<div class="d-flex" style="height: 100vh;">
    <input type="checkbox" id="check">
    <label for="check">
        <i class="fas fa-bars" id="btn"></i>
        <i class="fas fa-times" id="cancel"></i>
    </label>
    <div class="sidebar">
        <header><img src="./assets/images/icon/Browlesque.svg" class="logo-browlesque-client-2" alt="Browlesque Logo"></header>
        <h4 class="mt-3 ms-4">Management</h4>
        <a href="#" class="active">
            <img src="./assets/images/icon/Dashboard-Icon.svg" alt="Dashboard">
            <span>Dashboard</span>
        </a>
        <a href="#">
            <img src="./assets/images/icon/Clients-Icon.svg" alt="Clients">
            <span>Clients</span>
        </a>
        <a href="#">
            <img src="./assets/images/icon/Calendar-Icon.svg" alt="Calendar">
            <span>Calendar</span>
        </a>
        <h4 class="mt-3 ms-4">Content Desk</h4>
        <a href="#">
            <img src="./assets/images/icon/Services-Icon.svg" alt="Services">
            <span>Services</span>
        </a>
        <a href="#">
            <img src="./assets/images/icon/Promos-Icon.svg" alt="Promos">
            <span>Promos</span>
        </a>
        <a href="logout.php" class="logout-link">
            <img src="./assets/images/icon/Logout-Icon.svg" alt="Logout">
            <span class="text nav-text">Logout</span>
        </a>
    </div>
</div>


        <h1>Login successful</h1>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


<?php
} else {
    header("Location: index.php");
    die();
}
?>  