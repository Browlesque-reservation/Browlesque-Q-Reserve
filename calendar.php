<?php
define('INCLUDED', true);
define('APP_LOADED', true);
require_once('connect.php');
require_once('stopback.php');

if(isset($_SESSION['admin_email'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS for full calendar -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
    <!-- JS for jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- JS for full calendar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>

</head>
<body>
<div class="d-flex">
    <?php include "sidebar.php";?>
    <!-- Content container -->
    <div class="content-container content">
        <h1 class="page-header">Calendar</h1>
        <div class="container-fluid container-md-custom-s">
            <div id="calendar"></div>        
        </div>
    </div>
</div>

<!-- Start popup dialog box -->
<div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-custom">
            <button type="button" class="close_date" id="close_modal_button" aria-label="Close">&times;</button>
            <h5 class="modal-title" id="modalLabel">Appointments for <span id="selected_date"></span></h5>
            <div class="modal-body">
                <div id="appointments_list"></div>
            </div>
        </div>
    </div>
</div>

<!-- End popup dialog box -->

<script src="./assets/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- <script src="./assets/js/preloader.js"></script> -->
<script>
$(document).ready(function() {
    // Close modal when close button is clicked
    $('#close_modal_button').click(function() {
        $('#event_entry_modal').modal('hide');
    });
    
    $('#calendar').fullCalendar({
        events: {
            url: 'display_event.php',
            type: 'GET',
            success: function(data) {
                if (data.status) {
                    return data.data;
                } else {
                    console.error("Error fetching events:", data.msg);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
                console.error("Status: " + status);
                console.error("Response: " + xhr.responseText);
            }
        },
        displayEventTime: true,
        editable: false,
        eventLimit: true,
        eventRender: function(event, element) {
            element.find('.fc-time').remove();
        },
        buttonText: {
            today: 'Today'
        },
        viewRender: function(view, element) {
            // Disable the next button if the current view is at the end of the current year
            var endOfYear = moment().endOf('year');
            if (view.intervalEnd.isSameOrAfter(endOfYear)) {
                $('.fc-next-button').addClass('fc-state-disabled');
            } else {
                $('.fc-next-button').removeClass('fc-state-disabled');
            }
        },
        selectable: true,
        selectHelper: true,
        dayRender: function (date, cell) {
            if (date.day() === 3) { // 3 corresponds to Wednesday
                cell.addClass('fc-wednesday');
            }
        },
        select: function(start, end) {
            if (start.day() === 3) { // Prevent selection on Wednesdays
                // alert('Selection on Wednesdays is not allowed.');
                $('#calendar').fullCalendar('unselect');
                return;
            }
            var clickedDate = moment(start).format('YYYY-MM-DD');
            fetchAppointments(clickedDate);
        }
    });
});



function fetchAppointments(date) {
    $.ajax({
        url: "display_event.php",
        type: "GET",
        data: { date: date },
        success: function(response) {
            if (response.status == true) {
                var appointments = response.data;
                var appointmentsHtml = '<ul>';
                var selectedDate = moment(date).format('YYYY-MM-DD');
                var hasAppointments = false; // Flag to track if appointments exist for the selected date
                $.each(appointments, function(index, appointment) {
                    var appointmentDate = moment(appointment.start).format('YYYY-MM-DD');
                    if (appointmentDate === selectedDate) {
                        hasAppointments = true; // Set flag to true if appointments exist for the selected date
                        appointmentsHtml += '<li>' + appointment.title + '</li>';
                    }
                });
                appointmentsHtml += '</ul>';
                if (hasAppointments) { // Check if appointments exist before showing the modal
                    $('#selected_date').text(date);
                    $('#appointments_list').html(appointmentsHtml);
                    $('#event_entry_modal').modal('show');
                } else {
                    alert('No appointments for selected date.');
                }
            } else {
                alert(response.msg);
            }
        },
        error: function(xhr, status) {
            console.error('AJAX error = ' + xhr.statusText);
            alert('Failed to fetch appointments');
        }
    });
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
