<?php

require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";
$user = new userSession(true, [2]);

$mysqli = getDBConnection();
 
$id = $_REQUEST['prodID'];

$_SESSION['id'] = $id;

if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}


$sql = "SELECT name, description, price, vat, balance FROM PRODUCTS WHERE prod_id=".$id;

$result = $mysqli->query($sql);

$row = $result->fetch_row();
?>

<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="addproduct.css">
<title>Delete Product</title>
</head>
<body>
<header>
    <h1>Admin - Delete Product</h1>
</header>    
<article class="left">
	<h3>Is this the product you want to delete?</h3>
<form>
    <p>
        <label for="Name">Name:</label>
        <input type="text" name="name" id="Name" class="override" value="<?php echo $row[0];?>">
    </p>
    <p>
        <label for="Description">Description:</label>
        <input type="text" name="description" id="Description" class="override" value="<?php echo $row[1];?>">
    </p>
    <p>
        <label for="Price">Price:</label>
        <input type="text" name="price" id="Price" class="override" value="<?php echo $row[2];?>">
    </p>
    <p>
        <label for="Vat">Vat:</label>
        <input type="text" name="vat" id="Vat" class="override" value="<?php echo $row[3];?>">
    </p>
    <p>
        <label for="Balance">Stock:</label>
        <input type="text" name="balance" id="Balance" class="override" value="<?php echo $row[4];?>">
    </p>
</form>
</article>

<article class="right">
	<h3>Confirmation</h3>
<form action="delete.php" method="post">
    <p>
        <label for="Confirmation">Please enter 'Yes' in the field below:</label>
        <input type="text" name="confirmation" id="Confirmation" class="override2">
    </p>
        <input type="submit" value="Submit">
</form>
</article>

</body>
</html>

<?php
 
$mysqli->close();

?>