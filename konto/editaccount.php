<?php

require_once "../php/sql/db.conn.php";
require_once "../php/userSession.php";
$user = new userSession(true, [0,1]);
$mysqli = getDBConnection();

$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE");
 
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

$sql = "SELECT full_name, address, phone, email FROM USERS WHERE uid =".$_SESSION['uid'];

$result = $mysqli->query($sql);

$row = $result->fetch_row();

?>
<html>
<form>
    <p>
        <label for="Fullname">Full Name:</label>
        <input type="text" name="Fullname" id="Fullname" value="<?php echo $row[0];?>">
    </p>
    <p>
        <label for="Address">Address:</label>
        <input type="text" name="Address" id="Address" value="<?php echo $row[1];?>">
    </p>
    <p>
        <label for="Phone">Phone number:</label>
        <input type="text" name="Phone" id="Phone" value="<?php echo $row[2];?>">
    </p>
    <p>
        <label for="Email address">Email address:</label>
        <input type="text" name="Email" id="Email" value="<?php echo $row[3];?>">
    </p>
</form>

<form action="editconfirm.php" method="post">
    <p>
        <label for="Fullname">Full Name:</label>
        <input type="text" name="fullname" id="fullname">
    </p>
    <p>
        <label for="Address">Address:</label>
        <input type="text" name="address" id="address">
    </p>
    <p>
        <label for="Phone">Phone number:</label>
        <input type="text" name="phone" id="phone">
    </p>
    <p>
        <label for="Email address">Email address:</label>
        <input type="text" name="email" id="email">
    </p>
    <input type="submit" value="Submit">
</form>
</html>
<?php
 
$mysqli->close();

?>