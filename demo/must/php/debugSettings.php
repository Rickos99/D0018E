<?php
$debugIsEnabled = true;

if($debugIsEnabled){
    ini_set('display_errors', 'On');
    // error_reporting(E_ALL);
    error_reporting(E_ERROR);
}

function debug_v($var, string $name){
    echo '<div style="background: lightgrey; width: fit-content; padding: 10px; margin: 5px;">' . PHP_EOL;
    echo "<code>var_dump($name) = </code>" . PHP_EOL;
    echo '<pre>' . PHP_EOL;
    $str = var_dump($var);
    echo '</pre>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
}