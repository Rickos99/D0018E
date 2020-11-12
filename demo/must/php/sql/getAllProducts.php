<?php

require_once "db.conn.php";

function getAllProducts() {
    $mysqli = getDBConnection();
    $sql = "SELECT * FROM PRODUCTS";
    $returnValue = array();
    if ($result = $mysqli -> query($sql)) {
        while ($obj = $result -> fetch_object()) {
            array_push($returnValue, $obj);
        }
        $result -> free_result();
    } else {
        echo "Error: Unable to execute query"; ;
        echo "Debugging errno: " . $mysqli->errno . PHP_EOL;
        echo "Debugging error: " . $mysqli->error . PHP_EOL;
    }
    $mysqli -> close();
    return $returnValue;
}