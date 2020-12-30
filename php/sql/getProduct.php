<?php

require_once "db.conn.php";

function getProduct(int $pid, int $uid = -1) {
    $mysqli = getDBConnection();
    $sqlProduct = "SELECT P.prod_id, P.name, P.description, P.price, P.vat, P.balance FROM PRODUCTS as P WHERE prod_id = $pid";
    $sqlWishlist = "SELECT W.pid FROM WISHLIST_ITEMS as W WHERE W.pid = $pid and W.uid = $uid";
    $sqlReviews = "SELECT R.title, R.comment, R.rating, R.recommends, R.created_at, U.full_name FROM REVIEWS as R
	                 LEFT JOIN USERS as U ON R.uid = U.uid
                     WHERE R.product_id = $pid AND R.comment IS NOT NULL
                     ORDER BY R.created_at DESC";

    // Product information
    $product = new stdClass();
    $resultSetProduct = $mysqli->query($sqlProduct);
    if($resultSetProduct->num_rows === 0){
        return null;
    } else {
        $result = $resultSetProduct->fetch_object();

        $product->vat = $result->vat;
        $product->name = $result->name;
        $product->price = $result->price;
        $product->prod_id = $result->prod_id;
        $product->balance = $result->balance;
        $product->description = $result->description;
    }

    // Reviews
    $reviews = [];
    $totalNumberOfStars = 0;
    $resultSetReviews = $mysqli->query($sqlReviews);
    while ($result = $resultSetReviews->fetch_object()) {
        $review = new stdClass();
        $review->title = $result->title;
        $review->comment = $result->comment;
        $review->rating = $result->rating;
        $review->recommends = $result->recommends;
        $review->created_at = $result->created_at;
        $review->username = $result->full_name;
        array_push($reviews, $review);

        $totalNumberOfStars += $result->rating;
    }

    // Wishlist
    $resultSetWishlist = $mysqli->query($sqlWishlist);
    $productIsonWishlist = $resultSetWishlist->num_rows === 1;
    $resultSetWishlist->close();

    // Assign last properties to product
    $product->rating = empty($reviews) ? 0 : round((float)($totalNumberOfStars/count($reviews)), 1);
    $product->ratings = count($reviews);
    $product->reviews = $reviews;
    $product->onWishlist = $productIsonWishlist;

    $mysqli->close();
    return $product;
}
