<?php

include "../php/debugSettings.php";

//role
require_once "../php/userSession.php";
$user = new userSession(true, [0,1,2]);

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");
 
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

//Query for insertion into ORDERS
$ordersQuery = "INSERT INTO ORDERS (uid, status, created_at, full_name, address, phone, email)
   VALUES (?, ?, NOW(), ?, ?, ?, ?)";

//Query for insertion into ORDER_ITEMS
$orderItemsQuery = "INSERT INTO ORDER_ITEMS (order_id, product_id, quantity, price, vat)
SELECT LAST_INSERT_ID() as order_id, CI.product_id, CI.quantity, P.price, P.vat FROM CART_ITEMS as CI
   LEFT JOIN PRODUCTS as P ON CI.product_id = P.prod_id
   WHERE CI.cart_id = (SELECT SC.cart_id FROM SHOPPING_CARTS as SC WHERE uid =?)";



//Begin the transaction
$mysqli->begin_transaction();



//ORDERS Query
if($stmt1 = $mysqli->prepare($ordersQuery)){
    $stmt1->bind_param("isssss", $uid, $defaultStatus, $fullName, $address, $phoneNumber, $email);
        
    $uid = $_SESSION["uid"];
    $defaultStatus = "Processing";
    $fullName = $_REQUEST["name"];
    $address = $_REQUEST["address"];
    $phoneNumber = $_REQUEST["phonenumber"];
    $email = $_REQUEST["email"];

        
    if($stmt1->execute()){
        echo "ORDERS query performed successfully.";

    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        echo "Rollback performed";
        $mysqli->rollback();
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    echo "Rollback performed";
    $mysqli->rollback();
}





//ORDER_ITEMS Query
if($stmt2 = $mysqli->prepare($orderItemsQuery)){
    $stmt2->bind_param("i", $uid);
        
    $uid = $_SESSION["uid"];
        
    if($stmt2->execute()){
        echo "ORDER_ITEMS query performed successfully";

    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        echo "Rollback performed";
        $mysqli->rollback();
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    echo "Rollback performed";
    $mysqli->rollback();
}

$stmt1->close();
$stmt2->close();



//All queries performed successfully. Committing
$mysqli->commit();



 
$mysqli->close();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="fix.css">
    <title>Edit - Results</title>
</head>
<body>
    <article>
    <form action="index.html" method="post">
        <input type="submit" name="Submit" value="Placeholder">
    </form>
    </article>
</body>
</html>