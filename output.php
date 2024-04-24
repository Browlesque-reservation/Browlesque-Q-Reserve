<div class="d-flex">
    <?php include "sidebar.php";?>
    <!-- Content container -->
    <div class="content-container">
        <h1 class="page-header">Services</h1>
        <div class="container-fluid container-md-custom-s">
            <?php
            // Assuming you have already connected to your database

            // Query to fetch services from the database
            $query = "SELECT service_id, service_name, service_description, service_image FROM services";
            $result = mysqli_query($conn, $query);

            // Check if there are any services
            if (mysqli_num_rows($result) > 0) {
                // Loop through each service
                while ($row = mysqli_fetch_assoc($result)) {
                    $service_id = $row['service_id'];
                    $service_name = $row['service_name'];
                    $service_description = $row['service_description'];
                    $service_image = $row['service_image'];

                    // Display service details
                    echo "<div>";
                    echo "<h2>$service_name</h2>";
                    echo "<p>$service_description</p>";
                    echo "<img src='image.php?service_id=$service_id' alt='Service Image'>";
                    echo "</div>";
                }
            } else {
                echo "No services found.";
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>
    </div>
</div>
