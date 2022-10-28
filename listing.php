<?php include_once("header.php")?>
<?php require("utilities.php")?>

<?php
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
  
// >>> Get info from the URL: <<<
$auction_Id = $_GET['item_id'];

// >>> TODO: Use auction_id to make a query to the database. <<<
$query = "SELECT * from itemauction WHERE auctionId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $auction_Id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$auction_title = $row["auctionTitle"];
$item_description = $row["itemDescription"];
$current_price = $row["bidPrice"];
$num_bids = $row["bid_number"];
$end_date = $row["endDate"];
$reserve_price = $row["reservePrice"];

/* >>> TODO: Note: Auctions that have ended may pull a different set of data,
        like whether the auction ended in a sale or was cancelled due
        to lack of high-enough bids. Or maybe not. <<< */

// >>> Calculate time to auction end: <<<
$now = new DateTime();

if ($now < $end_date) {
  $time_to_end = date_diff($now, $end_date);
  $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
} // xxxxxxxxxxxxxxxx TO DO !!!!!!!!!xxxxxxxxxxxxxx

// TODO: If the user has a session, use it to make a query to the database
//        to determine if the user is already watching this item.

if (isset($_SESSION['userId'])) 
{
  $has_session = true;
  $userId = $_SESSION["userId"];
  $statement = "SELECT * FROM watchlist WHERE userId = ? AND auctionId = ? ";
  $stmt = $conn->prepare($statement);
  $stmt->bind_param("ii", $userId, $auction_Id);
  $stmt->execute();
  $result = $stmt->get_result();
  // Check if auction exists in user's watchlist
  if(mysqli_num_rows($result)==0)
  {
    $watching = false;
  }
  else
  {
    $watching = true;
    $has_session = true;
  }
}
else
{
  $has_session = false;
  $watching = false;
}
?>



<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($auction_title); ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if ($now < date_create($end_date)):
?>
    <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
    </div>
<?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->

    <div class="itemDescription">
    <?php echo($item_description); ?>
    </div>

  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <p>
<?php if ($now > date_create($end_date)): ?>
     This auction has ended: <?php
     echo(date_format(date_create($end_date), "j M H:i")) ?>
     <!-- TODO: Print the result of the auction here? -->
     <?php echo "<br> Final bid value: £$current_price";
      $statement_status = "UPDATE itemauction SET auctionStatus=? WHERE auctionId=?";
      if ($current_price < $reserve_price)
      {
      // echo "<br> Auction resulted in unsuccessful sale due to in sufficient bidding.";
      echo '<br><i style="color:red;font-size:20px;font-family:calibri ;">
      *Auction resulted in unsuccessful sale due to in sufficient bidding. </i> ';
      
      # Update the auction status to the database
      $auction_status = "Cancelled";
      $stmt = $conn->prepare($statement_status);
      $stmt->bind_param("si", $auction_status, $auction_Id );
      $stmt->execute();
      }
      else
      {
      // echo "<br> Auction resulted in successful sale.";
      echo '<br><i style="color:green;font-size:20px;font-family:calibri ;">
      *Auction resulted in successful sale. </i> ';

      # Update the auction status to the database
      $auction_status = "Ended";
      $stmt = $conn->prepare($statement_status);
      $stmt->bind_param("si", $auction_status, $auction_Id );
      $stmt->execute();
      }

      $stmt->close();
      $conn->close();
     ?>
<?php else: 
      $time_to_end = date_diff($now, date_create($end_date));
      $time_remaining = "<br>" . display_time_remaining($time_to_end) . ' remaining';?>
     Auction ends <?php echo(date_format(date_create($end_date), "j M H:i") . $time_remaining) ?></p>
    <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

    <!-- Bidding form -->
    <form method="POST" action="place_bid.php">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">£</span>
        </div>
	    <input type="number" class="form-control" id="bid" name="bidPrice">
          <input hidden name="auctionId" value="<?= $auction_Id?>">
      </div>
      <button type="submit" class="btn btn-primary form-control">Place bid</button>
    </form>
<?php endif ?>


  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->

<?php include_once("footer.php")?>


<script>
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo ($auction_Id);?>]},

    success:
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();

        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($auction_Id);?>]},

    success:
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();

        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>


