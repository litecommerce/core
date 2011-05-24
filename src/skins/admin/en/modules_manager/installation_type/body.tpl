{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Install updates or not
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<form action="admin.php" method="post">
  <input type="hidden" name="target" value="upgrade" />
  <input type="hidden" name="action" value="install_addon" />
  <input type="hidden" name="moduleId" value="{getModuleId()}" />

  <div class="install-warning-description">
    {t(#The system detects that some updates are available for enabled modules. It is strongly recommended to have all enabled modules updated to latest version for better compatibility before the install new ones from Marketplace.#)}
  </div>

  <ul class="actions">
    <li class="button">
      <widget class="\XLite\View\Button\Addon\Install" label="Install anyway" moduleId="{getModuleId()}" />
    </li>
    <li class="or">or</li>
    <li class="button">
      <widget class="\XLite\View\Button\Submit" label="Update modules" />
    </li>
  </ul>
  <div class="clear"></div>

</form>
