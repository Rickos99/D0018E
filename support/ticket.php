<?php

include "../php/debugSettings.php";
require_once "../php/sql/addSupportTicket.php";
require_once "../php/sql/getOrdersFromUserId.php";
require_once "../php/sql/addSupportTicketResponse.php";
require_once "../php/sql/getOwnerOfSupportTicket.php";
require_once "../php/sql/getSupportTicket.php";
require_once "../php/sql/getSupportTickets.php";
require_once "../php/sql/updateSupportTicketStatus.php";
require_once "../php/renderTemplate.php";
require_once "../php/userSession.php";

include_once "../php/mustacheHelpers.php";

$user = new userSession(true, [0, 1]);
$user->isSupportStaff = $user->role === 1 ? true : false;

// Check if post request
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $ticketId = intval($_POST['ticketId']);

    // Close an open ticket
    if($_POST['action'] === 'closeTicket' && $user->role === 1){
        if(!updateSupportTicketStatus($ticketId, 1, $user->uid)){
            die("ERROR: The ticket status could not be updated");
        } 

    // Reopen an already closed ticket
    } else if($_POST['action'] === 'openTicket' && $user->role === 1){
        if(!updateSupportTicketStatus($ticketId, 0, $user->uid)){
            die("ERROR: The ticket status could not be updated");
        }

    // Add an response to a ticket
    } else if($_POST['action'] === 'addResponse'){
        $ticketOwner = getOwnerOfSupportTicket($ticketId);
        if(!($uid !== $ticketOwner || $user->role !== 1)){
            header("HTTP/1.1 400 Bad Request");
            exit();
        }

        // Create a new response object
        $newResponse = new stdClass();
        $newResponse->uid = $user->uid;
        $newResponse->body = substr(trim($_POST['responseBody']), 0, 1000);
        $newResponse->ticketId = intval($_POST['ticketId']);
        if(!addSupportTicketResponse($newResponse, $user->uid)){
            die("ERROR: Could not save ticketresponse to database");
        }

    // Open a new ticket
    } else if ($_POST['action'] === 'addTicket'){
        $newTicket = new stdClass();
        $newTicket->body = substr(trim($_POST['body']), 0, 2000);
        $newTicket->subject = substr(trim($_POST['subject']), 0, 255);
        $newTicket->order_id = intval($_POST['order_id']);
        $newTicket->isReturn =  intval($_POST['isReturn']);
        $newTicket->isResolved = false;
        
        $insertedId = addSupportTicket($newTicket);
        if($insertedId < 0){
            die("ERROR: Could not save ticket to database");
        }

    } else {
        die("ERROR: Unkown actiontype!");
    }

    // Redirectback to same page to avoid 
    header("location: ".$_SERVER['REQUEST_URI']);
    exit();
}

// get ticketid from request
$ticketId = $_GET['t'];

// Display ticket by ticketid if found
if(!empty($ticketId)){
    $ticket = getSupportTicket($ticketId);
    if($user->role === 0 && $user->uid !== $ticket->owner || empty((array)$ticket)){
        $ticket = null;
    }
    renderTemplate("displaySupportTicketDetails", [
        "user" => $user,
        "ticket" => $ticket
    ]);

// If user is support staff, display all open tickets
} else if($user->role === 1) {
    renderTemplate("displaySupportTicketsStaff", [
        "user" => $user,
        "tickets" => getSupportTickets(),
        "fnTimeSince" => $fnTimeSinceShort
    ]);

// Else user is customer and all his/hers tickets will be displayed
} else {
    renderTemplate("displaySupportTicketsCustomer", [
        "user" => $user,
        "orders" => getOrdersFromUserId($user->uid),
        "tickets" => getSupportTickets($user->uid)
    ]);
}