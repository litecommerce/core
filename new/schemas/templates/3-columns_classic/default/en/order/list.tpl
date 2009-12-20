<widget class="CPager" data="{orders}" name="pager">

<br>
<form name="order_form" action="cart.php" method="get">
<table border="0">
<tr class="TableHead">
    <td nowrap>Order #</td>
    <td>&nbsp;</td>
    <td>Status</td>
    <td nowrap>Date</td>
    <td>Total</td>
</tr>
<tr FOREACH="pager.pageData,order">
    <td><a href="cart.php?target=order&order_id={order.order_id}">{order.order_id}</a></td>
    <td><input type="radio" name="order_id" value="{order.order_id}"></td>
    <td><widget template="common/order_status.tpl"></td>
    <td nowrap><a href="cart.php?target=order&order_id={order.order_id}">{time_format(order.date)}</a></td>
    <td align=right>{price_format(order,#total#):h}</td>
</tr>
<tr>
    <td colspan="4">
        <input type="hidden" name="target" value="order">
        <widget class="CButton" label="Details" href="javascript: document.order_form.submit();">
    </td>
</tr>
</table>
</form>
