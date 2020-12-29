<?php
require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";
$user = new userSession(true, [0]);
$mysqli = getDBConnection();
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="adminhome.css">
<head>
<meta charset="utf-8">
<title>Konto - Home Page</title>
</head>
<body>
<header>
	<h1>Konto - Home</h1>
</header>

<main>
	<a href="editaccount.php" class="leftbox center">Edit account information</a>
	<a href="editpassword.php" class="leftbox center">Edit password</a>
	<a href="orders.php" class="leftbox center">Previous Orders</a>
	<a href="wishlist.php" class="leftbox center">My Wishlist</a>
</main>

</body>
</html>