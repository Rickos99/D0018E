<?php

require_once "../php/userSession.php";
$user = new userSession(true, [2]);

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");
 
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 
$sql = "INSERT INTO PRODUCTS (name, description, price, vat, balance) VALUES (?, ?, ?, ?, ?)";
 
if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("ssddi", $name, $description, $price, $vat, $balance);
    
    $name = $_REQUEST['name'];
    $description = $_REQUEST['description'];
    $price = $_REQUEST['price'];
    $vat = $_REQUEST['vat'];
    $balance = $_REQUEST['balance'];
    
    if($stmt->execute()){
        echo "Records inserted successfully.";
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
    <title>Insert - Results</title>
</head>
<link rel="stylesheet" type="text/css" href="addproduct.css">
<body>
    <article>
    <form action="index.html" method="post">
        <input type="submit" name="Submit" value="Return to Admin - Home">
    </form>
    </article>
</body>
</html>