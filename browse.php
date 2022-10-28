<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          <input type="text" class="form-control border-left-0" id="keyword" placeholder="Search for anything", name="keyword">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat">
          <option selected value="all">All categories</option>
          <option value="tops">Tops</option>
          <option value="bottoms">Bottoms</option>
          <option value="dresses">Dresses</option>
          <option value="shoes">Shoes</option>
          <option value="accessories">Accessories</option>
        </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <select class="form-control" id="order_by">
          <option selected value="pricelow">Price (low to high)</option>
          <option value="pricehigh">Price (high to low)</option>
          <option value="endDate">Soonest expiry</option>
          <option value="bid_number">Popularity</option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->


</div>

<?php
  // Retrieve these from the URL
  if (!isset($_GET['keyword'])) {
    // TODO: Define behavior if a keyword has not been specified.
    $keyword = "%%";  
    // echo "Please enter keyword.";
  }
  else {
    $key_search = $_GET['keyword'];
    $keyword = "%{$key_search}%";
  }

  if (!isset($_GET['cat'])) {
    // TODO: Define behavior if a category has not been specified.
    $category = "all";
  }
  else {
    // $category = $_GET['cat'];
    $category = $_GET['cat'];
  }
  
  if (!isset($_GET['order_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
    $ordering = "bid_number";
    $direction = "DESC";
  }
  else {
    $ordering_title = $_GET['order_by'];
    if ($ordering_title == "pricehigh"){
      $ordering = "bidPrice";
      $direction = "DESC";
    }
    elseif($ordering_title == "pricelow"){
      $ordering = "bidPrice";
      $direction = "ASC";
    }
    else{
      $ordering = $ordering_title;
      $direction = "DESC";
    }

  }
  
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }

  /* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
  // $connection = mysqli_connect('localhost','exampleuser','mypassword','Example') or die('Error connecting to MySQL server.' . mysql_error());
  

  
// Connect to the database
$DBHOST = "localhost";
$DBUSER = "root";
$DBPWD = "123456";
$DBNAME = "auction_3";

$connection = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);	
 if($connection->connect_error)
 {
 die("Connection failed!".$connection->connect_error);
 }


  // $search_with_cat = "SELECT * FROM itemauction WHERE auctionTitle LIKE ? OR itemDescription LIKE ? AND category = ? ORDER BY ? ?";
  $search_with_cat = "SELECT * FROM itemauction WHERE auctionTitle LIKE ? OR itemDescription LIKE ? AND category = ? ";
  // $search_without_cat = "SELECT * FROM itemauction WHERE auctionTitle LIKE ? OR itemDescription LIKE ? ORDER BY ? ?" ;
  $search_without_cat = "SELECT * FROM itemauction WHERE auctionTitle LIKE ? OR itemDescription LIKE ? ORDER BY ?" ;

  
  // if ($category = "") {
  //   //$search_results = mysqli_query($connection,$search_without_cat);
  //   $stmt = $connection->prepare($search_without_cat);
  //   $stmt->bind_param("ss", $keyword, $keyword);
  //   $stmt->execute();
  //   $search_results = $stmt->get_result();
  // }


if ($category = "all"){
  $stmt = $connection->prepare($search_without_cat);
  $stmt->bind_param("sss", $keyword, $keyword, $ordering);
  $stmt->execute();
  $search_results = $stmt->get_result();
}
else{
  $stmt = $connection->prepare($search_with_cat);
  $stmt->bind_param("sss", $keyword, $keyword, $category);
  $stmt->execute();
  $search_results = $stmt->get_result();
  }

    
  $count_search = "SELECT COUNT(*) FROM itemauction WHERE auctionTitle LIKE ? OR itemDescription LIKE ? ";       
  //$num_results = mysqli_query($connection,$count_search); 
  $stmt = $connection->prepare($count_search);
  $stmt->bind_param("ss", $keyword, $keyword);
  $stmt->execute();
  $results = $stmt->get_result();
  $row = $results->fetch_assoc();
  $num_results = $row["COUNT(*)"];


  //mysqli_close($connection);
  /* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
  // TODO: Calculate me for real
  // $results_per_page = 10;
  // $max_page = ceil($num_results / $results_per_page);
?>

<div class="container mt-5">

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

<ul class="list-group">

<?php 

if ($num_results == 0) {
  echo "<h2> There were no results for this search </h2>";
}
else{
  
  while($row = $search_results->fetch_assoc()){
    $item_id = $row["auctionId"];
    $title = $row["auctionTitle"];
    $description = $row["itemDescription"];
    $current_price = $row["bidPrice"];;
    $num_bids = "This needs to be done";
    $end_date = $row["endDate"];;
    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
}

}
$results_per_page = 5;
$max_page = ceil($num_results / $results_per_page);
?>


</ul>

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
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
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
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>