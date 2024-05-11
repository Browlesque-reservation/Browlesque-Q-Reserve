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
                    <a class="nav-link back-button" href="Index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link back-button" href="book_appointment1.php">Book Appointment</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="index.php#about_us_section">About us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
  <div class="container-flex add-white-bg">
      <div class="container">
      <h1 class="fw-bold mt-2" id="title">BOOKING CONFIRMATION</h1>
        <div class="container-md container-md-custom">
            <div class="confirmation-details"> 
                <div class="sub-text sub-text-c">Summary</div>
                <?php
                    require_once('connect.php'); // Include your database connection script

                    // Fetch appointment details from the database
                    $query = "SELECT * FROM client_appointment ORDER BY appointment_id DESC LIMIT 1";
                    $result = mysqli_query($conn, $query);
                    $appointment = mysqli_fetch_assoc($result);

                    // Check if appointment exists
                    if ($appointment) {
                        // Fetch client details associated with the appointment
                        $appointmentId = $appointment['appointment_id'];
                        $query_details = "SELECT * FROM client_details WHERE appointment_id = $appointmentId";
                        $result_details = mysqli_query($conn, $query_details);
                        $clientDetails = mysqli_fetch_assoc($result_details);

                        // Fetch service names based on service IDs
                        $serviceIds = json_decode($appointment['service_id'], true); // Decode JSON string into array
                        $serviceNames = array();
                        if (!empty($serviceIds)) {
                            foreach ($serviceIds as $serviceId) {
                                $query_service = "SELECT service_name FROM services WHERE service_id = $serviceId";
                                $result_service = mysqli_query($conn, $query_service);
                                $service = mysqli_fetch_assoc($result_service);
                                if ($service) {
                                    $serviceNames[] = $service['service_name'];
                                }
                            }
                        }
                        
                        $promoIds = json_decode($appointment['promo_id'], true); // Decode JSON string into array
                        $promoNames = array();
                        if (!empty($promoIds)) {
                            foreach ($promoIds as $promoId) {
                                $query_promo = "SELECT promo_details FROM promo WHERE promo_id = $promoId";
                                $result_promo = mysqli_query($conn, $query_promo);
                                $promo = mysqli_fetch_assoc($result_promo);
                                if ($promo) {
                                    $promoNames[] = $promo['promo_details'];
                                }
                            }
                        }                        

                        $formattedDate = date('F j, Y', strtotime($appointment['client_date']));
                        $formattedStartTime = date('h:i A', strtotime($appointment['start_time']));
                        $formattedEndTime = date('h:i A', strtotime($appointment['end_time']));

                        // Now, you have both appointment and client details, as well as service names, you can inject them into HTML
                ?>
                <div class="sub-text sub-text-c dets mb-0">Name: <?php echo $clientDetails['client_name']; ?></div>
                <div class="sub-text sub-text-c dets mb-0 mt-0">Phone Number: <?php echo $clientDetails['client_contactno']; ?></div>
                <div class="sub-text sub-text-c dets mb-0 mt-0">No. of Companion/s : <?php echo $clientDetails['no_of_companions']; ?></div>
                <div class="sub-text sub-text-c dets mb-4">Service and Promo Availed: 
                    <?php 
                        if (!empty($serviceNames)) {
                            echo implode(', ', $serviceNames);
                        } else {
                            echo "No services availed";
                        } 
                        if (!empty($promoNames)) {
                            echo ', '.implode(', ', $promoNames);
                        } else {
                            echo ", No promos availed";
                        }
                    ?>
                </div>
                <div class="sub-text sub-text-c dets mt-0">Date and Time of Appointment: <?php echo $formattedDate; ?>. <?php echo $formattedStartTime; ?> - <?php echo $formattedEndTime; ?></div>
                <?php
                    } else {
                        // Handle case where no appointment is found
                        echo "<div>No appointment found.</div>";
                    }
                ?>
            </div>
            <div class="sub-text sub-text-c dets-1">Please save and present this QR code to your appointment day!</div>
            <!-- Display the generated QR code here -->
            <div class="qrcode-container">
                  <img src="generate-qr.php" alt="QR Code">
              <div class="under-qrcode mt-4">
                  <a id="downloadLink" href="generate-qr.php" download="appointment_qr_code.png">
                      <img src="./assets/images/icon/export-qr.svg" alt="QR Download Icon"> 
                  </a>
              </div>

                <div class="sub-text sub-text-c dets-2">Thank you for setting your appointment.</div>
            </div>
        </div>
            <div class="back-home mt-5">
                <a class="back-button" href="index.php">
                  <img src="./assets/images/icon/back-home.svg" alt="Back Home Icon">
                </a>
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
                <a class="footer-text-white back-button" href="https://www.facebook.com/BrowlesqueCavite">
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

<!-- Add a modal dialog -->
<div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reminderModalLabel">Reminder</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Please download the QR code before leaving the page.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- <script>
    // Variable to track whether QR code has been downloaded
    var qrDownloaded = false;

    // Event listener for the download link
    document.getElementById('downloadLink').addEventListener('click', function() {
        // Set flag to true when download link is clicked
        qrDownloaded = true;
    });

    // Event listener for all elements with the class 'back-button'
    var backButtonElements = document.getElementsByClassName('back-button');
    for (var i = 0; i < backButtonElements.length; i++) {
        backButtonElements[i].addEventListener('click', function(event) {
            // Prevent default action
            event.preventDefault();
            // Show the modal dialog if QR code has not been downloaded
            if (!qrDownloaded) {
                var myModal = new bootstrap.Modal(document.getElementById('reminderModal'));
                myModal.show();
            } else {
                // Otherwise, proceed with navigating to the previous page
                window.location.href = event.target.href;
            }
        });
    }
    
</script> -->



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
