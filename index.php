<?php

include("database.php");

include('config.php');

$login_button = '';
if(isset($_SESSION["profile"])){}
//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"]))
{
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

 //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
 if(!isset($token['error']))
 {
  //Set the access token used for requests
  $google_client->setAccessToken($token['access_token']);

  //Store "access_token" value in $_SESSION variable for future use.
  $_SESSION['access_token'] = $token['access_token'];

  //Create Object of Google Service OAuth 2 class
  $google_service = new Google_Service_Oauth2($google_client);

  //Get user profile data from google
  $data = $google_service->userinfo->get();

  //Below you can find Get profile data and store into $_SESSION variable
  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }
 }
}
//This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
if(!isset($_SESSION['access_token']))
{
 //Create a URL to obtain user authorization
 $login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="images/google.png"    style="width:100%;height:50%;object-fit: contain;border-radius: 20px;"/></a>';
}

$ErrorMessage = "";
    if(isset($_POST['login'])){
        $un = mysqli_real_escape_string($conn,$_POST['email']);
        $usql = "select * from user where email = '$un' ";
         $result= $conn->query($usql) or die($conn->error.__LINE__);//sql
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    
        $count = mysqli_num_rows($result);
        if($count == 1){
            $WrongPass = $row['wrongPass'];
                if($WrongPass < 3 ){
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['image'] = $row['image'];
                    header("location:userImage.php");                
                }else {
                    $ErrorMessage = "Wrong Password are over 3 time, click Forgot password to reset it.";
                }

        }else{
            // echo "<script>alert('Invalid Email !'); window.location.assign('index.php');</script>";
            $ErrorMessage = "unvariable username!";
        }
    }

    if(isset($_POST['logout'])){
        session_destroy();
        header("location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<?php
   if($login_button == '')
   {?>
    <form class="form-inline my-2 my-lg-0" action="index.php" method="post">

<div class="login-wrap" style="min-height: 500px;">
<div class="login-html">
    <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
    <label for="tab-1" class="tab sign-in-login" style="text-align:center;margin-left:10px;margin-bottom:10px;"></label>

    
    <input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
  
    <div class="login-form">

        <div class="sign-in-htm">
            <div class="group" style="text-align:center;">
                <label for="user" class="label">
                <img src="<?php echo $_SESSION['user_image'];?>" style="width:150px; height:150px;object-fit: contain;">
                </label>
                <label for="user" style="margin:10%;font-size:20px">Welcome   <?php echo $_SESSION['user_first_name'];?><?php echo$_SESSION['user_last_name'];?> !</label>
            </div>
        
            <div class="group">
                <input type="submit"  name="logout" class="button" value="Log Out">
            </div>
     
        </div>
       
    </div>
</div>
</div>

</form>
<?php
   }
   else
   {?>
<form action="index.php" method="post">

<div class="login-wrap" style="min-height:570px">
<div class="login-html">

<h2 align="center" ><?php echo $login_button ; ?></h2>
<div align="center" style="font-size: 20px;color:#B0B0B0;">sign in with google</div>
<hr>
    <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab sign-in-login-input">Sign In</label>
    <input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
    <div class="login-form">
        <div class="sign-in-htm">
            <div class="group">
                <label for="user" class="label">Username or Email</label>
                <span id="wrongPlace"><?php echo $ErrorMessage;?></span>
                <input id="user"  name="email" type="text" class="input" required>
            </div>
      
            <div class="group">
                <a href="forgotPassword.php" style="float:left;margin:0% 2%">Forget Password</a>
                <!-- <a href="register.php" style="float:right;margin:0% 2%">Register</a> -->
                <input type="submit"  name="login" class="button" value="Sign In">
            </div>
         
            <div class="group">
                <a href="register.php"><label  class="label">Sign Up</label></a>
            </div>
        </div>

    </div>
</div>
</div>
</form>
    <?php
   }
   ?>
</body>
</html>