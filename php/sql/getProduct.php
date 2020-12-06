<?php

require_once "db.conn.php";

function getProduct(int $productId) {
    $mysqli = getDBConnection();
    $sqlProduct = "SELECT P.prod_id, P.name, P.description, P.price, P.vat, P.balance, AVG(R.rating) as rating, COUNT(R.rating) as ratings FROM PRODUCTS as P
	                 LEFT JOIN REVIEWS as R ON P.prod_id = R.product_id
                     WHERE prod_id = ?
                     GROUP BY P.prod_id";
    $sqlReviews = "SELECT R.title, R.comment, R.rating, R.recommends, R.created_at, U.full_name FROM REVIEWS as R
	                 LEFT JOIN USERS as U ON R.uid = U.uid
                     WHERE R.product_id = ? AND R.comment IS NOT NULL
                     ORDER BY R.created_at DESC";

    $stmt = $mysqli -> prepare($sqlReviews);
    $stmt -> bind_param("i", $productId);
    $stmt -> execute();
    $stmt -> bind_result($title, $comment, $rating, $recommends, $created_at, $username);
    $stmt -> store_result();

    $reviews = [];
    while($stmt -> fetch()){
        $review = new stdClass();
        $review -> title = $title;
        $review -> comment = $comment;
        $review -> rating = $rating;
        $review -> recommends = $recommends;
        $review -> created_at = $created_at;
        $review -> username = $username;
        array_push($reviews, $review);
    }
    $stmt -> close();

    $stmt = $mysqli -> prepare($sqlProduct);
    $stmt -> bind_param("i", $productId);
    $stmt -> execute();
    $stmt -> bind_result($prod_id, $name, $description, $price, $vat, $balance, $rating, $ratings);
    $stmt -> store_result();
    
    $returnValue = new stdClass();
    if($stmt -> num_rows == 0){
        $returnValue = null;
    } else {
        $stmt -> fetch();
        $returnValue -> prod_id = $prod_id;
        $returnValue -> name = $name;
        $returnValue -> description = $description;
        $returnValue -> price = $price;
        $returnValue -> vat = $vat;
        $returnValue -> balance = $balance;
        $returnValue -> rating = empty($rating) ? 0 : round((float)$rating, 1);
        $returnValue -> ratings = $ratings;
        $returnValue -> reviews = $reviews;
    }

    $mysqli -> close();
    return $returnValue;
}
