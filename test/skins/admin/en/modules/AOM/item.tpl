<table width="100%" IF="widget.item" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td colspan="2" height="44px" valign="center"><table cellpadding="0" cellspacing="0" width="100%"><tr><td valign="top">{if:widget.clone}<input type="checkbox" name="delete_items[]" style="margin: 0px 5px 0px 5px;" value="{widget.item.uniqueKey:h}" onClick="javascript: this.blur()">{else:}<input type="checkbox" name="split_items[]" style="margin: 0px 5px 0px 5px;" value="{widget.item.uniqueKey:h}" onClick="javascript: this.blur();populateArray('{widget.item.uniqueKey:h}');">{end:}</td><td valign="top" class="DialogTitle"><a href="admin.php?target=product&product_id={widget.item.product_id}" target="_blank">Product #{widget.item.product_id} - {widget.item.product_name:h}</a></td></tr></table></td>
</tr>
<tr>
	<td width="100" height="100" valign="top" align="center">{if:widget.item.product.hasThumbnail()}<img src="{widget.item.product.thumbnailURL:h}" border="0" hspace="3px" vspace="3px" width="70">{else:}<img src="images/modules/AOM/no_image.gif" border="0" hspace="3px" vspace="3px">{end:}</td>
	<td valign="top">
		<table cellpadding="3" cellspacing="0" border="0">
			<tr IF="widget.item.product_sku">
				<td height="25" class="ProductDetailsTitle">SKU:</td>
				<td align="right">{widget.item.product_sku:h}</td>
			</tr>
			<tr>
				<td width="100" height="25" class="ProductDetailsTitle">Quantity:</td>
				<td IF="widget.clone" align="right"><input type="text" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;" size="6" name="clone_products[{widget.item.uniqueKey:h}][amount]" value="{widget.item.amount}"></td>
				<td IF="!widget.clone">{widget.item.amount}</td>
			</tr>
			<tr>
                <td height="25" class="ProductDetailsTitle">{if:widget.clone&widget.item.hasOptions()}Base{end:} Price:</td>
                <td align="right">
{if:widget.clone}
                <input type="text" size="6" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;" 
                    name="clone_products[{widget.item.uniqueKey:h}][price]" 
    {if:config.Taxes.prices_include_tax}
                    value="{widget.item.originalPrice}"
    {else:}
                    value="{widget.item.properties.price}"
    {end:} 
    {if:widget.item.hasWholesalePricing()&!isSelected(widget.item.properties.price,widget.item.price)}
                    disabled> 
                <input type="hidden" 
                    name="clone_products[{widget.item.uniqueKey:h}][price]" 
        {if:config.Taxes.prices_include_tax}
                    value="{widget.item.originalPrice}"
        {else:}
                    value="{widget.item.properties.price}"
        {end:} 
                >
    {else:}
                >
    {end:} 
{else:} 
                {price_format(widget.item.properties.price):h}
{end:}
                </td>
            </tr>
            <tr IF="{config.Taxes.prices_include_tax&widget.clone}">
                <td colspan="2"><font color="red" IF="{config.Taxes.prices_include_tax}">Price without taxes.</font></td>
            </tr>
            <tr IF="{config.Taxes.prices_include_tax&widget.clone}">
                <td height="25" class="ProductDetailsTitle">Taxed Price:</td>
                <td align="right">{price_format(widget.item.properties.price):h}</td>
            </tr>			
            <tr IF="{widget.clone&widget.item.hasOptions()}">
                <td height="25" class="ProductDetailsTitle">Estimated price:</td>
                <td align="right">{price_format(widget.item.price):h}</td>
            </tr>
            <tr IF="{widget.item.hasWholesalePricing()&!isSelected(widget.item.properties.price,widget.item.price)}">
                <td height="25" class="ProductDetailsTitle">Wholesale price:</td>
				<td align="right">{price_format(widget.item.price):h}</td>
            </tr>
			<tr IF="xlite.PromotionEnabled&widget.item.bonusItem">
                <td height="25" class="ProductDetailsTitle">Bonus price:</td>
                <td align="right">{price_format(widget.item.price):h}</td>
			</tr>
            <tr IF="xlite.PromotionEnabled&widget.item.discountCouponApplies">
                <td height="25" class="ProductDetailsTitle">Discounted price:</td>
                <td align="right">{price_format(widget.item.price):h}</td>
            </tr>
			<tr>
				<td height="25" class="ProductDetailsTitle">Total:</td>
				<td align="right">{price_format(widget.item.total):h}</td>
			</tr>
		</table>
		<table>	
			<tr>
				<td colspan="2">{if:widget.clone}<widget module="ProductOptions" item="{widget.item}" template="modules/AOM/product_options.tpl" visible="{widget.item.hasOptions()}">{else:}<widget module="ProductOptions" item="{widget.item}" template="modules/AOM/selected_options.tpl" visible="{widget.item.hasOptions()}">{end:}</td>
			</tr>
			<tr>
		    	<td colspan="2">&nbsp;</td>
			</tr>
		</table>
    </td>
</tr>
</table>
<table IF="!widget.item" width="100%" height="150" cellpadding="0" cellspacing="0" border="0" valign=="center">
	<tr align="center" valign="center">
		<td class="OrderTitle" style="font-size: 15px">{if:widget.clone}Product deleted{else:}No product{end:}</td>
	</tr>
</table>
