<?php

include "php/debugSettings.php";
require_once "php/userSession.php";
require_once "php/sql/getProduct.php";
require_once "php/sql/getProductReview.php";
require_once "php/sql/addProductReview.php";
require_once "php/renderTemplate.php";

$user = new UserSession(false, [0, 1, 2]);
$product = NULL;
$productID = $_GET["pid"];
$userReview = Null;
$formMessage = Null;

if($_SERVER['REQUEST_METHOD'] === 'POST' && $user->loggedIn){
    $review = new stdClass();
    $review -> uid = (int)$user->uid;
    $review -> pid = (int)$_POST['pid'];
    $review -> title = trim($_POST['title']);
    $review -> comment = trim($_POST['comment']);
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
    $product = getProduct($productID);
}

if($user->loggedIn && !$_POST){
    $userReview = getProductReview($user->uid, $productID);
}

$context = array(
    "product" => $product, 
    "user" => $user,
    "userReview" => $userReview,
    "formMessage" => $formMessage,
    "fnOutputStars" => function($stars, Mustache_LambdaHelper $helper){
        $stars = (int)$helper->render($stars);
        $i = 5;
        $output = "";
        while ($stars-- && $i--) {
            $output .= '<span class="material-icons text-gold">star_rate</span>';
        }
        while ($i--) {
            $output .= '<span class="material-icons text-black-50">star_rate</span>';
        }
        return $output;
    },
    "fnTimeSince" => function($time, Mustache_LambdaHelper $helper){
        /** https://stackoverflow.com/a/2916189 */
        $time = time() - strtotime($helper->render($time));
        $time = ($time<1) ? 1 : $time;
        $tokens = array (
            31536000 => ['år', 'år'],
            2592000 => ['månad', 'månader'],
            604800 => ['vecka', 'veckor'],
            86400 => ['dag', 'dagar'],
            3600 => ['timme', 'timmar'],
            60 => ['minut', 'minuter'],
            1 => ['sekund', 'sekunder']
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.(($numberOfUnits>1) ? $text[1] : $text[0]);
        }
    }
);

renderTemplate('displayProduct', $context);
