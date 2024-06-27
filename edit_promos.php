<?php
define('INCLUDED', true);
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_SESSION['admin_email'])) {
    $current_page = basename($_SERVER['PHP_SELF']);

    // Fetch promo details based on the promo_id in the URL
    if(isset($_GET['promo_id'])) {
        $promo_id = $_GET['promo_id'];
        $query = "SELECT promo_id, promo_details, promo_image, admin_id FROM promo WHERE promo_id = $promo_id";
        $result = mysqli_query($conn, $query);
        $promo = mysqli_fetch_assoc($result);
    } else {
        // Redirect if promo_id is not provided in the URL
        header("Location: display_promos.php");
        exit();
    }
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include "sidebar.php";?>
    <!-- Content container -->
    <div class="content-container content">
            <h1 class="page-header">Edit Promos</h1>
            <div class="container-fluid container-md-custom-s">
                <form id="promosForm" method="POST" action="update_promo.php" enctype="multipart/form-data" onsubmit="validateBeforeSubmit(event)">                
                    <!-- Hidden input field to store admin_id -->
                    <input type="hidden" id="admin_id" name="admin_id" value="<?php echo "$admin_id"; ?>">
                    <input type="hidden" id="promo_id" name="promo_id" value="<?php echo "$promo_id"; ?>">
                    <div class="form-group">
                        <label for="promo_image" class="label-checkbox"><span class="asterisk">*</span>Upload Picture:</label>
                        <div class="image-input-container">
                            <input type="file" class="form-control form-control-s img-upload" id="promo_image" name="promo_image" onchange="validateFile()">
                            <label for="promo_image" id="fileInputLabel" class="form-control-s btn btn-primary btn-primary-custom image-btn">Choose Image</label>
                            <!-- Display existing promo image -->
                            <img id="image_preview" src='image.php?promo_id=<?php echo $promo_id; ?>' alt="Promo Image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="promo_details" class="label-checkbox"><span class="asterisk">*</span>Promo Details:</label>
                        <!-- Display existing promo details -->
                        <textarea type="text" class="form-control form-control-s tall-input" id="promo_details" name="promo_details" placeholder="Details..." maxlength="400" required><?php echo $promo['promo_details']; ?></textarea>
                    </div>
                    <div class="fixed-buttons">
                        <button type="submit" name="up_promo_submit" class="btn btn-primary btn-primary-custom text-center">Submit</button>
                        <a href="display_promos.php" class="btn btn-primary btn-primary-custom cancel-btn text-center">Cancel</a>
                    </div>
                </form>
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

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="./assets/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
function validateFile() {
    var fileInput = document.getElementById('promo_image');
    var fileDisplay = document.getElementById('image_preview');
    var fileInputLabel = document.getElementById('fileInputLabel');
    var filePath = fileInput.value;

    // Check if a file is selected
    if (!fileInput.files || fileInput.files.length === 0) {
        resetFileInput();
        return false;
    }

    var file = fileInput.files[0];

    // Check file size (10 MB maximum)
    var maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
        showImageSizeModal();
        resetFileInput();
        return false;
    }

    // Allow image file types
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    if (!allowedExtensions.exec(filePath)) {
        showImageTypeModal();
        resetFileInput();
        return false;
    }

    var reader = new FileReader();
    reader.onload = function(e) {
        fileDisplay.src = e.target.result;
        fileDisplay.style.display = 'block';
    }
    reader.readAsDataURL(file);

    // Update file input label
    var fileName = filePath.split('\\').pop();
    var truncatedFileName = fileName.length > 20 ? fileName.substring(0, 20) + '...' : fileName;
    var replaceImageText = "Replace Image | ";
    fileInputLabel.title = fileName; 
    fileInputLabel.innerHTML = replaceImageText + truncatedFileName; 
    fileInputLabel.style.width = 'auto';

    return true;

    function resetFileInput() {
        fileInput.value = '';
        fileDisplay.src = '';
        fileDisplay.style.display = 'none';
        fileInputLabel.innerText = 'Choose Image';
    }
}

        // Add this function to display the existing image when the page loads
        window.onload = function() {
            displayExistingImage();
        };

        function displayExistingImage() {
            var fileDisplay = document.getElementById('image_preview');
            var promoId = <?php echo $promo_id; ?>;
            
            if (promoId) {
                // If promo_id is available, set the source of the image to display the existing image
                fileDisplay.src = 'image.php?promo_id=' + promoId;
                fileDisplay.style.display = 'block';
            }
        }

        // Event listener to trigger validation when a new file is selected
        document.getElementById('promo_image').addEventListener('change', function() {
            validateFile();
        });

        function validateBeforeSubmit(event) {
            event.preventDefault();
            var promoDetails = document.getElementById("promo_details").value.trim();

            if (promoDetails === "") {
                alert("Promo Details cannot be empty.");
                return false;
            }
            if (/^\s*$/.test(promoDetails)) {
                alert("Promo Details cannot be just spaces.");
                return false;
            }

            document.getElementById("promosForm").submit();
            return true; // Allow form submission
        }
        function showImageTypeModal() {
            var imageTypeModal = document.getElementById('imageTypeModal');
            imageTypeModal.style.display = 'block';
        }

        function showImageSizeModal() {
            var imageSizeModal = document.getElementById('imageSizeModal');
            imageSizeModal.style.display = 'block';
        }
</script>
</body>
</html>

<?php
} else {
    header("Location: index.php");
    die();
}
?>
