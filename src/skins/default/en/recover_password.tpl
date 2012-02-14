{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

 <div class="recover-password-message">
  {t(#To recover your password, please type in the valid e-mail address you use as a login#)}
 </div>
 <div class="recover-password-message">
  {t(#Your account information will be e-mailed to you shortly#)}
 </div>

<form action="cart.php" method="post" name="recover_password">
  <input type="hidden" name="target" value="recover_password" />
  <input type="hidden" name="action" value="recover_password" />

<table class="recover-password-form">

<tr>
    <td class="email-label label">{t(#E-mail#)}</td>
    <td class="star">*</td>
    <td class="email-field field">
      <input type="text" name="email" value="{email:r}" size="30" />
    </td>
</tr>

<tr IF="noSuchUser">
    <td colspan="2">&nbsp;</td>
    <td class="error-message">{t(#No such user#)}: {email}</td>
</tr>

<tr>
  <td colspan="3">&nbsp;</td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
    <td>
      <widget class="\XLite\View\Button\Submit" style="action" />
    </td>
</tr>

</table>

</form>
