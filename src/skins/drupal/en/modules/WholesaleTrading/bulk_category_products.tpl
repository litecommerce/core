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
<script language="JavaScript" type="text/javascript">
function selectProduct(p_id)
{
	var inputField = document.getElementById("productQty_" + p_id );
	var checkBox = document.getElementById("productSelected_" + p_id);
	var isNav4 = (navigator.appName == "Netscape") ? true : false;
    
    if (inputField != null) {
        if (isNav4) {
            if (inputField.hasAttribute('disabled')) {
                inputField.removeAttribute('disabled');
            } else {
                inputField.setAttribute('disabled', true);
            }
        } else {
            inputField.disabled = !(checkBox.checked);
        }
    }
}
</script>

<form name="bulk_shopping_form" action="{getShopUrl(#cart.php#)}" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="bulk">

<!-- error messages -->
<table border="0">
<tbody FOREACH="errors,key,value">
<tr>
	<td valign="top"><font class="ErrorMessage">*</font></td>
	<td><font class="ErrorMessage">
	Product "{value.pr_name}{if:value.options} ({foreach:value.options,k,v}{k}:{v}; {end:}){end:}" cannot be added to cart. 
	{if:value.amount=0}
	<span>The product {if:value.options}options combination{end:} is out of stock.</span>
	{else:}
	<span IF="value.type=#max#">You cannot buy more than {value.amount} items.</span>
	<span IF="value.type=#min#">You cannot buy less than {value.amount} items.</span>
	{end:}
	</font></td>
</tr>
</tbody>
<tr><td colspan=2>&nbsp;</td></tr>
</table>

<!-- product list -->
<table cellpadding="3" cellspacing="1" border="0" bgcolor="#cccccc" width="100%">
<tr class="TableHead">
	<td><b>Qty.</b></td>
	<td><b>Product</b></td>
	<td><b>Price</b></td>
	<td IF="{calculate}" nowrap><b>Sum. Price</b></td>
</tr>

<tbody FOREACH="category.products,p_key,product">

<!-- product has options -->
<tr IF="product.hasOptions()" bgcolor="#ffffff">
	<td>&nbsp;</td>
	<td colspan="{selectString(#3#,#2#,calculate)}">
       <span IF="isProductError(product.product_id,key)"><FONT color="red" size="2"><b>&gt;&gt;</b></FONT></span><a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT class="ProductTitle"><u>{product.name:h}</u></FONT></a><span IF="isProductError(product.product_id,key)"><FONT color="red" size="2"><b>&lt;&lt;</b></FONT></span>
	</td>
</tr>

<tr FOREACH="getProductExpandedItems(product),key,opts" bgcolor="#ffffff">
	<td nowrap valign="top">
		<input IF="product.saleAvailable" type="checkbox" size="5" name="opt_product_selected[{product.product_id}][{key}]" id="productSelected_{product.product_id}_{key}" onClick="selectProduct('{product.product_id}_{key}')" {checked(productSelected(product.product_id,key))} {disabled(isProductOutOfStock(product.product_id,key))} />
		<span IF="product.saleAvailable">
		<input size="5" name="opt_product_qty[{product.product_id}][{key}]" value="{quantity(product.product_id,key)}" id="productQty_{product.product_id}_{key}" {disabled(!productSelected(product.product_id,key))} />
		</span>
	</td>
	<td width="100%" IF="product.hasOptions()">
    &nbsp;&nbsp;
    <span FOREACH="opts,option">
        {option.class}: {option.option}
    </span>
	<span IF="isProductOutOfStock(product.product_id,key)" class="ErrorMessage">(out of stock)</span>
    </td>
	<td align="right" nowrap>
		<span IF="!wholesale_prices(product.product_id,key)">{price_format(product.getFullPrice(quantity(product.product_id,key),key,0)):h}&nbsp;</span>
		<span IF="wholesale_prices(product.product_id,key)">{price_format(wholesale_prices(product.product_id,key)):h}&nbsp;</span>
	</td>
	<td IF="calculate=#true#" nowrap align="right">
		{price_format(total_price(product.product_id,key)):h}
	</td>
</tr>
<!-- end line -->

<!-- product has no options -->
<tr IF="!product.hasOptions()" bgcolor="#ffffff">
	<td nowrap valign="top">
		<input IF="product.saleAvailable" type="checkbox" size="5" name="product_selected[{product.product_id}]" id="productSelected_{product.product_id}" onClick="selectProduct({product.product_id})" {checked(productSelected(product.product_id))} {disabled(isProductOutOfStock(product.product_id))} />
		<span IF="product.saleAvailable">
		<input size="5" name="product_qty[{product.product_id}]" value="{quantity(product.product_id)}" id="productQty_{product.product_id}" {disabled(!productSelected(product.product_id))} />
		</span>
	</td>
    <td width="100%">
		<span IF="isProductError(product.product_id)"><FONT color="red" size="2"><b>&gt;&gt;</b></FONT></span>
		<a href="cart.php?target=product&amp;product_id={product.product_id}&amp;category_id={category_id}"><FONT class="ProductTitle"><u>{product.name:h}</u></FONT></a>
		<span IF="isProductError(product.product_id)"><FONT color="red" size="2"><b>&lt;&lt;</b></FONT></span>
		<span IF="isProductOutOfStock(product.product_id)" class="ErrorMessage">(out of stock)</span>
	</td>
	
	<td align="right" nowrap>
		<span IF="!wholesale_prices(product.product_id)">{price_format(product.listPrice):h}</span>
		<span IF="wholesale_prices(product.product_id)">{price_format(wholesale_prices(product.product_id)):h}</span>
	</td>
	<td IF="calculate=#true#" nowrap align="right">{price_format(total_price(product.product_id)):r}</td>
</tr>
<!-- end line -->
</tbody>

<!-- subtotal -->
<tbody IF="calculate=#true#">
<tr bgcolor="#ffffff">
	<td colspan="4" align="right"><font class="ProductPriceTitle">Subtotal:</font>&nbsp;<font class="ProductPrice">{price_format(subtotal):r}</font></td>
</tr>
</tbody>
</table>

<!-- buttons -->
<br><br>
<table border="0" cellpadding="5" cellspacing="0">
<tr>
	<td>
	<widget class="XLite_View_Button" label="Reset" href="javascript: document.bulk_shopping_form.action.value=''; document.bulk_shopping_form.submit();">
	</td>	
	<td>
	<widget class="XLite_View_Button" label="Calculate" href="javascript: document.bulk_shopping_form.action.value='calculate_price'; document.bulk_shopping_form.submit();">
	</td>
</tr>
<tr>
	<td colspan="2">
	<widget class="XLite_View_Button" label="Add to Cart" href="javascript: document.bulk_shopping_form.submit()" img="cart4button.gif" font="FormButton">
	</td>
</tr>	
</table>
</form>
