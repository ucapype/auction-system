<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.
if ($_SESSION['account_type'] == 'seller') {
    echo('<div class="text-center">You are not buyer</div>');
} else {
    $DBHOST = "localhost";
    $DBUSER = "root";
    $DBPWD = "123456";
    $DBNAME = "auction_3";
    $conn = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);
    $buyerId = $_SESSION['userId'];
    $auction_id = $_POST["auctionId"];
    $bid_price = $_POST["bidPrice"];

    $statement = "SELECT * FROM itemauction WHERE auctionId=?";
    $stmt = $conn->prepare($statement);
    $stmt->bind_param("s", $auction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['bidPrice'] < $bid_price) {
        $statement = "INSERT INTO `bid`(`buyerId`, `bidPrice`, `auctionId`) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($statement);
        $stmt->bind_param("sss",$buyerId, $bid_price, $auction_id);
        $stmt->execute();

        $statement = "UPDATE `itemauction` SET `buyerId` = ?, `bidPrice` = ? WHERE `auctionId` = ?";
        $stmt = $conn->prepare($statement);
        $stmt->bind_param("sss", $buyerId, $bid_price, $auction_id);
        $stmt->execute();

        $conn->close();
        // If all is successful, let user know.
        echo('<div class="text-center">Bids successfully created! <a href="mybids.php">View your current bids.</a></div>');
    } else {
        echo('<div class="text-center">Bid price must above the current bid price</div>');
    }
}



?>