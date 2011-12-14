/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Install addon button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function PopupButtonInstallAddon()
{
  PopupButtonInstallAddon.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonInstallAddon, PopupButton);

PopupButtonInstallAddon.prototype.pattern = '.install-addon-button';

decorate(
  'PopupButtonInstallAddon',
  'callback',
  function (selector)
  {
    // Autoloading of switch button and license agreement widgets.
    // They are shown in License agreement popup window.
    // TODO. make it dynamically and move it to ONE widget initialization (Main widget)
    core.autoload(SwitchButton);
    core.autoload(LicenseAgreement);
    core.autoload(PopupButtonSelectInstallationType);
  }
);

core.autoload(PopupButtonInstallAddon);
