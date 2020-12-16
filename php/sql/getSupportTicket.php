<?php

require_once "db.conn.php";

function getSupportTicket(int $ticketId) : object {
    $mysqli = getDBConnection();

    $sql = "SELECT ST.ticket_id, ST.subject, ST.body, ST.isReturn, ST.isResolved, ST.created_at, ST.last_updated, O.uid
               FROM STORE.SUPPORT_TICKETS as ST
               LEFT JOIN STORE.ORDERS as O ON ST.order_id = O.order_id 
               WHERE ticket_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($ticketId, $subject, $body, $isReturn, $isResolved, $created_at, $last_updated, $owner);
    $stmt->fetch();
    if($stmt->num_rows === 0){
        return new stdClass();
    }
    
    $ticket = new stdClass();
    $ticket->body = $body;
    $ticket->subject = $subject;
    $ticket->ticket_id = $ticketId;
    $ticket->isReturn = $isReturn;
    $ticket->isResolved = $isResolved;
    $ticket->created_at = $created_at;
    $ticket->last_updated = $last_updated;
    $ticket->owner = $owner;
    $stmt->close();
    
    $sql = "SELECT R.body, R.created_at, U.full_name, U.role  FROM STORE.TICKET_RESPONSES as R
               LEFT JOIN STORE.USERS as U ON R.user_id = U.uid
               WHERE R.ticket_id = ?
               ORDER BY R.created_at DESC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $ticketId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($body, $created_at, $full_name, $userrole);
    
    $responses = array();
    while($stmt->fetch()){
        $response = new stdClass();
        $response->body = $body;
        $response->userrole = $userrole;
        $response->full_name = $full_name;
        $response->created_at = $created_at;
        $response->isStaff = $userrole === 0 ? false : true;
        array_push($responses, $response);
    }

    $ticket->responses = $responses;

    $stmt->close();
    $mysqli->close();
    return $ticket;
}
