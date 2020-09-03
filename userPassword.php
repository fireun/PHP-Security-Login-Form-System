<?php
include("database.php");

session_start();

$ErrorNum = "";
$ErrorMessage = "";
$email = $_SESSION['email'];
$psql = "select * from user where email = '$email'";
$result = mysqli_query($conn , $psql) or die (mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $Email= $row['email'];
        $f_name = $row['f_name'];
        $l_name = $row['l_name'];
        $userImage = $row['image'];
        $Password = $row['pass'];
        $ErrorNum = $row['wrongPass'];

        if($ErrorNum < 3){

            if(isset($_POST['checkPass'])){
                $email = $_SESSION['email'];
                $upass = mysqli_real_escape_string($conn,$_POST['password']);

                if (password_verify($upass, $Password )) {
                    $_SESSION['email'] =$Email;
                    $_SESSION['f_name'] = $f_name;
                    $_SESSION['l_name'] = $l_name;
                    $_SESSION['userimage'] = $userImage;
                    $_SESSION['userpass'] = $Password;
                    header('location: loginsuccess.php');
                }else {
                    $ErrorNum = $ErrorNum + 1;
                    $recordWrongPass = "UPDATE user SET wrongPass='$ErrorNum' WHERE email = '$Email'";
                    $resultRecordWrongPass = mysqli_query($conn , $recordWrongPass) or die (mysqli_error($conn));
                    // echo "<script>alert('invalid username/password !'); window.location.assign('userLogin.php');</script>";
                    $ErrorMessage = "wrong password !";
                    // header('refresh:1;url=userLogin.php');
                }  
            } 

        }else{
            $temporyPassword = uniqid();
    
            $WEmail = $_SESSION['email'];
            $WPassword = password_hash("$temporyPassword" , PASSWORD_DEFAULT);
            $SetPassSql = "UPDATE user SET pass='$WPassword' WHERE email = '$WEmail'";
            $resultSetPassSql = mysqli_query($conn , $SetPassSql) or die(mysqli_error($conn));
    
            $ErrorMessage = "The Password are wrong over 3! ";
            header('refresh:1;url=index.php');
                // echo "<script>alert('no my image !'); window.location.assign('userLogin.php');</script>";
        }
        
    }    
}else{//database 没有这个账户
    echo'<script type=text/javascript>';//add myself
    echo 'window.alert("Login failed")';
    echo '</script>';
    header('refresh:0.5;url=index.php');
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
    <title>userPass</title>
    <link rel="stylesheet" href="login.css">

</head>
<body>
    
    <form action="userPassword.php" method="post">
            <div class="login-wrap">
                <div class="login-html">
                    <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab sign-in-pass">Password</label>
                    <input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
                    <div class="login-form">
                        <div class="sign-in-htm">
                            <div class="group" style="text-align:center">
                                <label for="user" class="label"><?php echo $_SESSION['email'];?></label>
                                <span id="wrongPlace"><?php echo $ErrorMessage;?></span>
                                <input id="user"  name="password" type="password" class="input">
                            </div>
                            <div class="group">
                                <input type="submit"  name="checkPass" class="button" value="Sign In">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>


</body>
</html>