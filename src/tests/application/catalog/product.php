<?php

// test view product details
// Use product ID# 149 (SONY MCV-CD200(CDRW) Digital camera)

$get = array("product_id" => 149, "category_id" => 79);
sendRequest("product", "view", $get);
$w =& Widget::getByName("Product");
assert($w->dialog->product->get("product_id") == 149);

?>
