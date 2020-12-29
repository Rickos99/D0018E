<?php

function getDBConnection() : mysqli {
    $mysqli = new mysqli("127.0.0.1", "grupp16", "grupp16", "STORE"); // host, user, pass, db
    $mysqli->set_charset("utf8");
    if ($mysqli -> connect_errno) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . $mysqli -> connect_errno . PHP_EOL;
        echo "Debugging error: " . $mysqli -> connect_error . PHP_EOL;
        exit;
    }

    return $mysqli;
}
