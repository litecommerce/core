{* E-mail sent to customer when an order is processed *}
<html>
<body>
Dear {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}!
<p>
Your order # {order.order_id:r} has been processed. Thank you for your order made with our shopping system.<br>
Please come back soon!
<p>
<widget template="common/invoice.tpl">
<p>
{signature:h}
</body>
</html>
