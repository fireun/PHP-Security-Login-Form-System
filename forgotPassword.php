<?php

include("database.php");
session_start();

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// echo "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF'])."/forgetPassword.php?code=111111111";
if(isset($_POST['useremail'])){

    $EmailTo = $_POST['useremail'];

    $checkEmail = "select * from user where email = '$EmailTo'";
    $resultcheckEmail = mysqli_query($conn , $checkEmail) or die (mysqli_error($conn));
    if (mysqli_num_rows($resultcheckEmail) > 0) {

        $code = uniqid(true);
        $query = mysqli_query($conn,"insert into resetpasswords(code,email) values ('$code','$EmailTo')");
        if(!$query) { //false
            exit("Error");
        }
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings         
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = '';                     // SMTP username
            $mail->Password   = '';                               // SMTP password
            $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients - sender
            $mail->setFrom('company-Mail-test@gmail.com', 'Security Mail Company');
            $mail->addAddress($EmailTo);     // Add a recipient
            $mail->addReplyTo('no-reply@gmail.com', 'No reply');
            
            // Content
            $url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF'])."/resetPassword.php?code=$code";
            $mail->Subject = 'Reset Password';
            $mail->Body    = "<h1>You requested a password reset</h1>
                                click <a href='$url'>this link to modified password</a>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            // echo 'Reset password link has been sent to you email';
            echo "<script>alert('Reset password link has been sent to you email!'); window.location.assign('index.php');</script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        exit();
    }else{
        echo "<script>alert('Unvalid Email, Please Register Account!'); window.location.assign('register.php');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <form action="forgotPassword.php" method="post">

    <div class="login-wrap">
    <div class="login-html">
        <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Forgot Password</label>
        <input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
        <div class="login-form">
            <div class="sign-in-htm">
                <div class="group">
                    <label for="user" class="label">Email</label>
                    <input id="user"  name="useremail" type="email" class="input" placeholder="example@gmail.com" autocomplete="off">
                </div>
                <!-- <div class="group">
                    <label for="pass" class="label">Password</label>
                    <input id="pass" type="password" class="input" data-type="password">
                </div> -->
                <div class="group">
                    <input type="submit"  name="submit" class="button" value="Submit">
                </div>
                <!-- <div class="hr"></div> -->
            </div>

        </div>
    </div>
    </div>
    </form>
</body>
</html>