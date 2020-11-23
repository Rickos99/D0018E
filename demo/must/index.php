<?php

include "php/debugSettings.php";
require_once "php/sql/getAllProducts.php";
require_once "../../lib/Mustache/Autoloader.php";

Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array(
    "loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates"),
    "partials_loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates/shared"),
));
$template = $mustache -> loadTemplate("displayAllProducts.mustache");

$user = new stdClass();
$user -> name = "John Doe";
$user -> adress = "";
$user -> phone = "0701234567";
$user -> email = "john@doe.com";

$cart = new stdClass();
$cart -> products = ["Tandkräm"];
$cart -> items = count($cart -> products);

echo $template->render(array("products" => getAllProducts(), "user" => $user, "cart" => $cart));

if($debugIsEnabled){
    echo "<div class=\"container\">";
    
    echo "<hr>";
    echo "<pre>";
    echo "file: " . __FILE__ . PHP_EOL;
    echo "templates: " . __DIR__ . "/templates" . PHP_EOL;
    echo "-----" . PHP_EOL;
    echo "var_dump(getAllProducts()) = ";
    var_dump(getAllProducts());
    echo "</pre>";

    echo "</div>";
}