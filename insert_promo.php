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

        // Retrieve admin_id from the hidden input field
        $admin_id = $_POST['admin_id'];

        // Check if a file is uploaded
        if(isset($_FILES['promo_image']) && $_FILES['promo_image']['error'] === UPLOAD_ERR_OK) {
            // Get the image data
            $image_data = file_get_contents($_FILES['promo_image']['tmp_name']);

            // Insert query with BLOB data
            $query = "INSERT INTO promo (admin_id, promo_details, promo_image) VALUES (?, ?, ?)";
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'iss', $admin_id, $promo_details, $image_data);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Close statement
                    mysqli_stmt_close($stmt);
                    // Show success modal using JavaScript
                    // echo '<script>
                    //         // Show success modal
                    //         showSuccessModal();
                    //       </script>';
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
            // Handle error if no file is uploaded
            echo "Error: Please upload an image.";
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
    header("Location: promos.php");
    exit;
}
?>
