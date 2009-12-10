<?php

$source = "{* E-mail sent to admin when product quantity reaches low stock limit *}\n" . $source;
$source = strReplace('ID# {product.product_id}', 'Product ID# {product.product_id}', $source, __FILE__, __LINE__);
$source = strReplace('Product name: {product.name:h}<br>', 'Product name: {product.name:h}<br>'."\n".'<widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" IF="product.productOptions"/>'."\n", $source, __FILE__, __LINE__);

?>
