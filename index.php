<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
require_once "php/sql/getAllProducts.php";
require_once "php/renderTemplate.php";

$context = array(
    "products" => getAllProducts(),
    "user" => new UserSession(false, [0, 1, 2])
);
renderTemplate('displayAllProducts', $context);
