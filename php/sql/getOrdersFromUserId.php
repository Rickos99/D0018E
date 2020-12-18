<?php

require_once "db.conn.php";

function getOrdersFromUserId(int $uid) : array {
    $mysqli = getDBConnection();

    $sql = "SELECT O.order_id, O.status, O.created_at, sum(OI.price*(1 + OI.vat/100)*OI.quantity) FROM STORE.ORDERS as O 
               INNER JOIN STORE.ORDER_ITEMS as OI ON O.order_id = OI.order_id
               WHERE uid = ?
               GROUP BY O.order_id;";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($orderId, $status, $created_at, $totalPriceWithVat);
    
    $orders = [];
    while($stmt->fetch()){
        $order = new stdClass();
        $order->status = $status;
        $order->order_id = $orderId;
        $order->created_at = $created_at;
        $order->totalPriceWithVat = $totalPriceWithVat;
        array_push($orders, $order);
    }

    $stmt->close();
    $mysqli->close();
    return $orders;
}
