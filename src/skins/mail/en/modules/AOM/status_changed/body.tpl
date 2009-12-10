{* E-mail sent to customer when an order status is changed *}
<html>
<body>
Dear {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}!
<p>
Your order # {order.order_id:r} has been {order.orderStatus.name} by the shop administration.<br>
<p>
Note: {order.orderStatus.notes:h}
<p>
<widget template="common/invoice.tpl">
<p>
{signature:h}
</body>
</html>
