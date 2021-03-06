<?php

require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";
$user = new userSession(true, [2]);

$mysqli = getDBConnection();
 
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 
$sql = "DELETE FROM PRODUCTS WHERE prod_id=?";

$confirm = $_REQUEST['confirmation'];

if($confirm == 'Yes'){ 
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $id);
        
        $id = $_SESSION['id'];
        
        if($stmt->execute()){
            echo "Entry successfully deleted";
        } else{
            echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
        }
    } else{
        echo "ERROR: Could not prepare query: $sql. " . $mysqli->error;
    }
} else{
    echo "No confirmation, delete request canceled.";
}    

 
$stmt->close();
 
$mysqli->close();
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="addproduct.css">
    <title>Delete - Results</title>
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