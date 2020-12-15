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

<a href="editaccount.php">
<article class="leftbox">
	<div class="center">
		Edit account information
	</div>
</article>
</a>

<a href="editpassword.php">
<article class="midbox">
	<div class="center">
		Edit password
	</div>	
</article>
</a>

</body>
</html>