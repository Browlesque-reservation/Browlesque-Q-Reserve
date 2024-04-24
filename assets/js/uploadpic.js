function validateFile() {
    var fileInput = document.getElementById('service_image');
    var fileDisplay = document.getElementById('image_preview');
    var fileInputLabel = document.getElementById('fileInputLabel');
    var filePath = fileInput.value;
    // Allow image and SVG file types
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.svg)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alert('Please upload an image file (jpg, jpeg, png, gif) or SVG only.');
        fileInput.value = '';
        fileDisplay.src = '';
        fileDisplay.style.display = 'none';
        fileInputLabel.innerText = 'Choose Image';
        return false;
    }

    var reader = new FileReader();
    reader.onload = function(e) {
        fileDisplay.src = e.target.result;
        fileDisplay.style.display = 'block';
        fileInputLabel.innerText = "Replace Image | " + (filePath.split('\\').pop());
    }
    reader.readAsDataURL(fileInput.files[0]);

    return true;
}
