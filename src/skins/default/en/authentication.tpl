{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{* Login error page *}

<list name="customer.signin" />

<widget class="\XLite\View\Form\Login\Customer\Main" name="login_form" />

<table class="login-form">
<tr>
    <td class="email-label label"><label for="login">{t(#Username#)}:</label></td>
    <td class="email-field field">
        <input type="text" name="login" value="{login:r}" id="login" size="30" maxlength="128">
    </td>
</tr>
<tr>
    <td class="password-label label"><label for="password">{t(#Password#)}:</label></td>
    <td class="password-field field">
        <input type="password" name="password" value="" id="password" size="30" maxlength="128">
    </td>
</tr>

<tr IF="!valid">
    <td>&nbsp;</td>
    <td class="error-message">
      {t(#Invalid login or password#)}
      <a href="{buildURL(#recover_password#)}">{t(#Forgot password#)}?</a>
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>
        <widget class="\XLite\View\Button\Submit" label="{t(#Sign in#)}" style="action" />
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td>
        <a href="{buildURL(#profile#,##,_ARRAY_(#mode#^#register#))}">{t(#Create account#)}</a>
        <span class="spacer-bullet"></span>
        <a href="{buildURL(#recover_password#)}">{t(#Forgot password?#)}</a>
    </td>
</tr>

</table>

<widget name="login_form" end />
