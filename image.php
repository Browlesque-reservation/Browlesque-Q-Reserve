<?php
// Include your database connection file
define('INCLUDED', true);
require_once('connect.php');

// Function to fetch and output image based on service_id
function fetchServiceImage($conn, $service_id) {
    // Sanitize the input
    $service_id = mysqli_real_escape_string($conn, $service_id);

    // Query to fetch image data based on service_id
    $query = "SELECT service_path, service_type FROM services WHERE service_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind service_id parameter
    mysqli_stmt_bind_param($stmt, "i", $service_id);

    // Execute query
    mysqli_stmt_execute($stmt);

    // Bind result variables
    mysqli_stmt_bind_result($stmt, $service_image_path, $service_image_type);

    // Fetch and output the image
    if (mysqli_stmt_fetch($stmt)) {
        // Set appropriate headers
        header("Content-Type: $service_image_type");
        
        // Output the image data
        echo $service_image_path;
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Function to fetch and output image based on promo_id
function fetchPromoImage($conn, $promo_id) {
    // Sanitize the input
    $promo_id = mysqli_real_escape_string($conn, $promo_id);

    // Query to fetch image data based on promo_id
    $query = "SELECT promo_path, promo_type FROM promo WHERE promo_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind promo_id parameter
    mysqli_stmt_bind_param($stmt, "i", $promo_id);

    // Execute query
    mysqli_stmt_execute($stmt);

    // Bind result variables
    mysqli_stmt_bind_result($stmt, $promo_image_path, $promo_image_type);

    // Fetch and output the image
    if (mysqli_stmt_fetch($stmt)) {
        // Set appropriate headers
        header("Content-Type: $promo_image_type");
        
        // Output the image data
        echo $service_image_path;
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Check if the service_id is provided in the URL
if(isset($_GET['service_id'])) {
    fetchServiceImage($conn, $_GET['service_id']);
} else if(isset($_GET['promo_id'])) {
    fetchPromoImage($conn, $_GET['promo_id']);
} else {
    // If neither service_id nor promo_id is provided in the URL
    echo "Service ID or Promo ID not provided.";
}

// Close connection
mysqli_close($conn);
?>
