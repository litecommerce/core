/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Add address button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function PopupButtonAddAddress()
{
  PopupButtonAddAddress.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonAddAddress, PopupButton);

PopupButtonAddAddress.prototype.pattern = '.add-address';

decorate(
  'PopupButtonAddAddress',
  'callback',
  function (selector)
  {
    // Some autoloading could be added
  }
);

core.autoload(PopupButtonAddAddress);
