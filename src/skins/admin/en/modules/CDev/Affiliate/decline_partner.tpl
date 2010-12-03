{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
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
