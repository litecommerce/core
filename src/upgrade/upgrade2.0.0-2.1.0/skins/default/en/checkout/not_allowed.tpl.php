<?php

$notAllowedAdd =<<<EOT
<p IF="cart.maxOrderAmountError">
In order to perform checkout your order subtotal must be less than {price_format(config.General.maximal_order_amount):h}
</p>
<p IF="cart.minOrderAmountError">
EOT;

$source = $notAllowedAdd . $source;
$source = strReplace('{price_format(config.General.minimal_order_amount):h}', '{price_format(config.General.minimal_order_amount):h}</p>', $source, __FILE__, __LINE__);

?>
