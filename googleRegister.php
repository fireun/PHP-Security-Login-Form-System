<?php

include("database.php");
session_start();
    if(isset($_POST['register'])){
		require "protect.php";
		$gump = new GUMP();
		$_POST = $gump->sanitize($_POST); 
		
		$gump->validation_rules(array(
		  'f_name'        => 'required|alpha_space|max_len,30|min_len,1',	
		  'l_name'        => 'required|alpha_space|max_len,30|min_len,1',
		  'email'       => 'required|valid_email',
		  'password'    => 'required|max_len,50|min_len,1',
		
		));
		$gump->filter_rules(array(
		  'f_name'     => 'trim|sanitize_string',
		  'l_name'     => 'trim|sanitize_string',
		  'password' => 'trim',
		  'email'    => 'trim|sanitize_email',
		  ));
		  $validated_data = $gump->run($_POST);
		  if($validated_data === false) {
			?>
			<!-- <script>alert(' <?php echo $gump->get_readable_errors(true); ?> ');window.location.href='userLogin.php'</script>; -->
			<?php
		  }
		  else if ($_POST['password'] !== $_POST['password2']) 
		  {
			// echo  "<script>alert('Passwords do not match ');window.location.href='userLogin.php'</script>";
		  }
		  else {
		  $email = $validated_data['email'];
		  $checkemail = "SELECT * FROM user WHERE email = '$email'";
			  $run_check = mysqli_query($conn , $checkemail) or die(mysqli_error($conn));
			  $countusername = mysqli_num_rows($run_check); 
			  if ($countusername > 0 ) {
			// echo  "<script>alert('Email is already taken! try a different one');window.location.href='userLogin.php'</script>";
		}
		
		  else {
				$uploads_dir = 'images/'; //images floder name destination
				$image= basename($_FILES["userimage"]["name"]);//original image name
				$target = $uploads_dir.$image;//get destination + original image name

				  $f_name = $validated_data['f_name'];
				  $l_name = $validated_data['l_name'];
				  $email = $validated_data['email'];
				  $status = $validated_data['status'];
				  $password = $validated_data['password'];
				  $password = password_hash("$password" , PASSWORD_DEFAULT);
				  $query = "INSERT INTO user(email,f_name,l_name,pass,image,status) VALUES ('$email','$f_name','$l_name','$password','$image','$status')";
				  $result = mysqli_query($conn , $query) or die(mysqli_error($conn));
				  $_SESSION["Email"] = mysqli_insert_id($conn);
				  if (mysqli_affected_rows($conn) > 0) { 
					move_uploaded_file($_FILES["userimage"]["tmp_name"],$target);
					echo "<script>alert('Registration Successfully! ');
					window.location.href='googleRegister.php'</script>"; 
				}
				else {
				// echo "<script>alert('Error ');window.location.href='userLogin.php'</script>";
				}
			}
		  }
	}
	
	if(isset($_POST['cancel'])){
        session_destroy();
		header("location:userLogin.php");
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
    <title>Google Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <form action="googleRegister.php" method="post"  enctype="multipart/form-data">

    <div class="login-wrap" style="min-height: 700px;">
	<div class="login-html">
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab sign-in-login-input">Sign Up</label>
		<input id="" type="radio" name="tab" class="for-pwd" ><label for="tab-2" class="tab"></label>
		<div class="login-form">
			<div class="sign-in-htm">
			
					<input name="email"  value="<?php echo $_SESSION['user_email_address'];?>" style="display: none;">
					<input name="f_name" value="<?php echo $_SESSION['user_first_name'];?>" style="display: none;">
					<input name="l_name" value="<?php echo$_SESSION['user_last_name'];?>" style="display: none;">
					<input name="status" value="google" style="display: none;">
				<div class="group">
				<label for="pass" class="label">Image</label>
				<img src="images/uknown.jpeg" onclick="triggerClick()"  id="profileDisplay" style="width:40%;height:40%;position:relative;left:110px;object-fit: contain;border-radius: 75px;" >
				<input id="image"  name="userimage" type="file" class="input"  onchange="displayImage(this)" style="display: none;">
				</div>
            
				
				<div class="group">
					<label for="pass" class="label">Password</label>
					<input id="pass"   name="password" type="password" class="input" data-type="password" >
                </div>
				<div class="group">
					<label for="pass" class="label">Confirm Password</label>
					<input id="pass"   name="password2" type="password" class="input" data-type="password">
                </div>
   
				<div class="group"  style="margin-top:20%">
					<div class="row">
					<div class="col-md-6"><input type="submit"  name="register" class="button" value="Register"></div>
					<div class="col-md-6"><a href="userLogin.php"><input type="submit"  name="cancel" class="button" value="Cancel"></a></div>
					</div>
					
				</div>
			
			</div>

		</div>
	</div>
</div>
</form>
</body>
</html>
<script>
function triggerClick() {
    document.querySelector('#image').click();
}

function displayImage(e){
    if(e.files[0]){
        var reader = new FileReader();

        reader.onload = function(e) {
            document.querySelector('#profileDisplay').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(e.files[0]);
    }
}
</script>
