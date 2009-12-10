<widget class="CPager" data="{orders}" name="pager" itemsPerPage="{config.General.orders_per_page}">

<br>
<form name="order_form" action="admin.php" method="POST">
<input type="hidden" foreach="allParams,_name,_value" name="{_name}" value="{_value}"/>
<input type="hidden" name="action" value="delete">
<table border=0>
<tr class="TableHead">
    <td width=10>&nbsp;</td>
    <td nowrap>Order #</td>
    <td align=left>Status</td>
    <td nowrap align=left>Date</td>
    <td nowrap width=100% align=left>Customer</td>
    <td align=center>Total</td>
    <td nowrap>&nbsp;</td>
</tr>
<tr FOREACH="pager.pageData,oid,order">
    <td width=10 align=center><input type="checkbox" name="order_ids[{order.order_id}]"></td>
    <td>&nbsp;<a href="admin.php?target=order&order_id={order.order_id}"><u>{order.order_id}</u></a></td>
    <td><widget template="common/order_status.tpl"></td>
    <td nowrap><a href="admin.php?target=order&order_id={order.order_id}">{time_format(order.date)}</a></td>
    <td nowrap><a href="admin.php?target=order&order_id={order.order_id}">{order.profile.billing_title} {order.profile.billing_firstname} {order.profile.billing_lastname}</a></td>
    <td nowrap align=right>{price_format(order,#total#):h}</td>
    <td nowrap align=right>&nbsp;<a href="admin.php?target=order&order_id={order.order_id}"><u>details</u>&nbsp;&gt;&gt;</a></td>
</tr>
<tr><td colspan=7>&nbsp;</td></tr>
<tr>
    <td colspan=7 align=left><input type="button" value=" Delete " onclick="javascript: if (confirm('All related information will also be deleted from database!\n\nAre you sure you want to delete the selected orders?')) document.order_form.submit();"></td>
</tr>
</table>
</form>
