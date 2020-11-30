<?php

require_once "sql/addProductToCart.php";
require_once "sql/deleteProductFromCart.php";
require_once "sql/getProductsFromCart.php";

class UserSession {
    public bool $loggedIn;
    public int $uid;
    public Cart $cart;

    function __construct(){
        // Initialize the session
        session_start();
        $this -> loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
        if(!$this -> loggedIn){
            return;
        }
        $this -> uid = 8;//(int)$_SESSION["uid"];
        $this -> cart = new Cart($this->uid);
    }
    
    static function redirectToLoginPage(string $returnUrl = NULL){
        if($returnUrl === NULL){
            $returnUrl = $_SERVER['REQUEST_URI'];
        }
        header("location: /konto/login.php?returnUrl=" . urlencode($returnUrl));
    }
}

class Cart {

    private int $uid;
    public int $numberOfItems;
    public array $items;
    public object $priceSummary;

    function __construct($uid){
        $this -> uid = $uid;
        $this -> items = $this->getAllItems();
        $this -> numberOfItems = $this->numberOfItems();
        $this -> priceSummary = $this->calculatePriceSummary();
    }

    function addItem($pid, $quantity){
        addProductToCart($this->uid, $pid, $quantity);
    }

    function getAllItems() : array {
        return getProductsFromCart($this->uid);
    }

    function removeItem($pid){
        deleteProductFromCart($this->uid, $pid);
    }

    function calculatePriceSummary() : object {
        $priceSummary = new stdClass();
        $priceSummary -> totalpriceWithVat = 0;
        $priceSummary -> totalPrice = 0;
        $priceSummary -> totalVat = 0;

        foreach ($this->items as $item) {
            $priceSummary -> totalPriceWithVat += $item->totalPriceWithVat;
            $priceSummary -> totalPriceNoVat += $item->totalPriceNoVat;
            $priceSummary -> totalVat += $item->totalPriceWithVat - $item->totalPriceNoVat;
        }

        return $priceSummary;
    }

    function numberOfItems() : int{
        $nItems = 0;
        foreach ($this->items as $item) {
            $nItems += $item->quantity;
        }
        return $nItems;
    }
}