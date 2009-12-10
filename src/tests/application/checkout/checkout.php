<?php

// test checkout steps

// add item with price < min_order_amount
//
// use product ID# 16126 (Brine ATTACK Soccer Ball)
//
$get = array("product_id" => 16126, "category_id" => 243);
sendRequest("cart", "add", $get);

$cart =& Cart::getInstance();
assert(!$cart->isEmpty());

// attempt to checkout, should get "Checkout not allowed"
sendRequest("checkout");
$w =& Widget::getByName("CheckoutNotAllowed");
assert($w->status == ENABLED && $w->dialog->location == "checkout_not_allowed");

// increase amount by adding item twice
$get = array("product_id" => 16126, "category_id" => 243);
sendRequest("cart", "add", $get);
assert($cart->getSubTotal() == 19.70);

// attempt to checkout (user is not logged in!)
sendRequest("checkout");
// check whether registration form is activated
$w =& Widget::getByName("CheckoutRegistration");
assert($w->status == ENABLED);

// login with a registered user
$post = array("login" => "bit-bucket@rrf.ru", "password" => "123");
sendRequest("login", "login", null, $post);
assert($session->isRegistered("profile_id"));

// attempt to checkout, should get "Select Payment method " page
sendRequest("checkout");
$w =& Widget::getByName("CheckoutPaymentMethod");
assert($w->status == ENABLED);

// test select payment method
// use "phone ordering"
$post = array("payment_id" => "phone_ordering");
sendRequest("checkout", "payment", null, $post);
$cart =& Cart::getInstance();
assert($cart->get("payment_method") == "phone_ordering"); 

// test change payment request
sendRequest("checkout", "change_payment");
$w =& Widget::getByName("CheckoutPaymentMethod");
assert($w->status == ENABLED);

$cart->_initFields();
// test checkout 
$post = array("notes" => "test");
sendRequest("checkout", "checkout", null, $post);
assert($cart->get("status") == "Q" && !$session->isRegistered("order_id"));

// sanity

// cleanup queued order
$cart->delete();

?>
