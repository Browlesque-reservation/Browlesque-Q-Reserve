<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');
require_once('./vendors/tcpdf/tcpdf.php');

if (!isset($_SESSION['admin_email'])) {
    header("Location: index.php");
    die();
}
$admin_upload_path = '../Browlesque-Q-Reserve/';

$query2 = "
SELECT ar.antecedents, ar.consequents, s1.service_path AS antecedent_image, s2.service_path AS consequent_image
FROM association_rules ar
LEFT JOIN services s1 ON ar.antecedents = s1.service_name
LEFT JOIN services s2 ON ar.consequents = s2.service_name
ORDER BY ar.conviction DESC
LIMIT 1";

$result2 = $conn->query($query2);

if ($result2->num_rows > 0) {
    // Fetch the row
    $row2 = $result2->fetch_assoc();
    $antecedents = $row2['antecedents'];
    $consequents = $row2['consequents'];
    
    $antecedent_image_path = $admin_upload_path . $row2['antecedent_image'];
    $consequent_image_path = $admin_upload_path . $row2['consequent_image'];

    if (file_exists($antecedent_image_path)) {
        $antecedent_image = base64_encode(file_get_contents($antecedent_image_path));
    } else {
        $antecedent_image = base64_encode(file_get_contents('./Assets/images/pictures/microblading2.jpg')); // Default image
    }

    if (file_exists($consequent_image_path)) {
        $consequent_image = base64_encode(file_get_contents($consequent_image_path));
    } else {
        $consequent_image = base64_encode(file_get_contents('./Assets/images/pictures/microblading2.jpg')); // Default image
    }

    $message = "Our <b>" . htmlspecialchars($antecedents) . "</b> and <b>" . htmlspecialchars($consequents) . "</b> Service is likely to be availed together by most of our clients!";
} else {
    $antecedent_image = base64_encode(file_get_contents('./Assets/images/pictures/microblading2.jpg')); // Default image
    $consequent_image = base64_encode(file_get_contents('./Assets/images/pictures/microblading2.jpg')); // Default image
    $message = "We currently do not have any association rules to display. Please check back later for exciting services!";
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
            <section class="gallery" id="gallery">
                <h2 class="section_title">People's Choice</h2>
                    <div class="section_container">
                        <div class="gallery_container">
                        <div class="gallery_items">
                            <img src="data:image/jpeg;base64,<?php echo $antecedent_image; ?>" alt="Gallery Image" />
                        </div>
                        <div class="gallery_items">
                            <img src="data:image/jpeg;base64,<?php echo $consequent_image; ?>" alt="Gallery Image" />
                        </div>
                        <p class="text-center"> 
                            <?php echo $message; ?>
                        </p>
                        </div>
                    </div>
            </section>
            <div class="pdf-container"> 
                <form action="generate_pdf_one.php" method="post">
                    <button class="pdf-btn" type="submit">Download PDF Report</button>
                </form>
            </div>
        </div>

        <div class="container-md container-flex-chart" style="display: none;">
            <h4 class="chart-name"  style="display: none;" >Service Association Chart</h4>
            <div id="chartdiv" style="width: 100%; height: 420px; display: none;"></div>
            <div class="pdf-container" style="display: none;"> 
                <form action="generate_pdf_one.php" method="post">
                    <button class="pdf-btn"  style="display: none;" type="submit">Download PDF Report</button>
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
