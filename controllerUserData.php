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

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
        $check_email = "SELECT * FROM admin_login WHERE admin_email='$admin_email'";
        $run_sql = mysqli_query($conn, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE admin_login SET code = $code WHERE admin_email = '$admin_email'";
            $run_query =  mysqli_query($conn, $insert_code);
            if($run_query){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
    
                // Create a new PHPMailer instance
                $mail = new PHPMailer(true);
    
                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'genricredondo01@gmail.com'; // Your Gmail address
                    $mail->Password = 'ecnf mdvf plks zeyl'; // Your Gmail password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
    
                    // Sender and recipient
                    $mail->setFrom('genricredondo01@gmail.com', 'Genric Redondo');
                    $mail->addAddress($admin_email);
    
                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $message;
    
                    // Send email
                    $mail->send();
    
                    $info = "We've sent a password reset otp to your email - $admin_email";
                    $_SESSION['info'] = $info;
                    $_SESSION['admin_email'] = $admin_email;
                    header('location: reset-code.php');
                    exit();
                } catch (Exception $e) {
                    $errors['otp-error'] = "Failed while sending code! Error: {$mail->ErrorInfo}";
                }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
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
            $info = "Please create a new password that has at least 6 characters and contain at least one letter and one number.";
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
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: dashboard.php');
    }
?>