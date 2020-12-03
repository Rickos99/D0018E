<?php

require_once "db.conn.php";

function addProductToCart(int $uid, int $pid, int $quantity){
    if(empty($uid) || empty($pid || empty($quantity))){
        throw new InvalidArgumentException("Parameters cannot be empty");
    }
    if($quantity < 1){
        throw new OutOfRangeException('Parameter $quantity cannot be empty and must not be less than 1');
    }
    $mysqli = getDbConnection();
    
    $sql = "SELECT cart_id FROM `STORE`.`SHOPPING_CARTS` WHERE uid = ?";
    $stmtGetCart = $mysqli -> prepare($sql);
    $stmtGetCart -> bind_param("i", $uid);
    $stmtGetCart -> execute();
    $stmtGetCart -> store_result();
    $stmtGetCart -> bind_result($cartId);
    $stmtGetCart -> fetch();

    if($stmtGetCart -> num_rows == 0){
        $dateTimeNow = date("Y-m-d h:i:s");

        $sql = "INSERT INTO `STORE`.`SHOPPING_CARTS` (uid, changed_at) VALUES (?, ?)";
        $stmtCreateCart = $mysqli -> prepare($sql) or die("(".__LINE__ .") Error: " . $mysqli -> error);
        $stmtCreateCart -> bind_param("is", $uid, $dateTimeNow);
        $stmtCreateCart -> execute() or die("ERROR: Your cart failed to be created!");
        $stmtCreateCart -> free_result();
        $mysqli->close();

        return addProductToCart($uid, $pid, $quantity);
    }

    $sql = "SELECT quantity FROM `STORE`.`CART_ITEMS` WHERE cart_id = ? AND product_id = ?";
    $stmtGetProduct = $mysqli -> prepare($sql);
    $stmtGetProduct -> bind_param("ii", $cartId, $pid);
    $stmtGetProduct -> execute();
    $stmtGetProduct -> store_result();

    if($stmtGetProduct -> num_rows == 0){
        $sql = "INSERT INTO CART_ITEMS (quantity, product_id, cart_id) VALUES (?, ?, ?)";
    } else {
        $sql = "UPDATE `STORE`.`CART_ITEMS` SET quantity = ? WHERE product_id = ? AND cart_id = ?";
    }
    $stmtChangeProductQuantity = $mysqli -> prepare($sql);
    $stmtChangeProductQuantity -> bind_param("iii", $quantity, $pid, $cartId);
    $stmtChangeProductQuantity -> execute();
    $stmtChangeProductQuantity -> close();

    $sql = "UPDATE `STORE`.`SHOPPING_CARTS` SET `changed_at` = (SELECT NOW()) WHERE `cart_id` = ?";
    $stmtUpdateCart = $mysqli -> prepare($sql);
    $stmtUpdateCart -> bind_param("i", $cartId);
    $stmtUpdateCart -> execute();
    $stmtUpdateCart -> close();

    $mysqli -> close();
}