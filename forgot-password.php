

<?php
if (isset($_SESSION['admin_email'])) {
        header("Location: dashboard.php");
        die();
}
define('INCLUDED', true);
require_once "controllerUserData.php"

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
        <div class="size-form">
            <form action="forgot-password.php" method="POST" autocomplete="">
                    <h1 class="text-center mb-0 mt-3" id="login-text">Forgot Password</h1>
                    <div class="sub-text text-center">Enter your email address</div>
                    <?php
                        if(count($errors) > 0){
                            ?>
                            <div class="alert alert-danger text-center">
                                <?php 
                                    foreach($errors as $error){
                                        echo $error;
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="form-group-f">
                        <input class="form-control" type="email" name="admin_email" required value="<?php echo $admin_email ?>">
                    </div>
                    <div class="form-group-f">
                        <input class="btn btn-primary btn-primary-custom text-size" type="submit" name="check-email" value="Continue">
                    </div>
                </form>
        </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>