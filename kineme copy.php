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
</head>

<body>

<div class="d-flex" style="height: 100vh;">
    <div class="d-flex flex-column flex-shrink-0 p-3 sidebar" style="width: 280px;">
        <!-- Sidebar content -->
        <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto">
            <img src="./assets/images/icon/Browlesque.svg" class="logo-browlesque-client-2 large-logo" alt="Browlesque Logo">
            <img src="./assets/images/icon/Browlesque-Icon.svg" class="logo-browlesque-client-3 small-logo" alt="Browlesque Logo">
        </div>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <!-- Replace this SVG with your own SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                        <path d="M12 2L1 9h5v13h6v-9h2v9h6V9h5L12 2zm3 18h-2v-6H8v6H5V9.83l7-5.17 7 5.17V20z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                    </svg>
                    <!-- End of SVG replacement -->
                    <span class="nav-text">Home</span>
                </a>
            </li>
            <!-- Add more list items with icons and text here -->
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>mdo</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="#">New project...</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
        </div>
    </div>

    <div class="flex-grow-1">
        <!-- Main content area -->
        <h1>Login successful</h1>
        <a href="logout.php">
            <span class="text nav-text">Logout</span>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


<?php
} else {
    header("Location: index.php");
    die();
}
?>  