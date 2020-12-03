<?php

require_once "../php/userSession.php";
$user = new userSession(true, [2]);

?>

<!DOCTYPE html>
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
<article>
<form action="predelete.php" method="post">
    <p>
        <label for="ProdID">Please input the Product ID:</label>
        <input type="text" name="prodID" id="ProdID">
    </p>
    <input type="submit" value="Continue">
</form>
</article>
</body>
</html>