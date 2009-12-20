{* E-mail sent to customer when he recovers his password *}
<html>
<body>
<p>{config.Company.company_name}: Automated help-desk system

<p>You can now use the following credentials to access your account:

<p>E-mail: {email:h}<br>
Your new password: {new_password:h}

<p>To change the password, log into your {config.Company.company_name} account and use the 'Modify profile' link.

<br>

<p>{signature:h}
</body>
</html>
