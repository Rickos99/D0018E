<?php

require_once "db.conn.php";

function getWishlist(int $uid) : array {
    $mysqli = getDBConnection();

    $sql = "SELECT W.pid, P.name, P.price, P.vat, P.balance FROM WISHLIST_ITEMS as W
	           LEFT JOIN PRODUCTS as P ON W.pid = P.prod_id
               WHERE W.uid=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pid, $product_name, $price, $vat, $balance);
    
    $wishlist = array();
    while($stmt->fetch()){
        $item = new stdClass();
        $item->pid = $pid;
        $item->vat = $vat;
        $item->balance = $balance;
        $item->product_name = $product_name;
        $item->priceWithVat = (1 + $vat/100) * $price;
        
        array_push($wishlist, $item);
    }

    $stmt->close();
    $mysqli->close();
    return $wishlist;
}