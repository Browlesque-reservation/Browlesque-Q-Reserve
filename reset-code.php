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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container-fluid container-fluid-black">
    <div class="mt-4">
        <img src="./assets/images/icon/Browlesque-1.svg" class="logo-browlesque-client" alt="Browlesque Logo">
    </div>
    <div class="size-form">
        <form action="reset-code.php" method="POST" autocomplete="">
            <h1 class="text-center mb-0 mt-3" id="login-text">Code Verification</h1>
            <?php 
            if(isset($_SESSION['info'])){
                ?>
                <div class="alert alert-success text-center custom-alert" style="padding: 0rem 0rem">
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
                <input id="otp" class="form-control" type="text" name="otp" placeholder="Enter code" required inputmode="numeric" pattern="\d*" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
            <div class="form-group-f">
                <input id="submit-btn" class="btn btn-primary btn-primary-custom text-size" type="submit" name="check-reset-otp" value="Submit" disabled>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
document.getElementById('otp').addEventListener('input', function() {
    var otpInput = document.getElementById('otp').value;
    var submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = otpInput.length !== 6;
});

document.querySelector('input[name="otp"]').addEventListener('keydown', function(e) {
    if (e.key === 'e' || e.key === 'E' || e.key === '-' || e.key === '+') {
        e.preventDefault();
    }
});
</script>
</body>
</html>
