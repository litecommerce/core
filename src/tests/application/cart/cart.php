<?php

/*
* test "Add item to cart" 
*
* use two products:
*
* ID# 182 "Duo" board game, price $50.00
* ID# 183 "MAESTRO" board game, price $120.00
*/

// add first item
$get = array("product_id" => 182, "category_id" => 131);
sendRequest("cart", "add", $get);

$cart =& Cart::getInstance();

assert(count($cart->getItems()) == 1 && $cart->getSubTotal() == 50 && $cart->getTax() == 0 && $cart->getTotal() == 50);

// add second item
$get = array("product_id" => 183, "category_id" => 131);
sendRequest("cart", "add", $get);
assert(count($cart->getItems()) == 2 && $cart->getSubTotal() == 170);

// delete first item
$get = array("cart_id" => 0);
sendRequest("cart", "delete", $get);
assert(count($cart->getItems()) == 1 && $cart->getSubTotal() == 120);

// change quantity (increase amount)
$get = array("amount" => array(2));
sendRequest("cart", "update", $get);
assert(count($cart->getItems()) == 1 && $cart->getSubTotal() == 240);

// change quantity (add item twice)
$get = array("product_id" => 183, "category_id" => 131);
sendRequest("cart", "add", $get);
assert(count($cart->getItems()) == 1 && $cart->getSubTotal() == 360);

// clear cart
sendRequest("cart", "clear");
assert(count($cart->getItems()) == 0);

?>
