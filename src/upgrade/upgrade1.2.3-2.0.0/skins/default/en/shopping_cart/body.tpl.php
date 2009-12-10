<?php

$source = strReplace('cart.isEmpty()', 'cart.empty', $source, __FILE__, __LINE__);
$source = strReplace('<input type="hidden" name="target" value="cart">', '<input type="hidden" foreach="params,param" name="{param}" value="{get(param)}"/>', $source, __FILE__, __LINE__);
$source = strReplace('{itemWidget.display(cart_id,item)}', '<widget template="shopping_cart/item.tpl" IF="item.useStandardTemplate"/>', $source, __FILE__, __LINE__);
$source = strReplace('{GCItemWidget.display(cart_id,item)}', '<widget module="GiftCertificates" template="modules/GiftCertificates/item.tpl" IF="item.gcid"/>', $source, __FILE__, __LINE__);
$source = strReplace('{delivery.display()}', '<widget template="shopping_cart/delivery.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{totals.display()}', '<widget template="shopping_cart/totals.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{buttons.display()}', '<widget template="shopping_cart/buttons.tpl">', $source, __FILE__, __LINE__);

?>
