var gridApi;

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
    var emptyStateElement = document.getElementById('emptyState3');
    if (showEmptyState) {
        emptyStateElement.style.display = 'flex';
    } else {
        emptyStateElement.style.display = 'none';
    }
}

document.addEventListener("DOMContentLoaded", function () {
    var gridOptions = {
        columnDefs: [
            { field: 'restore', headerName: 'Restore', checkboxSelection: true, headerCheckboxSelection: true, headerCheckboxSelectionFilteredOnly: true, headerClass: 'custom-header', sortable: false, width: 120 },
            { field: 'archive_id', hide: true }, // Hidden column for archive_id
            { field: 'client_name', headerName: 'Customer Name', headerClass: 'custom-header', cellRenderer: 'clientNameRenderer' },
            { field: 'client_contactno', headerName: 'Contact Number', headerClass: 'custom-header' },
            { field: 'client_date', headerName: 'Date of Appointment', headerClass: 'custom-header', sort: 'desc' },
            { field: 'client_time', headerName: 'Time', headerClass: 'custom-header' },
            { field: 'status', headerName: 'Status', cellRenderer: 'statusCellRenderer', headerClass: 'custom-header' }
        ],
        rowSelection: 'multiple',
        quickFilterText: '',
        singleClickEdit: true,
        pagination: true,
        paginationPageSize: 10,
        suppressMovableColumns: true,
        suppressRowClickSelection: true,
        isRowSelectable: function(node) {
            return !(node.data.status === 'Cancelled' || node.data.status === 'Rejected');
        },
        components: {
            clientNameRenderer: function(params) {
                var link = document.createElement('a');
                link.href = '#';
                link.innerText = params.value;
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    showViewClientDetailsModal(params.data);
                });
                return link;
            },
            multilineCellRenderer: function(params) {
                if (params.value) {
                    var cellElement = document.createElement("div");
                    cellElement.innerText = params.value;
                    cellElement.style.whiteSpace = "pre-wrap";
                    cellElement.style.overflow = "auto";
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
        
                // Apply background color based on status
                var statusColors = {
                    'Rejected': '#794f9a',
                    'Confirmed': '#4F9A4F',
                    'Cancelled': '#C55151'
                };
                span.style.backgroundColor = statusColors[params.value] || 'transparent';
        
                return span;
            }
        }
    };

    window.addEventListener('resize', function() {
        gridOptions.gridApi.paginationSetPageSize(getPageSize());
    });

    var gridDiv = document.querySelector("#myGrid2");
    // Initialize the grid and assign the API to the global variable
    gridApi = agGrid.createGrid(gridDiv, gridOptions);

    var searchInput = document.querySelector("#searchInput");

        // Event listener for search input changes
        searchInput.addEventListener("input", function () {
            var searchText = searchInput.value.toLowerCase();
            gridApi.setQuickFilter(searchText);
            var noResults = gridApi.getDisplayedRowCount() === 0;
            toggleEmptyState(noResults);
        });
    
        // Initial check for search parameter in URL
        var urlParams = new URLSearchParams(window.location.search);
        var searchValue = urlParams.get('search');
        if (searchValue) {
            $('#searchInput').val(searchValue);
            performSearch(searchValue); // Call the search function with the searchValue
        }

    $.ajax({
        url: "fetch_data_archive.php",
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
                            gridApi.setGridOption('rowData', modifiedData); // Set row data
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

    // Function to add IDs to existing checkboxes
    function addCheckboxIDs() {
        var checkboxes = document.querySelectorAll('.ag-input-field-input.ag-checkbox-input');
        checkboxes.forEach(function(checkbox, index) {
            var checkboxID = 'archive_id_' + index; // Generate unique ID
            checkbox.setAttribute('id', checkboxID); // Add unique IDs to each checkbox
            // console.log("Checkbox ID:", checkboxID); 
        });
    }

    // Call the function to add IDs to existing checkboxes
    addCheckboxIDs();
});

function showConfirmationModalRestore() {
    // Get the grid's selected rows
    var selectedNodes = gridApi.getSelectedNodes();
    console.log("Number of checkboxes checked:", selectedNodes.length);

    var restoreIds = selectedNodes.map(function(node) {
        return node.data.archive_id;
    });

    if (restoreIds.length === 0) {
        alert("Please select at least one appointment to restore.");
        return;
    }

    // Store the archiveIds in the confirm button's data attribute
    $('#confirmButtonrestore').data('restoreIds', restoreIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}

// Event listener to show the confirmation modal when the confirm button is clicked
document.getElementById('confirmButtonrestore').addEventListener('click', showConfirmationModalRestore);

var currentClientData = null;

function showViewClientDetailsModal(clientData) {
    currentClientData = clientData; // Store client data globally
    var modal = document.getElementById('viewClientDetailsModal');
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
    var rejectionHtml = clientData.rejection_detail ? `<p><strong>Rejection Detail:</strong> ${clientData.rejection_detail}</p>` : '';

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
                ${rejectionHtml}
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