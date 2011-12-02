/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Modify user button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function PopupButtonModifyAddress()
{
  PopupButtonModifyAddress.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonModifyAddress, PopupButton);

PopupButtonModifyAddress.prototype.pattern = '.modify-address';

decorate(
  'PopupButtonModifyAddress',
  'callback',
  function (selector)
  {
    // Some autoloading could be added
  }
);

core.autoload(PopupButtonModifyAddress);
