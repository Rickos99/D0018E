<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
require_once "php/sql/getAllProducts.php";
require_once "php/renderTemplate.php";

$user = new UserSession(false, [0, 1, 2]);
$uid = $user->loggedIn ? $user->uid : -1;

renderTemplate('displayAllProducts', [
    "products" => getAllProducts($uid),
    "user" => $user
]);
