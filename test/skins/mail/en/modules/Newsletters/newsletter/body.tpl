{* Newsletter message template *}
<html>
<body>

<p>Dear {email:h},</p>
<p>Here is the latest news from {config.Company.company_name:h}: </p>

<p><b>{newsSubject:h}</b></p>

<p>{newsBody:h}</p>

{if:list}
<br><hr>
<p>You are receiving this e-mail message because you are subscribed to the {config.Company.company_name:h} newsletter "{list.name:h}".</p>
<p>To unsubscribe from {config.Company.company_name:h} newsletters, click on the link provided below:</p>
<p><a href="{xlite.getShopUrl(#cart.php#)}?target=news&action=confirm&type=unsubscribe&email={email}&code={code}">{xlite.getShopUrl(#cart.php#)}?target=news&action=confirm&type=unsubscribe&email={email}&code={code}</a></p>

<p>If you are a registered {config.Company.company_name:h} customer, you can manage your newsletter subscriptions from the "Modify profile" page of your personal account.</p>
{end:}

<p><i>{signature:h}</i></p>
</body>
</html>
