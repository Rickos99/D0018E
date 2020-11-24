<?php

class UserSession {
    public $loggedIn;

    function __construct(){
        // Initialize the session
        session_start();
        $this -> loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true; 
    }
    
    static function redirectToLoginPage(string $returnUrl = "/"){
        header("location: /konto/login.php?returnUrl=" . urlencode($returnUrl));
        exit();
    }
}