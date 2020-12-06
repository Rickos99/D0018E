<?php

require_once "db.conn.php";

function getAllProducts() {
    $mysqli = getDBConnection();
    $sql = "SELECT P.prod_id, P.name, P.description, P.price, P.vat, P.balance, AVG(R.rating) as rating, COUNT(R.rating) as ratings FROM PRODUCTS as P
              LEFT JOIN REVIEWS as R ON P.prod_id = R.product_id
              GROUP BY P.prod_id";
    
    $returnValue = array();
    if ($result = $mysqli -> query($sql)) {
        while ($obj = $result -> fetch_object()) {
            $obj -> priceInclVat = (1 + $obj->vat/100) * $obj->price;
            $obj -> rating = empty($obj->rating) ? 0 : round((float)$obj->rating, 1);

            array_push($returnValue, $obj);
        }
        $result -> free_result();
    } else {
        echo "Error: Unable to execute query"; ;
        echo "Debugging errno: " . $mysqli->errno . PHP_EOL;
        echo "Debugging error: " . $mysqli->error . PHP_EOL;
    }
    $mysqli -> close();
    return $returnValue;
}
