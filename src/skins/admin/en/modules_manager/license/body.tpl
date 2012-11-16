{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{* :TODO: divide into parts *}

<form action="admin.php" method="post">
  <input type="hidden" name="target" value="upgrade" />
  <input type="hidden" name="action" value="install_addon_force" />
  <input type="hidden" name="moduleId" value="{getModuleId()}" />

  <div class="module-license">

    <div class="form">

      <div class="license-block">

        <table>
          <tr>
            <td class="license-text">
              <textarea class="license-area" id="license-area" readonly="readonly">
              {getLicense()}
              </textarea>
            </td>
            <td class="switch-button">
          <widget class="\XLite\View\Button\SwitchButton" first="makeSmallHeight" second="makeLargeHeight" />
          </td>
          </tr>
        </table>

      </div>

      <table class="agree">
        <tr>
          <td>
            <label>
              <input type="checkbox" name="agree" value="Y" checked="checked" />
              {t(#Yes, I agree with License agreement#)}
            </label>
          </td>
        </tr>
      </table>

      <table class="install-addon">
        <tr>
          <td>
            <widget
              IF="isUpgradeEntryAvailable()"
              class="\XLite\View\Button\Addon\SelectInstallationType"
              moduleId="{getModuleId()}"
              label="{t(#Install add-on#)}"
              style="submit-button main-button"
              disabled=true />

            <widget
              IF="!isUpgradeEntryAvailable()"
              class="\XLite\View\Button\Submit"
              label="{t(#Install add-on#)}"
              style="submit-button main-button"
              disabled=true />
        </td>
        </tr>
      </table>

    </div>
  </div>

</form>
