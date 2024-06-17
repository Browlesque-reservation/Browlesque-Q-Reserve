// Function to submit the form and show success modal
//for SERVICESSSS
function submitForm() {
    // Submit the form using AJAX
    var formData = new FormData(document.getElementById('servicesForm'));
    $.ajax({
        type: "POST",
        url: "insert_service.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Show the success modal
            showSuccessModal();
            // Hide the confirmation modal
            hideConfirmationModal();
            console.log("Confirmation modal hidden");
        },
        error: function(xhr, status, error) {
            // Handle errors here
            console.error(xhr.responseText);
        }
    });
}

//for PROMOSSS
function submitFormP() {
    // Submit the form using AJAX
    var formData1 = new FormData(document.getElementById('promosForm'));
    $.ajax({
        type: "POST",
        url: "insert_promo.php",
        data: formData1,
        processData: false,
        contentType: false,
        success: function(response) {
            // Show the success modal
            showSuccessModal();
            // Hide the confirmation modal
            hideConfirmationModal();
            console.log("Confirmation modal hidden");
        },
        error: function(xhr, status, error) {
            // Handle errors here
            console.error(xhr.responseText);
        }
    });
}

function showConfirmationModalDelete() {
    var checkboxes = document.querySelectorAll('.delete-checkbox:checked');
    var serviceIds = Array.from(checkboxes).map(function(checkbox) {
        return checkbox.id.split('_')[2]; // Extract service ID from checkbox ID
    });
    
    if(serviceIds.length === 0) {
        alert("Please select at least one service to delete.");
        return;
    }

    // Store the service IDs in a data attribute of the confirm button
    $('#confirmButton').data('serviceIds', serviceIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}

function showConfirmationModalDeleteP() {
    var checkboxes = document.querySelectorAll('.delete-checkbox:checked');
    var serviceIds = Array.from(checkboxes).map(function(checkbox) {
        return checkbox.id.split('_')[2]; // Extract service ID from checkbox ID
    });
    
    if(serviceIds.length === 0) {
        alert("Please select at least one promo to delete.");
        return;
    }

    // Store the service IDs in a data attribute of the confirm button
    $('#confirmButton').data('serviceIds', serviceIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}

function showConfirmationModalArchive() {
    // Get the grid's selected rows
    var selectedNodes = gridApi.getSelectedNodes();
    console.log("Number of checkboxes checked:", selectedNodes.length);

    // Map to extract appointment_id from the selected rows
    var archiveIds = selectedNodes.map(function(node) {
        return node.data.appointment_id; // Extract appointment_id from node data
    });

    if (archiveIds.length === 0) {
        alert("Please select at least one appointment to archive.");
        return;
    }

    // Log the appointment_id selected
    // console.log("Appointment IDs selected:", archiveIds);

    // Store the archiveIds in the confirm button's data attribute
    $('#confirmButton').data('archiveIds', archiveIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}

document.getElementById('confirmButton').addEventListener('click', showConfirmationModalArchive);

function showConfirmationModalRestore() {
    // Get the grid's selected rows
    var selectedNodes = gridApi.getSelectedNodes();
    console.log("Number of checkboxes checked:", selectedNodes.length);

    // Map to extract archive_id from the selected rows
    var restoreIds = selectedNodes.map(function(node) {
        return node.data.archive_id; // Extract archive_id from node data
    });

    if (restoreIds.length === 0) {
        alert("Please select at least one appointment to restore.");
        return;
    }

    // Log the archive_id selected
    // console.log("Appointment IDs selected:", restoreIds);

    // Store the restoreIds in the confirm button's data attribute
    $('#confirmButtonrestore').data('restoreIds', restoreIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}


// Event listener to show the confirmation modal when the confirm button is clicked
document.getElementById('confirmButtonrestore').addEventListener('click', showConfirmationModalRestore);




function showConfirmationModal() {
    // Show the modal
    var confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.style.display = 'block';
}


// Function to hide the confirmation modal
function hideConfirmationModal() {
    // Hide the modal
    var confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.style.display = 'none';
}

// Function to show the success modal
function showSuccessModal() {
    // Show the modal
    var successModal = document.getElementById('successModal');
    successModal.style.display = 'block';
}

// Function to hide the success modal
function hideSuccessModal() {
    // Hide the modal
    var successModal = document.getElementById('successModal');
    successModal.style.display = 'none';
}