<?php
require_once('connect.php');

// Check if service_id is provided in the URL
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    
    // Fetch service information based on service_id
    $query = "SELECT service_name, service_description, service_image FROM services WHERE service_id = $service_id";
    $result = mysqli_query($conn, $query);
    
    // Check if service is found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $service_name = $row['service_name'];
        $service_description = $row['service_description'];
        $service_image = $row['service_image'];
    } else {
        // Handle case where service is not found
        $service_name = "Service Not Found";
        $service_description = "This service does not exist.";
        $service_image = ""; // You can provide a default image here if needed
    }
} else {
    // Handle case where service_id is not provided
    $service_name = "Service ID Not Provided";
    $service_description = "Please select a service to view details.";
    $service_image = ""; // You can provide a default image here if needed
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browlesque</title>
    
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom container-fluid">
    <div class="container">
        <a href="http://localhost/browlesque">
            <img src="./assets/images/icon/Browlesque.svg" class="logo-browlesque-client" alt="Browlesque Logo">
        </a>
        <a class="navbar-toggler" href="Index.php" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="Index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="book_appointment1.php">Book Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#about_us_section">About us</a>
                </li>
            </ul>
        </div>
        </div>
    </div>
</nav>

<div class="container-fluid">
  <div class="container-flex add-white-bg">
      <div class="head-browlesque text-center"> BROWLESQUE</div>
        <div class="serv-indiv-container container">
            <!-- Display service information -->
            <?php if (!empty($service_image)) : ?>
                <img class="service-image" src='image.php?service_id=<?php echo $service_id; ?>' alt='Service Image'>
            <?php endif; ?>
            <div class="serv-text">
              <div class="sn-indiv"><?php echo $service_name; ?></div>
              <div class="sd-indiv"><?php echo $service_description; ?></div>
            </div>
        </div>
  </div>

  

  <div class="container-flex add-black-bg">
      <div class="container">
        <div class="footer-container d-flex justify-content-between">
          <div class="contact-us">
            <span class="mb-3 mb-md-0 footer-text-white">Contact Us</span>
          </div>
          <div class="contacts">
            <ul class="nav d-flex align-items-center justify-content-end">
              <li class="ms-3">
                <span class="footer-text-white">
                  <img src="./assets/images/icon/email.svg" alt="Email Icon"> browlesque@gmail.com
                </span>
              </li>
              <li class="ms-3">
                <a class="footer-text-white" href="https://www.facebook.com/BrowlesqueCavite">
                  <img src="./assets/images/icon/Facebook.svg" alt="Facebook Icon"> Browlesque Cavite
                </a>
              </li>
              <li class="ms-3">
                <span class="footer-text-white">
                  <img src="./assets/images/icon/Phone.svg" alt="Phone Icon"> 09123456789
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>


</div>


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/text.js"></script>
<script src="./assets/js/carousel.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
