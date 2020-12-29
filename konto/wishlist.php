<?php

include "../php/debugSettings.php";
require_once "../php/sql/getWishlistFromUser.php";
require_once "../php/sql/addItemToWishlist.php";
require_once "../php/sql/deleteItemFromWishlist.php";
require_once "../php/renderTemplate.php";
require_once "../php/userSession.php";

$user = new UserSession(true, [0]);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $action = $_POST["action"];
    $pid = intval($_POST["pid"]);
    $uid = $user->uid;
    if(empty($pid)) {
        header("HTTP/1.1 400 Bad Request");
        exit();
    }

    switch ($action) {
        case "PUT":
            if(!addItemToWishlist($pid, $uid)){
                header("HTTP/1.1 500 Internal Server Error");
                exit();
            }
            break;
            
        case "DELETE":
            if(!deleteItemFromwishlist($pid, $uid)){
                header("HTTP/1.1 500 Internal Server Error");
                exit();
            }
            break;

        default:
            header("HTTP/1.1 400 Bad Request");
            exit();
    }
    header("location: ".$_SERVER['REQUEST_URI']);
    exit();
} else {
    renderTemplate("wishlist", [
        "user" => $user,
        "wishlist" => getWishlist($user->uid)
    ]);
}