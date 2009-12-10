<?php

	$find_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped&cart.getShippingRates()" align="right">
<b>Delivery:&nbsp;&nbsp;</b><select name="shipping" onChange="cart_form.submit()">
<option FOREACH="cart.getShippingRates(),key,rate" value="{rate.shipping.shipping_id}" selected="{cart.isSelected(#shipping_id#,key)}">{rate.shipping.name:h} {price_format(rate,#rate#):h}</option>
</select>
</p>
EOT;
	$replace_str = <<<EOT
<p IF="cart.shippingAvailable&cart.shipped&cart.getShippingRates()" align="right">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">
<span IF="!xlite.UPSOnlineToolsEnabled">
<b>Delivery:&nbsp;&nbsp;</b><select name="shipping" onChange="cart_form.submit()">
<option FOREACH="cart.getShippingRates(),key,rate" value="{rate.shipping.shipping_id}" selected="{cart.isSelected(#shipping_id#,key)}">{rate.shipping.name:h} {price_format(rate,#rate#):h}</option>
</select>
</span> {* /UPSOnlineTools *}
</p>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>