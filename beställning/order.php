<?php

session_start();

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");
 
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 
$uid = $_SESSION["uid"];

$orderQuery = "INSERT INTO ORDERS (uid, status, created_at) VALUES (?, ?, ?)";

 
if($stmt1 = $mysqli->prepare($orderQuery)){
    $stmt1->bind_param("iss", $id, $status, $created_at);
    
    $id = $uid;
    $status = "Processing";
    $created_at = date('Y/m/d H:i:s');
    
    if($stmt1->execute()){
        echo "Order added successfully.";
    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
    }
} else{
    echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
}
 
$stmt1->close();

$itemsQuery = "
INSERT INTO ORDER_ITEMS ()"
 
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
    <form action="index.html" method="post">
        <input type="submit" name="Submit" value="Return to Admin - Home">
    </form>
    </article>
</body>
</html>