document.addEventListener("DOMContentLoaded", function () {
  var gridOptions = {
    columnDefs: [
      { field: 'client_name', headerName: 'Customer Name' },
      { field: 'client_contactno', headerName: 'Contact Number' },
      { field: 'service_id', headerName: 'Services' },
      { field: 'promo_id', headerName: 'Promos' },
      { field: 'client_date', headerName: 'Date of Appointment' },
      { field: 'client_time', headerName: 'Time' },
      { field: 'no_of_companions', headerName: 'No. of Companions' },
      { field: 'client_notes', headerName: 'Notes' },
      { field: 'status', headerName: 'Status', editable: true, cellEditor: 'agSelectCellEditor', cellEditorParams: {
          values: ['Pending', 'Confirmed', 'Cancelled']
        }
      },
    ],
    quickFilterText: '', // Set quickFilterText to an empty string initially
    singleClickEdit: true, // Allow single-click editing
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
      // Modify the data before setting it to the grid
      var modifiedData = transformData(data);
      gridApi.setRowData(modifiedData);
    },
    error: function (error) {
      console.error("Error fetching data:", error);
    },
  });

  function transformData(data) {
    return data.map(function(row) {
        // Concatenate start_time and end_time to get the client_time
        var clientTime = row.start_time + " - " + row.end_time;

        // Add the concatenated client_time to the row object
        row.client_time = clientTime;

        return row;
    });
  }
});
