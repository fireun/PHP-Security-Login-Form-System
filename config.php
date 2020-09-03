
<?php

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('701121967873-7nqh7ksnbh0oen4b89014e77dj1juudl.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('0WRj2b9cLbDZFpyBt01zAS8p');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/slp/index.php');

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page
session_start();

?>
