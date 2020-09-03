<?php
include("database.php");



    if(isset($_POST['register'])){
		require "protect.php";
		$gump = new GUMP();
		$_POST = $gump->sanitize($_POST); 
		
		$gump->validation_rules(array(
		  'first_name'        => 'required|alpha_space|max_len,30|min_len,3',	
		  'last_name'        => 'required|alpha_space|max_len,30|min_len,3',
		  'email'       => 'required|valid_email',
		  'password'    => 'required|max_len,50|min_len,6',
		
		));
		$gump->filter_rules(array(
		  'first_name'     => 'trim|sanitize_string',
		  'last_name'     => 'trim|sanitize_string',
		  'password' => 'trim',
		  'email'    => 'trim|sanitize_email',
		  ));
		  $validated_data = $gump->run($_POST);
		  
		  if($validated_data === false) {
			?>
			<script>alert(' <?php echo $gump->get_readable_errors(true); ?> ');window.location.href='register.php'</script>;
			<?php
		  }
		  else if ($_POST['password'] !== $_POST['password2']) 
		  {
			echo  "<script>alert('Passwords do not match ');window.location.href='register.php'</script>";
		  }

		  else {
		  $email = $validated_data['email'];
		  $checkemail = "SELECT * FROM user WHERE email = '$email'";
			  $run_check = mysqli_query($conn , $checkemail) or die(mysqli_error($conn));
			  $countusername = mysqli_num_rows($run_check); 
			  if ($countusername > 0 ) {
			echo  "<script>alert('Email is already taken! try a different one');window.location.href='register.php'</script>";
		}
		
		  else {
				$uploads_dir = 'images/'; //images floder name destination
				$image= basename($_FILES["userimage"]["name"]);//original image name
				$target = $uploads_dir.$image;//get destination + original image name

				  $first_name = $validated_data['first_name'];
				  $last_name = $validated_data['last_name'];
				  $email = $validated_data['email'];
				  $password = $validated_data['password'];
				  $password = password_hash("$password" , PASSWORD_DEFAULT);
				  $query = "INSERT INTO user(email,f_name,l_name,pass,image,status) VALUES ('$email','$first_name','$last_name','$password','-','-')";
				  $result = mysqli_query($conn , $query) or die(mysqli_error($conn));
				  $_SESSION["Email"] = mysqli_insert_id($conn);
				  if (mysqli_affected_rows($conn) > 0) { 
					move_uploaded_file($_FILES["userimage"]["tmp_name"],$target);
					echo "<script>alert('Registration Successfully! ');
					window.location.href='index.php'</script>"; 
				}
				else {
				echo "<script>alert('Error ');window.location.href='register.php'</script>";
				}
			}
		  }
	}
	
?>

<html>  
    <head>  
        <title>How to Implement Google reCaptcha in PHP Form</title>  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>  
    <body>  
        <div class="container" style="width: 600px">
   <br />
   
   <h3 align="center">How to Implement Google reCaptcha in PHP Form</a></h3><br />
   <br />
   <div class="panel panel-default">
      <div class="panel-heading">Register Form</div>
    <div class="panel-body">
     
     <form metod="post"  action="text1.php" enctype="multipart/form-data">
      <div class="form-group">
       <div class="row">
        <div class="col-md-6">
         <label>First Name <span class="text-danger">*</span></label>
         <input type="text" name="first_name" id="first_name" class="form-control" />
         <span id="first_name_error" class="text-danger"></span>
        </div>
        <div class="col-md-6">
         <label>Last Name <span class="text-danger">*</span></label>
         <input type="text" name="last_name" id="last_name" class="form-control" />
         <span id="last_name_error" class="text-danger"></span>
        </div>
       </div>
      </div>
      <div class="form-group">
       <label>Email Address <span class="text-danger">*</span></label>
       <input type="text" name="email" id="email" class="form-control" />
       <span id="email_error" class="text-danger"></span>
      </div>
      <div class="form-group">
       <label>Password <span class="text-danger">*</span></label>
       <input type="password" name="password" id="password" class="form-control" />
       <span id="password_error" class="text-danger"></span>
      </div>
      <div class="group">
					<label for="pass" class="label">Confirm Password</label>
					<input id="pass"   name="password2" type="password" class="input" data-type="password">
                </div>
      <div class="form-group">
       <div class="g-recaptcha" data-sitekey="6LeepcMZAAAAAJfSwBpxzWAcL2S4bXjXDL6n6lFb"></div>
       <span id="captcha_error" class="text-danger"></span>
      </div>
      <div class="form-group">
       <input type="submit" name="register" id="register" class="btn btn-info" value="Register" />
       <div class="col-md-6"><input type="submit"  name="register" class="button" value="Register"></div>
      </div>
     </form>
     
    </div>
   </div>
  </div>
    </body>  
</html>

<script>
$(document).ready(function(){

 $('#captcha_form').on('submit', function(event){
  event.preventDefault();
  $.ajax({
   url:"process_data.php",
   method:"POST",
   data:$(this).serialize(),
   dataType:"json",
   beforeSend:function()
   {
    $('#register').attr('disabled','disabled');
   },
   success:function(data)
   {
    $('#register').attr('disabled', false);
    if(data.success)
    {
     $('#captcha_form')[0].reset();
     $('#first_name_error').text('');
     $('#last_name_error').text('');
     $('#email_error').text('');
     $('#password_error').text('');
     $('#captcha_error').text('');
     grecaptcha.reset();
     alert('Form Successfully validated');
    }
    else
    {
     $('#first_name_error').text(data.first_name_error);
     $('#last_name_error').text(data.last_name_error);
     $('#email_error').text(data.email_error);
     $('#password_error').text(data.password_error);
     $('#captcha_error').text(data.captcha_error);
    }
   }
  })
 });

});
</script>