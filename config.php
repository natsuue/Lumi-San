<?php
session_start();


$serverName="LAPTOP-G9D4RQQU"; 
$connectionOptions=[ 
  "Database"=>"FINALSDB", 
  "Uid"=>"", 
  "PWD"=>"" 
]; 
$conn = sqlsrv_connect($serverName, $connectionOptions);


  
  
  // Set flag: true if not logged in
  $requireRegistration = !isset($_SESSION['Email']); 
  $userEmail = $_SESSION['Email'] ?? null;
  $userName  = $_SESSION['UserName'] ?? null;
  $userPic   = $_SESSION['Picture'] ?? null; // for Google users

  $errors = [
    'Login' => $_SESSION['login_error'] ?? '',
    'Register' => $_SESSION['register_error'] ?? ''
  ];
  
  // Clear errors so they don’t show again on next load
  unset($_SESSION['login_error'], $_SESSION['register_error']);

define('GOOGLE_CLIENT_ID', 'censored by natsulim');
define('GOOGLE_CLIENT_SECRET', 'censored by natsulim');
define('GOOGLE_REDIRECT_URI', 'censored by natsulim');

  include 'header.php';

?>