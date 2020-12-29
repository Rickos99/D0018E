<?php

require_once "db.conn.php";

function updateSupportTicketStatus(int $ticketId, int $closeTicket, int $uid) : bool {
    if($closeTicket !== 0 && $closeTicket !== 1){
        throw new OutOfRangeException('$closeTicket must be either 0 or 1');
    }

    $mysqli = getDBConnection();
    $sqlUpdateStatus = "UPDATE STORE.SUPPORT_TICKETS SET isResolved = ? WHERE ticket_id = ?";
    $sqlInsertResponse = "INSERT INTO STORE.TICKET_RESPONSES (ticket_id, user_id, body, created_at) VALUES (?, ?, ?, NOW())";
    $msg = $closeTicket === 1 ? "Supportärendet är nu avslutat." : "Supportärendet är nu öppnat.";

    $sqlSucces = true;
    try {
        $stmt = $mysqli->prepare($sqlUpdateStatus);
        $stmt->bind_param("ii", $closeTicket, $ticketId);
        $stmt->execute();

        $stmt = $mysqli->prepare($sqlInsertResponse);
        $stmt->bind_param("iis", $ticketId, $uid, $msg);
        $stmt->execute();

        $mysqli->commit();
    } catch (mysqli_sql_exception $exc) {
        $mysqli->rollback();
        $sqlSucces = false;
    } finally {
        $mysqli->close();
    }

    return $sqlSucces;
}
