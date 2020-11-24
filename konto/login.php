<?php
 
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin" === true]){
    header("location: ../demo/must");
    exit;
}

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");

$email = $password = "";
$email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($email_err) && empty($password_err)){
        $sql = "SELECT full_name, email, hashed_pwd FROM USERS WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $param_email = $email;
            $stmt->bind_param("s", $param_email);

            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    
                    $stmt->bind_result($full_name, $email, $hashed_password);
                    
                    if($stmt->fetch()){
                        
                        if(password_verify($password, $hashed_password)){

                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["fullname"] = $full_name;
                            $_SESSION["email"] = $email;
                            
                            header("location: ../demo/must");
                        } else{
                            $password_err = "Incorrect password";
                            echo "Error in password";
                        }
                    }
                } else{
                    $email_err = "No account found under that email.";
                    echo "Error in email";
                }
            } else{
                echo "Error in SQL query";
            }

            $stmt->close();
        }
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    
</head>
<body>
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Email</label>
        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
        <input type="submit" class="btn btn-primary" value="Login">
        <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </form>

</body>
</html>