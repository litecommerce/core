<?php

// test product details view
// use category ID# 243 (Sport) with two products (ID# 16126, 16123)

$get = array("category_id" => 243);
sendRequest("category", "view", $get);

$w =& Widget::getByName("Products");
assert(count($w->dialog->products) == 2 &&
    $w->dialog->products[0]->get("product_id") == 16126 &&
    $w->dialog->products[1]->get("product_id") == 16123);

?>
