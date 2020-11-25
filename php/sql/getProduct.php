<?php

require_once "db.conn.php";

function getProduct(int $productId) {
    $mysqli = getDBConnection();
    $sql = "SELECT prod_id, name, description, price, vat, balance FROM PRODUCTS WHERE prod_id = ?";

    $returnValue = new stdClass();
    if ($stmt = $mysqli -> prepare($sql)) {

        $stmt -> bind_param("i", $productId);
        $stmt -> execute();
        $stmt -> bind_result($prod_id, $name, $description, $price, $vat, $balance);
        $stmt -> fetch();

        $returnValue -> prod_id = $prod_id;
        $returnValue -> name = $name;
        $returnValue -> description = $description;
        $returnValue -> price = $price;
        $returnValue -> vat = $vat;
        $returnValue -> balance = $balance;
        
    }

    $mysqli -> close();
    return $returnValue;
}