var gridApi2;

function getPageSize() {
    if (window.innerWidth < 600) {
        return 5;
    } else if (window.innerWidth < 1024) {
        return 7; 
    } else {
        return 10;
    }
}

function toggleEmptyState2(showEmptyState) {
    var emptyStateElement = document.getElementById('emptyState2');
    if (showEmptyState) {
        emptyStateElement.style.display = 'flex';
    } else {
        emptyStateElement.style.display = 'none';
    }
}

document.addEventListener("DOMContentLoaded", function () {
    var gridOptions = {
        columnDefs: [
            { field: 'archive', headerName: 'Archive', checkboxSelection: true, headerCheckboxSelection: true, headerCheckboxSelectionFilteredOnly: true, headerClass: 'custom-header', sortable: false, width: 120 },
            { field: 'appointment_id', hide: true }, // Hidden column for appointment_id
            { field: 'client_name', headerName: 'Customer Name', headerClass: 'custom-header' },
            { field: 'client_contactno', headerName: 'Contact Number', headerClass: 'custom-header' },
            // { field: 'services', headerName: 'Services', headerClass: 'custom-header', cellRenderer: 'multilineCellRenderer', autoHeight: 'true' },
            // { field: 'promos', headerName: 'Promos', headerClass: 'custom-header', cellRenderer: 'multilineCellRenderer', autoHeight: 'true' },
            { field: 'client_date', headerName: 'Date of Appointment', headerClass: 'custom-header', sort: 'desc' },
            { field: 'client_time', headerName: 'Time', headerClass: 'custom-header' },
            // { field: 'client_notes', headerName: 'Notes', headerClass: 'custom-header' },
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
                if (params.value === 'Confirmed') {
                    span.style.backgroundColor = '#4F9A4F'; // Green background for Confirmed
                } else {
                    // Default styles for other statuses
                    span.style.backgroundColor = statusColors[params.value] || 'transparent';
                }
        
                return span;
            }
        },
        onCellValueChanged: function(params) {
            var updatedData = {
                appointment_id: params.data.appointment_id,
                status: params.data.status
            };
    
            // Send an AJAX request to update the database
            $.ajax({
                url: "update_data_clients.php",
                method: "POST",
                data: updatedData,
                success: function (response) {
                    console.log("Data updated successfully");
                    // If the update was successful, update the data in the grid
                    var rowData = gridOptions.api.getRowNode(params.node.id).data;
                    rowData.status = params.data.status;
                    gridOptions.api.applyTransaction({ update: [rowData] });
                },
                error: function (error) {
                    console.error("Error updating data:", error);
                }
            });
        }
    };
    
    window.addEventListener('resize', function() {
        gridOptions.gridApi2.paginationSetPageSize(getPageSize());
    });

    var gridDiv = document.querySelector("#myGrid2");
    // Initialize the grid and assign the API to the global variable
    gridApi2 = agGrid.createGrid(gridDiv, gridOptions);

    var searchInput = document.querySelector("#searchInput2");

    searchInput.addEventListener("input", function () {
        var searchText = searchInput.value.toLowerCase();
        gridApi2.setQuickFilter(searchText);
    });

    // Check for search query parameter in the URL
    var urlParams = new URLSearchParams(window.location.search);
    var searchValue = urlParams.get('search');

    if (searchValue) {
        $('#searchInput2').val(searchValue);
        performSearch(searchValue); // Call the search function with the searchValue
    }

    // Define the performSearch function
    function performSearch(searchValue) {
        // Trigger the search functionality on the ag-Grid
        gridApi2.setQuickFilter(searchValue);
    }

    // Event listener for search input changes
    searchInput.addEventListener("input", function () {
        var searchText = searchInput.value.toLowerCase();
        gridApi2.setQuickFilter(searchText);
        var noResults = gridApi2.getDisplayedRowCount() === 0;
        toggleEmptyState2(noResults);
    });

    // Initial check for search parameter in URL
    var urlParams = new URLSearchParams(window.location.search);
    var searchValue = urlParams.get('search');
    if (searchValue) {
        $('#searchInput2').val(searchValue);
        performSearch(searchValue); // Call the search function with the searchValue
    }

    $.ajax({
        url: "fetch_data_clients_confirmed.php",
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
                            gridApi2.setGridOption('rowData', modifiedData); // Set row data
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

function showConfirmationModalArchive() {
    // Get the grid's selected rows
    var selectedNodes = gridApi2.getSelectedNodes();
    console.log("Number of checkboxes checked:", selectedNodes.length);

    // Map to extract appointment_id from the selected rows
    var archiveIds = selectedNodes.map(function(node) {
        return node.data.appointment_id; // Extract appointment_id from node data
    });

    if (archiveIds.length === 0) {
        alert("Please select at least one appointment to archive.");
        return;
    }

    // Store the archiveIds in the confirm button's data attribute
    $('#confirmButton').data('archiveIds', archiveIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}

// Event listener to show the confirmation modal when the confirm button is clicked
document.getElementById('confirmButton').addEventListener('click', showConfirmationModalArchive);
