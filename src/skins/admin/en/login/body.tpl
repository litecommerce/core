{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Login widget
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="login-box-wrapper">
  <div class="login-box">

    <h2>{t(#Please identify yourself#)}</h2>

    <div class="additional-note" IF="additional_note">
      {additional_note:r}
    </div>

    <form id="login_form" action="{buildURL(#login#)}" method="post" name="login_form">
      <input type="hidden" name="target" value="login" />
      <input type="hidden" name="action" value="login" />

      <table>
      <tr>
        <th>{t(#Email#)}:</th>
        <td><input type="text" name="login" value="{login:r}" size="32" maxlength="128" /></td>
      </tr>
      <tr>
        <th>{t(#Password#)}:</th>
        <td><input type="password" name="password" value="{password:r}" size="32" maxlength="128" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
          <widget class="\XLite\View\Button\Submit" label="Log in" style="main-button" />
          <div class="forgot-password">
            <a href="cart.php?target=recover_password">{t(#Forgot password?#)}</a>
          </div>
        </td>
      </tr>
      </table>
    </form>

  </div>
</div>
