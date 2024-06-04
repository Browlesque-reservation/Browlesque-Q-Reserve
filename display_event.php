<?php
define('INCLUDED', true);
require_once('connect.php');

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // Fetch appointments for the selected date range
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    $query = "SELECT 
                ca.appointment_id, 
                ca.client_date, 
                ca.start_time, 
                ca.end_time, 
                cd.client_name 
              FROM 
                client_appointment ca
              JOIN 
                client_details cd 
              ON 
                ca.appointment_id = cd.appointment_id 
              WHERE 
                ca.client_date BETWEEN '$start_date' AND '$end_date'";

    $result = mysqli_query($conn, $query);   
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $appointments = array();
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {    
        $appointment = array(
            'id' => $row['appointment_id'],
            'title' => $row['client_name'],
            'date' => $row['client_date'],
            'start_time' => date("h:i A", strtotime($row['start_time'])),
            'end_time' => date("h:i A", strtotime($row['end_time']))
        );
        $appointments[] = $appointment;
    }

    $response = array(
        'status' => true,
        'data' => $appointments
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    // Fetch events for the calendar
    $display_query = "SELECT 
                        ca.appointment_id, 
                        ca.client_date, 
                        ca.start_time, 
                        ca.end_time, 
                        cd.client_name 
                      FROM 
                        client_appointment ca
                      JOIN 
                        client_details cd 
                      ON 
                        ca.appointment_id = cd.appointment_id";

    $results = mysqli_query($conn, $display_query);   
    if (!$results) {
        die("Query failed: " . mysqli_error($conn));
    }

    $count = mysqli_num_rows($results);  

    if($count > 0) {
        $data_arr = array();
        while($data_row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {    
            $event = array();
            $event['id'] = $data_row['appointment_id'];
            $event['title'] = "•" . date("h:i", strtotime($data_row['start_time'])) . " - " . date("h:i", strtotime($data_row['end_time'])) . " " . $data_row['client_name'];
            $event['start'] = $data_row['client_date'] . 'T' . date("H:i:s", strtotime($data_row['start_time']));
            $event['end'] = $data_row['client_date'] . 'T' . date("H:i:s", strtotime($data_row['end_time']));
            $event['color'] = '#' . substr(uniqid(), -6); 

            $data_arr[] = $event;
        }
        
        $data = array(
            'status' => true,
            'msg' => 'successfully!',
            'data' => $data_arr
        );
    } else {
        $data = array(
            'status' => false,
            'msg' => 'No appointments found!'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
