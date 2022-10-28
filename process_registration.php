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

// Set up the connection to the database.
// Please make sure the names match to the ones on your computer.
$DBHOST = "localhost";
$DBUSER = "root";
$DBPWD = "123456";
$DBNAME = "auction_3";
$conn = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);

if($conn->connect_error)
{
die("Connection failed!".$conn->connect_error);
}

// Check if user entered data for all the mandatory components.
if(!empty($_POST["accountType"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["passwordConfirmation"]) && !empty($_POST["firstname"]) && !empty($_POST["lastname"]))
{
    // Check if email is valid.
    if(strpos($_POST['email'], "@") && strpos($_POST['email'], '.com') !== false)
    {
        // User inputs
        $accountType = $_POST["accountType"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_confirmation = $_POST["passwordConfirmation"];
        $fname = $_POST["firstname"];
        $lname = $_POST["lastname"];
        $display_name = $_POST["displayname"];

        // Check if the password_confirmation matches with the password entered.
        if($password == $password_confirmation)
        {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Verify if there is a duplicate user.
            $statement = "SELECT * FROM account WHERE emailAddress=?";
            $stmt = $conn->prepare($statement);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            // Redirect user to register.php if the email is already in the database.
            if($result->num_rows>=1)
            {
            echo('<div class="text-center">The email already exists. Please check your email or try login instead.</div>');
            header("refresh:5;url=register.php");
            }
            // Creat a new account and redirect user to browse.php .
            else
            {
            $statement = "INSERT INTO account(emailAddress, password, fName, lName, accountType, displayName) VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($statement);
            $stmt->bind_param("ssssss", $email, $hashed, $fname, $lname, $accountType, $display_name);
            $stmt->execute();
            
            // Extract "UserID" from the database
            $statement = "SELECT * FROM account WHERE emailAddress=?";
            $stmt = $conn->prepare($statement);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $user_ID = $row["userId"];

            // Automatically logged user in, and direct them to the browse.php .
            session_start();
            $_SESSION["account_type"] = $accountType;
            $_SESSION["logged_in"] = true;
            $_SESSION["userId"] = $user_ID;
            echo('<div class="text-center">Successfully registered! You will be redirected shortly.</div>');
            header("refresh:5;url=browse.php");
            } 
            $conn->close();
        }

        // Password did not match and redirect the user to register.php again.
        else
        {
            echo('<div class="text-center">The password did not match. Please try again.</div>');

            echo('<div class="text-center">You will be redirected shortly.</div>');
            header("refresh:5;url=register.php");
        }   // Password check finishes.
    }

    else
   {
    echo('<div class="text-center">Invalid email. Please try again.</div>');
    echo('<div class="text-center">You will be redirected shortly.</div>');
    header("refresh:5;url=register.php");    
    }   // Email check finishes.
    
}
else
{
    echo ('<div class="text-center">Please enter all the components that marked as required.</div>');
    echo "<br>";
    echo ('<div class="text-center">You will be redirected shortly.</div>');
    header("refresh:5; url=register.php");
}



?>
</html>