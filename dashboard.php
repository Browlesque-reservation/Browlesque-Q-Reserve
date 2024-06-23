<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');
require_once('./vendors/tcpdf/tcpdf.php');
if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    die();
}
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

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

    <div class="content-container content">
        <h1 class="page-header-db">DASHBOARD</h1>
        <div class="container-md container-flex-chart">
            <h4 class="chart-name">Service Association Chart</h4>
            <div id="chartdiv" style="width: 100%; height: 420px;"></div>
            <div class="pdf-container"> 
                <form action="generate_pdf_one.php" method="post">
                    <button class="pdf-btn" type="submit">Download PDF Report</button>
                </form>
            </div>
        </div>

        <div class="container-md container-flex-chart">
            <h4 class="chart-name">Total Number per Services Availed</h4>
            <div id="chartdiv2" style="width: 100%; height: 420px;"></div>
            <div class="pdf-container"> 
                <form action="generate_pdf_two.php" method="post">
                    <button class="pdf-btn" type="submit">Download PDF Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/sidebar.js"></script>
<script src="./assets/js/charts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<!-- <script src="./assets/js/preloader.js"></script> -->
<script>
$('#generate-pdf-btn').click(function() {
    $.ajax({
        url: 'generate_pdf.php',
        type: 'GET',
        success: function(response) {
            // Optionally handle success response (e.g., display a success message)
            console.log('PDF generation successful');
        },
        error: function(xhr, status, error) {
            // Handle error response (e.g., display an error message)
            console.error('Error generating PDF:', error);
        }
    });
});
</script>
</body>
</html>
