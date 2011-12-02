/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Modules list controller (install)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

ItemsList.prototype.listeners.popup = function(handler)
{
  // TODO: REWORK to load it dynamically with POPUP button widget JS files
  core.autoload(PopupButtonInstallAddon);
  core.autoload(PopupButtonSelectInstallationType);
}
