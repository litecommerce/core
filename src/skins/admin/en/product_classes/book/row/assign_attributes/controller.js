/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * JS for "Assign attributes" popup
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 */

function PopupButtonAssignAttributes()
{
  PopupButtonAssignAttributes.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonAssignAttributes, PopupButton);

PopupButtonAssignAttributes.prototype.pattern = '.assign-attributes-button';

decorate(
  'PopupButtonAssignAttributes',
  'callback',
  function (selector, link)
  {
    // previous method call
    arguments.callee.previousMethod.apply(this, arguments);

    jQuery('input[type="checkbox"].group-selector').click(
      function () {
        jQuery(this).parent('li').nextAll('li').children('input[type="checkbox"]').attr('checked', jQuery(this).is(':checked'));
      }
    );
  }
);

decorate(
  'PopupButtonAssignAttributes',
  'afterSubmit',
  function (selector)
  {
    // previous method call
    arguments.callee.previousMethod.apply(this, arguments);

    self.location.reload();
  }
);

core.autoload(PopupButtonAssignAttributes);
