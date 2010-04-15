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
<a name=test_gpg></a>
<p>You can test AdvancedSecurity module after you have configured GnuPG.

<p>Enter Master Password (a GnuPG secret key passphrase) and click on "Test" button below.

<form action="admin.php#test_gpg_result" method=POST>
<input type=hidden name=target value=advanced_security>
<input type=hidden name=action value=test>
<table border=0>
<tr>
<td>Master password:</td><td><input type=password name=passphrase size=32></td>
</tr>
<tr>
<td>&nbsp;</td><td>* required only for testing data decryption.</td>
</tr>
</table>
<p>
<input type=submit value=" Test configuration ">
</form>

<a name=test_gpg_result></a>
<p IF="action=#test#">
<br>
<table border=0 cellpadding=3 cellspacing=3>
<tr><td colspan=4 class=AdminHead>Configuration summary</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr class=TableHead>
    <td nowrap>Configuration option</td>
    <td align=center>Value</td>
    <td align=center>Status</td>
    <td align=center>Reason</td>
</tr>
<!-- global amin zone security -->
<tr valign=top class=SidebarBox>
    <td nowrap>HTTPS for administrator's zone</td>
    {if:https}
    <td nowrap align=center>On</td>
    {else:}
    <td nowrap align=center>Off</td>
    {end:}
    {if:https}
    <td align=center class=SuccessMessage>OK</td>
    <td>&nbsp;</td>
    {else:}
    <td align=center class=ErrorMessage>WARNING</td>
    <td align=justify>You're accessing administrator's zone using insecure HTTP protocol. For security reasons it is strongly recommended to <a href="admin.php?target=settings&page=Security"><u>turn on HTTPS for administrator's zone</u></a>. Your master password (gnupg passphrase), gnupg public and secret keys, order secure details can be sniffed and compromised otherwise!</td>
    {end:}
</tr>
<!--  home directory -->
<tr valign=top class=SidebarBox>
    <td nowrap>GnuPG home directory</td>
    {if:gpg.homedir}
    <td nowrap>{gpg.homedir}</td>
    {else:}
    <td align=center>-</td>
    {end:}
    {if:gpg.homedir&gpg.writable}
    <td align=center class=SuccessMessage>OK</td>
    <td>&nbsp;</td>
    {else:}
    <td align=center class=ErrorMessage nowrap>NOT FOUND</td>
    <td align=justify>You have no specified GnuPG home directory and/or no GNUPGHOME environment variable found or directory is not writable.</td>
    {end:}
</tr>
<!-- GPG executable -->
<tr valign=top class=SidebarBox>
    <td nowrap>GnuPG executable path</td>
    {if:gpg.exe}
    <td nowrap>{gpg.exe}</td>
    {else:}
    <td align=center>-</td>
    {end:}
    {if:gpg.exe&gpg.executable}
    <td align=center class=SuccessMessage>OK</td>
    <td>&nbsp;</td>
    {else:}
    <td align=center class=ErrorMessage nowrap>NOT FOUND</td>
    <td align=justify>You have no specified GnuPG executable path and/or no GnuPG executable found in your PATH environment variable and/or the file sepcified is not an executable.</td>
    {end:}
</tr>
<!-- GnuPG user ID -->
<tr valign=top class=SidebarBox>
    <td nowrap>GnuPG user id</td>
    {if:gpg.recipient}
    <td nowrap>{gpg.recipient}</td>
    {else:}
    <td align=center>-</td>
    {end:}
    {if:gpg.recipient}
    <td align=center class=SuccessMessage>OK</td>
    <td>&nbsp;</td>
    {else:}
    <td align=center class=ErrorMessage nowrap>NOT FOUND</td>
    <td align=justify>You have no specified GnuPG user id. AdvancedSecurity module will not be able to access encrypted order details data without GnuPG user id.</td>
    {end:}
</tr>
<!-- public key -->
<tr valign=top class=SidebarBox>
    <td nowrap>GnuPG public key</td>
    {if:gpg.publicKey}
    <td nowrap align=center>set</td>
    {else:}
    <td align=center>-</td>
    {end:}
    {if:gpg.publicKey}
    <td align=center class=SuccessMessage>OK</td>
    <td>&nbsp;</td>
    {else:}
    <td align=center class=ErrorMessage nowrap>NOT FOUND</td>
    <td align=justify>You have no specified GnuPG public key. AdvancedSecurity module will not be able to encrypt order details data without public key.<pre IF="gpg.errorLog">{gpg.errorLog}</pre></td>
    {end:}
</tr>
<!-- secret key -->
<tr valign=top class=SidebarBox>
    <td nowrap>GnuPG secret key</td>
    {if:gpg.secretKey}
    <td nowrap align=center>set</td>
    {else:}
    <td align=center>-</td>
    {end:}
    {if:gpg.secretKey}
    <td align=center class=SuccessMessage>OK</td>
    <td>&nbsp;</td>
    {else:}
    <td align=center class=ErrorMessage nowrap>NOT FOUND</td>
    <td align=justify>You have no specified GnuPG secret key. AdvancedSecurity module will not be able to decrypt order details data without secret key.<pre IF="gpg.errorLog">{gpg.errorLog}</pre></td>
    {end:}
</tr>

<!-- test encrypt / decrypt data -->
<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4 class=AdminHead>Testing encrypt/decrypt</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
    <td colspan=4>
    {testEncrypt()}
    Testing data encryption ... <span IF="encryptResult" class=SuccessMessage>[PASSED]</span><span IF="!encryptResult" class=ErrorMessage>[FAILED]</span>
    </td>
</tr>
<tr IF="!encryptResult">
    <td colspan=4>GnuPG error message: <pre>{gpg.errorLog}</pre><br><br></td>
</tr>

<tr>
    <td colspan=4>
    {testDecrypt()}
    Testing data decryption ... <span IF="decryptResult" class=SuccessMessage>[PASSED]</span><span IF="!decryptResult" class=ErrorMessage>[FAILED]</span>
</tr>
<tr IF="!decryptResult">
    <td colspan=4>GnuPG error message: <pre>{gpg.errorLog}</pre></td>
</tr>

</table>

</p>
