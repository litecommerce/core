{* E-mail sent to partner when order become processed *}
<html>
<head><title>Referral order</title></head>
<body>
<p>Dear {partner.login:h}!</p>

<p>An order has been placed at {config.Company.company_name:h}. This order resulted from a referral from you.</p>

<p>Order id# {payment.order_id:r}</p>

<p>You will receive a payout of {price_format(payment,#commissions#):h} for this order.</p>

<p>{signature:h}</p>
</body>
</html>

