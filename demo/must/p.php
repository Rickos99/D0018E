<?php

include "php/debugSettings.php";
require_once "php/sql/getProduct.php";
require_once "../../lib/Mustache/Autoloader.php";

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
echo $template->render(array("product" => getProduct($productID), "user" => $user, "cart" => $cart));

if($debugIsEnabled){
    echo "<div class=\"container\">";
    
    echo "<hr>";
    echo "<pre>";
    echo "file: " . __FILE__ . PHP_EOL;
    echo "templates: " . __DIR__ . "/templates" . PHP_EOL;
    echo "-----" . PHP_EOL;
    echo "var_dump(getAllProducts()) = ";
    var_dump(getProduct($productID));
    echo "</pre>";

    echo "</div>";
}