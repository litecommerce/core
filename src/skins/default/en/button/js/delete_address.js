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

function ButtonDeleteAddress(obj)
{
  this.button = jQuery(obj);
  this.button.get(0).deleteAddressController = this;

  this.addressId = core.getCommentedData(this.button, 'address_id');
  this.warningText = core.getCommentedData(this.button, 'warning_text');

  this.button.click(
    function(event) {
      return this.deleteAddressController.displayPopup(event);
    }
  );
}

ButtonDeleteAddress.autoload = function()
{
  jQuery('.delete-address').each(
    function () {
      new ButtonDeleteAddress(this);
    }
  );
}

ButtonDeleteAddress.prototype.button = null;

ButtonDeleteAddress.prototype.addressId = null;

ButtonDeleteAddress.prototype.warningText = null;

ButtonDeleteAddress.prototype.getParams = function()
{
  return {
    'address_id' : this.addressId,
    'target'     : 'address_book',
    'action'     : 'delete'
  };
}

ButtonDeleteAddress.prototype.displayPopup = function()
{
  result = confirm(this.warningText);
  if (result) {
    self.location = URLHandler.buildURL(this.getParams());
  }
}

core.autoload(ButtonDeleteAddress);
