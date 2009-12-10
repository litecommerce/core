<?php

	$find_str = <<<EOT
        <widget module="ProductOptions" template="modules/ProductOptions/product_quantity.tpl" visible="{product.inventory.found}"/>
EOT;
	$replace_str = <<<EOT
        <widget module="ProductOptions" template="modules/ProductOptions/product_quantity.tpl">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice" nowrap>{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>
EOT;
	$replace_str = <<<EOT
        <tr><td width="30%" class="ProductPriceTitle">Price:</td><td class="ProductPrice">{price_format(product,#listPrice#):h}<font class="ProductPriceTitle"> {product.priceMessage:h}</font></td></tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>