<?php

	$find_str = <<<EOT
	<td>{order.shippingMethod.name:h}</td>
EOT;
	$replace_str = <<<EOT
	<td>{if:order.shippingMethod}{order.shippingMethod.name:h}{else:}N/A{end:}</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
			<td align="center">{item.sku:h}</td>
			<td align="center">{item.amount:h}</td>
EOT;
	$replace_str = <<<EOT
			<td align="center">{item.sku:h}&nbsp;</td>
			<td align="center">{item.amount:h}</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
			<td nowrap colspan="4" align="right">
			<b>Subtotal</b><br>
            <widget module="Promotion" template="modules/Promotion/print_invoice_discount_label.tpl">
            <b>Shipping cost</b><br>
            <widget module="Promotion" template="modules/Promotion/print_invoice_label.tpl">
            <span FOREACH="order.getDisplayTaxes(),tax_name,tax">
    		<b>{order.getTaxLabel(tax_name)}</b><br></span>
            <widget module="GiftCertificates" template="modules/GiftCertificates/print_invoice_label.tpl">
			<b>TOTAL</b>
EOT;
	$replace_str = <<<EOT
			<td nowrap colspan="4" align="right">
			<widget module="WholesaleTrading" template="modules/WholesaleTrading/print_invoice_discount_label.tpl" ignoreErrors>
			<b>Subtotal</b><br>
            <widget module="Promotion" template="modules/Promotion/print_invoice_discount_label.tpl">
            <b>Shipping cost</b><br>
            <span FOREACH="order.getDisplayTaxes(),tax_name,tax">
    		<b>{order.getTaxLabel(tax_name)}</b><br></span>
            <widget module="Promotion" template="modules/Promotion/print_invoice_label.tpl">
            <widget module="GiftCertificates" template="modules/GiftCertificates/print_invoice_label.tpl">
			<b>TOTAL</b>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
			</td>
			<td align="right">
			{price_format(order,#subtotal#):h}<br>
            <widget module="Promotion" template="modules/Promotion/print_invoice_discount.tpl">
			{price_format(order,#shipping_cost#):h}<br>
            <widget module="Promotion" template="modules/Promotion/print_invoice_total.tpl">
		        <span FOREACH="order.getDisplayTaxes(),tax_name,tax">
			{price_format(tax):h}<br></span>
            <widget module="GiftCertificates" template="modules/GiftCertificates/print_invoice.tpl">
			<b>{price_format(order,#total#):h}</b>
EOT;
	$replace_str = <<<EOT
			</td>
			<td align="right">
			<widget module="WholesaleTrading" template="modules/WholesaleTrading/print_invoice_discount.tpl" ignoreErrors>
			{price_format(order,#subtotal#):h}<br>
            <widget module="Promotion" template="modules/Promotion/print_invoice_discount.tpl">
			{price_format(order,#shipping_cost#):h}<br>
		        <span FOREACH="order.getDisplayTaxes(),tax_name,tax">
			{price_format(tax):h}<br></span>
            <widget module="Promotion" template="modules/Promotion/print_invoice_total.tpl">
            <widget module="GiftCertificates" template="modules/GiftCertificates/print_invoice.tpl">
			<b>{price_format(order,#total#):h}</b>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>