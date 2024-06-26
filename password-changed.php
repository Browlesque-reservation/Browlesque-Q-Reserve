<?php
define('INCLUDED', true); 
require_once "controllerUserData.php";
$admin_email = $_SESSION['admin_email'];
if($admin_email == false){
  header('Location: index.php');
}

// Assuming your password change logic sets $_SESSION['info'] upon successful password change
if(isset($_SESSION['info'])) {
    $success_message = $_SESSION['info'];
    // Clear the session info after displaying it
    unset($_SESSION['info']);
} else {
    $success_message = "Password changed successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container-fluid container-fluid-black">
    <div class="mt-4">
        <img src="./assets/images/icon/Browlesque-1.svg" class="logo-browlesque-client" alt="Browlesque Logo">
    </div>
        <div class="size-form s-size">
                    <div class="alert alert-success text-center custom-alert add-margin">
                        <?php echo $success_message; ?>
                    </div> 
                <form action="logout.php" method="POST">
                    <div class="form-group-f">
                        <input class="btn btn-primary btn-primary-custom text-size add-margin" type="submit" name="login-now" value="Login Now">
                    </div>
                </form>
        </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>