<?php

require_once "../php/userSession.php";
$user = new userSession(true, [2]);

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");
 
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
<title>Edit Product</title>
</head>
<body>
<header>
    <h1>Admin - Edit Product</h1>
</header>    
<article class="left">
	<h3>Old values</h3>
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

<article class="mid">

<div class="hack0">
	<br>
&#129030;
</div>

<div class="hack1">
	<br>
&#129030;
</div>

<div class="hack2">
	<br>
&#129030;
</div>

<div class="hack3">
	<br>
&#129030;
</div>

<div class="hack4">
	<br>
&#129030;
</div>
	
</article>

<article class="right">
	<h3>New values</h3>
<form action="edit.php" method="post">
    <p>
        <label for="Name">Name:</label>
        <input type="text" name="name" id="Name" class="override2">
    </p>
    <p>
        <label for="Description">Description:</label>
        <input type="text" name="description" id="Description" class="override2">
    </p>
    <p>
        <label for="Price">Price:</label>
        <input type="text" name="price" id="Price" class="override2">
    </p>
    <p>
        <label for="Vat">Vat:</label>
        <input type="text" name="vat" id="Vat" class="override2">
    </p>
    <p>
        <label for="Balance">Stock:</label>
        <input type="text" name="balance" id="Balance" class="override2">
    </p>
        <input type="submit" value="Submit">
</form>
</article>

</body>
</html>

<?php
 
$mysqli->close();

?>