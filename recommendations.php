<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

    <h2 class="my-3">Recommendations</h2>

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

    // Obtain userId from session cookies
    $userId = $_SESSION['userId'];

    // Retrieve page number from the URL
    if (!isset($_GET['page'])) {
        $curr_page = 1;
    }
    else {
        $curr_page = $_GET['page'];
    }

    $statement = "SELECT * FROM itemauction WHERE buyerId=?";
    $stmt = $conn->prepare($statement);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $interest = [];
    while ($row = $result->fetch_assoc()) {
        $interest[] = $row['category'];
    }
    $interestString = "('";
    foreach ($interest as $key => $v) {
        $interestString .= $v."',";
    }
    $interestString .= ")";
    $interestString = str_replace(",)",")",$interestString);
    // Retrive all the listings for the seller from the database
    $statement = "SELECT * FROM itemauction WHERE `category` IN ".$interestString;
    $stmt = $conn->prepare($statement);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_results = mysqli_num_rows($result); // Total number of rows
    $results_per_page = 5; // The number of items we want to display per page
    $max_page = ceil($num_results / $results_per_page); // Maximum page for the tage at the bottom

    // Display items
    $page1 = ($curr_page*$results_per_page)-$results_per_page;
    $statement_2 = "SELECT * FROM itemauction WHERE `category` IN ".$interestString;
    $stmt_2 = $conn->prepare($statement_2);
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
