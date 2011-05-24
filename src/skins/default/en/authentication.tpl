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

{* Login error page *}
<div class="register-message-header">
{t(#If you already have an account, you can authenticate yourself by filling in the form below. The fields which are marked with * are mandatory#)}.
</div>

<hr size="1" noshade />

<widget class="\XLite\View\Form\Login\Customer\Main" name="login_form" />

<table class="login-form">
<tr>
    <td class="email-label label">{t(#E-mail#)}</td>
    <td class="star">*</td>
    <td class="email-field field">
        <input type="text" name="login" value="{login:r}" size="30" maxlength="128">
    </td>
</tr>
<tr>
    <td class="password-label label">{t(#Password#)}</td>
    <td class="star">*</td>
    <td class="password-field field">
        <input type="password" name="password" value="" size="30" maxlength="128">
    </td>
</tr>

<tr IF="!valid">
    <td colspan="2">&nbsp;</td>
    <td class="error-message">
      {t(#Invalid login or password#)} <a href="{buildURL(#recover_password#)}">{t(#Forgot password#)}?</a>
    </td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
    <td>
        <widget class="\XLite\View\Button\Submit" style="action" />
        <a href="{buildURL(#recover_password#)}">{t(#Forgot password?#)}</a>
    </td>
</tr>
</table>

<div class="register-message">
  {t(#If you do not have an account, you can easily#)} <a href="{buildURL(#profile#,##,_ARRAY_(#mode#^#register#))}">{t(#register here#)}</a>
</div>

<widget name="login_form" end />
