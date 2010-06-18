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
<widget template="common/invoice.tpl">

<p>
<b><a href="admin.php?target=order&mode=invoice&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Print invoice</a></b>
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/show_container_details.tpl">

<P>
<table border=0>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="order">
<input type="hidden" name="action" value="update">
<input type="hidden" name="order_id" value="{order.order_id}">
<tr>
	<td>Status:</td>
	<td IF="order.status=config.General.clear_cc_info"><widget class="XLite_View_StatusSelect" field="status" value="{order.status}"></td>
	<td IF="!order.status=config.General.clear_cc_info"><widget class="XLite_View_StatusSelect" field="status" value="{order.status}" pm="{order.paymentMethod.payment_method}"></td>
</tr>
{foreach:order.details,name,val}
<tr valign="top" IF="order.getDetailLabel(name)">
	<td >{order.getDetailLabel(name)}:</td>
	<td>
 	  <input type="text" name="details[{name}]" size="40" value="{val:r}">
    </td>
</tr>
{end:}
<tr valign="top">
	<td>Notes:</td>
	<td><textarea name="notes" cols="60" rows="7">{order.notes:h}</textarea></td> 
</tr>

{displayViewListContent(#order.details#)}

<tr valign="top">
	<td></td>
	<td><input type="submit" value=" Submit"></td>
</tr>
</form>
</table>
