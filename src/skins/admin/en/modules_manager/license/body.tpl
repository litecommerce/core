{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{* :TODO: divide into parts *}

<div class="module-license">

  <div class="form">

    <form action="admin.php" method="post" name="getAddonForm" >
      <input type="hidden" name="target" value="addon_install" />
      <input type="hidden" name="action" value="install" />
      <input type="hidden" name="module_id" value="{getModuleId()}" />

      <div class="license-block">

      <table>
        <tr>
          <td class="license-text">
            <textarea class="license-area" id="license-area" readonly="readonly">
              {getLicense()}
            </textarea>
          </td>
          <td class="switch-button">
            <img src="images/spacer.gif" class="switch-button" alt="" onclick="javascript:switchHeight('#license-area');" />
          </td>
        </tr>
      </table>

      </div>

      <table class="agree">
        <tr>
          <td>
            <input type="checkbox" id="agree" name="agree" value="Y" checked="checked" />
            <label for="agree">{t(#Yes, I agree with License agreement#)}</label>
          </td>
        </tr>
      </table>

      <table class="install-addon">
        <tr>
          <td>
            <widget class="\XLite\View\Button\Submit" label="{t(#Install add-on#)}" />
          </td>
        </tr>
      </table>

    </form>

  </div>

</div>
