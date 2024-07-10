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
        $service_id = $_POST['service_id'];

        // Check if a file is uploaded
        if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
            // Define upload directory
            $upload_dir = 'uploads/';

            // Generate unique filename
            $file_name = uniqid() . '_' . basename($_FILES['service_image']['name']);
            $file_path = $upload_dir . $file_name;

            // Get the current service image path
            $current_image_query = "SELECT service_path FROM services WHERE service_id = ?";
            $stmt = mysqli_prepare($conn, $current_image_query);
            mysqli_stmt_bind_param($stmt, 'i', $service_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $current_image_path);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            // Move uploaded file to specified directory
            if (move_uploaded_file($_FILES['service_image']['tmp_name'], $file_path)) {
                // Convert image to WebP format
                $service_type = 'image/webp';
                $webp_file_path = $upload_dir . pathinfo($file_name, PATHINFO_FILENAME) . '.webp';
                if (convertToWebP($file_path, $webp_file_path)) {
                    // Delete the original uploaded file
                    unlink($file_path);

                    // Delete the old image if it exists
                    if (file_exists($current_image_path)) {
                        unlink($current_image_path);
                    }

                    // Update query with service path and type
                    $query = "UPDATE services SET service_name = ?, service_price = ?, service_description = ?, service_path = ?, service_type = ? WHERE service_id = ?";

                    // Prepare statement
                    $stmt = mysqli_prepare($conn, $query);
                    if ($stmt) {
                        // Bind parameters
                        mysqli_stmt_bind_param($stmt, 'sisssi', $service_name, $service_price, $service_description, $webp_file_path, $service_type, $service_id);

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
                    // Handle WebP conversion error
                    echo "Error converting image to WebP.";
                }
            } else {
                // Handle file upload error
                echo "Error uploading file.";
            }
        } else {
            // Update query without service image
            $query = "UPDATE services SET service_name = ?, service_price = ?, service_description = ? WHERE service_id = ?";

            // Prepare statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'sisi', $service_name, $service_price, $service_description, $service_id);

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

// Function to convert image to WebP format
function convertToWebP($source, $destination) {
    $info = getimagesize($source);
    $mime = $info['mime'];

    if ($mime == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($mime == 'image/png') {
        $image = imagecreatefrompng($source);
    } else {
        return false; // Unsupported file type
    }

    // Save as WebP
    $result = imagewebp($image, $destination, 80);

    // Free up memory
    imagedestroy($image);

    return $result;
}
?>