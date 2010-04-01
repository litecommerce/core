<p align=justify>This section displays statistics on shipping and payment methods used in orders for the specified period.</p>

<p><b>NOTE:</b> Only payment and shipping methods used in orders is present in search form.</p>

<br>

<a name=report_form></a>

<form name=ecommerce_report_form action="admin.php" method=GET>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>

<table border="0" cellpadding="1" cellspacing="3">

<widget template="modules/EcommerceReports/period_form.tpl">

<tr>
	<td>Shipping method:</td>
	<td>
		<select name="shipping_id">
            <option value=all selected="shipping_id=#all#">All</option>
            <option FOREACH="shippingMethods,sidx,sm" value="{sm.shipping_id}" selected="shipping_id=sm.shipping_id">{sm.name:h}</option>
		</select>
	</td>
</tr>

<tr>
	<td>By payment method:</td>
	<td>
		<select name="payment_method">
            <option value=all selected="payment_method=#all#">All</option>
            <option FOREACH="paymentMethods,pidx,pm" value="{pm.payment_method}" selected="payment_method=pm.payment_method">{pm.name} ({pm.class})</option>
		</select>
	</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=search value=Search></td>
</tr>
</table>
</form>

<br>

<a name="result"></a>

<p IF="search">{countOrders(orders)} orders(s) found</p>

<p IF="search&orders">
<table border=0 cellpadding=5 cellspacing=1>
<tr class="TableHead">
	<td>Shipping method</td>
	<td>Payment method</td>
	<td align=center>Orders</td>
	<td align=right>Total</td>
	<td align=center>%</td>
</tr>	
<tr FOREACH="orders,oidx,od" valign=top class="{getRowClass(oidx,#TableRow#,##)}">
	<td>{od.shipping_method.name:h}</td>
	<td>{od.payment_method.name:h}</td>
	<td align="center">{od.orders}</td>
	<td align="right">{price_format(od.total):h}</td>
	<td align="right">{od.percent}</td>
</tr>
</table>
</p>
