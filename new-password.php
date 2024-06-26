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
    <meta name="viewport" content="width=device=width, initial-scale=1.0">
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
                <div class="password-container">
                    <input class="form-control mb-4" type="password" id="admin_password" name="admin_password" placeholder="Create new password" required>
                    <button type="button" class="password-toggle" id="password-toggle-btn" onclick="togglePasswordVisibility()">Show</button>
                </div>
            </div>
            <div class="form-group-f">
                <div class="password-container">
                    <input class="form-control" type="password" id="cpassword" name="cpassword" placeholder="Confirm your password" required>
                    <button type="button" class="password-toggle" id="password-toggle-btn1" onclick="togglePassword2Visibility()">Show</button>
                </div>
            </div>
            <div id="password-match-indicator" class="text-center mt-2"></div>
            <div class="form-group-f">
                <input id="submit-btn" class="btn btn-primary btn-primary-custom text-size mt-4" type="submit" name="change-password" value="Submit" disabled>
            </div>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    var passwordField = document.getElementById("admin_password");
    var passwordToggleBtn = document.getElementById("password-toggle-btn");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordToggleBtn.textContent = "Hide";
    } else {
        passwordField.type = "password";
        passwordToggleBtn.textContent = "Show";
    }
}

function togglePassword2Visibility() {
    var passwordField = document.getElementById("cpassword");
    var passwordToggleBtn = document.getElementById("password-toggle-btn1");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordToggleBtn.textContent = "Hide";
    } else {
        passwordField.type = "password";
        passwordToggleBtn.textContent = "Show";
    }
}

document.getElementById('admin_password').addEventListener('input', checkPasswords);
document.getElementById('cpassword').addEventListener('input', checkPasswords);

function checkPasswords() {
    var password = document.getElementById('admin_password').value;
    var confirmPassword = document.getElementById('cpassword').value;
    var indicator = document.getElementById('password-match-indicator');
    var submitBtn = document.getElementById('submit-btn');
    
    var hasLetter = /[a-zA-Z]/.test(password);
    var hasNumber = /\d/.test(password);

    if (password.length >= 6 && hasLetter && hasNumber) {
        if (password === confirmPassword) {
            indicator.textContent = "Passwords match!";
            indicator.style.color = "green";
            submitBtn.disabled = false;
        } else if (confirmPassword.length > 0) {
            indicator.textContent = "Passwords do not match.";
            indicator.style.color = "red";
            submitBtn.disabled = true;
        } else {
            indicator.textContent = "";
            submitBtn.disabled = true;
        }
    } else if (password.length < 6) {
        indicator.textContent = "Password must be at least 6 characters.";
        indicator.style.color = "red";
        submitBtn.disabled = true;
    } else if (!hasLetter || !hasNumber) {
        indicator.textContent = "Password must contain at least one letter and one number.";
        indicator.style.color = "red";
        submitBtn.disabled = true;
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
