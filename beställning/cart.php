<?php

include "../php/debugSettings.php";
require_once "../php/sql/addProductToCart.php";

addProductToCart(8, 8, 1);
pprint("Success");