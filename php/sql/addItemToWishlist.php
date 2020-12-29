<?php

require_once "db.conn.php";

function addItemToWishlist(int $pid, int $uid) : bool {
    $mysqli = getDBConnection();

    $sql = "INSERT INTO WISHLIST_ITEMS (pid, uid) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $pid, $uid);
    $stmt->execute();

    $succes = $stmt->affected_rows === 1 ? true : false;

    $stmt->close();
    $mysqli->close();
    return $succes;
}