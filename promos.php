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
    <title>Add Promos</title>
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<div class="d-flex">
    <?php include "sidebar.php";?>
    <!-- Content container -->
    <div class="content-container">
        <h1 class="page-header">Add Promos</h1>
        <div class="container-fluid container-md-custom-s">
        <form id="promosForm" method="POST" action="insert_promo.php" enctype="multipart/form-data" onsubmit="event.preventDefault(); showConfirmationModal();">                
                <div class="form-group">
                    <label for="promo_image" class="label-checkbox"><span class="asterisk">*</span>Upload Picture:</label>
                    <div class="image-input-container">
                        <input type="file" class="form-control form-control-s img-upload" id="promo_image" name="promo_image" onchange="validateFile()" required>
                        <label for="promo_image" id="fileInputLabel" class="form-control-s btn btn-primary btn-primary-custom image-btn">Choose Image</label>
                        <img id="image_preview" alt="Promo Image"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="promo_details" class="label-checkbox"><span class="asterisk">*</span>Promo Details:</label>
                    <textarea type="text" class="form-control form-control-s tall-input" id="promo_details" name="promo_details" placeholder="Details..." required></textarea>
                </div>
                <div class="fixed-buttons">
                    <button type="submit" name="user_submit" class="btn btn-primary btn-primary-custom text-center" onclick="validateBeforeSubmit()">Submit</button>
                    <a href="display_promos.php" class="btn btn-primary btn-primary-custom cancel-btn text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="confirmationModal" data-bs-backdrop="static" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/add-icon.svg" class="mt-3" alt="Success Icon" width="70" height="70">
        <h2 class="text-center mt-3 mb-0">Are you sure you want to add a new <br>Promo?</h2>
            <div class="d-flex justify-content-end mt-5">
                <button type="button" id="confirmButton" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="submitFormP()">Confirm</button>
                <button type="button" id="editButton" class="btn btn-primary-custom cancel-btn me-2 fs-5 text-center" onclick="hideConfirmationModal()">Edit</button>
            </div>
    </div>
</div>


<div id="successModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <!-- Replace the inline SVG with an <img> tag referencing your SVG file -->
        <img src="./assets/images/icon/successful-icon.svg" alt="Success Icon" width="70" height="70">
        <!-- End of replaced SVG -->
        <h2 class="text-center custom-subtitle mt-2 mb-2">The promo has been added successfully.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="hideSuccessModal(); window.location.href = 'display_promos.php';">Back</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="./assets/js/uploadpicPromo.js"></script>
<script src="./assets/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
} else {
    header("Location: index.php");
    die();
}
?>
