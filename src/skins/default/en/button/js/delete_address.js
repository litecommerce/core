/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Delete address button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function ButtonDeleteAddress()
{
  var obj = this;

  jQuery('.delete-address').each(
    function () {
      var o = jQuery(this);
      var addressId = core.getCommentedData(o, 'address_id');
      var warningText = core.getCommentedData(o, 'warning_text');

      o.click(function (event) {
        result = confirm(warningText);
        if (result) {
          self.location = URLHandler.buildURL(obj.getParams(addressId));
        }
      });
    }
  );
}

ButtonDeleteAddress.prototype.getParams = function (addressId) {
  return {
    'address_id'  : addressId,
    'target'      : 'address_book',
    'action'      : 'delete'
  };
}

core.autoload(ButtonDeleteAddress);
