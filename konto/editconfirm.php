<?php

require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";
$user = new userSession(true, [0,1]);
$mysqli = getDBConnection();


$uid = $_SESSION["uid"];


$sql = "SELECT full_name, address, phone, email FROM USERS WHERE uid =" . $uid;

$result = $mysqli->query($sql);

$row = $result->fetch_row();

$sql = "UPDATE USERS SET full_name = ?, address = ?, phone = ?, email = ? where uid = ?";

if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("ssssi", $full_name, $address, $phone, $email, $uid);

    
    if(empty(trim($_REQUEST["fullname"]))){
        $full_name = $row[0];
    } else{
        $full_name = trim($_REQUEST["fullname"]);
    }
    
    if(empty(trim($_REQUEST["address"]))){
        $address = $row[1];
    } else{
        $address = trim($_REQUEST["address"]);
    }
    
    if(empty(trim($_REQUEST["phone"]))){
        $phone = $row[2];
    } else{
        $phone = trim($_REQUEST["phone"]);
    }
    
    if(!empty(trim($_REQUEST["email"]))){
        $sql2 = "SELECT email FROM USERS WHERE email = ?";
    
        if($stmt2 = $mysqli->prepare($sql2)){
            
            $stmt2->bind_param("s", $param_email);
    
            $param_email = trim($_REQUEST["email"]);
            
            if($stmt2->execute()){
                $stmt2->store_result();
    
                if($stmt2->num_rows() >= 1){
                    $email_err = "This email is already taken.";
                    echo "This email is already taken.";
                    $email = $row[3];
                } else{
                    $email = trim($_REQUEST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later. " . $mysqli->error;
                $email_err = "What";
            }
    
            $stmt2->close();
        }
    } else {
        $email = $row[3];
    }

    $uid = $_SESSION['uid'];

    if($stmt->execute()){
        echo "Account information has been updated";
    } else{
        echo "Error, something went wrong";
    }
} $stmt->close();

$mysqli->close();
?>

<html>
<head>
    <title>Edit - Results</title>
</head>
<body>
    <article>
    <form action="/" method="post">
        <input type="submit" name="Submit" value="Return to Home">
    </form>
    </article>
</body>
</html>

