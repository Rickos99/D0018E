<?php

require_once "db.conn.php";

function getAllProducts(int $uid = -1) {
    $sql = "SELECT P.prod_id, P.name, P.description, P.price, P.vat, P.balance, AVG(R.rating) as rating, COUNT(R.rating) as ratings, (SELECT W.uid IS NOT NULL) as onWishlist FROM PRODUCTS as P
                LEFT JOIN REVIEWS as R ON P.prod_id = R.product_id
                LEFT JOIN WISHLIST_ITEMS as W ON P.prod_id = W.pid and W.uid = $uid
                GROUP BY P.prod_id";
    
    $mysqli = getDBConnection();
    $returnValue = array();
    if ($result = $mysqli->query($sql)) {
        while ($obj = $result->fetch_object()) {
            $obj->onWishlist = boolval($obj->onWishlist);
            $obj->priceInclVat = (1 + $obj->vat/100) * $obj->price;
            $obj->rating = empty($obj->rating) ? 0 : round((float)$obj->rating, 1);

            array_push($returnValue, $obj);
        }
        $result -> free_result();
    } else {
        throw new Error($mysqli->error, $mysqli->errno);
    }
    $mysqli->close();
    return $returnValue;
}
