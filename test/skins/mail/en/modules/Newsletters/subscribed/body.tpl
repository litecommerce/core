{* E-mail sent to newsletter subscriber to notify about subscription *}
<html>
<body>
<p IF="!profile">Dear {email:h},</p>
<p IF="profile">Dear {profile.billing_firstname:h} {profile.billing_lastname:h},</p>
<br>
<p>Your e-mail has been added to the {config.Company.company_name:h} newsletter "{list.name:h}" subscription list.</p>

<p>To unsubscribe from {config.Company.company_name:h} newsletters, click on the link provided below:</p>
<p><a href="{xlite.shopUrl(#cart.php#)}?target=news&action=confirm&type=unsubscribe&email={email}&code={code}">{xlite.shopUrl(#cart.php#)}?target=news&action=confirm&type=unsubscribe&email={email}&code={code}</a></p>
<br>
<p IF="!profile">If you are a registered {config.Company.company_name:h} customer, you can manage your newsletter subscriptions from the "Modify profile" page of your personal account.</p>

<p><i>{signature:h}</i></p>
</body>
</html>
