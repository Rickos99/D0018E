<?php

//Check role
require_once "../php/userSession.php";
$user = new userSession(true, [0,1,2]);

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");

//Get Session variables
$uid = $_SESSION["uid"];

//Query for SHOPPING_CARTS
$a = "SELECT cart_id FROM SHOPPING_CARTS WHERE uid=".$uid;

/* OLDER CODE, Kept for reference
//Query for CART_ITEMS
$b = "SELECT product_id FROM CART_ITEMS WHERE cart_id=(".$a.")";

//Query for Quantity
$quantityQuery = "SELECT quantity FROM CART_ITEMS WHERE cart_id=(".$a.")";

//Query for PRODUCTS
$productQuery = "SELECT name, price WHERE prod_id=(".$b.")";
*/

//Final Query for Getting contents of cart
$finalQuery = "SELECT PRODUCTS.name, PRODUCTS.price, CART_ITEMS.quantity FROM PRODUCTS INNER JOIN CART_ITEMS ON PRODUCTS.prod_id=CART_ITEMS.product_id WHERE cart_id=(".$a.")";

$r = mysqli_query($mysqli, $finalQuery);

while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    $values[] = array(
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $row['quantity']
    );
}


//Query for user information
$userQuery = "SELECT full_name, address, phone, email FROM USERS WHERE uid=".$uid;

$s = mysqli_query($mysqli, $userQuery);

$userRow = $s->fetch_row();



?>
<html>
<head>
	<title>BESTTEST</title>
	<link rel="stylesheet" type="text/css" href="order.css">
    <meta charset="UTF-8">
</head>
<body>
<header>
	<h1>Checkout</h1>
</header>
<article class="left">
	<h2>Your Cart</h2>
<table style="width:100%">
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>
<?php
foreach($values as $v){
    echo '
    <tr>
        <td>'.$v['name'].'</td>
        <td>'.$v['price'].'</td>
        <td>'.$v['quantity'].'</td>
    </tr>
    ';
}
?>
</table> 
</article>

<article class="right">
	<h2>Shipping information - Please Review</h2>
<form action="order.php" method="post">
    <p>
        <label for="Name">Name:</label>
        <input type="text" name="name" id="Name" class="override2" value="<?php echo $userRow[0];?>">
    </p>
    <p>
        <label for="Address">Address:</label>
        <input type="text" name="address" id="Address" class="override2" value="<?php echo $userRow[1];?>">
    </p>
    <p>
        <label for="Phonenumber">Phone number:</label>
        <input type="text" name="phonenumber" id="Phonenumber" class="override2" value="<?php echo $userRow[2];?>">
    </p>
    <p>
        <label for="Email">Email:</label>
        <input type="text" name="email" id="Email" class="override2" value="<?php echo $userRow[3];?>">
    </p>
        <input type="submit" value="Place Order">
</form>
</article>
	
</body>
</html>