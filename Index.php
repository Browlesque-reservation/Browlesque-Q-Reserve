<?php
   // define('INCLUDED', true);
   require_once('connect.php');
   
   $query = "SELECT service_id, service_name, service_description, service_image FROM services";
   $result = mysqli_query($conn, $query);

   $query1 = "SELECT promo_id, promo_details, promo_image FROM promo";
   $result1 = mysqli_query($conn, $query1);

   if (!$result1) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Count the number of rows
$totalPromos = mysqli_num_rows($result1);
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
                    <a class="nav-link" href="#" onclick="scrollToAboutSection()">About us</a>
                </li>
            </ul>
        </div>
        </div>
    </div>
</nav>

<div class="container-fluid">
  <div class="container-flex add-white-bg">
    <div class="welcome-container container">
        <div class="left-content">
            <div class="mt-2" id="browlesque">Welcome to Browlesque!</div>
            <div class="sub-text">Take this opportunity to have the brows and natural pinkish youthful lips you have always wanted!</div>
            <div class="button-appoint mt-0">
                <button type="button" class="btn btn-primary btn-primary-custom book-now" onclick="window.location.href='book_appointment1.php'">BOOK NOW</button>
            </div>
        </div>
        <img src="./assets/images/pictures/face.svg" id="microblading2" alt="First Image" class="right-content">
    </div>
  </div>

  <div class="services-container container-flex">
    <div class="container add-pb pb-4">
        <h1 class="mt-2 text-center" id="services_label">Our Services</h1>
        <div class="mt-2 grid-container-services">
            <?php
            // Check if there are any services
            if (mysqli_num_rows($result) > 0) {
                // Loop through each service
                while ($row = mysqli_fetch_assoc($result)) {
                    $service_id = $row['service_id'];
                    $service_name = $row['service_name'];
                    $service_description = $row['service_description'];
                    $service_image = $row['service_image'];
                    ?>
                    <div class="grid-item">
                        <div class="grid-text"><?php echo $service_name; ?></div>
                        <img class="grid-image" src='image.php?service_id=<?php echo $service_id; ?>' alt='Service Image'>
                        <div class="description-text" id="text_service"><?php echo $service_description; ?></div>
                        <button type="button" id="read_button_<?php echo $service_id; ?>" class="btn read-button mt-4 ml-4" onclick="redirectToServicePage(<?php echo $service_id; ?>)">READ MORE</button>
                    </div>
                <?php }
            } else {
                echo "No services found.";
            }
            ?>
        </div>
    </div>
</div>

  <div class="container-flex add-white-bg">
    <div class="container">
        <h1 class="mt-2 text-center" id="people_label">People's Choice</h1>
      <div class="people-container">
          <!-- left position -->
          <img src="./assets/images/pictures/microblading2.jpg" id="people-choice" alt="People Choice" class="circle-pic">
          <!-- right position -->
          <div class="pc-desc-container">
            <div class="sub-text">Our Microblading and Lip Tattoo Service is availed by more than a hundred clients! Book now and be one to experience this!</div>
          </div>
      </div>
    </div>
  </div>

  <div class="container-flex add-gray-bg">
    <div class="container">
      <div class="promo-container">
        <div class="promo-welcome">
          <h1 class="mt-2 text-center" id="just-promos">Just for you promos!</h1>
          <div class="sub-text sub-text-r">Avail now to get quality services in lower price</div>
        </div>
          <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php for ($i = 0; $i < $totalPromos; $i++) : ?>
                    <button data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i; ?>" <?php echo ($i == 0) ? 'class="active"' : ''; ?>></button>
                <?php endfor; ?>
            </div>

            <!-- Carousel items with dynamic data -->
            <div class="carousel-inner">
                <?php
                $index = 0;
                while ($row = mysqli_fetch_assoc($result1)) :
                    ?>
                    <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                        <img class="d-block custom-image" src="image.php?promo_id=<?php echo $row['promo_id']; ?>" alt="<?php echo $row['promo_details']; ?>">
                    </div>
                    <?php
                    $index++;
                endwhile;
                ?>
            </div>

            <!-- Carousel controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
      </div>
      <div class="see-more text-left"><a href="promos.php">See more information</a></div>
    </div>
  </div>

  <div class="container-flex add-white-bg">
    <div class="container">
        <div class="people-container" id="about_us_section">
            <!-- left position -->
            <img src="./assets/images/pictures/browlesque.svg" id="browlesque-img" alt="Browlesque Image" class="about-us-img">
            <!-- right position -->
            <div class="about-us-text">
                <h1 class="mt-2 abt-center" id="about_label">About Us</h1>
                <div class="sub-text sub-text-m">The Hollywood celebrities and star's choices for best Microblading eyebrows, scalp and other micropigmentation procedures.<br><br>Take this opportunity to have the brows and natural pinkish youthful lips you have always wanted!</div>
                <div class="sub-text sub-text-m mt-0 add-bold"><br>Located at 12 Real Street Bacoor, Cavite, Philippines.</div>
            </div>
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

<script>
    function redirectToServicePage(serviceId) {
        // You can redirect the user to a specific page based on the service ID
        window.location.href = 'service.php?service_id=' + serviceId;
    }

    function scrollToAboutSection() {
        // Select the About Us section by its ID
        var aboutSection = document.getElementById('about_us_section');
        // Scroll to the About Us section with smooth behavior
        aboutSection.scrollIntoView({ behavior: 'smooth' });
    }
</script>

</body>
</html>
