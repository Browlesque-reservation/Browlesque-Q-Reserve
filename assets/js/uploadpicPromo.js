function validateFile() {
    var fileInput = document.getElementById('promo_image');
    var fileDisplay = document.getElementById('image_preview');
    var fileInputLabel = document.getElementById('fileInputLabel');
    var filePath = fileInput.value;
    
    // Allow image and SVG file types
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    if (!allowedExtensions.exec(filePath)) {
        // alert('Please upload an image file (jpg, jpeg, png)');
        showImageTypeModal();
        fileInput.value = '';
        fileDisplay.src = '';
        fileDisplay.style.display = 'none';
        fileInputLabel.innerText = 'Choose Image';
        return false;
    }

    // Check file size (10 MB maximum)
    var file = fileInput.files[0];
    var maxSize = 10 * 1024 * 1024; // 10 MB in bytes
    if (file.size > maxSize) {
        showImageSizeModal();
        fileInput.value = '';
        fileDisplay.src = '';
        fileDisplay.style.display = 'none';
        fileInputLabel.innerText = 'Choose Image';
        return false;
    }

    var fileName = filePath.split('\\').pop();
    var truncatedFileName = fileName.length > 20 ? fileName.substring(0, 20) + '...' : fileName;
    var replaceImageText = "Replace Image | ";
    fileInputLabel.title = fileName;
    fileInputLabel.innerHTML = replaceImageText + truncatedFileName;
    fileInputLabel.style.width = 'auto';

    var reader = new FileReader();
    reader.onload = function(e) {
        fileDisplay.src = e.target.result;
        fileDisplay.style.display = 'block';
    }
    reader.readAsDataURL(file);

    return true;
}


function validateBeforeSubmit(event) {
    event.preventDefault();
    var fileInput = document.getElementById('promo_image');

    if (!fileInput.files || fileInput.files.length === 0) {
        showChooseImageModal();
        return false;
    }

    showConfirmationModal();
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

function showChooseImageModal() {
    var imageSizeModal = document.getElementById('chooseImageModal');
    imageSizeModal.style.display = 'block';
}