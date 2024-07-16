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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>var __basePath = './';</script>
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.0.1/dist/ag-grid-community.min.js"></script>
    <script src="./assets/js/clients_pending.js"></script>
    <script src="./assets/js/clients_confirmed.js"></script>
    <script src="./assets/js/clients_forverify.js"></script>
    <script src="./assets/js/modal.js"></script>
</head>
<body>
<div class="d-flex">
    <?php include "sidebar.php";?>
    <!-- Content container -->
    <div class="content-container content">
        <h1 class="page-header">List of Clients</h1>
                <div class="container-md" id="table">
                    <h3 id="tableTitle">Appointment Request</h3>
                    <div id="titleLine"></div>
                    <div class="sas-table">
                        <div class="search-container">
                            <input type="text" id="searchInput3" class="mb-2" placeholder="Search..." onkeyup="onSearchInputChange()">
                        </div>
                    </div>
                    <center><div id="myGrid3" style="width: 89.5%; height: 520px" class="ag-theme-quartz"></div></center>
                    <div id="emptyState4" class="empty-state4">
                        <img src="./assets/images/pictures/no-data.svg" alt="No results found for appointment requests.">
                        <p>No results found</p>
                    </div>
                </div>

                <div class="container-md" id="table">
                    <h3 id="tableTitle">Accepted Client Appointments</h3>
                    <div id="titleLine"></div>
                    <div class="sas-table">
                        <div class="search-container">
                            <input type="text" id="searchInput" class="mb-2" placeholder="Search..." onkeyup="onSearchInputChange()">
                        </div>
                        <button class="archive-btn mb-2 me-1" id="openModalBtn">
                            <img src="./assets/images/icon/qrscan.svg" class="qrscan-svg" alt="Scan Icon">
                        </button>
                    </div>
                    <center><div id="myGrid1" style="width: 89.5%; height: 520px" class="ag-theme-quartz"></div></center>
                    <div id="emptyState" class="empty-state">
                        <img src="./assets/images/pictures/no-data.svg" alt="No results found for accepted client appointments.">
                        <p>No results found</p>
                    </div>
                </div>

                <div class="container-md" id="table">
                    <h3 id="tableTitle">Confirmed Client Appointments</h3>
                    <div id="titleLine"></div>
                    <div class="sas-table">
                        <div class="search-container">
                            <input type="text" id="searchInput2" class="mb-2" placeholder="Search..." onkeyup="onSearchInputChange()">
                        </div>
                        <button class="archive-btn mb-2 me-3" onclick="showConfirmationModalArchive()">
                            <img src="./assets/images/icon/archive.svg" class="archive-svg" alt="Archive Icon">
                        </button>
                    </div>
                        <div id="myGrid2" style="width: 100%; height: 520px" class="ag-theme-quartz"></div>
                        <div id="emptyState2" class="empty-state2">
                            <img src="./assets/images/pictures/no-data.svg" alt="No results found for confirmed client appointments.">
                            <p>No results found</p>
                        </div>
                </div>
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

<div class="modal fade" id="qrScanModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-custom">
            <button type="button" class="close_date" id="close_modal_button" aria-label="Close">&times;</button>
            <div class="modal-body">
                <div class="loader"></div>
                <form action="process_qr.php" method="POST" id="qrForm" style="display:none;">
                    <textarea name="qrData" id="qrData"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="qrScannedDetailsModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <!-- <img src="./assets/images/icon/confirm-archive.svg" class="mt-3" alt="Success Icon" width="70" height="70"> -->
        <h2 class="text-center mt-3 mb-0">QR Scanned Client Details</h2>
            <div class="qr-modal-body" id="qrScannedBody"></div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="button" id="confirmScanButton" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="statusUpdate()">Confirm</button>
                    <button type="button" id="cancelButton" class="btn btn-primary-custom cancel-btn me-2 fs-5 text-center" onclick="$('#qrScannedDetailsModal').hide();">Cancel</button>
                </div>
    </div>
</div>

<div id="clientDetailsModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <button type="button" class="close_date" id="close_modal_button" onclick="$('#clientDetailsModal').hide();">&times;</button>
        <!-- <img src="./assets/images/icon/confirm-archive.svg" class="mt-3" alt="Success Icon" width="70" height="70"> -->
        <h2 class="text-center mt-3 mb-0">Client Appointment Request Details</h2>
            <div class="view-modal-body" id="viewScannedBody"></div>
                <div class="d-flex justify-content-end">
                    <button type="button" id="confirmScanButton" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="acceptAppointment()">Accept</button>
                    <button type="button" id="cancelButton" class="btn btn-primary-custom cancel-btn me-2 fs-5 text-center" onclick="rejectAppointment()">Reject</button>
                </div>
    </div>
</div>

<div id="acceptSuccessModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <!-- Replace the inline SVG with an <img> tag referencing your SVG file -->
        <img src="./assets/images/icon/successful-icon.svg" alt="Success Icon" width="70" height="70">
        <!-- End of replaced SVG -->
        <h2 class="text-center custom-subtitle mt-2 mb-2">The appointment has been successfully accepted and an email has been sent to the client.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="hideAcceptModal(); window.location.href = 'clients.php';">Back</button>
        </div>
    </div>
</div>

<div id="notRecognizedModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" class="mt-3" alt="Success Icon" width="70" height="70">
        <h2 class="text-center mt-3 mb-0">QR Code Not Recognized!</h2>
            <div class="d-flex justify-content-end mt-2">
                <button type="button" id="cancelButton" class="btn btn-primary-custom cancel-btn reset-ml fs-5 text-center" onclick="$('#notRecognizedModal').hide();">Back</button>
            </div>
    </div>
</div>

<div id="atLeastModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">Please select at least one appointment to archive.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#atLeastModal').hide();">Back</button>
        </div>
    </div>
</div>

<div id="datePassedModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">The appointment date has already passed.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#datePassedModal').hide();">Back</button>
        </div>
    </div>
</div>

<div id="statusCancelledModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">The appointment was already cancelled.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#statusCancelledModal').hide();">Back</button>
        </div>
    </div>
</div>

<div id="statusConfirmedModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">The appointment was already confirmed.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#statusConfirmedModal').hide();">Back</button>
        </div>
    </div>
</div>

<div id="statusCompleteModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/wrong-qr.svg" alt="Success Icon" width="70" height="70">
        <h2 class="text-center custom-subtitle mt-2 mb-2">The appointment was already completed.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="$('#statusCompleteModal').hide();">Back</button>
        </div>
    </div>
</div>


<script src="./assets/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- <script src="./assets/js/preloader.js"></script> -->
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
        showAtLeastModalC();
    }

    // Hide the confirmation modal after processing
    $('#confirmationModal').hide();
}

function showAtLeastModalC() {
    var atLeastModal = document.getElementById('atLeastModal');
    atLeastModal.style.display = 'block';
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
        showAtLeastModalC();
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


$(document).ready(function() {
    var modal = $('#qrScanModal');
    var btn = $('#openModalBtn');
    var span = $('#close_modal_button');

    btn.on('click', function() {
        modal.modal('show'); // Ensure modal is shown correctly
        document.addEventListener('keydown', handleScan);
    });

    span.on('click', function() {
        modal.modal('hide'); // Ensure modal is hidden correctly
        document.removeEventListener('keydown', handleScan);
    });

    $(window).on('click', function(event) {
        if ($(event.target).is(modal)) {
            modal.modal('hide'); // Ensure modal is hidden correctly when clicking outside
            document.removeEventListener('keydown', handleScan);
        }
    });

    var scannedData = '';

    function handleScan(event) {
        if (event.key === 'Enter') {
            $('#qrData').val(scannedData);
            $.ajax({
                type: 'POST',
                url: 'process_qr.php',
                data: $('#qrForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Check if the date has already passed
                        const currentDate = new Date();
                        const clientDateParts = response.client_date.split('-');
                        const clientDate = new Date(clientDateParts[0], clientDateParts[1] - 1, clientDateParts[2]);
                        const status = response.status;

                        if (clientDate < currentDate) {
                            showDatePassedModal();
                        }
                        else if (status === 'Confirmed') {
                            showStatusConfirmedModal();
                        } else if (status === 'Completed') {
                            showStatusCompleteModal();
                        } else if (status === 'Cancelled') {
                            showStatusCancelledModal();
                        }
                        else {
                            // Store the appointment_id in a hidden field
                            $('#qrScannedBody').data('appointmentId', response.appointment_id);
                            $('#qrScannedBody').data('clientName', response.client_name);
                            $('#qrScannedBody').data('clientDate', response.client_date);
                            $('#qrScannedBody').data('startTime', response.start_time);
                            $('#qrScannedBody').data('endTime', response.end_time);
                            // Populate qrScannedBody with retrieved data
                            $('#qrScannedBody').html(`
                                <div style="padding: 1rem; padding-bottom: 0; margin-left: 1rem; margin-right: 1rem;">
                                    <p style="margin-top: 1rem; margin-bottom: 0.5rem;"><strong>Client Name:</strong> ${response.client_name}</p>
                                    <p style="margin-bottom: 0.5rem;"><strong>Contact Number:</strong> ${response.client_contactno}</p>
                                    <p style="margin-bottom: 0.5rem;"><strong>Service/s Availed:</strong> ${response.service_names}</p>
                                    <p style="margin-bottom: 0.5rem;"><strong>Promo/s Availed:</strong> ${response.promo_details}</p>
                                    <p style="margin-bottom: 0.5rem;"><strong>No. of Companions:</strong> ${response.no_of_companions}</p>
                                    <p style="margin-bottom: 0.5rem;"><strong>Notes:</strong> ${response.client_notes}</p>
                                    <p style="margin-bottom: 0.5rem;"><strong>Date:</strong> ${response.client_date}</p>
                                    <p style="margin-bottom: 0;"><strong>Time:</strong> ${response.start_time} - ${response.end_time}</p>
                                </div>
                            `);

                            // Show qrScannedDetailsModal correctly
                            showQRDetailsModal();
                        }
                    } else {
                        // alert('Error: ' + response.message);
                        showNotRecogModal();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + error);
                }
            });

            // Clear scanned data and hide QR modal
            scannedData = '';
            modal.modal('hide');
            document.removeEventListener('keydown', handleScan);
        } else {
            if (event.key !== 'Shift' && !event.ctrlKey && !event.altKey && !event.metaKey) {
                scannedData += event.key;
            }
        }
    }

});

function statusUpdate() {
    // Retrieve the appointment_id and client_name from the data attributes
    var appointmentId = $('#qrScannedBody').data('appointmentId');
    var clientName = $('#qrScannedBody').data('clientName');
    var clientDate = $('#qrScannedBody').data('clientDate');
    var startTime = $('#qrScannedBody').data('startTime');
    var endTime = $('#qrScannedBody').data('endTime');

    if (appointmentId && clientName && clientDate && startTime && endTime) {
        // Send an AJAX request to update the status
        $.ajax({
            url: 'update_status.php', // Replace with the actual path to your PHP script
            type: 'POST',
            dataType: 'json', // Expect JSON response from PHP
            data: { appointment_id: appointmentId },
            success: function(response) {
                if (response.success) {
                    console.log('Appointment status updated successfully.');
                    // Reload the page with the client's name as a query parameter
                    window.location.href = 'clients.php?search=' + encodeURIComponent(clientName) + ' ' + encodeURIComponent(clientDate) + ' ' + encodeURIComponent(startTime) + ' - ' + encodeURIComponent(endTime);
                } else {
                    console.error('Error updating appointment status:', response.message);
                    alert('Error updating appointment status: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log(xhr.responseText);
                alert('AJAX Error: ' + xhr.responseText);
            }
        });
    } else {
        console.warn('No client name and appointment date found.');
        alert('Unable to update appointment status. No client name and appointment date found.');
    }
}

document.getElementById('confirmScanButton').addEventListener('click', statusUpdate);

function onSearchInputChange() {
    var searchValue = $('#searchInput').val();
    gridApi.setQuickFilter(searchValue);
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