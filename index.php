<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
$user = new userSession(true, [0,1,2]);
require_once "php/sql/getAllProducts.php";
require_once "lib/Mustache/Autoloader.php";

Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array(
    "loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates"),
    "partials_loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates/shared"),
));
$template = $mustache -> loadTemplate("displayAllProducts.mustache");

$user = new UserSession(false, [0]);
debug_v($user, '$_SESSION');
echo $template->render(array("products" => getAllProducts(), "user" => $user));
