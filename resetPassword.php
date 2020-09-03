<?php

include("database.php");
session_start();

if(isset($_GET["code"])){
    $_SESSION['code'] = $_GET["code"];
    $code = $_SESSION['code'];

    $getEmailQuery = "select email from resetpasswords where code='$code'";
    $getEmailQueryResult = $conn->query($getEmailQuery) or die($conn->error.__LINE__);

    if(mysqli_num_rows($getEmailQueryResult) > 0) {
        while ($row = mysqli_fetch_array($getEmailQueryResult)) {
            $_SESSION['email'] = $row['email'];
        }
    }
}

$newWrongPass = 0;
if(isset($_POST['submit'])){
        $code = $_SESSION['code'];
        $email = $_SESSION['email'];

        require "protect.php";
		$gump = new GUMP();
		$_POST = $gump->sanitize($_POST); 
		
		$gump->validation_rules(array(
		  'password'    => 'required|max_len,50|min_len,6',
		));
		$gump->filter_rules(array(
		  'password' => 'trim',
		));
		$validated_data = $gump->run($_POST);
		if($validated_data === false) {
			?>
			<script>alert(' <?php echo $gump->get_readable_errors(true); ?> ');window.location.href='resetPassword.php'</script>;
			<?php
        }else{
              $password = $validated_data['password'];

            // $password =  mysqli_real_escape_string($conn,$_POST['password']);
            $Encrypassword = password_hash("$password" , PASSWORD_DEFAULT);
            
            $ResetPass = mysqli_query($conn,"UPDATE user SET pass='$Encrypassword',wrongpass = '$newWrongPass' WHERE email = '$email'");
            if($ResetPass == true){
                $DelectQuery = mysqli_query($conn,"DELETE FROM resetpasswords WHERE code = '$code'");
                session_destroy();
                echo "<script>alert('Reset Successful!'); window.location.assign('index.php');</script>";
            }else{
                exit("Something error wrong");
            }
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<form action="resetPassword.php" method="post">

    <div class="login-wrap">
    <div class="login-html">
        <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Reset New Password</label>
        <input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
        <div class="login-form">
            <div class="sign-in-htm">
                <div class="group">
                    <label for="user" class="label">New Password</label>
                    <input id="user"  name="password" type="password" class="input" placeholder="new password">
                </div>
                <!-- <div class="group">
                    <label for="pass" class="label">Password</label>
                    <input id="pass" type="password" class="input" data-type="password">
                </div> -->
                <div class="group">
                    <input type="submit"  name="submit" class="button" value="Update Password">
                </div>
                <!-- <div class="hr"></div> -->
            </div>

        </div>
    </div>
    </div>
</form>
</body>
</html>