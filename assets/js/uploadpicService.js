function validateFile() {
    var fileInput = document.getElementById('service_image');
    var fileDisplay = document.getElementById('image_preview');
    var fileInputLabel = document.getElementById('fileInputLabel');
    var filePath = fileInput.value;
    // Allow image and SVG file types
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    if (!allowedExtensions.exec(filePath)) {
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
    var truncatedFileName = fileName.length > 20 ? fileName.substring(0, 20) + '...' : fileName; // Truncate long file names
    var replaceImageText = "Replace Image | ";
    fileInputLabel.title = fileName; // Set full file name as title for tooltip
    fileInputLabel.innerHTML = replaceImageText + truncatedFileName; // Concatenate the text
    fileInputLabel.style.width = 'auto'; // Ensure that label width adjusts to its content

    var reader = new FileReader();
    reader.onload = function(e) {
        fileDisplay.src = e.target.result;
        fileDisplay.style.display = 'block';
    }
    reader.readAsDataURL(fileInput.files[0]);

    return true;
}

function validateBeforeSubmit(event) {
    event.preventDefault();
    
    var fileInput = document.getElementById('service_image');

    if (!fileInput.files || fileInput.files.length === 0) {
        showChooseImageModal();
        return false;
    }

    showConfirmationModal();
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
    var imageSizeModal = document.getElementById('chooseImageModal');
    imageSizeModal.style.display = 'block';
}