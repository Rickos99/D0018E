<?php

require_once "db.conn.php";

function deleteItemFromwishlist(int $pid, int $uid) : bool {
    $mysqli = getDBConnection();

    $sql = "DELETE FROM WISHLIST_ITEMS WHERE pid=? AND uid=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $pid, $uid);
    $stmt->execute();
    
    $succes = $stmt->affected_rows === 1 ? true : false;

    $stmt->close();
    $mysqli->close();
    return $succes;
}