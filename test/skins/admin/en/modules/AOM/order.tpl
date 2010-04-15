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
<span IF="order.shippingMethod.class=#ups#">
<b>Note:</b> You cannot edit the order details because the order utilizes one of the UPS shipping methods.<br><br>
</span>
<widget template="modules/AOM/order_info.tpl">
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td align="right">&nbsp;</td>
	</tr>
    <tr>
        <td align="right">
            <input class="ProductDetailsTitle" type="button" value=" Send invoice " onClick="window.location = 'admin.php?target=order&action=send&order_id={order.order_id}';">    
            <input class="ProductDetailsTitle" type="button" value=" Print invoice " onClick="window.open('admin.php?target=order&mode=invoice&order_id={order.order_id}')">
			<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/show_container_details.tpl" style="button">
	
        </td>
    </tr>
</table>
<br>
<table border=0 cellpadding="3" cellspacing="3" align="left" width="75%">
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="order">
<input type="hidden" name="action" value="update">
<input type="hidden" name="order_id" value="{order.order_id}">
	<tr>
        <td class="ProductDetailsTitle" width="20%">Status:</td>
        {if:config.General.clear_cc_info}
	    <td align="left" IF="order.status=config.General.clear_cc_info"><widget class="XLite_View_StatusSelect" field="substatus" value="{order.orderStatus.status}" style="width: 150px; border-width: 1px; border-color: #516176; border-style: solid;"></td>
        <td align="left" IF="!order.status=config.General.clear_cc_info"><widget class="XLite_View_StatusSelect" field="substatus" value="{order.orderStatus.status}" style="width: 150px; border-width: 1px; border-color: #516176; border-style: solid;" pm="{order.paymentMethod.payment_method}"></td>
        {else:}
        <td align="left"><widget class="XLite_View_StatusSelect" field="substatus" value="{order.orderStatus.status}" style="width: 150px; border-wi
dth: 1px; border-color: #516176; border-style: solid;"></td>
        {end:}
	</tr>
	<tr IF="order.detailLabels">
		<td colspan="2" class="ProductDetailsTitle">Details:</td>
	</tr>
	<tbody FOREACH="order.details,name,val">
	<tr valign="top" IF="order.getDetailLabel(name)">
    	<td style="white-space: nowrap;">&nbsp;{order.getDetailLabel(name)}:</td>
	    <td width="250px"><input type="text" name="details[{name}]" value="{val:r}" class="Input" style="width: 250px"></td>
	</tr>
	</tbody>	
	<tr valign="top">
	    <td class="ProductDetailsTitle">Notes:</td>
	    <td><textarea name="notes" cols="60" rows="7" class="Input" style="width: 350px">{order.notes:h}</textarea>
	</tr>
	<tr valign="top">
        <td class="ProductDetailsTitle">Admin notes:</td>
	    <td><textarea name="admin_notes" cols="60" rows="7" class="Input" style="width: 350px">{order.admin_notes:h}</textarea>
    </tr>
	<tr valign="top">
    	<td>&nbsp;</td>
	    <td><input type="submit" value=" Submit" class="ProductDetailsTitle"></td>
	</tr>
</form>
</table>
