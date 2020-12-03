<?php

$returnUrl = "/";
if(!empty($_GET["returnUrl"])){
    $returnUrl = $_GET["returnUrl"];
}

// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: $returnUrl");
exit;
?>