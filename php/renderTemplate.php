<?php

require_once "lib/Mustache/Autoloader.php";

function renderTemplate(string $templateName, array $context){
    $templatePath = __DIR__."/../templates";
    $partialsPath = __DIR__."/../templates/shared";

    Mustache_Autoloader::register();
    $mustache = new Mustache_Engine(array(
        "loader" => new Mustache_Loader_FilesystemLoader($templatePath),
        "partials_loader" => new Mustache_Loader_FilesystemLoader($partialsPath),
    ));

    if(str_endsWith($templateName, '.mustache')){
        $templateName .= '.mustache';
    }

    $template = $mustache -> loadTemplate($templateName);
    echo $template->render($context);
}

// src: https://stackoverflow.com/a/834355
function str_endsWith(string $haystack, string $needle ) : bool {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}