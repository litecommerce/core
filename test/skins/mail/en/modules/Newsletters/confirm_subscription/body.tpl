{* E-mail sent to newsletter subscriber to confirm subscription *}
<html>
<body>
Dear {email:h},
<p>You have requested to subscribe to {config.Company.company_name:h} newsletters.</p>
<p>To confirm your subscription, please click on the link provided below, or copy and paste the link into your web browser:</p>
<p><a href="{xlite.getShopUrl(#cart.php#)}?target=news&action=confirm&type=subscribe&email={email}&code={code}">{xlite.getShopUrl(#cart.php#)}?target=news&action=confirm&type=subscribe&email={email}&code={code}</a></p>
<p>After your subscription is complete, you will start receiving newsletter messages.</p>
<hr>
<p>To unsubscribe from the newsletters, click on link provided below:
<p><a href="{xlite.getShopUrl(#cart.php#)}?target=news&action=confirm&type=unsubscribe&email={email}&code={code}">{xlite.getShopUrl(#cart.php#)}?target=news&action=confirm&type=unsubscribe&email={email}&code={code}</a></p>

<p><i>{signature:h}</i></p>
</body>
</html>
