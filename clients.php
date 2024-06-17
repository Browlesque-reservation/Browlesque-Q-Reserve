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
    <title>List of Clients</title>
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
    <div class="content-container container">
        <h1 class="page-header">List of Clients</h1>
        <section class="home" id="clients">
            <div class="container-fluid">
                <div class="" id="table">
                    <div class="sas-table">
                        <div class="search-container">
                            <input type="text" id="searchInput" class="mb-2" placeholder="Search...">
                        </div>
                        <button class="archive-btn mb-2 me-3" onclick="showConfirmationModalArchive()">
                            <img src="./assets/images/icon/archive.svg" class="archive-svg" alt="Archive Icon">
                        </button>
                        <button class="archive-btn mb-2 me-1" onclick="showConfirmationModalQRScan()">
                            <img src="./assets/images/icon/qrscan.svg" class="qrscan-svg" alt="Scan Icon">
                        </button>
                    </div>
                    <div id="myGrid1" style="width: 100%; height: 90%" class="ag-theme-quartz"></div>
                </div>
            </div>
        </section>
    </div>
</div>

<div id="confirmationModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/confirm-archive.svg" class="mt-3" alt="Success Icon" width="70" height="70">
        <h2 class="text-center mt-3 mb-0">Are you sure you want to archive this data?</h2>
            <div class="d-flex justify-content-end mt-5">
                <button type="button" id="confirmButton" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="archiveChecked()">Confirm</button>
                <button type="button" id="cancelButton" class="btn btn-primary-custom cancel-btn me-2 fs-5 text-center" onclick="$('#confirmationModal').hide();">Cancel</button>
            </div>
    </div>
</div>


<div id="successModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <!-- Replace the inline SVG with an <img> tag referencing your SVG file -->
        <img src="./assets/images/icon/successful-icon.svg" alt="Success Icon" width="70" height="70">
        <!-- End of replaced SVG -->
        <h2 class="text-center custom-subtitle mt-2 mb-2">The data has been successfully archived.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="hideSuccessModal(); window.location.href = 'clients.php';">Back</button>
        </div>
    </div>
</div>

<script src="./assets/js/sidebar.js"></script>
<script>var __basePath = './';</script>
<script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.0.1/dist/ag-grid-community.min.js"></script>
<script src="./assets/js/clients.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
// Function to archive checked appointments
function archiveChecked() {
    // Retrieve archiveIds from the confirm button's data attribute
    var archiveIds = $('#confirmButton').data('archiveIds');

    // Log the archiveIds to console
    console.log('Archiving these appointment IDs:', archiveIds);

    // Check if archiveIds is an array and not empty
    if (Array.isArray(archiveIds) && archiveIds.length > 0) {
        // Perform AJAX request to archive appointments
        $.ajax({
            url: 'archive_appointments.php', // Replace with the actual path to your PHP script
            type: 'POST',
            dataType: 'text', // Expect text response from PHP
            data: { archive_ids: archiveIds }, // Send archiveIds as 'archive_ids' in POST data
            success: function(response) {
                if (response.trim() === 'success') {
                    console.log('Appointments archived successfully.');
                    hideConfirmationModal();
                    showSuccessModal();
                    // Handle success case if needed
                    // For example, update UI to reflect archived status
                } else {
                    console.error('Error archiving appointments:', response);
                    // Handle error case if needed
                    // For example, show an error message to the user
                    alert('Error archiving appointments: ' + response);
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
        console.warn('No appointments selected to archive.');
        // Handle case where no appointments were selected
        alert('Please select appointments to archive.');
    }

    // Hide the confirmation modal after processing
    $('#confirmationModal').hide();
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