<?php
include("database.php");

session_start();
if(isset($_POST['logout'])){
    session_destroy();
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>
<body>


    <form class="form-inline my-2 my-lg-0" action="loginsuccess.php" method="post">

<div class="login-wrap" style="min-height: 500px;">
<div class="login-html">
    <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
    <label for="tab-1" class="tab sign-in-login" style="text-align:center;margin-left:10px;margin-bottom:10px;"></label>

    
    <input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
  
    <div class="login-form">

        <div class="sign-in-htm">
            <div class="group">
                <label for="user" class="label">
                <img src="images/<?php if(isset($_SESSION['email'])){echo $_SESSION['image'];} ?>" style="width:150px; height:150px;object-fit: contain;">
                </label>
                <label for="user" style="margin:10%;font-size:20px">Welcome    <?php echo $_SESSION['f_name']; ?><?php echo $_SESSION['l_name']; ?> !</label>
            </div>
        
            <div class="group">
                <input type="submit"  name="logout" class="button" value="Log Out">
            </div>
     
        </div>
       
    </div>
</div>
</div>

</form>

</body>
</html>