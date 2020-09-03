
<?php

//process_data.php

if(isset($_POST["first_name"]))
{
 $first_name = '';
 $last_name = '';
 $email = '';
 $password = '';

 $first_name_error = '';
 $last_name_error = '';
 $email_error = '';
 $password_error = '';
 $captcha_error = '';

 if(empty($_POST["first_name"]))
 {
  $first_name_error = 'First name is required';
 }
 else
 {
  $first_name = $_POST["first_name"];
 }

 if(empty($_POST["last_name"]))
 {
  $last_name_error = 'Last name is required';
 }
 else
 {
  $last_name = $_POST["last_name"];
 }
 if(empty($_POST["email"]))
 {
  $email_error = 'Email is required';
 }
 else
 {
  if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
  {
   $email_error = 'Invalid Email';
  }
  else
  {
   $email = $_POST["email"];
  }
 }

 if(empty($_POST["password"]))
 {
  $password_error = 'Password is required';
 }
 else
 {
  $password = $_POST["password"];
 }

 if(empty($_POST['g-recaptcha-response']))
 {
  $captcha_error = 'Captcha is required';
 }
 else
 {
  $secret_key = '6LeepcMZAAAAAJlW_DzIK0lAweJ8ObDR9eLhCzrq';

  $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);

  $response_data = json_decode($response);

  if(!$response_data->success)
  {
   $captcha_error = 'Captcha verification failed';
  }
 }

 if($first_name_error == '' && $last_name_error == '' && $email_error == '' && $password_error == '' && $captcha_error == '')
 {
  $data = array(
   'success'  => true
  );
 }
 else
 {
  $data = array(
   'first_name_error' => $first_name_error,
   'last_name_error' => $last_name_error,
   'email_error'  => $email_error,
   'password_error' => $password_error,
   'captcha_error'  => $captcha_error
  );
 }

 echo json_encode($data);
}

?>
