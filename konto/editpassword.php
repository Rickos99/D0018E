<?php

require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";
$user = new userSession(true, [0]);
$mysqli = getDBConnection();
$uid = $_SESSION["uid"];



if($_SERVER["REQUEST_METHOD"] == "POST"){

    $oldpass = trim($_POST["oldpass"]);
    $newpass = trim($_POST["newpass"]);
    $check = "False";

    if(!empty($oldpass) && !empty($newpass)){
        $sql = "SELECT hashed_pwd FROM USERS WHERE uid = ?";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $uid);

            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    
                    $stmt->bind_result($hashed_pwd);
                    if($stmt->fetch()){
                        if(password_verify($oldpass, $hashed_pwd)){
                            $newhashpass = password_hash($newpass, PASSWORD_DEFAULT);
                            $check = "True";
                        } else{
                            echo "Wrong password";
                        }
                    }

                }
            }
        } $stmt->close();

        if($check == "True"){
            $sql = "UPDATE USERS SET hashed_pwd = ? WHERE uid = " . $uid;
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("s", $newhashpass);
                if($stmt->execute()){
                    echo "Password successfully changed";
                    header("location: index.php");
                } else{
                    echo "Error, password not changed";
                }
            }
        }
    } else{
        echo "Please enter both fields";
    }
}

?>

<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="addproduct.css">
<title>Edit Password</title>
</head>
<body>
<header>
    <h1>Konto - Edit Password</h1>
</header>

<form method="post">
    <p>
        <label for="OldPass">Old password:</label>
        <input type="text" name="oldpass" id="OldPass" class="override2">
    </p>
    <p>
        <label for="NewPass">New password:</label>
        <input type="text" name="newpass" id="NewPass" class="override2">
    </p>
        <input type="submit" value="Submit">
</form>