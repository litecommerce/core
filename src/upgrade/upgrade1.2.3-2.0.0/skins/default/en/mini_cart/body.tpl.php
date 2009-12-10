<?php

$source = strReplace('cart.isEmpty()', 'cart.empty', $source, __FILE__, __LINE__);
$source = strReplace('cart.getItemsCount()', 'cart.itemsCount', $source, __FILE__, __LINE__);

?>
