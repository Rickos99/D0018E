<?php

require_once "../php/userSession.php";
$user = new userSession(true, [2]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="addproduct.css">
<title>Add Product</title>
</head>
<body>
<header>
    <h1>Admin - Add Product</h1>
</header>    
<article>
<form action="insert.php" method="post">
    <p>
        <label for="Name">Name:</label>
        <input type="text" name="name" id="Name">
    </p>
    <p>
        <label for="Description">Description:</label>
        <input type="text" name="description" id="Description">
    </p>
    <p>
        <label for="Price">Price:</label>
        <input type="text" name="price" id="Price">
    </p>
    <p>
        <label for="Vat">Vat:</label>
        <input type="text" name="vat" id="Vat">
    </p>
    <p>
        <label for="Balance">Stock:</label>
        <input type="text" name="balance" id="Balance">
    </p>
    <input type="submit" value="Submit">
</form>
</article>
</body>
</html>