<?php

require_once "db.conn.php";

function deleteProductFromCart(int $uid, int $pid){
    $mysqli = getDbConnection();
    
    $sql = "SELECT cart_id FROM `STORE`.`SHOPPING_CARTS` WHERE uid = ?";
    $stmtGetCart = $mysqli -> prepare($sql);
    $stmtGetCart -> bind_param("i", $uid);
    $stmtGetCart -> execute();
    $stmtGetCart -> store_result();
    $stmtGetCart -> bind_result($cartId);
    $stmtGetCart -> fetch();

    $sql = "DELETE FROM `STORE`.`CART_ITEMS` WHERE cart_id = ? AND product_id = ?";
    $stmtDeleteCartItem = $mysqli -> prepare($sql);
    $stmtDeleteCartItem -> bind_param("ii", $cartId, $pid);
    $stmtDeleteCartItem -> execute();
    $stmtDeleteCartItem -> close();

    $mysqli -> close();
}