<?php

require_once "../php/sql/db.conn.php";

$returnUrl = "/";
if(!empty($_GET["returnUrl"])){
    $returnUrl = $_GET["returnUrl"];
}

$mysqli = getDBConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){


    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        $sql = "SELECT email FROM USERS WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
            
            $stmt->bind_param("s", $param_email);
    
            $param_email = trim($_POST["email"]);
            
            if($stmt->execute()){
                $stmt->store_result();
    
                if($stmt->num_rows() >= 1){
                    $email_err = "This email is already taken.";
                    echo "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later. " . $mysqli->error;
                $email_err = "What the fuck";
            }
    
            $stmt->close();
        }
    
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter a valid phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter a address.";
    } else {
        $address = trim($_POST["address"]);
    }

    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($phone_err) && empty($name_err) && empty($address_err)){
        $sql = "INSERT INTO USERS (full_name, hashed_pwd, address, phone, email, role) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){

            $stmt->bind_param("sssssi", $param_fullname, $param_password, $param_address, $param_phone, $param_email, $param_role);

            $param_fullname = $name;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_address = $address;
            $param_phone = $phone;
            $param_email = $email;
            $param_role = 0;

            if($stmt->execute()){
                header("location: login.php?returnUrl=$returnUrl");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    $mysqli->close();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>

    <form action="?returnUrl=<?=$returnUrl?>" method="post">
    
    <label>Full name</label>
    <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">

    <label>Address</label>
    <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
    
    <label>Phone</label>
    <input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">

    <label>Email</label>
    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">

    <label>Password</label>
    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">

    <input type="submit" class="btn btn-primary" value="Submit">
    <input type="reset" class="btn btn-default" value="Reset">

    </form>
</body>
</html>