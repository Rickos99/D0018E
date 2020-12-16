<?php

require_once "db.conn.php";

/**
 * @param object $ticket ticket object to add to database
 * @return int if insertion was successfull the function will return the inserted id. If the insertion failed, -1 will be returned.
 */
function addSupportTicket(object $ticket) : int {
    $mysqli = getDBConnection();

    $sql = "INSERT INTO SUPPORT_TICKETS(`order_id`, `subject`, `body`, `isReturn`, `isResolved`, `created_at`, `last_updated`) 
               VALUES (?, ?, ?, ?, 0, NOW(), NOW())";
    $stmt = $mysqli -> prepare($sql);
    $stmt -> bind_param("issi", $ticket->order_id, $ticket->subject, $ticket->body, $ticket->isReturn);
    $stmt -> execute();
    return $stmt->insert_id;
}
