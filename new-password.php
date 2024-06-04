<?php
define('INCLUDED', true); 
require_once "controllerUserData.php";
$admin_email = $_SESSION['admin_email'];
if($admin_email == false){
  header('Location: index.php');
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
</head>
<body>
<div class="container-fluid container-fluid-black">
    <div class="mt-4">
        <img src="./assets/images/icon/Browlesque-1.svg" class="logo-browlesque-client" alt="Browlesque Logo">
    </div>
        <div class="size-form">
            <form action="new-password.php" method="POST" autocomplete="">
                    <h1 class="text-center mb-0 mt-3 pt-1" id="login-text">New Password</h1>
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center custom-alert">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center custom-alert">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>


                    <div class="form-group-f">
                        <input class="form-control mb-4" type="password" name="admin_password" placeholder="Create new password" required>
                    </div>
                    <div class="form-group-f">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group-f">
                        <input class="btn btn-primary btn-primary-custom text-size mt-4" type="submit" name="change-password" value="Change">
                    </div>
                </form>
        </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>