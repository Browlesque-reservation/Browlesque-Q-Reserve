<?php 
if (!defined('INCLUDED')) {
    // If not included, redirect to an error page or any other page you prefer
    header("Location: index.php");
    exit;
}

session_start();
require "connect.php";
$admin_email = "";
$admin_name = "";
$errors = array();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

    //if user click verification code submit button
    if(isset($_POST['check'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
        $check_code = "SELECT * FROM admin_login WHERE code = $otp_code";
        $code_res = mysqli_query($conn, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $admin_email = $fetch_data['admin_email'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE admin_login SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($conn, $update_otp);
            if($update_res){
                $_SESSION['admin_name'] = $admin_name;
                $_SESSION['admin_email'] = $admin_email;
                header('location: dashboard.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

// if user clicks continue button in forgot password form
if (isset($_POST['check-email'])) {
    $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
    $check_email = "SELECT * FROM admin_login WHERE admin_email='$admin_email'";
    $run_sql = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($run_sql) > 0) {
        $row = mysqli_fetch_assoc($run_sql);
        $admin_name = $row['admin_name']; // Adjust this to the actual column name for the first name in your database

        $code = rand(999999, 111111);
        $insert_code = "UPDATE admin_login SET code = $code WHERE admin_email = '$admin_email'";
        $run_query = mysqli_query($conn, $insert_code);

        if ($run_query) {
            $subject = "Password Reset Code";
            $message = "
                Hi $admin_name, <br><br>
                We received a request to reset your Browlesque account password. Your reset password code is <strong>$code</strong>.<br><br>
                Enter this code on our password reset page to set a new password. If you didn't request a reset, please ignore this email.<br><br>
                Best regards,<br>
                The Browlesque Team
            ";

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'browlesquebacoorbranch@gmail.com'; // Your Gmail address
                $mail->Password = 'ohrk idmk sulk wdlq'; // Your Gmail app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Sender and recipient
                $mail->setFrom('browlesquebacoorbranch@gmail.com', 'Browlesque Cavite');
                $mail->addAddress($admin_email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                // Send email
                $mail->send();

                $info = "We've sent a password reset OTP to your email - $admin_email";
                $_SESSION['info'] = $info;
                $_SESSION['admin_email'] = $admin_email;
                header('location: reset-code.php');
                exit();
            } catch (Exception $e) {
                $errors['otp-error'] = "Failed while sending code! Error: {$mail->ErrorInfo}";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['admin_email'] = "This email address does not exist!";
    }
}

    

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
        $check_code = "SELECT * FROM admin_login WHERE code = $otp_code";
        $code_res = mysqli_query($conn, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $admin_email = $fetch_data['admin_email'];
            $_SESSION['email'] = $admin_email;
            $info = "Please create a new password with the following requirements:<ul><li>At least 6 characters</li><li>Contains at least one uppercase letter</li><li>Contains at least one lowercase letter</li><li>Contains at least one number</li><li>Contains at least one special character</li></ul>";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $admin_password = mysqli_real_escape_string($conn, $_POST['admin_password']);
        $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
        if($admin_password !== $cpassword){
            $errors['admin_password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $admin_email = $_SESSION['admin_email']; //getting this email using session
            // Assuming $admin_password contains the plain-text password
            $encpass = md5($admin_password);
            $update_pass = "UPDATE admin_login SET code = $code, admin_password = '$encpass' WHERE admin_email = '$admin_email'";
            $run_query = mysqli_query($conn, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
                exit();
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
?>