<?php

require_once "db.conn.php";

function addSupportTicketResponse(object $response, int $uid) : bool {
    if(empty($response)){
        return false;
    }
    $mysqli = getDBConnection();
    $sql = "INSERT INTO TICKET_RESPONSES (ticket_id, user_id, body, created_at) 
               VALUES (?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iis",$response->ticketId, $uid, $response->body);
    if(!($stmt->execute())){
        return false;
    }

    $stmt->close();
    $mysqli->close();

    return true;
}
