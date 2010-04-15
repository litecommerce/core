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
<p align=justify>This page allows you to configure AdvancedSecurity module. You should have <a href="http://www.gnupg.org" target=_blank><u>GNU Privacy Guard software</u></a> version 1.2.3 or better installed on your hosting to use this module. You can test module configuration using <a href="#test_gpg"><u>Test AdvancedSecurity configuration</u></a> dialog below. 

<br><br>

<form action="admin.php" method=POST>
<input type=hidden name=target value=advanced_security>
<input type=hidden name=action value=options>

<table border=0 cellpadding=3 cellspacing=3>
<tr> <td colspan=2 class=AdminHead>GnuPG settings [{if:gpg.configurationValid}<font class="SuccessMessage">VALID</font>{else:}<font class="ErrorMessage">INVALID</font>{end:}]</td> </tr>
<tr> <td colspan=2 class=AdminHead>&nbsp;</td> </tr>
<tr IF="!gpg.exe">
    <td colspan=2 class=ValidateErrorMessage>&gt;&gt; Warning! GnuPG executable not found! &lt;&lt;</td>
</tr>    
<tr FOREACH="securityOptions,so" valign=top>
    <td align=justify width="40%">{so.comment:h}</td>
    <td>
    {if:so.checkbox}
    <input id="{so.name}" type="checkbox" name="{so.name}" checked="{so.checked}">
    {end:}
    {if:so.text}
    <input id="{so.name}" type="text" name="{so.name}" value="{so.value}" size=30>
    {end:}
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit value=Update></td>
</tr>
</table>
</form>

<br><br>


<a name=gpg_keyring></a>
<table border=0 cellpadding=3 cellspacing=3>
<tr> <td colspan=2 class=AdminHead>GnuPG keyring settings</td> </tr>
<tr> <td colspan=2>&nbsp;</td> </tr>
<tr> <td colspan=2 align=justify>You should set up GnuPG settings before uploading GnuPG keypair. After you successfully upload public/secret keypair, you can encrypt all existing order details already stored in your database with <a href="#order_management"><u>Secure order management</u></a> </td> </tr>
<tr> <td colspan=2>&nbsp;</td> </tr>

{if:gpg.publicKey&gpg.secretKey}

<form action="admin.php#gpg_keyring" method=POST name=delete_keys onSubmit="return confirmDelete();">
<input type=hidden name=target value=advanced_security>
<input type=hidden name=action value=delete_keys>

<tr>
    <td colspan=2><b>Installed keypair</b></td>
</tr>
<tr valign=top>
    <td nowrap>Public key:</td>
    <td width="100%"><pre>{gpg.publicKeyInfo}</pre></td>
</tr>
<tr valign=top>
    <td nowrap>Secret key:</td>
    <td width="100%"><pre>{gpg.secretKeyInfo}</pre></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit button name=delete value="Delete keypair(s)"></td>
</tr>    

<script language=JavaScript>
function confirmDelete() {
    return confirm("Encrypted order details will be unavailable after you delete keypair!\nIt is strongly recommended to decrypt an encrypted database data\nbefore deleting keypair.\n\nAre you sure you want to continue?");
}
</script>

</form>

{else:}

<form action="admin.php#gpg_keyring" method=POST enctype="multipart/form-data">
<input type=hidden name=target value=advanced_security>
<input type=hidden name=action value=upload_keys>

<tr>
    <td colspan=2><b>Install keypair</b></td>
</tr>
<tr>
    <td nowrap>Keypair public key file:</td>
    <td width="100%"><input type=file name=gpg_public_file></td>
</tr>
<tr>
    <td nowrap>Keypair secret key file:</td>
    <td width="100%"><input type=file name=gpg_secret_file></td>
</tr>
<tr IF="!valid"><td>&nbsp;</td><td nowrap class=ErrorMessage>Invalid keypair!</td></tr>
<tr>
    <td>&nbsp;</td>
    <td><input type=submit name=upload value=Upload></td>
</tr>    

</form>

{end:}

</table>

<br><br>

<form IF="gpg.secretKey" action="admin.php#download_key" method=POST>
<input type=hidden name=target value=advanced_security>
<input type=hidden name=action value=download_secret_key>

<a name=download_key></a>
<table border=0 cellpadding=3 cellspacing=3>
<tr> <td colspan=3 class=AdminHead>Download secret key</td> </tr>
<tr> <td colspan=3 class=AdminHead>&nbsp;</td> </tr>
<tr> <td colspan=3>You can download the GnuPG secret key and use it in your email client. You should have GnuPG extension installed in your email client. Enter master password (a gnupg secret key passphrase) and click on Download button to download the secret key.</td> </tr>
<tr><td nowrap>Master password:</td><td class=Star>*</td><td width="100%"><input type=password name=download_password size=32></td></tr>
<tr IF="invalidPassword"><td colspan=2>&nbsp;</td><td class=ErrorMessage>Invalid master password!</td></tr>
<tr> <td colspan=2>&nbsp;</td><td><input type=submit value="Download secret key"></td> </tr>
</table>
</form>
