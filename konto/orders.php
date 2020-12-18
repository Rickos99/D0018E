<?php

include "../php/debugSettings.php";
require_once "../php/sql/getOrdersFromUserId.php";
require_once "../php/renderTemplate.php";
require_once "../php/userSession.php";

$user = new userSession(true, [0, 1, 2]);
renderTemplate("displayOrderHistory", [
    "user" => $user,
    "orders" => getOrdersFromUserId($user->uid)
]);