<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">
</head>

<?php

session_start();

unset($_SESSION["logged_in"]);
unset($_SESSION["account_type"]);
unset($_SESSION["userId"]);
setcookie(session_name(), "", time() - 360);
session_destroy();

echo('<div class="text-center">You are now logged out! You will be redirected shortly.</div>');

// Redirect to index
header("refresh:5; url=index.php");

?>