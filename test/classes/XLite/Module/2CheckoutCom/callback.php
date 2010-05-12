<?php

$_REQUEST["target"] = "callback";
$_REQUEST["action"] = "callback";
$_REQUEST["order_id_name"] = "cart_order_id";
if (isset($_REQUEST["product_id"])) {
    $_REQUEST["2co_product_id"] = $_REQUEST["product_id"];
    unset($_REQUEST["product_id"]);
}
if (isset($_POST["product_id"])) {
    $_POST["2co_product_id"] = $_POST["product_id"];
    unset($_POST["product_id"]);
}
            
// FIXME - is it needed?
// chdir("../../..");

include LC_ROOT_DIR . 'cart.php';

