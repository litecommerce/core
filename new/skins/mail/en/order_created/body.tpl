{* E-mail sent to customer about initially placed order *}
<html>
<body>
Dear {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}!
<p>
Thank you for your order made with our shopping system.<br>
Please come back soon!
<p>
<widget template="common/invoice.tpl">
<p>
{signature:h}
</body>
</html>
