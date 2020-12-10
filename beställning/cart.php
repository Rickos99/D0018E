<?php

include "../php/debugSettings.php";
require_once "../php/userSession.php";
require_once "../php/renderTemplate.php";

$user = new userSession(false, [0, 1, 2]);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Display all products in cart
    
    $cartItems = Null;
    if(!empty($user->cart)){
        $cartItems = $user->cart->items;
    }

    $context = array(
        "cartItems" => $cartItems, 
        "user" => $user
    );

    renderTemplate('cart', $context);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Unknown request method");
}

if($_POST["action"] === "PUT"){
    // Add n of products to cart
    $pid = (int)$_POST["pid"];
    $quantity = (int)$_POST["quantity"];
    $user->cart->addItem($pid, $quantity);
} else if($_POST["action"] === "DELETE") {
    // Delete product from cart
    $pid = (int)$_POST["pid"];
    $user->cart->removeItem($pid);
} else {
    die("Unknown action");
}

header("location: " . $_SERVER['REQUEST_URI']);
