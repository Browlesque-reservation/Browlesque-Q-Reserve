document.addEventListener("DOMContentLoaded", function () {
  var gridOptions = {
    columnDefs: [
        { field: 'appointment_id', headerName: 'Appointment ID' },
        { field: 'service_id', headerName: 'Service ID' },
        { field: 'promo_id', headerName: 'Promo ID' },
        { field: 'client_date', headerName: 'Date' },
        { field: 'client_time', headerName: 'Time' },
        { field: 'client_name', headerName: 'Name' },
        { field: 'client_contactno', headerName: 'Contact Number' },
        { field: 'no_of_companions', headerName: 'Number of Companions' },
        { field: 'client_notes', headerName: 'Notes' },
        { field: 'terms_conditions', headerName: 'Terms and Conditions' },
        { field: 'status', headerName: 'Status' },
    ],
    quickFilterText: '', // Set quickFilterText to an empty string initially
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
      gridApi.setGridOption('rowData', data); // Update to setGridOption
    },
    error: function (error) {
      console.error("Error fetching data:", error);
    },
  });
});
