<?php

include "../php/debugSettings.php";
require_once "../php/userSession.php";
require_once "../lib/Mustache/Autoloader.php";

$user = new userSession(false, [0, 1, 2]);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Display all products in cart

    Mustache_Autoloader::register();
    $mustache = new Mustache_Engine(array(
        "loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/../templates"),
        "partials_loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/../templates/shared"),
    ));
    $template = $mustache -> loadTemplate("cart.mustache");
    
    $cartItems = Null;
    if(!empty($user->cart)){
        $cartItems = $user->cart->items;
    }
    echo $template->render(array("cartItems" => $cartItems, "user" => $user));
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
    pprint("Method: PUT");
} else if($_POST["action"] === "DELETE") {
    // Delete product from cart
    $pid = (int)$_POST["pid"];
    $user->cart->removeItem($pid);
    pprint("Method: DELETE");
} else {
    die("Unknown action");
}

header("location: " . $_SERVER['REQUEST_URI']);
