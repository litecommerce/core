<?php

	$find_str = <<<EOT
        <widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/cart_item.tpl" visible="{xlite.PA_InventorySupport}">
        <br>
        <br>
        <widget class="CButton" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
        <span IF="!item.valid"><font class="ProductPriceSmall"><br>(!) This product is out of stock or it has been disabled for sale.</font></span>
EOT;
	$replace_str = <<<EOT
        <widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/cart_item.tpl" visible="{xlite.PA_InventorySupport}">
        <br>
        <br>
		<table><tr><td>
        <widget class="CButton" label="Delete item" href="cart.php?target=cart&action=delete&cart_id={cart_id}" font="FormButton">
		</td><td>&nbsp;</td><td>
		<widget class="CButton" label="Update item" href="javascript: document.cart_form.action.value='update'; document.cart_form.submit()" font="FormButton">
		</td></tr></table>
		<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/item.tpl">
        <span IF="!item.valid"><font class="ProductPriceSmall"><br>(!) This product is out of stock or it has been disabled for sale.</font></span>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>