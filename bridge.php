<?php
$mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "test"); // host, user, pass, db

// från php manualen php.net/manual
if ($mysqli -> connect_errorno) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . $mysqli -> connect_errno . PHP_EOL;
    echo "Debugging error: " . $mysqli -> connect_error . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made!" . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($mysqli) . PHP_EOL;

$sql = "SELECT * FROM test";

echo "<div>";
echo "<b>Data</b>";
echo "<table>";

$returnValue = array();
if ($result = $mysqli -> query($sql)) {
    while ($obj = $result -> fetch_object()) {
        array_push($returnValue, $obj);
        echo "<tr><td>" . $obj->idtest . "</td></tr>";
    }
    $result -> free_result();
}
echo "</table>";
echo "</div>";
echo "<hr>";
var_dump($returnValue);
 
$mysqli -> close();
