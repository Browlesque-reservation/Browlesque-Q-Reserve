<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    header("Location: dashboard.php");
    die();
}
define('INCLUDED', true);
require_once ('connect.php');

if($conn)
{
    if(isset($_POST['admin_submit']))
    { 
        $admin_email=mysqli_real_escape_string($conn,$_POST['admin_email']);
        $admin_password=mysqli_real_escape_string($conn,$_POST['admin_password']);
        $admin_password=md5($admin_password); 
        $sql="SELECT * FROM admin_login WHERE admin_email='$admin_email' AND admin_password='$admin_password'";
        $result=mysqli_query($conn,$sql);
        
        if ($result) {
            if (mysqli_num_rows($result) >= 1) {
                $user = mysqli_fetch_assoc($result);
                $_SESSION['message'] = "You are now Logged In";
                $_SESSION['admin_email'] = $admin_email;
                $_SESSION['is_admin'] = $user['is_admin'];
                
                header("location: dashboard.php");
            } else {
                $_SESSION['error'] = "Email or Password are incorrect.";
                header("Location: index.php"); // Redirect back to the login page
                exit();
            }
        } else {
            $_SESSION['error'] = "Query error: ".mysqli_error($conn);
            header("Location: index.php"); // Redirect back to the login page
            exit();
        }
    }
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
    <div class="container-md container-md-custom <?php echo isset($_SESSION['error']) ? 'error-displayed' : ''; ?> form-container">
            <h3 class="text-center" id="login-text">Login Account</h3>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="a-center alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); // Clear the error message after displaying it ?>
            <?php endif; ?>
            <form method="post" action="index.php">
                <div class="mb-3">
                    <label for="admin_email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="admin_email" name="admin_email" aria-describedby="emailHelp" placeholder="e.g., browlesque@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label for="admin_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="admin_password" name="admin_password" placeholder="Enter you password" required>
                </div>
                <div class="form-label text-left"><a href="forgot-password.php">Forgot password?</a></div>
                <button type="submit" name="admin_submit" class="btn btn-primary btn-primary-custom text-size">Login</button>
            </form>
        </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>