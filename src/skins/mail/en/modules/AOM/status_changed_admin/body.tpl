{* E-mail sent to admin when an order status is changed *}
<html>
<body>
Order # {order.order_id:r} has been {order.orderStatus.name}<br>
<p>
Note: {order.orderStatus.notes:h}
<p>
Admin notes: {order.admin_notes:h}
<p>
<widget template="common/invoice.tpl">
<p>
{signature:h}
</body>
</html>
