<?php
session_start(); // Start session if not already started

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (isset($_SESSION['admin_email'])) {
        // Include your database connection file
        define('INCLUDED', true);
        require_once('connect.php');

        if (!mysqli_ping($conn)) {
            mysqli_close($conn);
            $conn = mysqli_connect($servername, $username, $password, $database);
        }

        // Escape user inputs for security
        $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
        $service_price = mysqli_real_escape_string($conn, $_POST['service_price']);
        $service_description = mysqli_real_escape_string($conn, $_POST['service_description']);

        // Retrieve admin_id from the hidden input field
        $admin_id = $_POST['admin_id'];

        // Check if a file is uploaded
        if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
            // Get the image data
            $image = $_FILES['service_image'];
            $imageType = mime_content_type($image['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png'];

            if (in_array($imageType, $allowedTypes)) {
                $imagePath = $image['tmp_name'];
                $webpPath = 'uploads/' . uniqid() . '.webp';

                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                if ($imageType == 'image/jpeg') {
                    $imageResource = imagecreatefromjpeg($imagePath);
                } elseif ($imageType == 'image/png') {
                    $imageResource = imagecreatefrompng($imagePath);
                }

                if ($imageResource && imagewebp($imageResource, $webpPath)) {
                    $service_path = $webpPath;
                    $service_type = 'image/webp';

                    // Insert query with image path and type
                    $query = "INSERT INTO services (admin_id, service_name, service_price, service_description, service_path, service_type) VALUES (?, ?, ?, ?, ?, ?)";

                    // Prepare statement
                    $stmt = mysqli_prepare($conn, $query);
                    if ($stmt) {
                        // Bind parameters
                        mysqli_stmt_bind_param($stmt, 'isisss', $admin_id, $service_name, $service_price, $service_description, $service_path, $service_type);

                        // Execute the statement
                        if (mysqli_stmt_execute($stmt)) {
                            // Close statement
                            mysqli_stmt_close($stmt);
                            // // Show success modal using JavaScript
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

                    imagedestroy($imageResource);
                } else {
                    echo "Failed to convert image to WebP.";
                }
            } else {
                echo "Invalid file type. Please upload a JPEG or PNG image.";
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
    header("Location: services.php");
    exit;
}
?>
