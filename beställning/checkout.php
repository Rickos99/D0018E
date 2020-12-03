<?php

//Check role
require_once "../php/userSession.php";
$user = new userSession(true, [0,1,2]);

//Get Session variables
$uid = $_SESSION["uid"];

echo $uid;
//Query for SHOPPING_CARTS
$a = "SELECT cart_id FROM SHOPPING_CARTS WHERE uid=".$uid;

//Query for CART_ITEMS
$b = "SELECT product_id FROM CART_ITEMS WHERE cart_id=(".$a.")";

//Query for Quantity
$c = "SELECT quantity FROM CART_ITEMS WHERE cart_id=(".$a.")";

//Query for PRODUCTS
$d = "SELECT name, description, price WHERE prod_id=(".$b.")";

echo $d;

/*
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    $values[] = array(
        'name' => $row['name'],
        'address' => $row['address'],
        'content' => $row['content']
    );
}
*/

?>
<html>
<head>
	<title>BESTTEST</title>
	<link rel="stylesheet" type="text/css" href="order.css">
</head>
<body>
<header>
	<h1>Checkout</h1>
</header>
<article class="left">
	<h2>Your Cart</h2>
<table style="width:100%">
  <tr>
    <th>Product name</th>
    <th>Price</th>
    <th>Quantity</th>
  </tr>
  <tr>
    <td>Tårta</td>
    <td>50</td>
    <td>2</td>
  </tr>
  <tr>
    <td>Hörlurar</td>
    <td>1000</td>
    <td>1</td>
  </tr>
</table> 
</article>

<article class="right">
	<h2>Shipping information</h2>
<form action="edit.php" method="post">
    <p>
        <label for="Name">Name:</label>
        <input type="text" name="name" id="Name" class="override2">
    </p>
    <p>
        <label for="Address">Address:</label>
        <input type="text" name="address" id="Address" class="override2">
    </p>
    <p>
        <label for="Phonenumber">Phone number:</label>
        <input type="text" name="phonenumber" id="Phonenumber" class="override2">
    </p>
    <p>
        <label for="Email">Email:</label>
        <input type="text" name="email" id="Email" class="override2">
    </p>
        <input type="submit" value="Place Order">
</form>
</article>
	
</body>
</html>