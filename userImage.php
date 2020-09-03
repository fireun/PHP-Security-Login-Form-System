<?php
include("database.php");

session_start();

if(isset($_POST['noMyImg'])){
session_destroy();
echo "<script>alert('no my image !'); window.location.assign('index.php');</script>";

}


if(isset($_POST['yesMyImg'])) {

header('location:userPassword.php');
       
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
    <title>userImage</title>
    <link rel="stylesheet" href="login.css">

</head>
<body>

    <form action="userImage.php" method="post">
            
             <div class="login-wrap" style="min-height:570px">
                    <div class="login-html login-image-p">
                        <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Verify User Image</label>
                        <input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
                        <div class="login-form">
                            <div class="sign-in-htm">
                                <div class="group">
                                    <label for="user" class="label" style="margin-top:-40px;text-align:center;"><?php if(isset($_SESSION['email'])){echo $_SESSION['email'];} ?></label>
                                    <!-- <input id="user"  name="username" type="text" class="input" placeholder="username"> -->
                                    <img src="images/<?php if(isset($_SESSION['email'])){echo $_SESSION['image'];} ?>" style="width:300px; height:300px;object-fit: contain;position:relative;left:40px">
                                </div>
                                <div class="group">
                                    <div class="row">
                                        <div class="col-md-6"><input type="submit"  name="noMyImg" class="button" value="No, My Image"></div>
                                        <div class="col-md-6"><input type="submit"  name="yesMyImg" class="button" value="Yes, My Image"></div>
                                    </div>
                                </div>
                                <!-- <div class="hr"></div> -->
                            </div>
                        </div>
                    </div>
                </div>
             </form>
</body>
</html>