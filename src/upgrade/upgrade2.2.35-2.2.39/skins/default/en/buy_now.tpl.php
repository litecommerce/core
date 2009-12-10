<?php

	$find_str = <<<EOT
<widget IF="config.General.buynow_button_enabled" class="CButton" label="Buy Now" href="cart.php?target=product&action=buynow&product_id={widget.product.product_id}" img="cart4button.gif"/>
EOT;
	$replace_str = <<<EOT
<widget IF="config.General.buynow_button_enabled" class="CButton" label="Buy Now" href="cart.php?target=product&action=buynow&product_id={widget.product.product_id}&category_id={widget.product.category.category_id}" img="cart4button.gif"/>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>