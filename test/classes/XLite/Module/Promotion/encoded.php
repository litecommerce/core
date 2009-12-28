<?php

/**
* @package Module_Promotion
* @access public
* @version $Id$
*/

function func_in_category_recursive($product, $category)
{
    if (is_null($category)) return false;
    if (is_null($product)) return false;
    if ($product->inCategory($category)) return true;
    foreach ($category->getSubcategories() as $c) {
        if (func_in_category_recursive($product, $c)) return true;
    }
    return false;
}

function func_calc_discount(&$order)
{
    $d = 0;
    if ($order->call("DC.checkCondition", $order)) {
        if ($order->get("DC.applyTo") == "total") {
            // return only total discount
            $subtotal = $order->get("discountableTotal");
            switch ($order->get("DC.type")) {
                case "absolute": $d = min($subtotal, $order->get("DC.discount")); break;
                case "percent": $d = $subtotal * $order->get("DC.discount") / 100; break;
            }
        }
    }
	$d = min($order->get("subtotal"), $d);
    $order->set("discount", $order->formatCurrency($d));
}
?>
