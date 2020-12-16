<?php

require_once "db.conn.php";

function getOwnerOfSupportTicket(int $ticketId) : int {
    if(empty($ticketId)){
        throw new InvalidArgumentException("TicketId must not be empty or null!");
    }

    $mysqli = getDBConnection();
    $sql = "SELECT O.uid FROM STORE.ORDERS as O
	           WHERE O.order_id = ( SELECT order_id FROM STORE.SUPPORT_TICKETS as T WHERE T.ticket_id = ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($uid);
    $stmt->fetch();
    
    $owner = -1;
    if($stmt->num_rows !== 0){
        $owner = $uid;
    }

    $stmt->close();
    $mysqli->close();

    return $owner;
}
