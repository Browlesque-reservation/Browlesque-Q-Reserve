document.addEventListener("DOMContentLoaded", function () {
  var gridOptions = {
    columnDefs: [
      { field: 'client_name', headerName: 'Customer Name', headerClass: 'custom-header' },
      { field: 'client_contactno', headerName: 'Contact Number', headerClass: 'custom-header' },
      { field: 'services', headerName: 'Services', headerClass: 'custom-header', cellRenderer: 'multilineCellRenderer', autoHeight: 'true' },
      { field: 'promos', headerName: 'Promos', headerClass: 'custom-header', cellRenderer: 'multilineCellRenderer', autoHeight: 'true' },
      { field: 'client_date', headerName: 'Date of Appointment', headerClass: 'custom-header' },
      { field: 'client_time', headerName: 'Time', headerClass: 'custom-header' },
      { field: 'no_of_companions', headerName: 'No. of Companions', headerClass: 'custom-header' },
      { field: 'client_notes', headerName: 'Notes', headerClass: 'custom-header' },
      { field: 'status', headerName: 'Status', editable: true, cellEditor: 'agSelectCellEditor', cellEditorParams: {
          values: ['Pending', 'Confirmed', 'Complete', 'Cancelled']
        }, headerClass: 'custom-header' },
        { field: 'archive', headerName: 'Archive', checkboxSelection: true, headerCheckboxSelection: true, headerCheckboxSelectionFilteredOnly: true, headerClass: 'custom-header'  },
    ],
    rowSelection: 'multiple',
    quickFilterText: '', // Set quickFilterText to an empty string initially
    singleClickEdit: true, // Allow single-click editing
    components: {
      // Define a multiline cell renderer
      multilineCellRenderer: function(params) {
          if (params.value) {
              // Create a div element to contain the multiline text
              var cellElement = document.createElement("div");
              // Add the multiline text to the div element
              cellElement.innerText = params.value;
              // Add CSS styles to prevent wrapping
              cellElement.style.whiteSpace = "pre-wrap";
              cellElement.style.overflow = "auto";
              return cellElement;
          }
      }
  },
    onCellValueChanged: function(params) {
      // When a cell value changes, send the updated data to the server
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

  var gridDiv = document.querySelector("#myGrid1");
  var gridApi = agGrid.createGrid(gridDiv, gridOptions);

  var searchInput = document.querySelector("#searchInput");

  searchInput.addEventListener("input", function () {
    var searchText = searchInput.value.toLowerCase();
    gridApi.setQuickFilter(searchText);
  });

  $.ajax({
    url: "fetch_data_clients.php",
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

                console.log("Services Map:", servicesMap); // Log services map

                $.ajax({
                    url: "fetch_promos.php", // Endpoint to fetch promos dynamically
                    method: "GET",
                    dataType: "json",
                    success: function (promos) {
                        promos.forEach(function (promo) {
                            promosMap[promo.promo_id] = promo.promo_details;
                        });

                        console.log("Promos Map:", promosMap); // Log promos map

                        // Modify the data before setting it to the grid
                        var modifiedData = transformData(data, servicesMap, promosMap);
                        gridApi.setGridOption('rowData', modifiedData); // Updated here
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
    },
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
    // Debug: Log the service IDs from the current row
    console.log("Service IDs for current row:", row.service_id);

    // Convert start_time and end_time to non-military time with AM/PM
    var startTimeAMPM = convertToAMPM(row.start_time);
    var endTimeAMPM = convertToAMPM(row.end_time);
    var clientTime = startTimeAMPM + ' - ' + endTimeAMPM;

    // Fetch services and promos for the current appointment
    var serviceIDs = Array.isArray(row.service_id) ? row.service_id : parseIds(row.service_id);
    var promoIDs = Array.isArray(row.promo_id) ? row.promo_id : parseIds(row.promo_id);
    // Debug: Log the parsed service IDs
    console.log("Parsed Service IDs:", serviceIDs);

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
