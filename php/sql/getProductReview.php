<?php

require_once "db.conn.php";

function getProductReview(int $uid, int $pid) : object {
    $mysqli = getDBConnection();
    $sql = "SELECT R.title, R.comment, R.rating, R.recommends FROM REVIEWS as R WHERE R.uid = ? and R.product_id = ?";
    $stmt = $mysqli -> prepare($sql);
    $stmt -> bind_param("ii", $uid, $pid);
    $stmt -> execute();
    $stmt -> bind_result($title, $comment, $rating, $recommends);
    $stmt -> store_result();
    
    $returnValue = new stdClass();
    if($stmt -> num_rows > 0) {
        $stmt -> fetch();
        $returnValue -> title = $title;
        $returnValue -> comment = $comment;
        $returnValue -> rating = $rating;
        $returnValue -> recommends = $recommends === 0 ? false : true;
    }
    $stmt->close();
    $mysqli->close();

    return $returnValue;
}