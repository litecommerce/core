<?php

	$find_str = <<<EOT
<p IF="cart.minOrderAmountError">
In order to perform checkout your order subtotal must be more than {price_format(config.General.minimal_order_amount):h}
</p>
<p>
<widget class="CButton" label="Go back" href="javascript: history.go(-1)" font="FormButton">
</p>

EOT;

	$replace_str = <<<EOT
<p IF="cart.minOrderAmountError">
In order to perform checkout your order subtotal must be more than {price_format(config.General.minimal_order_amount):h}
</p>
<div>
<widget class="CButton" label="Go back" href="javascript: history.go(-1)" font="FormButton">
</div>

EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
