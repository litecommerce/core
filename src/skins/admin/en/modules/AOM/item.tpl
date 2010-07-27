{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table width="100%" IF="item" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td colspan="2" height="44px" valign="center"><table cellpadding="0" cellspacing="0" width="100%"><tr><td valign="top">{if:clone}<input type="checkbox" name="delete_items[]" style="margin: 0px 5px 0px 5px;" value="{item.uniqueKey:h}" onClick="javascript: this.blur()">{else:}<input type="checkbox" name="split_items[]" style="margin: 0px 5px 0px 5px;" value="{item.uniqueKey:h}" onClick="javascript: this.blur();populateArray('{item.uniqueKey:h}');">{end:}</td><td valign="top" class="DialogTitle"><a href="admin.php?target=product&product_id={item.product_id}" target="_blank">Product #{item.product_id} - {item.product_name:h}</a></td></tr></table></td>
</tr>
<tr>
	<td width="100" height="100" valign="top" align="center">{if:item.product.hasThumbnail()}<img src="{item.product.thumbnailURL:h}" border="0" hspace="3px" vspace="3px" width="70">{else:}<img src="images/modules/AOM/no_image.gif" border="0" hspace="3px" vspace="3px">{end:}</td>
	<td valign="top">
		<table cellpadding="3" cellspacing="0" border="0">
			<tr IF="item.product_sku">
				<td height="25" class="ProductDetailsTitle">SKU:</td>
				<td align="right">{item.product_sku:h}</td>
			</tr>
			<tr>
				<td width="100" height="25" class="ProductDetailsTitle">Quantity:</td>
				<td IF="clone" align="right"><input type="text" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;" size="6" name="clone_products[{item.uniqueKey:h}][amount]" value="{item.amount}"></td>
				<td IF="!clone">{item.amount}</td>
			</tr>
			<tr>
                <td height="25" class="ProductDetailsTitle">{if:clone&item.hasOptions()}Base{end:} Price:</td>
                <td align="right">
{if:clone}
                <input type="text" size="6" style="BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3;" 
                    name="clone_products[{item.uniqueKey:h}][price]" 
    {if:config.Taxes.prices_include_tax}
                    value="{item.originalPrice}"
    {else:}
                    value="{item.properties.price}"
    {end:} 
    {if:item.hasWholesalePricing()&!isSelected(item.properties.price,item.price)}
                    disabled> 
                <input type="hidden" 
                    name="clone_products[{item.uniqueKey:h}][price]" 
        {if:config.Taxes.prices_include_tax}
                    value="{item.originalPrice}"
        {else:}
                    value="{item.properties.price}"
        {end:} 
                >
    {else:}
                >
    {end:} 
{else:} 
                {price_format(item.properties.price):h}
{end:}
                </td>
            </tr>
            <tr IF="{config.Taxes.prices_include_tax&clone}">
                <td colspan="2"><font color="red" IF="{config.Taxes.prices_include_tax}">Price without taxes.</font></td>
            </tr>
            <tr IF="{config.Taxes.prices_include_tax&clone}">
                <td height="25" class="ProductDetailsTitle">Taxed Price:</td>
                <td align="right">{price_format(item.properties.price):h}</td>
            </tr>			
            <tr IF="{clone&item.hasOptions()}">
                <td height="25" class="ProductDetailsTitle">Estimated price:</td>
                <td align="right">{price_format(item.price):h}</td>
            </tr>
            <tr IF="{item.hasWholesalePricing()&!isSelected(item.properties.price,item.price)}">
                <td height="25" class="ProductDetailsTitle">Wholesale price:</td>
				<td align="right">{price_format(item.price):h}</td>
            </tr>
			<tr IF="xlite.PromotionEnabled&item.bonusItem">
                <td height="25" class="ProductDetailsTitle">Bonus price:</td>
                <td align="right">{price_format(item.price):h}</td>
			</tr>
            <tr IF="xlite.PromotionEnabled&item.discountCouponApplies">
                <td height="25" class="ProductDetailsTitle">Discounted price:</td>
                <td align="right">{price_format(item.price):h}</td>
            </tr>
			<tr>
				<td height="25" class="ProductDetailsTitle">Total:</td>
				<td align="right">{price_format(item.total):h}</td>
			</tr>
		</table>
		<table>	
			<tr>
				<td colspan="2">{if:clone}<widget module="ProductOptions" item="{item}" template="modules/AOM/product_options.tpl" IF="{item.hasOptions()}">{else:}<widget module="ProductOptions" item="{item}" template="modules/AOM/selected_options.tpl" IF="{item.hasOptions()}">{end:}</td>
			</tr>
			<tr>
		    	<td colspan="2">&nbsp;</td>
			</tr>
		</table>
    </td>
</tr>
</table>
<table IF="!item" width="100%" height="150" cellpadding="0" cellspacing="0" border="0" valign=="center">
	<tr align="center" valign="center">
		<td class="OrderTitle" style="font-size: 15px">{if:clone}Product deleted{else:}No product{end:}</td>
	</tr>
</table>
