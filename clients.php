<?php
define('INCLUDED', true);
define('APP_LOADED', true);
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_SESSION['admin_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Clients</title>
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include "sidebar.php";?>
    <!-- Content container -->
    <div class="content-container container">
        <h1 class="page-header">List of Clients</h1>
        <section class="home" id="clients">
            <div class="container-fluid">
                <div class="" id="table">
                    <div class="sas-table">
                        <div class="search-container">
                            <input type="text" id="searchInput" class="mb-2" placeholder="Search...">
                        </div>
                        <button class="archive-btn mb-2 me-3" onclick="showConfirmationModalDeleteP()">
                            <img src="./assets/images/icon/archive.svg" class="archive-svg" alt="Archive Icon">
                        </button>
                        <button class="archive-btn mb-2 me-1" onclick="showConfirmationModalDeleteP()">
                            <img src="./assets/images/icon/qrscan.svg" class="qrscan-svg" alt="Scan Icon">
                        </button>
                    </div>
                    <div id="myGrid1" style="width: 100%; height: 90%" class="ag-theme-quartz"></div>
                </div>
            </div>
        </section>
    </div>
</div>


<script src="./assets/js/sidebar.js"></script>
<script>var __basePath = './';</script>
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.0.1/dist/ag-grid-community.min.js"></script>
<script src="./assets/js/clients.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


<?php
} else {
    header("Location: index.php");
    die();
}
?>  