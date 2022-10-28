<?php include_once("header.php")?>

<div class="container my-5">

<?php







// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */
//$auction_creation = mysqli_query($connection,$create_auction);
//$connection = mysqli_connect('localhost','root','','auciton_2') or die('Error connecting to MySQL server.' . mysql_error());

$DBHOST = "localhost";
$DBUSER = "root";
$DBPWD = "";
$DBNAME = "auction_3";
$conn = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);
//session_start();
/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */

$sellerId = $_SESSION['userId'];
$title = $_POST['auctionTitle'];
$description = $_POST['auctionDetails'];
$category = $_POST["auctionCategory"];
$start_price = $_POST["auctionStartPrice"];
$reserve_price = $_POST["auctionReservePrice"];
$start_date = $_POST["auctionStartDate"];
$end_date = $_POST["auctionEndDate"];

/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */
//$create_auction = "INSERT INTO itemAuction( auctionTitle, auctionDescription, category, startingPrice, reservePrice, startDate, endDate)
//VALUES ( $title, '$description , $category, $start_price , $reserve_price, $start_date, $end_date)";


$statement = "INSERT INTO itemAuction(sellerId, auctionTitle, itemDescription, category, startingPrice, reservePrice, startDate, endDate) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($statement);
$stmt->bind_param("ssssssss",$sellerId, $title, $description , $category, $start_price , $reserve_price, $start_date, $end_date);
$stmt->execute();

$conn->close();
// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>