<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

if (isset($_SESSION['admin_email'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
              crossorigin="anonymous">
        <link rel="stylesheet" href="./assets/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

        <!-- amcharts script -->
        <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/locales/de_DE.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/geodata/germanyLow.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/fonts/notosans-sc.js"></script>
    </head>
    <body>
    <div class="d-flex">
    <?php include "sidebar.php";?>

        <div class="content-container container-flex content">
            <div class="text-welcome"><?php echo "Welcome, $admin_name!"; ?></div>
            <h1 class="page-header-db">DASHBOARD</h1>
            <div class="container-md container-flex-chart">
                <div id="chartdiv"></div>
            </div>

            <div class="container-md container-flex-chart">
                <div id="chartdiv2"></div>
            </div>
        </div>
    </div>

    <script src="./assets/js/sidebar.js"></script>
    <script src="./assets/js/charts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    </body>
    </html>

    <?php
} else {
    header("Location: index.php");
    die();
}
?>
