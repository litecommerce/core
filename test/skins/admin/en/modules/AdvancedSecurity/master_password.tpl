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
<form IF="{!session.masterPassword}" action="admin.php" method=POST>
<input type="hidden" foreach="allParams,_name,_value" name="{_name}" value="{_value}"/>
<input type=hidden name=action value=submit_password>

<table border=0>
<tr>
<td colspan=2>
Your encrypted data and secret key are protected with master password (a GnuPG secret key passphrase). Please enter master password to view the encrypted data.
</td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
{if:gpg.publicKey&gpg.secretKey}
<tr>
<td nowrap>Master password:</td>
<td width="100%">
<input type=password name=master_password size=32>
<input type=submit name=submit value=Submit>
</td>
</tr>
{else:}
<tr>
	<td align="left" class="ErrorMessage" colspan="2">Warning: AdvancedSecurity module is not configured correctly. Adjust the settings on the <a href="admin.php?target=advanced_security">AdvancedSecurity configuration</a> page.</td>
</tr>
{end:}
<tr IF="invalidMasterPassword">
<td>&nbsp;</td>
<td class=ErrorMessage>Invalid password</td>
</tr>
</table>
</form>

<form IF="{session.masterPassword}" action="admin.php" method=POST>
<input type="hidden" foreach="allParams,_name,_value" name="{_name}" value="{_value}"/>
<input type=hidden name=action value=clear_password>

<table border=0 cellspacing=3 cellpadding=5>
<tr valign=top>
    <td><img src="skins/admin/en/modules/AdvancedSecurity/icon_warning.gif"></td>
    <td>You have entered master password for this session. If you are not using it, it is strongly recommended to clear master password from the session for security reasons.
    <br><br>
    <input type=submit value="Clear master password">
    </td>
</tr>
</table>
</form>
