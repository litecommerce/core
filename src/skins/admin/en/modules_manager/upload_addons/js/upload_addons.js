/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Upload addons controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function PopupButtonUploadAddon()
{
  PopupButtonUploadAddon.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonUploadAddon, PopupButton);

PopupButtonUploadAddon.prototype.pattern = '.upload-addons';

PopupButtonUploadAddon.prototype.enableBackgroundSubmit = false;

core.autoload(PopupButtonUploadAddon);

window.core.multiAdd = function (addArea, addObj, removeElement)
{
  var cloneObj;

  if (cloneObj == undefined) {
    cloneObj = {};
  }

  jQuery(addObj).click(
    function ()
    {
      if (cloneObj[addArea] == undefined) {
        cloneObj[addArea] = jQuery(addArea);
      }

      cloneObj[addArea].clone().append(
        jQuery(removeElement).click(
          function()
          {
            jQuery(this).closest(addArea).remove();
          }
        )
      )
      .insertAfter(cloneObj[addArea]);
    }
  );
}
