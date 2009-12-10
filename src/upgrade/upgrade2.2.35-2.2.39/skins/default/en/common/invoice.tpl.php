<?php

	$find_str = <<<EOT
        <td>{order.details.cc_cvv2:h}</td>
    </tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
    </tbody>

	<tr>
EOT;
	$replace_str = <<<EOT
        <td>{order.details.cc_cvv2:h}</td>
    </tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
    </tbody>

    <tbody IF="order.showECheckInfo&adminMail">
    <tr>
        <td nowrap><b>eCheck information:</b></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_routing_number:h}</td>
        <td>{order.details.ch_routing_number:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_acct_number:h}</td>
        <td>{order.details.ch_acct_number:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_type:h}</td>
        <td>{order.details.ch_type:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_bank_name:h}</td>
        <td>{order.details.ch_bank_name:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_acct_name:h}</td>
        <td>{order.details.ch_acct_name:h}</td>
    </tr>
    <tr IF="order.details.ch_number">
        <td nowrap>{order.detail_labels.ch_number:h}</td>
        <td>{order.details.ch_number:h}</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>

	<tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
	<tr>
		<td nowrap>Delivery</td>
		<td>{order.shippingMethod.name:h}</td>
	</tr>
	<tr>
EOT;
	$replace_str = <<<EOT
	<tr>
		<td nowrap>Delivery</td>
		<td>{if:order.shippingMethod}{order.shippingMethod.name:h}{else:}N/A{end:}</td>
	</tr>
	<widget module="WholesaleTrading" template="modules/WholesaleTrading/invoice.tpl">
	<tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
		<td>{price_format(order,#subtotal#):h}</td>
	</tr>
	<widget module="WholesaleTrading" template="modules/WholesaleTrading/invoice.tpl">
    <widget module="Promotion" template="modules/Promotion/invoice_discount.tpl">
	<tr>
EOT;
	$replace_str = <<<EOT
		<td>{price_format(order,#subtotal#):h}</td>
	</tr>
    <widget module="Promotion" template="modules/Promotion/invoice_discount.tpl">
	<tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
		<td>{price_format(order,#shipping_cost#):h}</td>
	</tr>
    <widget module="Promotion" template="modules/Promotion/invoice.tpl">
	<tr FOREACH="order.getDisplayTaxes(),tax_name,tax">
    		<td nowrap>{order.getTaxLabel(tax_name)}</td>
		<td>{price_format(tax):h}</td>
	</tr>
    <widget module="GiftCertificates" template="modules/GiftCertificates/invoice.tpl">
	<tr>
EOT;
	$replace_str = <<<EOT
		<td>{price_format(order,#shipping_cost#):h}</td>
	</tr>
	<tr FOREACH="order.getDisplayTaxes(),tax_name,tax">
    		<td nowrap>{order.getTaxLabel(tax_name)}</td>
		<td>{price_format(tax):h}</td>
	</tr>
    <widget module="Promotion" template="modules/Promotion/invoice.tpl">
    <widget module="GiftCertificates" template="modules/GiftCertificates/invoice.tpl">
	<tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>