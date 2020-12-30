<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
require_once "php/sql/getProduct.php";
require_once "php/sql/getProductReview.php";
require_once "php/sql/addProductReview.php";
require_once "php/renderTemplate.php";

include_once "php/mustacheHelpers.php";

$user = new UserSession(false, [0, 1, 2]);
$product = NULL;
$productID = $_GET["pid"];
$userReview = Null;
$formMessage = Null;

if($_SERVER['REQUEST_METHOD'] === 'POST' && $user->loggedIn){
    $review = new stdClass();
    $review -> uid = (int)$user->uid;
    $review -> pid = (int)$_POST['pid'];
    $review -> title = substr(trim($_POST['title']), 0, 255);
    $review -> comment = substr(trim($_POST['comment']), 0, 8000);
    $review -> rating = (int)$_POST['reviewRating'];
    $review -> recommends = (int)$_POST['recommends'];
    
    if(addProductReview($review) === true){
        header("location: " . $_SERVER['REQUEST_URI']);
    } else {
        $userReview = $review;
        $formMessage = 'Något gick fel när din recension skulle sparas. Var god försök igen!';
    }
}

if(isset($productID) && $productID > 0){
    $uid = $user->loggedIn ? $user->uid : -1;
    $product = getProduct($productID, $uid);
}

if($user->loggedIn && !$_POST){
    $userReview = getProductReview($user->uid, $productID);
}

renderTemplate('displayProduct', [
    "product" => $product, 
    "user" => $user,
    "userReview" => $userReview,
    "formMessage" => $formMessage,
    "fnOutputStars" => $fnOutputStars,
    "fnTimeSince" => $fnTimeSince
]);
