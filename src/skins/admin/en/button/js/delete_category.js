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

function PopupButtonDeleteCategory()
{
  PopupButtonDeleteCategory.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonDeleteCategory, PopupButton);

PopupButtonDeleteCategory.prototype.pattern = '.popup-button.delete-category';

PopupButtonDeleteCategory.prototype.callback = function (selector)
{
  jQuery('.back-button').each(
    function () {

      jQuery(this).attr('onclick', '')
      .bind(
        'click',
        function (event) {
          event.stopPropagation();

          jQuery(selector).dialog('close');

          return true;
        }
      );

    }
  );
}

core.autoload(PopupButtonDeleteCategory);
