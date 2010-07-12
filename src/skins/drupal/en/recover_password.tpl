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
<p align=justify>In order to recover your password, please type in your valid e-mail address you used as a login.<br>Your account information will be e-mailed to you shortly.</p>

<form action="cart.php" method=post name="recover_password">
<input type="hidden" name="target" value="recover_password">
<input type="hidden" name="action" value="recover_password">

<table border=0 cellpadding=0 cellspacing=0>
<tr>
    <td height="10" width="78" class=FormButton>E-mail</td>
    <td width="10" height="10" class="Star">*</td>
    <td height="10"><input type="text" name="email" value="{email:r}" size="30"><widget class="\XLite\Validator\EmailValidator" field="email"></td>
</tr>
<tr IF="noSuchUser">
    <td colspan="2">&nbsp;</td>
    <td class="ErrorMessage">No such user: {email}</td>
</tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td><widget class="\XLite\View\Submit" href="javascript: document.recover_password.submit()" font="FormButton"></td>
</tr>
</table>

</form>
