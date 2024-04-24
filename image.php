<?php
// Include your database connection file
define('INCLUDED', true);
require_once('connect.php');

// Check if the service_id is provided in the URL
if(isset($_GET['service_id'])) {
    // Sanitize the input
    $service_id = mysqli_real_escape_string($conn, $_GET['service_id']);

    // Query to fetch image data based on service_id
    $query = "SELECT service_image FROM services WHERE service_id = $service_id";

    $result = mysqli_query($conn, $query);

    if($result) {
        // Check if any rows are returned
        if(mysqli_num_rows($result) > 0) {
            // Fetch the image data
            $row = mysqli_fetch_assoc($result);
            $image_data = $row['service_image'];

            // Output appropriate MIME type header
            header("Content-type: image/jpeg"); // Change the MIME type according to your image type

            // Output the image data
            echo $image_data;
        } else {
            // Image not found in the database
            echo "Image not found.";
        }
    } else {
        // Query execution error
        echo "Error retrieving image data: " . mysqli_error($conn);
    }

    // Free result set
    mysqli_free_result($result);
} else if(isset($_GET['promo_id'])) {
    // Sanitize the input
    $promo_id = mysqli_real_escape_string($conn, $_GET['promo_id']);

    // Query to fetch image data based on promo_id
    $query = "SELECT promo_image FROM promo WHERE promo_id = $promo_id";

    $result = mysqli_query($conn, $query);

    if($result) {
        // Check if any rows are returned
        if(mysqli_num_rows($result) > 0) {
            // Fetch the image data
            $row = mysqli_fetch_assoc($result);
            $image_data = $row['promo_image'];

            // Output appropriate MIME type header
            header("Content-type: image/jpeg"); // Change the MIME type according to your image type

            // Output the image data
            echo $image_data;
        } else {
            // Image not found in the database
            echo "Image not found.";
        }
    } else {
        // Query execution error
        echo "Error retrieving image data: " . mysqli_error($conn);
    }

    // Free result set
    mysqli_free_result($result);
} else {
    // If neither service_id nor promo_id is provided in the URL
    echo "Service ID or Promo ID not provided.";
}

// Close connection
mysqli_close($conn);
?>
