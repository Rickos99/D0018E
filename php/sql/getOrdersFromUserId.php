<?php

require_once "db.conn.php";

function getOrdersFromUserId(int $uid) : array {
    $mysqli = getDBConnection();

    $sql = "SELECT O.order_id, O.status, O.created_at, O.address, O.email, O.full_name, O.phone, sum(OI.price*(1 + OI.vat/100)*OI.quantity) FROM STORE.ORDERS as O 
               INNER JOIN STORE.ORDER_ITEMS as OI ON O.order_id = OI.order_id
               WHERE uid = ?
               GROUP BY O.order_id";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($orderId, $status, $created_at, $address, $email, $full_name, $phone, $totalPriceWithVat);
    
    $orders = [];
    while($stmt->fetch()){
        $order = new stdClass();
        $order->email = $email;
        $order->phone = $phone;
        $order->status = $status;
        $order->address = $address;
        $order->order_id = $orderId;
        $order->full_name = $full_name;
        $order->created_at = $created_at;
        $order->totalPriceWithVat = round($totalPriceWithVat, 2);

        $sql = "SELECT P.name, OI.product_id, OI.quantity, OI.price*(1 + OI.vat/100) as unitPrice, OI.discount FROM STORE.ORDER_ITEMS AS OI 
                   INNER JOIN STORE.PRODUCTS as P ON OI.product_id = P.prod_id
                   WHERE order_id = $orderId";

        $mysqli->real_query($sql);
        $resultSet = $mysqli->use_result();
        $products = [];
        while($row = $resultSet->fetch_object()){
            $product = new stdClass();
            $product->name = $row->name;
            $product->product_id = $row->product_id;
            $product->quantity = $row->quantity;
            $product->unitPrice = round($row->unitPrice, 2);
            $product->discount = $row->discount;
            $product->total = round($row->unitPrice*$row->quantity, 2);
            array_push($products, $product);
        }
        $order->products = $products;
        array_push($orders, $order);
        $resultSet->free_result();
    }

    $stmt->close();
    $mysqli->close();
    return $orders;
}
