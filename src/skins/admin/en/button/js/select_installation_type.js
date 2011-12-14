/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Controller-decorator for Selection installation type button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function PopupButtonSelectInstallationType()
{
  PopupButtonSelectInstallationType.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonSelectInstallationType, PopupButton);

PopupButtonSelectInstallationType.prototype.pattern = '.select-installation-type-button';

decorate(
  'PopupButtonSelectInstallationType',
  'callback',
  function (selector)
  {
    // Autoloading of switch button and license agreement widgets.
    // They are shown in License agreement popup window.
    // TODO. make it dynamically and move it to ONE widget initialization (Main widget)

  }
);

core.autoload(PopupButtonSelectInstallationType);
