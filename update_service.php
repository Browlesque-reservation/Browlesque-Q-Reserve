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
        $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
        $service_description = mysqli_real_escape_string($conn, $_POST['service_description']);

        // Retrieve service_id from the hidden input field
        $service_id = $_POST['service_id'];

        // Check if a file is uploaded
        if(isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
            // Get the image data
            $image_data = file_get_contents($_FILES['service_image']['tmp_name']);

            // Update query with service image
            $query = "UPDATE services SET service_name = ?, service_description = ?, service_image = ? WHERE service_id = ?";
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'sssi', $service_name, $service_description, $image_data, $service_id);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Close statement
                    mysqli_stmt_close($stmt);
                    // Redirect to display_services.php after successful update
                    header("Location: display_services.php");
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
            // Update query without service image
            $query = "UPDATE services SET service_name = ?, service_description = ? WHERE service_id = ?";
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'ssi', $service_name, $service_description, $service_id);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Close statement
                    mysqli_stmt_close($stmt);
                    // Redirect to display_services.php after successful update
                    header("Location: display_services.php");
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
    header("Location: edit_services.php");
    exit;
}
?>
