<?php

include "../php/debugSettings.php";
require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";

//role
$user = new userSession(true, [0,1,2]);

$mysqli = getDBConnection();
 
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

$uid = $_SESSION["uid"];

//Query for insertion into ORDERS
$ordersQuery = "INSERT INTO ORDERS (uid, status, created_at, full_name, address, phone, email)
   VALUES (?, ?, NOW(), ?, ?, ?, ?)";

//Query for insertion into ORDER_ITEMS
$orderItemsQuery = "INSERT INTO ORDER_ITEMS (order_id, product_id, quantity, price, vat)
SELECT LAST_INSERT_ID() as order_id, CI.product_id, CI.quantity, P.price, P.vat FROM CART_ITEMS as CI
   LEFT JOIN PRODUCTS as P ON CI.product_id = P.prod_id
   WHERE CI.cart_id = (SELECT SC.cart_id FROM SHOPPING_CARTS as SC WHERE uid =?)";

//Query for retrieval of data required for changing quantity. 
$a = "SELECT cart_id FROM SHOPPING_CARTS WHERE uid=".$uid;
$b = "FROM PRODUCTS INNER JOIN CART_ITEMS ON PRODUCTS.prod_id=CART_ITEMS.product_id WHERE cart_id=(".$a.")";
$retrieveID = "SELECT PRODUCTS.prod_id ".$b;
$retrieveQuantity = "SELECT PRODUCTS.balance ".$b;
$retrieveCartQuantity = "SELECT CART_ITEMS.quantity ".$b;


//Update Quantity Query
$updateQuery = "UPDATE PRODUCTS as B 
    RIGHT JOIN CART_ITEMS as CI ON B.prod_id = CI.product_id
    SET B.balance = B.balance - CI.quantity
    WHERE CI.cart_id = (SELECT cart_id FROM SHOPPING_CARTS WHERE uid = ?)";

//Delete from CART_ITEMS
$deleteCartItemsQuery = "DELETE FROM CART_ITEMS WHERE cart_id = (SELECT cart_id FROM SHOPPING_CARTS WHERE uid = ?)";

//Delete from SHOPPING_CARTS
$deleteCartQuery = "DELETE FROM SHOPPING_CARTS WHERE uid = ?";

$rollbackCheck= 0;

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
        echo "ORDERS query performed successfully.\r\n";

    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        $rollbackCheck = 1;
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    $rollbackCheck = 1;
}


//ORDER_ITEMS Query
if($stmt2 = $mysqli->prepare($orderItemsQuery)){
    $stmt2->bind_param("i", $uid);
        
    $uid = $_SESSION["uid"];
        
    if($stmt2->execute()){
        echo "ORDER_ITEMS query performed successfully\r\n";

    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        $rollbackCheck = 1;
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    $rollbackCheck = 1;
}

//QUERY FOR updating stock quantity.
if($stmt3 = $mysqli->prepare($updateQuery)){
    $stmt3->bind_param("i", $uid);
            
    $uid = $_SESSION['uid'];
        
    if($stmt3->execute()){
        echo "Stock quantity query performed successfully\r\n";

    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        $rollbackCheck = 1;
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    $rollbackCheck = 1;
}

//Query for deleting cart items
if($stmt4 = $mysqli->prepare($deleteCartItemsQuery)){
    $stmt4->bind_param("i", $uid);
        
    $uid = $_SESSION["uid"];
        
    if($stmt4->execute()){
        echo "CART_ITEMS query performed successfully\r\n";

    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        $rollbackCheck = 1;
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    $rollbackCheck = 1;
}

//Query for deleting shopping cart
if($stmt5 = $mysqli->prepare($deleteCartQuery)){
    $stmt5->bind_param("i", $uid);
        
    $uid = $_SESSION["uid"];
        
    if($stmt5->execute()){
        echo "SHOPPING_CARTS query performed successfully\r\n";

    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        $rollbackCheck = 1;
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    $rollbackCheck = 1;
}



$stmt1->close();
$stmt2->close();
$stmt3->close();
$stmt4->close();
$stmt5->close();

//Check if rollback will be performed
if ($rollbackCheck == 1) {
    echo "Rollback performed";
    $mysqli->rollback();
}


//All queries performed successfully. Committing
$mysqli->commit();

 
$mysqli->close();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="fix.css">
    <title>ORDER - Results</title>
</head>
<body>
    <div class="container">
        <a href="/" class="button-green">Return to home</a>
    </div>
</body>
</html>