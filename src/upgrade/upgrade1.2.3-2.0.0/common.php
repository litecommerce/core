<?php
// common replacements:
$source = preg_replace("/\.get\(#([^#]*)#\)/", ".\\1", $source);
$source = str_replace("billing_state_code", "billingState.code", $source);
$source = str_replace("shipping_state_code", "shippingState.code", $source);
$source = str_replace("billing_state_name", "billingState.state", $source);
$source = str_replace("shipping_state_name", "shippingState.state", $source);
$source = str_replace("billing_country_name", "billingCountry.country", $source);
$source = str_replace("shipping_country_name", "shippingCountry.country", $source);
$source = str_replace("getDetailsNames(),name", "details,name,val", $source);
$source = str_replace("getDetailsLabel(name)", "getDetailLabel(name)", $source);
$source = str_replace("dialog.order.getDetails(name)", "val", $source);
$source = str_replace("isEnabled()", "enabled", $source);
$source = str_replace("hasSubcategories()", "subcategories", $source);
$source = str_replace("hasProducts()", "products", $source);
$source = str_replace("dialog.subcategories", "dialog.category.subcategories", $source);
$source = str_replace(".getItems()", ".items", $source);
$source = str_replace(".getAmount()", ".amount", $source);
$source = str_replace(".isShippingAvailable()", ".shippingAvailable", $source);
$source = str_replace(".payment_method.name", ".paymentMethod.name", $source);
$source = str_replace(".payment_method.params", ".paymentMethod.params", $source);
$source = str_replace(".shipping_method.", ".shippingMethod.", $source);
$source = str_replace(" FLEXYIGNORE", "", $source);
$source = str_replace("list_price", "listPrice", $source);
$source = str_replace("price_message", "priceMessage", $source);
$source = str_replace("&action=view", "", $source);
$source = preg_replace("/dialog\.(?!(tpl|allparams))/", "", $source);
$source = str_replace("getURL", "shopURL", $source);
$source = preg_replace("/registration_form\[([^\]]*)\]/", "\\1", $source);
$source = preg_replace("/profile_form\[([^\]]*)\]/", "\\1", $source);

?>
