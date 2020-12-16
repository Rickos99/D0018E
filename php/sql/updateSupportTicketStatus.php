<?php

require_once "db.conn.php";

function updateSupportTicketStatus(int $ticketId, int $closeTicket) : bool {
    if($closeTicket !== 0 && $closeTicket !== 1){
        throw new OutOfRangeException('$closeTicket must be either 0 or 1');
    }

    echo "Id: $ticketId, closeTicket: $closeTicket";

    $mysqli = getDBConnection();
    $sql = "UPDATE STORE.SUPPORT_TICKETS SET isResolved = ? WHERE ticket_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $closeTicket, $ticketId);
    $stmt->execute();

    $insertionSuccess = true;
    if($stmt->affected_rows === 0){
        $insertionSuccess = false;
    }

    $stmt->close();
    $mysqli->close();

    return $insertionSuccess;
}
