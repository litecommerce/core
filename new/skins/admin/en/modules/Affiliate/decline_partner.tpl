<form action="admin.php" method=POST>
<input type=hidden name=target value=decline_partner>
<input type=hidden name=action value=decline_partner>
<input type=hidden name=profile_id value="{profile.profile_id}">
<input type=hidden name=returnUrl value="{returnUrl}">

Are you sure you want to decline partner registration for <a href="admin.php?target=profile&profile_id={profile.profile_id}&mode=modify"><u>{profile.login:h}</u></a>?

<p>Decline reason: <input type=text name=reason size=35>

<p>
<input type=submit name=decline value=" Decline ">
&nbsp;&nbsp;
<input type=button name=cancel value=" Cancel " onclick="document.location='admin.php?target=profile&profile_id={profile.profile_id}&mode=modify'">
</form>
