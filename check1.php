
<?php

//process_data.php

if(isset($_POST["first_name"]))
{

 $email = '';


 $email_error = '';
 $captcha_error = '';



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

 if($email_error == ''  && $captcha_error == '')
 {
  $data = array(
   'success'  => true
  );
 }
 else
 {
  $data = array(
   'email_error'  => $email_error,
   'captcha_error'  => $captcha_error
  );
 }

 echo json_encode($data);
}

?>
