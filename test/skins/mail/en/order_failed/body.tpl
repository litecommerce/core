{* E-mail sent to customer when an order failed or was declined by the shop administration *}
<html>
<body>
Dear {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}!
<p>
Your order # {order.order_id:r} has failed or been declined by the shop administration.<br>
<p>
<widget template="common/invoice.tpl">
<p>
{signature:h}
</body>
</html>
