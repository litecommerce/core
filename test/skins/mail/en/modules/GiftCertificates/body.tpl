{* E-mail sent to customer when Gift Certificate is issued for him *}
<html>
<body IF="!gc.ecard_id">
Dear {gc.recipient},
<p>
{gc.purchaser} sent you a Gift Certificate for {price_format(gc.amount):h}
<p>
Message:<br>
{gc.formattedMessage:h}
<p>&nbsp;
<table border="1" cellspacing="0" cellpadding="5">
<tr><td>Gift Certificate ID: {gc.gcid}</td></tr>
</table>
<br>
In order to redeem this gift certificate please follow these steps:
<ol>
<li> Go to our site at <a href="{config.Company.company_website:r}">{config.Company.company_website}</a>
<li> Add to cart some products
<li> Click 'checkout'
<li> Enter your personal details
<li> Select 'Gift Certificate' as payment method
<li> Enter your Gift Certificate ID and click 'Submit order' button
</ol>
{signature:h}
</body>
<body IF="gc.ecard_id">
{gc.showECardBody()}
</body>
</html>
