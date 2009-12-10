<?php
    $find_str = <<<EOT
<tr>
	<td valign="top">Address</td>
	<td>{config.Company.location_address} <br>
	{config.Company.location_zipcode} {config.Company.location_city} {config.Company.location_country}
	</td>
</tr>
<tr>
EOT;
    $replace_str = <<<EOT
<tr>
	<td valign="top">Address</td>
	<td>{config.Company.location_address} <br>
	{config.Company.location_city} {config.Company.locationState.state} {config.Company.location_zipcode} <br>
	{config.Company.location_country}
	</td>
</tr>
<tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
	<td>Fax</td>
	<td>{config.Company.company_fax}</td>
</tr>
</table>
<br>
<br>
EOT;
    $replace_str = <<<EOT
	<td>Fax</td>
	<td>{config.Company.company_fax}</td>
</tr>
<tr if="order.isTaxRegistered()">
    <td colspan="2">
		<table cellpadding=0 cellspacing=0 border=0 width="100%">    
		<tr>
			<td>Tax registration numbers:</td>
			<td>&nbsp;</td>
		</tr>
		<tr FOREACH="order.getDisplayTaxes(),tax_name,tax">
			<td>{order.getTaxLabel(tax_name)}</td>
			<td>{order.getRegistration(tax_name)}</td>
		</tr>
		</table>
	</td>	
</tr>
</table>
<br>
<br>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
		<td nowrap><b>Total</b></td>
		<td>{price_format(order,#total#):h}</td>
	</tr>
</table>
<br>
<br>
EOT;
    $replace_str = <<<EOT
		<td nowrap><b>Total</b></td>
		<td>{price_format(order,#total#):h}</td>
	</tr>
	<widget module=Promotion template="modules/Promotion/order_offers.tpl">
</table>
<br>
<br>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
