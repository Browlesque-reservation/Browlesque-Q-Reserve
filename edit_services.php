<?php
define('INCLUDED', true);
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_SESSION['admin_email'])) {
    $current_page = basename($_SERVER['PHP_SELF']);

    if(isset($_GET['service_id'])) {
        $service_id = $_GET['service_id'];
        $query = "SELECT service_id, service_description, service_name, service_image, admin_id FROM services WHERE service_id = $service_id";
        $result = mysqli_query($conn, $query);
        $service = mysqli_fetch_assoc($result);
    } else {
        header("Location: display_services.php");
        exit();
    }
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
        <h1 class="page-header">Edit Services</h1>
        <div class="container-fluid container-md-custom-s">
        <form id="servicesForm" method="POST" action="update_service.php" enctype="multipart/form-data" onsubmit="validateBeforeSubmit(event)">                
                   <!-- Hidden input field to store admin_id -->
                <input type="hidden" id="admin_id" name="admin_id" value="<?php echo "$admin_id"; ?>">
                <input type="hidden" id="service_id" name="service_id" value="<?php echo "$service_id"; ?>">
                <div class="form-group">
                    <label for="service_image" class="label-checkbox"><span class="asterisk">*</span>Upload Picture:</label>
                    <div class="image-input-container">
                        <input type="file" class="form-control form-control-s img-upload" id="service_image" name="service_image" onchange="validateFile()">
                        <label for="service_image" id="fileInputLabel" class="form-control-s btn btn-primary btn-primary-custom image-btn">Choose Image</label>
                        <img id="image_preview" src='image.php?service_id=<?php echo $service_id; ?>' alt="Service Image"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="service_name" class="label-checkbox"><span class="asterisk">*</span>Service Name:</label>
                    <input type="text" class="form-control form-control-s" id="service_name" name="service_name" placeholder="Service Name" value="<?php echo htmlspecialchars($service['service_name']); ?>" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="service_description" class="label-checkbox"><span class="asterisk">*</span>Details:</label>
                    <textarea type="text" class="form-control form-control-s tall-input" id="service_description" name="service_description" placeholder="Details..." maxlength="400" required><?php echo $service['service_description']; ?></textarea>
                </div>
                <div class="fixed-buttons">
                    <button type="submit" name="up_service_submit" class="btn btn-primary btn-primary-custom text-center">Submit</button>
                    <a href="display_services.php" class="btn btn-primary btn-primary-custom cancel-btn text-center">Cancel</a>
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
<!-- <script src="./assets/js/modal.js"></script> -->
<script src="./assets/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
function handleKeyPress(event) {
    var charCode = event.charCode;
    var inputValue = event.target.value;
    
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


 function validateFile() {
    var fileInput = document.getElementById('service_image');
    var fileDisplay = document.getElementById('image_preview');
    var fileInputLabel = document.getElementById('fileInputLabel');
    var filePath = fileInput.value;

    // Check if a file is selected
    if (!fileInput.files || fileInput.files.length === 0) {
        // Check if an existing image is already displayed
        if (fileDisplay.src && fileDisplay.src !== '' && !fileDisplay.src.startsWith('data:')) {
            return true; // Existing image is present, validation passes
        } else {
            resetFileInput();
            return false;
        }
    }

    var file = fileInput.files[0];

    // Check file size (2 MB maximum)
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
        fileInputLabel.innerText = 'Choose Image';
    }
}

// Add this function to display the existing image when the page loads
window.onload = function() {
    displayExistingImage();
};

function displayExistingImage() {
    var fileDisplay = document.getElementById('image_preview');
    var fileInputLabel = document.getElementById('fileInputLabel');
    var serviceId = <?php echo $service_id; ?>;
    
    if (serviceId) {
        // If service_id is available, set the source of the image to display the existing image
        fileDisplay.src = 'image.php?service_id=' + serviceId;
        fileDisplay.style.display = 'block';
        fileInputLabel.innerText = 'Replace Image'; // Update label for existing image
    }
}

// Event listener to trigger validation when a new file is selected
document.getElementById('service_image').addEventListener('change', function() {
    validateFile();
});

function validateBeforeSubmit(event) {
    event.preventDefault();
    
    var serviceName = document.getElementById("service_name").value.trim();
    var serviceDetails = document.getElementById("service_description").value.trim();
    var fileInput = document.getElementById('service_image');
    var fileDisplay = document.getElementById('image_preview');

    if ((!fileInput.files || fileInput.files.length === 0) && (!fileDisplay.src || fileDisplay.src === '' || fileDisplay.src.startsWith('data:'))) {
        showChooseImageModal();
        return false;
    }

    document.getElementById("servicesForm").submit();
    return true;
}

function showImageTypeModal() {
    var imageTypeModal = document.getElementById('imageTypeModal');
    imageTypeModal.style.display = 'block';
}

function showImageSizeModal() {
    var imageSizeModal = document.getElementById('imageSizeModal');
    imageSizeModal.style.display = 'block';
}

function showChooseImageModal() {
    var chooseImageModal = document.getElementById('chooseImageModal');
    chooseImageModal.style.display = 'block';
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
