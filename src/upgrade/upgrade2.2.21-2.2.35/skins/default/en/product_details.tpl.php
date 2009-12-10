<?php

	$find_str = <<<EOT
-->
</script>

<form action="{shopURL(#cart.php#)}" method=GET name="add_to_cart" onSubmit="isValid()">
<input type="hidden" name="target" value="cart">
<input type="hidden" name="action" value="add">
<input type="hidden" name="product_id" value="{product.product_id}">
EOT;
	$replace_str = <<<EOT
-->
</script>

<form action="{shopURL(#cart.php#)}" method="GET" name="add_to_cart" onSubmit="isValid()">
<input type="hidden" name="target" value="cart">
<input type="hidden" name="action" value="add">
<input type="hidden" name="product_id" value="{product.product_id}">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
        <widget module="ProductOptions" template="modules/ProductOptions/product_quantity.tpl" visible="{product.inventory.found}"/>

        <widget class="CExtraFields" product="{product}">
        <tr IF="{!product.weight=0}"><td width="30%" class="ProductDetails">Weight:</td><td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td></tr>
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice" nowrap>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>

EOT;
	$replace_str = <<<EOT
        <widget module="ProductOptions" template="modules/ProductOptions/product_quantity.tpl" visible="{product.inventory.found}"/>

        <widget class="CExtraFields" product="{product}">
        <tbody>
        <tr IF="{!product.weight=0}"><td width="30%" class="ProductDetails">Weight:</td><td class="ProductDetails" nowrap>{product.weight} {config.General.weight_symbol}</td></tr>
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice" nowrap>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>

EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
