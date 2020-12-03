<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
require_once "php/sql/getProduct.php";
require_once "lib/Mustache/Autoloader.php";

Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array(
    "loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates"),
    "partials_loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates/shared"),
));
$template = $mustache -> loadTemplate("displayProduct.mustache");

$productID = $_GET["pid"];
if($productID == NULL){
    die ("<code>pid</code> kan inte vara NULL");
}

$user = new UserSession(false, [0, 1, 2]);

echo $template->render(array("product" => getProduct($productID), "user" => $user));
