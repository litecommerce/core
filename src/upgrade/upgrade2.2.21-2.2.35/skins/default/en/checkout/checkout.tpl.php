<?php

	$find_str = <<<EOT
<td><input type=text size=3 name="amount[{key}]" value="{item.amount}"></td>
<td>{item.sku}</td>
<td IF="!item.hasOptions()"><b><span IF="item.product.product_id"><a href="cart.php?target=product&amp;product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b></td>
<td IF="item.hasOptions()" id="close{key}" style="cursor: hand;" onClick="visibleBox('{key}')"><b><a href="{item.url}">{truncate(item,#name#,#30#):h}</a></b><font IF="{item.hasOptions()}" class=SidebarItem><br>&nbsp;&nbsp;<img src="images/modules/ProductOptions/open.gif" width="13" height="13" border="0" align="absmiddle" alt="Click to view selected product options">&nbsp;Selected options</font></td>
<td IF="item.hasOptions()" id="open{key}" style="display: none; cursor: hand;" onClick="visibleBox('{key}')"><b><span IF="item.product.product_id"><a href="cart.php?target=product&amp;product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b><span IF="{item.hasOptions()}"><table border=0 cellpadding=0 cellspacing=0><tr><td>&nbsp;&nbsp;<img src="images/modules/ProductOptions/close.gif" width="13" height="13" border="0" align="absmiddle" alt="hide options list"></td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td><widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}"></td></tr></table></span></td>
<td class="ProductPriceSmall" align="right">{price_format(item,#price#):h}</td>
<td class="ProductPriceSmall" align="right">{price_format(item,#total#):h}</td>
</tr>
EOT;

	$replace_str = <<<EOT
<td><input type=text size=3 name="amount[{key}]" value="{item.amount}"></td>
<td>{item.sku}</td>
<td IF="!item.hasOptions()"><b><span IF="item.product.product_id"><a href="cart.php?target=product&amp;product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b></td>
<td IF="item.hasOptions()" id="close{key}" style="cursor: hand;" onClick="visibleBox('{key}')"><b><a href="{item.url}">{truncate(item,#name#,#30#):h}</a></b><font IF="{item.hasOptions()}" class=SidebarItem><br>&nbsp;&nbsp;<img src="images/modules/ProductOptions/open.gif" width="13" height="13" border="0" align="middle" alt="Click to view selected product options">&nbsp;Selected options</font></td>
<td IF="item.hasOptions()" id="open{key}" style="display: none; cursor: hand;" onClick="visibleBox('{key}')"><b><span IF="item.product.product_id"><a href="cart.php?target=product&amp;product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b><div IF="{item.hasOptions()}"><table border=0 cellpadding=0 cellspacing=0><tr><td>&nbsp;&nbsp;<img src="images/modules/ProductOptions/close.gif" width="13" height="13" border="0" align="middle" alt="hide options list"></td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td><widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}"></td></tr></table></div></td>
<td class="ProductPriceSmall" align="right">{price_format(item,#price#):h}</td>
<td class="ProductPriceSmall" align="right">{price_format(item,#total#):h}</td>
</tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
