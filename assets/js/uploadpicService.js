function validateFile() {
    var fileInput = document.getElementById('service_image');
    var fileDisplay = document.getElementById('image_preview');
    var fileInputLabel = document.getElementById('fileInputLabel');
    var filePath = fileInput.value;
    // Allow image and SVG file types
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alert('Please upload an image file (jpg, jpeg, png, gif, webp only)');
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
    var serviceName = document.getElementById("service_name").value.trim();
    var serviceDetails = document.getElementById("service_description").value.trim();

    // Check if a file has been selected
    if (!fileInput.files || fileInput.files.length === 0) {
        alert("Please choose an image.");
        return false;
    }

    if (serviceName === "") {
        alert("Service Name cannot be empty.");
        return false;
    }
    if (serviceDetails === "") {
        alert("Service Details cannot be empty.");
        return false;
    }
    if (/^\s*$/.test(serviceName)) {
        alert("Service Name cannot be just spaces.");
        return false;
    }
    if (/^\s*$/.test(serviceDetails)) {
        alert("Service Details cannot be just spaces.");
        return false; 
    }

    showConfirmationModal();
    return true;
}