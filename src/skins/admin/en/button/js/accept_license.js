/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function PopupButtonAcceptLicense()
{
  PopupButtonAcceptLicense.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonAcceptLicense, PopupButton);

// New pattern is defined
PopupButtonAcceptLicense.prototype.pattern = '.accept-license-button';

decorate(
  'PopupButtonAcceptLicense',
  'callback',
  function (selector)
  {
    alert('ddd');
    jQuery(selector).dialog('close');
  }
);

core.autoload(PopupButtonAcceptLicense);
