<?php

include "../php/debugSettings.php";

//role
require_once "../php/userSession.php";
$user = new userSession(true, [0,1,2]);

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");
 
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
$updateQuery = "UPDATE PRODUCTS SET balance=? WHERE prod_id=?";

//Delete from CART_ITEMS
$deleteCartItemsQuery = "DELETE FROM CART_ITEMS WHERE cart_id = (SELECT cart_id FROM SHOPPING_CARTS WHERE uid = ?)";

//Delete from SHOPPING_CARTS
$deleteCartQuery = "DELETE FROM SHOPPING_CARTS WHERE uid = ?";


//Begin the transaction
$mysqli->begin_transaction();


//SUPER QUERY

//Retrieving an array of the product id's
$piData = [];
$pi = mysqli_query($mysqli, $retrieveID);

if($pi){
    while ($row1 = $pi->fetch_array()) {
        $piData[] = $row1;
    }
}else{
    echo "Retrieval of product id's failed";
    echo "Rollback performed";
    mysqli->rollback();
}

//Retreiving an array of the quantities
$QData = [];
$Q = mysqli_query($mysqli, $retrieveQuantity);

if($Q){
    while ($row2 = $Q->fetch_array()) {
        $QData[] = $row2;
    }
}else {
    echo "Retrieval of quantities failed";
    echo "Rollback performed";
    mysqli->rollback();
}

//Retrieving an array of cart quantities
$cartData = [];
$cart = mysqli_query($mysqli, $retrieveCartQuantity);

if ($cart) {
    while ($row3 = $cart->fetch_array()) {
        $cartData[] = $row3;
    }
}else {
    echo "Retrieval of cart quantities failed";
    echo "Rollback performed";
    mysqli->rollback();
}

//Safety check
$piLength = count($pidata);
$qLength = count($QData);

if($piLength != $qLength){
    echo "Product id and quantity not the same length";
    echo "Rollback performed";
    mysqli->rollback();
} 

//Checking that all items are in stock
$i = 0;
while ($i < $piLength) {
    if ($QData[$i] < $cartData[$i]) {
        echo "Not enough items in stock";
        echo "Rollback performed";
        mysqli->rollback();
    }
    $i = $i + 1;
}


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
        echo "ORDER_ITEMS query performed successfully\r\n";

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

//QUERY FOR updating stock quantity. NEEDS REVIEW!!!
$j = 0;
$newQuantity = 0;
while ($j < $piLength) {

    $newQuantity = $QData[$j] - $cartData[$j];

    if($stmt3 = $mysqli->prepare($updateQuery)){
        $stmt3->bind_param("ii", $quant, $pid);
            
        $quant = $newQuantity;
        $pid = $piData[$j];
            
        if($stmt3->execute()){
            echo "ORDER_ITEMS query ".$j." performed successfully\r\n"; //For debugging, mostly
            $j = $j + 1;

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
}

//Query for deleting cart items
if($stmt4 = $mysqli->prepare($deleteCartItemsQuery)){
    $stmt4->bind_param("i", $uid);
        
    $uid = $_SESSION["uid"];
        
    if($stmt4->execute()){
        echo "CART_ITEMS query performed successfully\r\n";

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

//Query for deleting shopping cart
if($stmt5 = $mysqli->prepare($deleteCartQuery)){
    $stmt5->bind_param("i", $uid);
        
    $uid = $_SESSION["uid"];
        
    if($stmt5->execute()){
        echo "SHOPPING_CARTS query performed successfully\r\n";

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
$stmt3->close();
$stmt4->close();
$stmt5->close();


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
    <article>
    <form action="http://130.240.200.54" method="post">
        <input type="submit" name="Submit" value="Return to Home">
    </form>
    </article>
</body>
</html>