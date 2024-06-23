<?php
define('INCLUDED', true);
define('APP_LOADED', true);
require_once ('connect.php');
require_once ('stopback.php');

if(isset($_SESSION['admin_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Appointments</title>
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
        <h1 class="page-header">Archived Appointments</h1>
                <div class="container-md" id="table">
                    <div class="sas-table">
                        <div class="search-container">
                            <input type="text" id="searchInput" class="mb-2" placeholder="Search...">
                        </div>
                        <button class="archive-btn mb-2" onclick="showConfirmationModalRestore()">
                            <img src="./assets/images/icon/restore.svg" class="archive-svg" alt="Restore Icon">
                        </button>
                    </div>
                    <div id="myGrid2" style="width: 100%; height: 480px" class="ag-theme-quartz"></div>
                </div>
    </div>
</div>

<div id="confirmationModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/restore.svg" class="mt-3" alt="Success Icon" width="70" height="70">
        <h2 class="text-center mt-3 mb-0">Are you sure you want to restore this data?</h2>
            <div class="d-flex justify-content-end mt-5">
                <button type="button" id="confirmButtonrestore" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="restoreChecked()">Confirm</button>
                <button type="button" id="cancelButton" class="btn btn-primary-custom cancel-btn me-2 fs-5 text-center" onclick="$('#confirmationModal').hide();">Cancel</button>
            </div>
    </div>
</div>


<div id="successModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <!-- Replace the inline SVG with an <img> tag referencing your SVG file -->
        <img src="./assets/images/icon/deleted-icon.svg" alt="Success Icon" width="70" height="70">
        <!-- End of replaced SVG -->
        <h2 class="text-center custom-subtitle mt-2 mb-2">The data has been successfully restored.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="hideSuccessModal(); window.location.href = 'archive.php';">Back</button>
        </div>
    </div>
</div>

<script src="./assets/js/sidebar.js"></script>
<script>var __basePath = './';</script>
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.0.1/dist/ag-grid-community.min.js"></script>
<!-- <script src="./assets/js/clients.js"></script> -->
<script src="./assets/js/archive.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
// Function to restore checked restored appointments
function restoreChecked() {
    // Retrieve restoreIds from the confirm button's data attribute
    var restoreIds = $('#confirmButtonrestore').data('restoreIds');

    // Log the restoreIds to console
    console.log('Archiving these resotre IDs:', restoreIds);

    // Check if restoreIds is an array and not empty
    if (Array.isArray(restoreIds) && restoreIds.length > 0) {
        // Perform AJAX request to restore appointments
        $.ajax({
            url: 'restore_appointments.php', // Replace with the actual path to your PHP script
            type: 'POST',
            dataType: 'text', // Expect text response from PHP
            data: { restore_ids: restoreIds }, // Send archiveIds as 'restore_ids' in POST data
            success: function(response) {
                if (response.trim() === 'success') {
                    console.log('Appointments archived successfully.');
                    hideConfirmationModal();
                    showSuccessModal();
                    // Handle success case if needed
                    // For example, update UI to reflect restored status
                } else {
                    console.error('Error restoring appointments:', response);
                    // Handle error case if needed
                    // For example, show an error message to the user
                    alert('Error restoring appointments: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                // Log the full responseText for detailed error debugging
                console.log(xhr.responseText);
                alert('AJAX Error: ' + xhr.responseText);
            }
        });
    } else {
        console.warn('No appointments selected to restore.');
        // Handle case where no appointments were selected
        alert('Please select appointments to restore.');
    }

    // Hide the confirmation modal after processing
    $('#confirmationModal').hide();
}

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
</script>

</body>
</html>

<?php
} else {
    header("Location: index.php");
    die();
}
?>  