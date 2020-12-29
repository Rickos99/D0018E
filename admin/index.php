<?php

require_once "../php/userSession.php";
$user = new userSession(true, [2]);

?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="adminhome.css">
<head>
<meta charset="utf-8">
<title>Admin - Home Page</title>
</head>
<body>
<header>
	<h1>Admin - Home</h1>
</header>

<a href="/admin/addproduct.php">
<article class="leftbox">
	<div class="center">
		Add a Product
	</div>
</article>
</a>

<a href="/admin/editproduct.php">
<article class="midbox">
	<div class="center">
		Edit a Product
	</div>	
</article>
</a>

<a href="/admin/deleteproduct.php">
<article class="rightbox">
	<div class="center">
		Remove a Product
	</div>	
</article>
</a>

</body>
</html>