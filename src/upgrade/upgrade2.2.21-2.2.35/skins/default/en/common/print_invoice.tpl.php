<?php

	$find_str = <<<EOT
<table border="0" cellpadding="3" cellspacing="0" width="600">
<tr>
<td valign="top" width="300">
<FONT color=#B2B2B3 style="font-size: 30px;"><b>INVOICE</b></FONT><BR>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td nowrap><b>Order id</b></td>
EOT;

	$replace_str = <<<EOT
<table border="0" cellpadding="3" cellspacing="0" width="600">
<tr>
<td valign="top" width="300">
<FONT color="#B2B2B3" style="font-size: 30px;"><b>INVOICE</b></FONT><BR>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td nowrap><b>Order id</b></td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


	$find_str = <<<EOT
	<tbody FOREACH="order.items,item">
		<tr>
			<td><b>{item.name:h}</b><BR>
				<table><widget module="ProductOptions" template="modules/ProductOptions/invoice_options.tpl"></table></td>
			<td align="center">{item.sku:h}</td>
			<td align="center">{item.amount:h}</td>
			<td align="right">{price_format(item,#price#):h}</td>
EOT;

	$replace_str = <<<EOT
	<tbody FOREACH="order.items,item">
		<tr>
			<td><b>{item.name:h}</b><BR>
				<table><widget module="ProductOptions" template="modules/ProductOptions/invoice_options.tpl">
<widget module="Egoods" template="modules/Egoods/invoice.tpl"></table></td>
			<td align="center">{item.sku:h}</td>
			<td align="center">{item.amount:h}</td>
			<td align="right">{price_format(item,#price#):h}</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
