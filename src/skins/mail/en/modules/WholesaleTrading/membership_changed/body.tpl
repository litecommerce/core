{* E-mail sent to customer when membership changed *}
<html>
<head><title>Membership changed</title></head>
<body>
<p>Dear {profile.billing_firstname:h} {profile.billing_lastname:h}!
<p>
Your membership has changed from "{oldMembership}" to "{newMembership}".
<p>
{signature:h}
</body>
</html>
