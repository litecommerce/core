/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Browser server button and popup controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function PopupButtonEnterLicenseKey()
{
  PopupButtonEnterLicenseKey.superclass.constructor.apply(this, arguments);
}

// New POPUP button widget extends POPUP button class
extend(PopupButtonEnterLicenseKey, PopupButton);

// New pattern is defined
PopupButtonEnterLicenseKey.prototype.pattern = '.enter-license-key';

PopupButtonEnterLicenseKey.prototype.enableBackgroundSubmit = false;

// Autoloading new POPUP widget
core.autoload(PopupButtonEnterLicenseKey);
