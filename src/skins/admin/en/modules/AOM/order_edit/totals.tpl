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
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr valign="top">
            <td align="left" class="OrderTitle" style="font-size: 20px">Order #{order.order_id:h}: Totals</td>
	        <td IF="target=#order#" align="right" valign="center"><widget template="modules/AOM/common/clone_button.tpl"></td>
    </tr>   
</table>    
<br>
<script language="JavaScript">
<!--
	var shipping 	= new Array();

	function changeShippingCost(shipping_id)
	{
		var shipping_cost = document.getElementById("shipping_cost");
		if (shipping[shipping_id] == 0 || shipping_id == "")	{ 
			shipping_cost.value = "0.00";
			shipping_cost.disabled = true;
		}		
		else {
			shipping_cost.value = shipping[shipping_id];
			shipping_cost.disabled = false;
		}	
	}

	function price_format(price) 
	{
		var x, cnt, top, botom;
		var precision = 2;
		number_format_th = ",";
		number_format_dec = ".";
	    precision = Math.pow(10, precision);
	    price = Math.round(price*precision)/precision;
	    top = Math.floor(price);
	    bottom = Math.round((price-top)*precision)+precision;
	    top = top+"";
		bottom = bottom+"";
	    cnt = 0;
		for(x = top.length; x >= 0; x--) {
			if(cnt%3 == 0 && cnt > 0 && x > 0)
				top = top.substr(0,x)+number_format_th+top.substr(x,top.length);
	        cnt++;
	    }
		price = top+number_format_dec+bottom.substr(1,bottom.length);
		return price;
	}
-->
</script>
<table width="100%" cellpadding="2" cellspacing="2">
	<tr>
    	<th width="200" height="25" class="AomTableHead">Product</th>
		<th height="25" class="AomTableHead">SKU</th>
		<th height="25" class="AomTableHead">Quantity</th>
		<th height="25" class="AomTableHead">Item price</th>
		<th height="25" class="AomTableHead" width="100">Total</th>
	</tr>
	<tbody IF="cloneOrder.items">
	<tr FOREACH="cloneOrder.items,item" valign="top" class="{getRowClass(#0#,#TableRow#)}">
		<td class="ProductDetailsTitle">{item.product_name}<br>
			<table cellspacing="5">
            <widget module="ProductOptions" template="modules/AOM/invoice_options.tpl" item="{item}" IF="{item.hasOptions()}">
            <widget module="Egoods" template="modules/Egoods/invoice.tpl">
            </table>
		</td>
		<td>{item.product_sku}</td>
		<td align="center">{item.amount}</td>
		<td align="right">{price_format(item.price):h}</td>
		<td align="right">{price_format(item.total):h}</td>
	</tr>
	</tbody>
	<tr IF="!cloneOrder.items">
		<td colspan="5" class="ProductDetailsTitle" align="center">No products</td>
	</tr>
	<tr>
		<td colspan="5" valign="top">&nbsp;</td>
	</tr>
</table>

<form action="admin.php" method="POST" name="totals_form">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="update_totals">
<input type="hidden" name="mode" value="totals">
<table width="100%" cellpadding="2" cellspacing="2">
<tr class="AomTableHead" height="25">
	<td width="200"><b style="font-size: 12px">Properties</b></td>
	<td IF="target=#order#"><b style="font-size: 12px">Original</b></td>
	<td>{if:target=#order#}<b style="font-size: 12px">Current</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="clone[manual_edit]" checked="{isSelected(cloneOrder.manual_edit,#1#)}" title="Manual editing order totals">Manual editing{else:}<b style="font-size: 12px">Properties</b>{end:}</td>
</tr>
</tr>
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
    <td><b>Payment method</b></td>
    <td IF="target=#order#">{if:order.paymentMethod}{order.paymentMethod.name}{else:}N/A{end:}</td>
    <td><select name="clone[payment_method]">
    		{if:!order.paymentMethod}
			<option value="" selected>Select one...</option>
			{end:}
			<option FOREACH="paymentMethods,payment_method" value="{payment_method.payment_method}" selected="{payment_method.payment_method=cloneOrder.payment_method}">{payment_method.name:h}</option>
		</select>
		<font class="Star" IF="!order.paymentMethod">&nbsp;(*)</font>
	</td>
</tr>
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
    <td><b>Shipping method</b></td>
    <td IF="target=#order#" nowrap>
        {if:order.shippingMethod}
            {order.shippingMethod.name:h}
        {else:}
            {if:order.shipping_id=#0#}
                Free
            {else:}
                N/A
            {end:}
        {end:}
    </td>
    <td><select name="clone[shipping_id]" id="shipping_id" onChange="javascript: changeShippingCost(this.value)">
    		{if:cloneOrder.shipping_id=#-1#}
			<option value="" selected>Select one...</option>
    		{end:}
			<option value="0" selected="{cloneOrder.shippingMethod&cloneOrder.shipping_id=#0#}">Free</option>
<script>
   shipping[0] = "0.00";
</script>
										
            <option FOREACH="shippingRates,shipping_id,rate" value="{shipping_id}" selected="{shipping_id=cloneOrder.shipping_id}">{rate.shipping.name:h} ({price_format(rate.rate):h})</option>
		</select>
		<script FOREACH="shippingRates,shipping_id,rate">
			shipping[{shipping_id}] = price_format({rate.rate});
		</script>
		<font class="Star" IF="cloneOrder.shipping_id=#-1#">&nbsp;(*)</font>
	</td>
</tr>
<tr>
    <td colspan="3">
        {if:!order.shippingMethod}{if:!order.shipping_id=#0#}&nbsp;<i><b>Note:</b>&nbsp;this order uses a shipping method, that was either disabled or deleted or a module,which provided this method, is disabled.</i>{end:}{end:}
    </td>
</tr>
<tbody IF="!cloneOrder.shipping_id=#-1#">
<widget module="WholesaleTrading" template="modules/AOM/order_edit/wholesale_totals.tpl">
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">  
    <td><b>Subtotal</b></td>
    <td IF="target=#order#">{price_format(order.subtotal):h}</td>
    <td>{price_format(cloneOrder.subtotal):h}</td>
</tr>
<widget module="Promotion" template="modules/AOM/order_edit/promotion_totals.tpl">
<widget module="GiftCertificates" template="modules/AOM/order_edit/gc_totals.tpl">
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
    <td><b>Shipping cost</b></td>
    <td IF="target=#order#">{price_format(order.shipping_cost):h}</td>
    <td><input id="shipping_cost" type="text" name="clone[shipping_cost]" {if:cloneOrder.shipping_cost}value="{cloneOrder.shipping_cost:h}"{else:}value="0.00" disabled{end:} size="5"><span id="restore_shipping">&nbsp;&nbsp;<a href="javascript: void(0);" OnClick="changeShippingCost({cloneOrder.shipping_id});"><u>Restore shipping cost</u></A></span></td>
</tr>
<tr FOREACH="ordersTaxes,name,tax" class="{getRowClass(#0#,#TableRow#)}" height="25">
	<td nowrap><b>{name}</b></td>
	<td IF="target=#order#">{price_format(tax.order):h}</td>
	<td>{if:cloneOrder.manual_edit}<input type="textbox" name="taxes[{name}]" value="{tax.clone}" size="10">{else:}{price_format(tax.clone):h}{end:}</td>
</tr>
<widget module="Promotion" template="modules/AOM/order_edit/promotion_bonus_points.tpl">
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
    <td><b>Total</b></td>
    <td IF="target=#order#">{price_format(order.total):h}</td>
	<td>{if:cloneOrder.manual_edit}<input id="total" type="text" name="clone[total]" value="{cloneOrder.total}" size="10">{else:}{price_format(cloneOrder.total):h}{end:}</td>
</tr>
</tbody>
<tbody IF="cloneOrder.shipping_id=#-1#">
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
    <td><b>Shipping cost</b></td>
    <td IF="target=#order#">{price_format(order.shipping_cost):h}</td>
    <td><input id="shipping_cost" type="text" name="clone[shipping_cost]" {if:cloneOrder.shipping_cost}value="{cloneOrder.shipping_cost:h}"{else:}value="0.00" disabled{end:} size="5"></td>
</tr>
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
    <td></td>
    <td IF="target=#order#"></td>
    <td><font class="Star">To view subtotal, tax and total amounts please choose a valid delivery method and click "Calculate/Update"!</font></td>
</tr>
</tbody>
<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
	<td>&nbsp;</td>
	<td IF="target=#order#">&nbsp;</td>
	<td>{if:!cloneOrder.manual_edit}<input class="UpdateButton" type="button" onClick="javascript: document.totals_form.action.value = 'calculate_totals'; document.totals_form.submit(); " value=" Calculate ">&nbsp;&nbsp;{end:}<input type="submit" value=" Update "></td>
</tr>
</table>
</form>

<br>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr height="2" class="TableRow">
    <td colspan="2"><img src="images/spacer.gif" width="1" height="1" border="0"></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td><a class="AomMenu" href="javascript: Previous();"><img src="images/back.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;</a><a class="AomMenu" href="javascript: Previous();" id="totals_prev">Previous</a></td>
        <td align="right" valign="center" IF="isCloneUpdated()">
            <font class="Star">(*)</font> <a class="AomMenu" href="admin.php?target={target}&order_id={order_id}&page=order_preview">Review and Save Order&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="middle"></a>
        </td>		
    </tr>
</table>

<script language="JavaScript">
shipping_id = '{cloneOrder.shipping_id}';
shipping_cost = '{cloneOrder.shipping_cost}';

<!--
if ( shipping_id != "" && shipping[shipping_id] == shipping_cost )
{
	obj = document.getElementById('restore_shipping');
	if ( obj ) {
		obj.style.display = 'none';
	}
}
-->
</script>
