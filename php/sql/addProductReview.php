<?php

require_once "db.conn.php";

function addProductReview(object $review) : bool {
    $mysqli = getDBConnection();
    $sqlGetReview = "SELECT * FROM REVIEWS WHERE uid=? and product_id=?";
    $stmt = $mysqli -> prepare($sqlGetReview);
    $stmt -> bind_param("ii", $review->uid, $review->pid);
    if(!$stmt -> execute()){
        return false;
    }
    $stmt -> store_result();
    
    $sql;
    if($stmt -> num_rows === 0){
        $sql = "INSERT INTO REVIEWS(title, comment, rating, recommends, uid, product_id, created_at)
                   VALUES (?, ?, ?, ?, ?, ?, NOW())";
    } else {
        $sql = "UPDATE REVIEWS SET title=?, comment=?, rating=?, recommends=?
                   WHERE uid=? and product_id=?";
                   die("UPDATE");
    }
    $stmt -> close();

    $stmt = $mysqli -> prepare($sql);
    $stmt -> bind_param("ssiiii", $review->title, $review->comment, $review->rating, 
                        $review->recommends, $review->uid, $review->pid);
    if(!$stmt -> execute()){
        die("Error: ".$mysqli->error);
        return false;
    }
    $stmt -> close();
    $mysqli -> close();

    return true;
}