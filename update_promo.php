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

        $promo_details = mysqli_real_escape_string($conn, $_POST['promo_details']);
        $promo_id = $_POST['promo_id'];

        // Check if a file is uploaded
        if(isset($_FILES['promo_image']) && $_FILES['promo_image']['error'] === UPLOAD_ERR_OK) {
            // Define upload directory
            $upload_dir = 'uploads/';

            // Generate unique filename
            $file_name = uniqid() . '_' . basename($_FILES['promo_image']['name']);
            $file_path = $upload_dir . $file_name;

            // Move uploaded file to specified directory
            if(move_uploaded_file($_FILES['promo_image']['tmp_name'], $file_path)) {
                // Convert image to WebP format
                $promo_type = 'image/webp';
                $webp_file_path = $upload_dir . pathinfo($file_name, PATHINFO_FILENAME) . '.webp';
                if (convertToWebP($file_path, $webp_file_path)) {
                    // Update query with service path and type
                    $query = "UPDATE promo SET promo_details = ?, promo_path = ?, promo_type = ? WHERE promo_id = ?";
                    
                    // Prepare statement
                    $stmt = mysqli_prepare($conn, $query);
                    if ($stmt) {
                        // Bind parameters
                        mysqli_stmt_bind_param($stmt, 'sssi', $promo_details, $webp_file_path, $promo_type, $promo_id);

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
                    // Handle WebP conversion error
                    echo "Error converting image to WebP.";
                }
            } else {
                // Handle file upload error
                echo "Error uploading file.";
            }
        } else {
            // Update query without service image
            $query = "UPDATE promo SET promo_details = ?, promo_path = ?, promo_type = ? WHERE promo_id = ?";
            
            // Prepare statement
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt, 'sssi', $promo_details, $promo_path, $promo_type, $promo_id);

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
