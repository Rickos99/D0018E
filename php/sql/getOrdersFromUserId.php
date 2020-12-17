<?php

require_once "db.conn.php";

function getOrdersFromUserId(int $uid) : array {
    $mysqli = getDBConnection();

    $sql = "SELECT order_id FROM STORE.ORDERS WHERE uid = ?;";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($orderId);
    
    $orders = [];
    while($stmt->fetch()){
        array_push($orders, $orderId);
    }

    $stmt->close();
    $mysqli->close();
    return $orders;
}
