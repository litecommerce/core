/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Delete user button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function PopupButtonDeleteUser()
{
  PopupButtonDeleteUser.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonDeleteUser, PopupButton);

PopupButtonDeleteUser.prototype.pattern = '.delete-user-button';

decorate(
  'PopupButtonDeleteUser',
  'callback',
  function (selector)
  {
    // Some autoloading could be added
    jQuery('.button-cancel').each(
      function () {

        jQuery(this).attr('onclick', '')
        .bind(
          'click',
          function (event) {
            event.stopPropagation();

            jQuery(selector).dialog('close');

            return true;
          });

      });
  }
);

core.autoload(PopupButtonDeleteUser);
