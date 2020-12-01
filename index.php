<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
require_once "php/sql/getAllProducts.php";
require_once "lib/Mustache/Autoloader.php";

Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array(
    "loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates"),
    "partials_loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates/shared"),
));
$template = $mustache -> loadTemplate("displayAllProducts.mustache");

$user = new UserSession(true, [0]);

echo $template->render(array("products" => getAllProducts(), "user" => $user));
