<widget class="CPager" data="{orders}" name="pager">

<br>
<table border="0">
<tr class="TableHead">
    <th nowrap>Order #</th>
    <th>Status</th>
    <th>Date</th>
    <th nowrap>Products purchased</th>
    <th>Total</th>
</tr>
<tr FOREACH="pager.pageData,id,order" class="{getRowClass(id,##,#BottomBox#)}">
    <td nowrap valign=top><a href="cart.php?target=order&amp;order_id={order.order_id}">#{order.order_id}&nbsp;<b>details&gt;&gt;</b></a></td>
    <td valign=top><widget template="common/order_status.tpl"></td>
    <td nowrap valign=top><a href="cart.php?target=order&amp;order_id={order.order_id}">{time_format(order.date)}</a></td>
    <td nowrap>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tbody FOREACH="order.items,item">
    	<tr>
    		<td width="100%">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
            		<td width="100%">&nbsp;&nbsp;{item.name}&nbsp;</td>
            		<td>(Qty:&nbsp;</td>
            		<td>{item.amount})</td>
            	</tr>
				</table>
			</td>
    	</tr>
    	<tr IF="!itemArrayPointer=itemArraySize" class="TableHead">
    		<td></td>
    	</tr>
    	</tbody>
		</table>
    </td>
    <td align=right valign=top >{price_format(order,#total#):h}</td>
</tr>
</table>
