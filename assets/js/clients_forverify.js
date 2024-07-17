var gridApi3;

function getPageSize() {
    if (window.innerWidth < 600) {
        return 5;
    } else if (window.innerWidth < 1024) {
        return 7; 
    } else {
        return 10;
    }
}

function toggleEmptyState(showEmptyState) {
    var emptyStateElement = document.getElementById('emptyState4');
    if (showEmptyState) {
        emptyStateElement.style.display = 'flex';
    } else {
        emptyStateElement.style.display = 'none';
    }
}

document.addEventListener("DOMContentLoaded", function () {
    var gridOptions = {
        columnDefs: [
            { field: 'appointment_id', hide: true }, // Hidden column for appointment_id
            { field: 'client_name', headerName: 'Customer Name', headerClass: 'custom-header', cellRenderer: 'clientNameRenderer' },
            { field: 'client_contactno', headerName: 'Contact Number', headerClass: 'custom-header' },
            { field: 'client_date', headerName: 'Date of Appointment', headerClass: 'custom-header', sort: 'desc' },
            { field: 'client_time', headerName: 'Time', headerClass: 'custom-header' },
            { field: 'status', headerName: 'Status', headerClass: 'custom-header', cellRenderer: 'statusCellRenderer', sortable: false }
        ],
        rowSelection: 'multiple',
        quickFilterText: '',
        singleClickEdit: true,
        pagination: true,
        paginationPageSize: 10,
        suppressMovableColumns: true,
        suppressRowClickSelection: true,
        components: {
            clientNameRenderer: function(params) {
                var link = document.createElement('a');
                link.href = '#';
                link.innerText = params.value;
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    showClientDetailsModal(params.data);
                });
                return link;
            },
            multilineCellRenderer: function(params) {
                if (params.value) {
                    var cellElement = document.createElement("div");
                    cellElement.innerText = params.value;
                    cellElement.style.whiteSpace = "pre-wrap";
                    cellElement.style.overflow = "auto";
                    cellElement.style.paddingTop = "0"; 
                    cellElement.style.paddingBottom = "0";
                    cellElement.style.marginTop = "0";
                    cellElement.style.marginBottom = "0"; 
                    return cellElement;
                }
            },
            statusCellRenderer: function(params) {
                var span = document.createElement('span');
                span.innerText = params.value;
                span.style.position = 'relative';
                span.style.padding = '5px 10px';
                span.style.borderRadius = '5px';
                span.style.color = 'white';
        
                // Apply different styles for 'Confirmed' status
                if (params.value === 'For Verification') {
                    span.style.backgroundColor = '#706E71'; // Green background for Confirmed
                } else {
                    // Default styles for other statuses
                    span.style.backgroundColor = statusColors[params.value] || 'transparent';
                }
        
                return span;
            }
        }
    };
    
    window.addEventListener('resize', function() {
        gridOptions.gridApi3.paginationSetPageSize(getPageSize());
    });

    var gridDiv = document.querySelector("#myGrid3");
    // Initialize the grid and assign the API to the global variable
    gridApi3 = agGrid.createGrid(gridDiv, gridOptions);

    var searchInput = document.querySelector("#searchInput3");

    searchInput.addEventListener("input", function () {
        var searchText = searchInput.value.toLowerCase();
        gridApi3.setQuickFilter(searchText);
    });

    // Check for search query parameter in the URL
    var urlParams = new URLSearchParams(window.location.search);
    var searchValue = urlParams.get('search');

    if (searchValue) {
        $('#searchInput3').val(searchValue);
        performSearch(searchValue); // Call the search function with the searchValue
    }

    // Define the performSearch function
    function performSearch(searchValue) {
        // Trigger the search functionality on the ag-Grid
        gridApi3.setQuickFilter(searchValue);
    }

    // Event listener for search input changes
    searchInput.addEventListener("input", function () {
        var searchText = searchInput.value.toLowerCase();
        gridApi3.setQuickFilter(searchText);
        var noResults = gridApi3.getDisplayedRowCount() === 0;
        toggleEmptyState(noResults);
    });

    // Initial check for search parameter in URL
    var urlParams = new URLSearchParams(window.location.search);
    var searchValue = urlParams.get('search');
    if (searchValue) {
        $('#searchInput3').val(searchValue);
        performSearch(searchValue); // Call the search function with the searchValue
    }

    $.ajax({
        url: "fetch_data_clients_forverify.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            // Fetch services and promos
            var servicesMap = {};
            var promosMap = {};
            $.ajax({
                url: "fetch_services.php", // Endpoint to fetch services dynamically
                method: "GET",
                dataType: "json",
                success: function (services) {
                    services.forEach(function (service) {
                        servicesMap[service.service_id] = service.service_name;
                    });

                    $.ajax({
                        url: "fetch_promos.php", // Endpoint to fetch promos dynamically
                        method: "GET",
                        dataType: "json",
                        success: function (promos) {
                            promos.forEach(function (promo) {
                                promosMap[promo.promo_id] = promo.promo_details;
                            });

                            // Modify the data before setting it to the grid
                            var modifiedData = transformData(data, servicesMap, promosMap);
                            gridApi3.setGridOption('rowData', modifiedData); // Set row data
                        },
                        error: function (error) {
                            console.error("Error fetching promos:", error);
                        }
                    });
                },
                error: function (error) {
                    console.error("Error fetching services:", error);
                }
            });
        },
        error: function (error) {
            console.error("Error fetching data:", error);
        }
    });

    function transformData(data, servicesMap, promosMap) {
        function parseIds(idsString) {
            if (!idsString) return [];
            try {
                return JSON.parse(idsString);
            } catch (error) {
                // Handle non-JSON formatted string (e.g., "1,2,3")
                return idsString.split(',').map(id => id.trim());
            }
        }

        // Function to convert military time to non-military time with AM/PM
        function convertToAMPM(timeString) {
            var timeParts = timeString.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = parseInt(timeParts[1]);
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)
            minutes = minutes < 10 ? '0' + minutes : minutes; // Add leading zero for single digit minutes
            return hours + ':' + minutes + ' ' + ampm;
        }

        return data.map(function(row) {
            // Convert start_time and end_time to non-military time with AM/PM
            var startTimeAMPM = convertToAMPM(row.start_time);
            var endTimeAMPM = convertToAMPM(row.end_time);
            var clientTime = startTimeAMPM + ' - ' + endTimeAMPM;

            // Fetch services and promos for the current appointment
            var serviceIDs = Array.isArray(row.service_id) ? row.service_id : parseIds(row.service_id);
            var promoIDs = Array.isArray(row.promo_id) ? row.promo_id : parseIds(row.promo_id);

            // Map service and promo IDs to their corresponding names
            var services = serviceIDs.map(function(id) {
                var parsedID = parseInt(id, 10); // Parse each ID as an integer
                return servicesMap[parsedID] || 'Service not found'; // Check if service ID exists in the map
            });
            var promos = promoIDs.map(function(id) {
                var parsedID = parseInt(id, 10); // Parse each ID as an integer
                return promosMap[parsedID] || 'Promo not found'; // Check if promo ID exists in the map
            });

            // Add services and promos to the row object
            row.services = services.join(', '); // Convert array to comma-separated string
            row.promos = promos.join(', '); // Convert array to comma-separated string

            // Add the concatenated client_time to the row object
            row.client_time = clientTime;

            return row;
        });
    }
});

var currentClientData = null;

function showClientDetailsModal(clientData) {
    currentClientData = clientData; // Store client data globally
    var modal = document.getElementById('clientDetailsModal');
    var modalBody = modal.querySelector('.view-modal-body');

    var fullImagePath = `../Browlesque-Q-Reserve-Client-1/${clientData.image_path}`;
    
    // Check if clientData.image_path exists and is not empty
    if (!clientData.image_path) {
        fullImagePath = './assets/images/pictures/gcashplaceholder.svg'; // Replace with your default image path
    }

    // Create the image HTML element
    var imageHtml = `<img src="${fullImagePath}" alt="Client Image" class="client-image"/>`;

    // Conditionally include services and promos only if they have values
    var servicesHtml = clientData.services ? `<p><strong>Services:</strong> ${clientData.services}</p>` : '';
    var promosHtml = clientData.promos ? `<p><strong>Promos:</strong> ${clientData.promos}</p>` : '';
    var notesHtml = clientData.client_notes ? `<p><strong>Notes:</strong> ${clientData.client_notes}</p>` : '';

    // Populate modal with client data
    modalBody.innerHTML = `
        <div class="modal-content-wrapper">
            <div class="client-details">
                <p class="mt-4"><strong>Name:</strong> ${clientData.client_name}</p>
                <p><strong>Email:</strong> ${clientData.client_email}</p>
                <p><strong>Contact Number:</strong> ${clientData.client_contactno}</p>
                ${servicesHtml}
                ${promosHtml}
                <p><strong>Date of Appointment:</strong> ${clientData.client_date}</p>
                <p><strong>Time:</strong> ${clientData.client_time}</p>
                ${notesHtml}
                <p><strong>Status:</strong> ${clientData.status}</p>
            </div>
            <div class="client-image-wrapper">
                <p class="mt-4"><strong>GCASH payment proof:</strong></p>
                ${imageHtml}
            </div>
        </div>
    `;

    modal.style.display = 'block';
}

function acceptAppointment() {
    if (!currentClientData) {
        alert('Client data is not available.');
        return;
    }
    
    // Generate QR code and send email
    $.ajax({
        url: 'accept_appointment.php',
        type: 'POST',
        data: { 
            client_name: currentClientData.client_name,
            client_email: currentClientData.client_email,
            appointment_id: currentClientData.appointment_id
        },
        dataType: 'json', // Ensure JSON response parsing
        success: function(response) {
            console.log('Response:', response);
            if(response.success) {
                // Show success modal
                $('#clientDetailsModal').hide();
                showAcceptModal();
            } else {
                alert('Failed to send email: ' + response.message); // Show specific error message
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            console.log('XHR:', xhr);
        }
    });
}

function showAcceptModal() {
    // Show the modal
    var SuccessModal = document.getElementById('acceptSuccessModal');
    SuccessModal.style.display = 'block';
}

// Function to hide the confirmation modal
function hideAcceptModal() {
    // Hide the modal
    var SuccessModal = document.getElementById('acceptSuccessModal');
    SuccessModal.style.display = 'none';
}

function showRejectionModal() {
    if (!currentClientData) {
        alert('Client data is not available.');
        return;
    }

    var appointmentId = currentClientData.appointment_id;
    var clientEmail = currentClientData.client_email;
    var clientName = currentClientData.client_name;

    $('#appointmentId').val(appointmentId);
    $('#clientEmail').val(clientEmail);
    $('#clientName').val(clientName);
    $('#clientDetailsModal').hide();
    $('#rejectionModal').show();
}

function confirmRejection() {
    var rejectionDetails = document.getElementById('rejectionDetails').value;
    var appointmentId = $('#appointmentId').val();
    var clientEmail = $('#clientEmail').val();
    var clientName = $('#clientName').val();

    console.log('Data being sent to send_rejection_email.php:');
    console.log('Appointment ID:', appointmentId);
    console.log('Rejection Details:', rejectionDetails);
    console.log('Client Email:', clientEmail);
    console.log('Client Name:', clientName);

    $.ajax({
        url: 'send_rejection_email.php',
        type: 'POST',
        data: {
            appointmentId: appointmentId,
            rejectionDetails: rejectionDetails,
            clientEmail: clientEmail,
            clientName: clientName
        },
        dataType: 'json', // Ensure expecting JSON response
        success: function(response) {
            console.log('AJAX request successful:', response);
            // Handle success response
            if (response.success) {
                // Hide rejection modal
                hideRejectModal();
                // Show success modal
                showRejectionSuccessModal();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX request error:', status, error);
            // Handle error response
            alert('Error sending rejection email and updating the database. Please try again.');
        }
    });
}


function hideRejectModal() {
    // Hide the modal
    var RejectModal = document.getElementById('rejectionModal');
    RejectModal.style.display = 'none';
}

function showRejectionSuccessModal() {
    var RejectModal = document.getElementById('rejectSuccessModal');
    RejectModal.style.display = 'block';
}

function hideRejectSuccessModal() {
    // Hide the modal
    var RejectModal = document.getElementById('rejectSuccessModal');
    RejectModal.style.display = 'none';
}