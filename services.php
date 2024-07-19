<?php
define('INCLUDED', true);
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_SESSION['admin_email'])) {
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Services</title>
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
    <div class="content-container content">
        <h1 class="page-header">Add Services</h1>
        <div class="container-fluid container-md-custom-s">
        <form id="servicesForm" method="POST" action="insert_service.php" enctype="multipart/form-data" onsubmit="validateBeforeSubmit(event)">                
                   <!-- Hidden input field to store admin_id -->
                <input type="hidden" id="admin_id" name="admin_id" value="<?php echo "$admin_id"; ?>">
                <div class="form-group">
                    <label for="service_image" class="label-checkbox"><span class="asterisk">*</span>Upload Picture:</label>
                    <div class="image-input-container">
                        <input type="file" class="form-control form-control-s img-upload" id="service_image" name="service_image" onchange="validateFile()">
                        <label for="service_image" id="fileInputLabel" class="form-control-s btn btn-primary btn-primary-custom image-btn">Choose Image</label>
                        <img id="image_preview" alt="Service Image"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="service_name" class="label-checkbox"><span class="asterisk">*</span>Service Name:</label>
                    <input type="text" class="form-control form-control-s" id="service_name" name="service_name" placeholder="Service Name" required minlength="3" maxlength="50">
                </div>
                <div class="form-group">
                    <label for="service_price" class="label-checkbox"><span class="asterisk">*</span>Service Price:</label>
                    <input type="number" class="form-control form-control-s" id="service_price" name="service_price" placeholder="Service Price" required min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label for="service_description" class="label-checkbox"><span class="asterisk">*</span>Details:</label>
                    <textarea type="text" class="form-control form-control-s tall-input" id="service_description" name="service_description" placeholder="Details..." required  minlength="3" maxlength="400"></textarea>
                    <div id="charLimitMessage" class="char-limit-messag mb-2">Note: Maximum input of 400 characters only.</div>
                </div>
                <div class="fixed-buttons">
                    <button type="submit" name="user_submit" class="btn btn-primary btn-primary-custom text-center">Submit</button>
                    <a href="display_services.php" class="btn btn-primary btn-primary-custom cancel-btn text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="confirmationModal" data-bs-backdrop="static" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/add-icon.svg" class="mt-3" alt="Success Icon" width="70" height="70">
        <h2 class="text-center mt-3 mb-0">Are you sure you want to add a new <br>Service?</h2>
            <div class="d-flex justify-content-end mt-5">
                <button type="button" id="confirmButton" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="submitForm()">Confirm</button>
                <button type="button" id="editButton" class="btn btn-primary-custom cancel-btn me-2 fs-5 text-center" onclick="hideConfirmationModal()">Edit</button>
            </div>
    </div>
</div>


<div id="successModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <!-- Replace the inline SVG with an <img> tag referencing your SVG file -->
        <img src="./assets/images/icon/successful-icon.svg" alt="Success Icon" width="70" height="70">
        <!-- End of replaced SVG -->
        <h2 class="text-center custom-subtitle mt-2 mb-2">The service has been added successfully.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="hideSuccessModal(); window.location.href = 'display_services.php';">Back</button>
        </div>
    </div>
</div>

<div id="imageTypeModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">Please upload an image file in JPEG/JPG or PNG format.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#imageTypeModal').hide();">Back</button>
        </div>
    </div>
</div>

<div id="imageSizeModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">File size exceeds 10 MB. Please upload a smaller file.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#imageSizeModal').hide();">Back</button>
        </div>
    </div>
</div>

<div id="chooseImageModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">Please upload an image.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#chooseImageModal').hide();">Back</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="./assets/js/uploadpicService.js"></script>
<script src="./assets/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
function handleKeyPress(event) {
    var charCode = event.charCode;
    var inputValue = event.target.value;

    if (inputValue.slice(-1) === ' ' && charCode === 32) {
        event.preventDefault();
        return;
    }

    event.target.value = inputValue.replace(/\s{2,}/g, ' ');
    
    if (inputValue.length === 0 && charCode === 32) {
        event.preventDefault();
        return;
    }
}

function handleBlur(event) {
    event.target.value = event.target.value.trim();
}

var serviceName = document.getElementById("service_name");
var serviceDescription = document.getElementById("service_description");

serviceName.addEventListener("keypress", handleKeyPress);
serviceName.addEventListener("blur", handleBlur);
serviceDescription.addEventListener("keypress", handleKeyPress);
serviceDescription.addEventListener("blur", handleBlur);

    const charLimitMessage = document.getElementById('charLimitMessage');

    serviceDescription.addEventListener('input', function () {
      if (serviceDescription.value.length >= 400) {
        charLimitMessage.style.display = 'block';
      } else {
        charLimitMessage.style.display = 'none';
      }
    });


document.querySelector('input[name="service_price"]').addEventListener('keydown', function(e) {
    if (e.key === 'e' || e.key === 'E' || e.key === '-' || e.key === '+') {
        e.preventDefault();
    }
});
</script>

</body>
</html>

<?php
} else {
    header("Location: index.php");
    die();
}
?>
