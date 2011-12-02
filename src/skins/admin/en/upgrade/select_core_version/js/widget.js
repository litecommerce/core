/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Core version selector controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function PopupButtonUpgradeWidget()
{
  PopupButtonUpgradeWidget.superclass.constructor.apply(this, arguments);

  // Remove redirection to HREF. (top link menu)
  jQuery('a' + this.pattern).attr('href', 'javascript:void(0);');
}

extend(PopupButtonUpgradeWidget, PopupButton);

PopupButtonUpgradeWidget.prototype.pattern = '.upgrade-popup-widget';

PopupButtonUpgradeWidget.prototype.options = {
  'width'       : 'auto',
  'dialogClass' : 'popup upgrade-popup-block'
};

core.autoload(PopupButtonUpgradeWidget);
