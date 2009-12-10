{* E-mail sent to newsletter subscriber to notify about unsubscription *}
<html>
<body>
<p IF="!profile">Dear {email:h},</p>
<p IF="profile">Dear {profile.billing_firstname:h} {profile.billing_lastname:h},</p>
<br>

<p>At your request your e-mail has been removed from the {config.Company.company_name:h} newsletter "{list.name:h}" subscription list.</a></p>

<p><i>{signature:h}</i></p>
</body>
</html>
