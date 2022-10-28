<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.


  // TODO: Check user's credentials (cookie/session).
  // Creates a session ID string in background, added to requests and replies (T5 p. 17):
  // session_start();
  // if (!isset($_SESSION['userID'])) {
  //   // Not already logged in
  //   // Insert code to check the username & password
  // // Things you can also do:
  // // Track variables or this session:
  // $_SESSION['userID'] = $row['id'];
  // $_SESSION['username'] = $row['username'];
  // }

  // TODO: Perform a query to pull up their auctions.
  // Choose what content to display (T5 p. 18):
  # session_start();
  // if (isset($_SESSION['userId'])) {
  // // User recognised.
  // // Display content, create/update session variables.
  // $query = " SELECT auctionId, auctionTitle, auctionStatus, endDate
  //            FROM account, itemauction, bid
  //            WHERE account.userId = bid.sellerId ";
  // } else {
  // // User not identified; display generic
  // // content, error message, login prompt, etc.
  // echo "User not recognised, cannot display any relevant data.";
  // }


  // TODO: Loop through results and print them out as list items.
  // session_start();  Is this needed here?
  // Creates dynamic table from DB results (T3 p. 9):
  // echo '<table border="1">';
  // while ($row = mysqli_fetch_array($result))
  // {
  //   echo '<tr><td>' . $row['auctionId'].
  //        '</td><td>' . $row['auctionTitle'].
  //        '</td><td>' . $row['auctionStatus'].
  //        '</td><td>' . $row['endDate'].
  //        '</td></tr>';
  // }
  // echo '</table>’;


// Connect to the database
$DBHOST = "localhost";
$DBUSER = "root";
$DBPWD = "123456";
$DBNAME = "auction_3";

$conn = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);	
if($conn->connect_error)
{
die("Connection failed!".$conn->connect_error);
}

// Obtain userId from session cookies
$userId = $_SESSION['userId'];

// Retrieve page number from the URL
if (!isset($_GET['page'])) {
  $curr_page = 1;
}
else {
  $curr_page = $_GET['page'];
}

// Retrive all the listings for the seller from the database
$statement = "SELECT * FROM itemauction WHERE sellerId=?";
$stmt = $conn->prepare($statement);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$num_results = mysqli_num_rows($result); // Total number of rows
$results_per_page = 5; // The number of items we want to display per page
$max_page = ceil($num_results / $results_per_page); // Maximum page for the tage at the bottom

// Display items
$page1 = ($curr_page*$results_per_page)-$results_per_page;
$statement_2 = "SELECT * FROM itemauction WHERE sellerId=? LIMIT $page1,$results_per_page";
$stmt_2 = $conn->prepare($statement_2);
$stmt_2->bind_param("i", $userId);
$stmt_2->execute();
$result_2 = $stmt_2->get_result();

while ($row = $result_2->fetch_assoc())
{
  $item_id = $row["auctionId"];
  $title = $row["auctionTitle"];
  $description = $row["itemDescription"];
  $current_price = $row["bidPrice"];
  $end_date = $row["endDate"];
  $num_bids = $row["bid_number"];

  print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
}

?>


<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>

<?php include_once("footer.php")?>
