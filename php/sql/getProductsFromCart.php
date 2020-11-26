<?php

require_once "db.conn.php";

function getProductsFromCart(int $uid) : array {
    $mysqli = getDbConnection();
    
    $sql = "SELECT cart_id FROM `STORE`.`SHOPPING_CARTS` WHERE uid = ?";
    $stmtGetCart = $mysqli -> prepare($sql);
    $stmtGetCart -> bind_param("i", $uid);
    $stmtGetCart -> execute();
    $stmtGetCart -> store_result();
    $stmtGetCart -> bind_result($cartId);
    $stmtGetCart -> fetch();

    $sql = "SELECT CART_ITEMS.product_id, CART_ITEMS.quantity, PRODUCTS.name, PRODUCTS.price, PRODUCTS.vat FROM `STORE`.`CART_ITEMS`
              LEFT JOIN `STORE`.`PRODUCTS` ON `CART_ITEMS`.`product_id` = `PRODUCTS`.`prod_id`
              WHERE cart_id = ?";
    $stmtGetItems = $mysqli -> prepare($sql);
    $stmtGetItems -> bind_param("i", $cartId);
    $stmtGetItems -> execute();
    $stmtGetItems -> store_result();
    $stmtGetItems -> bind_result($pid, $quantity, $name, $price, $vat);
    
    $productsInCart = array();
    while($stmtGetItems -> fetch()){
        $product = new stdClass();
        $product -> pid = $pid;
        $product -> quantity = $quantity;
        $product -> name = $name;
        $product -> price = $price;
        $product -> vat = $vat;
        array_push($productsInCart, $product);
    }
    
    $stmtGetItems -> close();
    $mysqli -> close();
    
    return $productsInCart;
}