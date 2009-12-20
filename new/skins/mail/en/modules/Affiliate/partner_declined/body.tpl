{*   E-mail sent to partner when partner is declined by site admin *}
<html>
<head><title>Partner declined</title></head>
<body>
<p>Dear {profile.login}!</p>

<p>Your partner registration has been declined by the shop administrator.</p>

<p IF="profile.reason">Reason: {profile.reason}</p>

<p>{signature:h}</p>
</body>
</html>
