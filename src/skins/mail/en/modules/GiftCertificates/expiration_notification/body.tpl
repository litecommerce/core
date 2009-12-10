{* Text of an e-mail notification sent to customers to inform them of gift certificate expiration *}
<html>
<head><title>Your Gift Certificate will expire soon!</title></head>
<body>
<p>Dear {cert.recipient},</p>

<p>you have received this message because your gift certificate {cert.gcid} will expire on {date_format(cert.expirationDate)}.</p>

<p>{signature:h}</p>
</body>
</html>
