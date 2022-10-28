<?php

// connect_to_database:
// This function connects to the database
function connect_to_database($dbhost, $dbuser, $dbpwd, $dbname)
{
  $conn = new mysqli($dbhost, $dbuser, $dbpwd, $dbname);	
  if($conn->connect_error)
  {
    die("Connection failed!".$conn->connect_error);
  }
}


// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if ($now > date_create($end_time)) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, date_create($end_time));
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
    <li class="list-group-item d-flex justify-content-between">
    <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '</div>
    <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
  </li>'
  );
}


// send_mail_successful:
// Send out emails to both buyer and seller in a successful auction
function send_mail_successful($auction_Id)
{
  // Connect to the database
  $DBHOST = "localhost";
  $DBUSER = "root";
  $DBPWD = "123456";
  $DBNAME = "auction_3";
  // connect_to_database($DBHOST, $DBUSER, $DBPWD, $DBNAME);
  $conn = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);	
  if($conn->connect_error)
  {
    die("Connection failed!".$conn->connect_error);
  }

  // Retrieve buyerId and sellerId from itemauction
  $statement = "SELECT * FROM itemauction WHERE auctionId=?";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("i", $auction_Id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $buyerId = $row["buyerId"];
  $sellerId = $row["sellerId"];
  $current_price = $row["bidPrice"];
  $auction_title = $row["auctionTitle"];

  // Retrieve their emails using buyerId and sellerId from account
  $statement_email = "SELECT emailAddress FROM account WHERE userId=?";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("i", $buyerId);
  $stmt->execute();
  $buyer_email = $stmt->get_result();
  $stmt->bind_param("i", $sellerId);
  $stmt->execute();
  $seller_email = $stmt->get_result();

  // Send out the emails
  $name = "COMP0178 Group11"; //sender’s name
  $sender_email = "comp.ucl.test@gmail.com"; //sender’s e-mail address
  $recipient = "cwh9108@gmail.com"; //recipient
  $subject = "Auction Result"; //subject
  $mail_body= "Auction ID: ($auction_Id) \nAuction Title:$auction_title\nThe auction was ended sucessfully, The final bid price was £$current_price"; //mail body
  $header = "From: ". $name . " <" . $sender_email . ">\r\n";
  //optional headerfields
  mail($recipient, $subject, $mail_body, $header); //mail function
}


// // send_mail_unsuccessful:
// // Send out emails to all users who bidded on and the seller in an unsuccessful auction
// function send_mail_successful($auction_Id)
// {
//   // Connect to the database
//   $DBHOST = "localhost";
//   $DBUSER = "root";
//   $DBPWD = "";
//   $DBNAME = "auction_3";
//   // connect_to_database($DBHOST, $DBUSER, $DBPWD, $DBNAME);
//   $conn = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);	
//   if($conn->connect_error)
//   {
//     die("Connection failed!".$conn->connect_error);
//   }

//   // Retrieve sellerId from itemauction
//   $statement = "SELECT * FROM itemauction WHERE auctionId=?";
//   $stmt = $conn->prepare($statement);
//   $stmt->bind_param("i", $auction_Id);
//   $stmt->execute();
//   $result = $stmt->get_result();
//   $row = $result->fetch_assoc();
//   $buyerId = $row["buyerId"];
//   $sellerId = $row["sellerId"];
//   $current_price = $row["bidPrice"];
//   $auction_title = $row["auctionTitle"];

//   // TODO
//   // // Retrieve their emails using buyerId and sellerId from account
//   // $statement_email = "SELECT emailAddress FROM account WHERE userId=?";
//   // $stmt = $conn->prepare($statement);
//   // $stmt->bind_param("i", $buyerId);
//   // $stmt->execute();
//   // $buyer_email = $stmt->get_result();
//   // $stmt->bind_param("i", $sellerId);
//   // $stmt->execute();
//   // $seller_email = $stmt->get_result();

//   // // Send out the emails
//   // $name = "COMP0178 Group11"; //sender’s name
//   // $sender_email = "comp.ucl.test@gmail.com"; //sender’s e-mail address
//   // $recipient = "cwh9108@gmail.com"; //recipient
//   // $subject = "Auction Result"; //subject
//   // $mail_body= "Auction ID: ($auction_Id) \nAuction Title:$auction_title\nThe auction was ended unsucessfully, The final bid price was £$current_price."; //mail body
//   // $header = "From: ". $name . " <" . $sender_email . ">\r\n";
//   // //optional headerfields
//   // mail($recipient, $subject, $mail_body, $header); //mail function
// }
?>