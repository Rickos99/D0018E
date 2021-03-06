<?php

require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";
$user = new userSession(true, [2]);

$mysqli = getDBConnection();
 
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 
$sql = "UPDATE PRODUCTS SET name=?, description=?, price=?, vat=?, balance=? WHERE prod_id=?";
 
if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("ssddii", $name, $description, $price, $vat, $balance, $id);
    
    $name = $_REQUEST['name'];
    $description = $_REQUEST['description'];
    $price = $_REQUEST['price'];
    $vat = $_REQUEST['vat'];
    $balance = $_REQUEST['balance'];
    $id = $_SESSION['id'];
    
    if($stmt->execute()){
        echo "Records changed successfully.";
    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
}
 
$stmt->close();
 
$mysqli->close();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="addproduct.css">
    <title>Edit - Results</title>
</head>
<link rel="stylesheet" type="text/css" href="addproduct.css">
<body>
    <article>
    <form action="index.php" method="post">
        <input type="submit" name="Submit" value="Return to Admin - Home">
    </form>
    </article>
</body>
</html>