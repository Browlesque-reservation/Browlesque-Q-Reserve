<?php
session_start(); // Start session if not already started

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if(isset($_SESSION['admin_email'])) {
        // Include your database connection file
        define('INCLUDED', true);
        require_once('connect.php');

        if (!mysqli_ping($conn)) {
            mysqli_close($conn);
            $conn = mysqli_connect($servername, $username, $password, $database);
        }

        // Escape user inputs for security
        $promo_details = mysqli_real_escape_string($conn, $_POST['promo_details']);

        // Retrieve promo_id from the hidden input field
        $promo_id = $_POST['promo_id'];

        // Check if a file is uploaded
        if(isset($_FILES['promo_image']) && $_FILES['promo_image']['error'] === UPLOAD_ERR_OK) {
            // Get the image data
            $image_data = file_get_contents($_FILES['promo_image']['tmp_name']);

            // Update query with promo image
            $query = "UPDATE promo SET promo_details = ?, promo_image = ? WHERE promo_id = ?";
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'ssi', $promo_details, $image_data, $promo_id);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Close statement
                    mysqli_stmt_close($stmt);
                    // Redirect to display_promos.php after successful update
                    header("Location: display_promos.php");
                    exit;
                } else {
                    // Handle error
                    echo "Error: Unable to execute statement. Error: " . mysqli_error($conn);
                    echo "<script>console.error('Error: Unable to execute statement. Error: " . mysqli_error($conn) . "');</script>";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                // Handle error
                echo "Error: Unable to prepare statement.";
            }
        } else {
            // Update query without promo image
            $query = "UPDATE promo SET promo_details = ? WHERE promo_id = ?";
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'si', $promo_details, $promo_id);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Close statement
                    mysqli_stmt_close($stmt);
                    // Redirect to display_promos.php after successful update
                    header("Location: display_promos.php");
                    exit;
                } else {
                    // Handle error
                    echo "Error: Unable to execute statement. Error: " . mysqli_error($conn);
                    echo "<script>console.error('Error: Unable to execute statement. Error: " . mysqli_error($conn) . "');</script>";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                // Handle error
                echo "Error: Unable to prepare statement.";
            }
        }

        // Close connection
        mysqli_close($conn);
    } else {
        // Redirect to login page if user is not logged in
        header("Location: index.php");
        exit;
    }
} else {
    // If the form is not submitted, redirect to the form page
    header("Location: edit_promos.php");
    exit;
}
?>
