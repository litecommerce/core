/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product quantity box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

/**
 * Controller
 */

function ProductQuantityBoxController(base)
{
  this.callSupermethod('constructor', arguments);

  if (jQuery('input.quantity', this.base) && jQuery('span.product-max-qty-container', this.base)) {

    this.block = jQuery('input.quantity', this.base);
    this.errorBox = jQuery('.product-max-qty', this.base);
    this.add2CartButton = jQuery('button.add2cart', this.base);
    this.buyMoreButton = jQuery('button.buy-more', this.base);

    var o = this;

    this.block.change(
      function (event) {
        (parseInt(jQuery('span.product-max-qty-container', this.base).html()) < parseInt(jQuery('input.quantity', this.base).val())) 
          ? o.showErrorBox() 
          : o.hideErrorBox();
      }
    );
  }
}

extend(ProductQuantityBoxController, AController);

// Controller name
ProductQuantityBoxController.prototype.name = 'ProductQuantityBoxController';

// Controller associated main widget
ProductQuantityBoxController.prototype.block = null;

// Maximum allowed product qty
ProductQuantityBoxController.prototype.maxQuantity = null;

/**
 * Show error box 
 */
ProductQuantityBoxController.prototype.showErrorBox = function()
{
  this.block.addClass('wrong-amount');
  this.errorBox.show();
  this.add2CartButton.attr('disabled', 'disabled');
  this.buyMoreButton.attr('disabled', 'disabled');
}

/**
 * Hide error box 
 */
ProductQuantityBoxController.prototype.hideErrorBox = function()
{
  this.block.removeClass('wrong-amount');
  this.errorBox.hide();
  this.add2CartButton.attr('disabled', '');
  this.buyMoreButton.attr('disabled', '');
}

core.autoload(ProductQuantityBoxController);
