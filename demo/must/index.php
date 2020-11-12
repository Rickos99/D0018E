<?php

include "php/debugSettings.php";
require_once "php/sql/getAllProducts.php";
require_once "../../lib/Mustache/Autoloader.php";

Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array(
    "loader" => new Mustache_Loader_FilesystemLoader(__DIR__."/templates") 
));
$template = $mustache -> loadTemplate("displayAllProducts.mustache");
echo $template->render(array("products" => getAllProducts()));

if($debugIsEnabled){
    echo "<hr>";
    echo "file: " . __FILE__ . PHP_EOL;
    echo "<br>templates: " . __DIR__ . "/templates" . PHP_EOL;

    echo "<hr>";
    var_dump(getAllProducts());
}