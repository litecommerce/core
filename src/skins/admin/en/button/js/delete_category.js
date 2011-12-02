/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Delete category button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

// New Delete category popup button widget constructor
function PopupButtonDeleteCategory()
{
  PopupButtonDeleteCategory.superclass.constructor.apply(this, arguments);
}

// New POPUP button widget extends POPUP button class
extend(PopupButtonDeleteCategory, PopupButton);

// New pattern is defined
PopupButtonDeleteCategory.prototype.pattern = '.delete-category-button';

// Decorating of callback of new class for POPUP widget
decorate(
  'PopupButtonDeleteCategory',
  'callback',
  function (selector)
  {
    // Delete categories popup dialog has 'back-button' button with defined action.
    // We change this action to 'popup dialog close' action.
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
);

// Autoloading new POPUP widget
core.autoload(PopupButtonDeleteCategory);
