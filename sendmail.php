<!-- This function sends out the emails to the buyer -->
<?php require("utilities.php")?>
<?php

// $name = "COMP0178 Group11"; //sender’s name
// $email = "comp.ucl.test@gmail.com"; //sender’s e-mail address
// $recipient = "cwh9108@gmail.com"; //recipient
// $subject = "Auction Result"; //subject
// $mail_body= "Your password is $password"; //mail body
// $header = "From: ". $name . " <" . $email . ">\r\n";
// //optional headerfields
// mail($recipient, $subject, $mail_body, $header); //mail function


$auctionId = 4;

send_mail_successful($auctionId);
?>
