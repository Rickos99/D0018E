<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
require_once "php/sql/getAllProducts.php";
require_once "../../lib/Mustache/Autoloader.php";

Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array(
    "loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates"),
    "partials_loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates/shared"),
));
$template = $mustache -> loadTemplate("displayAllProducts.mustache");

$user = new UserSession();
if($user -> loggedIn){
    $user -> name = $_SESSION["fullname"];
    $user -> adress = "";
    $user -> phone = "";
    $user -> email = $_SESSION["email"];
} else {
    UserSession::redirectToLoginPage($_SERVER['REQUEST_URI']);
}

$cart = new stdClass();
$cart -> products = ["Tandkräm"];
$cart -> items = count($cart -> products);

echo $template->render(array("products" => getAllProducts(), "user" => $user, "cart" => $cart));

if($debugIsEnabled){
    echo debug_v( __FILE__, 'path to file');
    echo debug_v(__DIR__ . "/templates", 'path to templates');
}