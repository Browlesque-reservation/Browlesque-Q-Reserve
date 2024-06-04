<?php
define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

if (isset($_SESSION['admin_email'])) {
    // Query to fetch services from the database
    $query = "SELECT promo_id, promo_details, promo_image FROM promo";
    $result = mysqli_query($conn, $query);

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promos</title>
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include "sidebar.php"; ?>
    <!-- Content container -->
    <div class="content-container container">
        <div class="header-container">
            <h1 class="page-header">Promos</h1>
            <a href="promos.php" class="btn btn-primary btn-add-service">
                <img src="./assets/images/icon/plus-icon.svg" alt="Add Promo" width="30" height="30">
                Promo
            </a>
        </div>
        <div class="container-fluid container-md-custom-s">
            <div class="service-container">
                <?php
                // Check if there are any services
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each service
                    while ($row = mysqli_fetch_assoc($result)) {
                        $promo_id = $row['promo_id'];
                        $promo_details = $row['promo_details'];
                        $promo_image = $row['promo_image'];
                        ?>
                        <div class="service-card">
                            <a href="edit_promos.php?promo_id=<?php echo $promo_id; ?>">
                                <img src='image.php?promo_id=<?php echo $promo_id; ?>' alt='Service Image'>
                            </a>
                            <p class="mb-4 mt-2"><?php echo $promo_details; ?></p>
                            <label for="delete_checkbox_<?php echo $promo_id; ?>">Delete</label>
                            <input type="checkbox" id="delete_checkbox_<?php echo $promo_id; ?>"
                                   class="delete-checkbox form-check-input">
                        </div>
                    <?php }
                } else {
                    echo "No promos found.";
                }
                ?>
                <button class="text-center delete-btn mb-2 me-3" onclick="showConfirmationModalDeleteP()">Delete</button>
            </div>
        </div>
    </div>
</div>

<div id="confirmationModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <img src="./assets/images/icon/question-icon.svg" class="mt-3" alt="Success Icon" width="70" height="70">
        <h2 class="text-center mt-3 mb-0">Are you sure you want to delete this promo?</h2>
            <div class="d-flex justify-content-end mt-5">
                <button type="button" id="confirmButton" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="deleteChecked()">Confirm</button>
                <button type="button" id="cancelButton" class="btn btn-primary-custom cancel-btn me-2 fs-5 text-center" onclick="hideConfirmationModal()">Cancel</button>
            </div>
    </div>
</div>


<div id="successModal" class="modal">
    <div class="modal-content custom-modal-content d-flex flex-column align-items-center">
        <!-- Replace the inline SVG with an <img> tag referencing your SVG file -->
        <img src="./assets/images/icon/deleted-icon.svg" alt="Success Icon" width="70" height="70">
        <!-- End of replaced SVG -->
        <h2 class="text-center custom-subtitle mt-2 mb-2">The promo has been successfully deleted.</h2>
        <div class="d-flex justify mt-4">
            <button type="button" class="btn btn-primary btn-primary-custom me-2 fs-5 text-center" onclick="hideSuccessModal(); window.location.href = 'display_promos.php';">Back</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="./assets/js/sidebar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        
<script>
function deleteChecked() {
    var checkboxes = document.querySelectorAll('.delete-checkbox:checked');
    var promoIds = Array.from(checkboxes).map(function(checkbox) {
        return checkbox.id.split('_')[2]; // Extract promo ID from checkbox ID
    });
    
    if(promoIds.length === 0) {
        alert("Please select at least one service to delete.");
        return;
    }

    // Store the promo IDs in a data attribute of the confirm button
    $('#confirmButton').data('promoIds', promoIds);
}

// Function to handle deletion confirmation
$('#confirmButton').click(function() {
    var promoIds = $(this).data('promoIds');

    // Send AJAX request to delete_promos.php
    $.ajax({
        type: "POST",
        url: "delete_promos.php",
        data: { promo_ids: promoIds },
        success: function(response) {
            if (response === "success") {
                // Remove deleted promos from the frontend
                promoIds.forEach(function(promoId) {
                    document.getElementById('delete_checkbox_' + promoId).parentElement.remove();
                });
                hideConfirmationModal();
                // Show success modal after deletion
                showSuccessModal();
            } else {
                alert("Error deleting promos.");
            }
        },
        error: function() {
            alert("Error deleting promos. Please try again later.");
        }
    });
});
</script>

</body>
</html>


    <?php
} else {
    header("Location: index.php");
    die();
}
?>
