<?php

require_once "db.conn.php";

function getSupportTickets(int $uid = -1) : array {
    $mysqli = getDBConnection();

    $whereCondition;
    if($uid === -1){
        $whereCondition = "";
    } else {
        $whereCondition = "WHERE order_id IN (SELECT order_id FROM STORE.ORDERS as O WHERE O.uid = ?)";
    }
    $sql = "SELECT T.ticket_id, T.subject, T.body, T.isReturn, T.isResolved, T.created_at, MAX(R.created_at) as lastUpdated, COUNT(R.created_at) FROM STORE.SUPPORT_TICKETS as T
	           LEFT JOIN TICKET_RESPONSES as R ON T.ticket_id = R.ticket_id
	           $whereCondition
               GROUP BY T.ticket_id
               ORDER BY T.isResolved ASC, lastUpdated ASC";

    $stmt = $mysqli -> prepare($sql);
    if($uid !== -1){
        $stmt->bind_param("i", $uid);
    }
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($ticketId, $subject, $body, $isReturn, $isResolved, $created_at, $last_updated, $responses);

    $returnValue = array();
    while($stmt->fetch()){
        $ticket = new stdClass();
        $ticket->body = $body;
        $ticket->subject = $subject;
        $ticket->ticket_id = $ticketId;
        $ticket->isReturn = $isReturn;
        $ticket->responses = $responses;
        $ticket->isResolved = $isResolved;
        $ticket->created_at = $created_at;
        $ticket->last_updated = $last_updated;
        array_push($returnValue, $ticket);
    }

    return $returnValue;
}
