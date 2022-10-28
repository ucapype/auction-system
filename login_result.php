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

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.

/*
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = "test";
$_SESSION['account_type'] = "buyer";

echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to index after 5 seconds
header("refresh:5;url=index.php");*/

if(!empty($_POST["email"]) && !empty($_POST["password"]))
{
    $DBHOST = "localhost";
    $DBUSER = "root";
    $DBPWD = "";
    $DBNAME = "auction_3";

    $conn = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);
    if($conn->connect_error)
    {
        die("Connection failed!" .$conn->connect_error);
    }

    // Check if the username and password are correct.
    $email = $_POST["email"];
    $statement = "SELECT * FROM account where emailAddress=?";
    $stmt = $conn->prepare($statement);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hash = $row["password"];
    $account_type = $row["accountType"];
    $user_Id = $row['userId'];

    if(password_verify($_POST["password"], $hash))
    {
        echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
        // Store information in the session cookies.
        session_start();
        //$_SESSION["email"] = $_POST["email"];
        $_SESSION["account_type"] = $account_type;
        $_SESSION["logged_in"] = true;
        $_SESSION["userId"] = $user_Id;
        $conn->close();

        // Redirect to browse after 5 seconds.
        header("refresh:5;url=browse.php");
    }

    else
    {
        echo('<div class="text-center">Wrong Password entered! You will be redirected shortly.</div>');
        $conn->close();
        header("refresh:5;url=browse.php");
    }
}

// Prevent user from directly accessing this page.
else
{
    header("Location:browse.php");
}



?>