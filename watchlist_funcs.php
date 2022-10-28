 <?php

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

// Extract arguments from the POST variables:
$item_Id = $_POST['arguments'];
$auction_Id = $item_Id[0];

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


session_start();
$user_Id = $_SESSION['userId'];


if ($_POST['functionname'] == "add_to_watchlist") {
  // TODO: Update database and return success/failure.
  $statement = "INSERT INTO watchlist(userId, auctionId) VALUES(?, ?)";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("ii", $user_Id, $auction_Id);
  $stmt->execute();
  
  $conn->close();
  $res = "success";
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
  // TODO: Update database and return success/failure.
  $statement = "DELETE FROM watchlist WHERE userId=? AND auctionId=?";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("ii", $user_Id, $auction_Id);
  $stmt->execute();

  $conn->close();
  $res = "success";
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo $res;

?>