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

  if (this.base && this.base.length && jQuery('input.quantity', this.base).length > 0) {

    this.block = jQuery('input.quantity', this.base.get(0));

    if (!this.isCartPage) {

      this.actionButton = jQuery(this.base)
        .parents('div.product-buttons')
        .find('button.add2cart, button.buy-more');

    } else {

      this.actionButton = jQuery('ul.totals li.button button');

    }

    var o = this;

    this.block.bind(
      'blur keyup',
      function (event) {
        return !o.block.closest('form').validationEngine('validate')
          ? o.showError()
          : o.hideError();
      }
    );
  }
}

extend(ProductQuantityBoxController, AController);

// Controller name
ProductQuantityBoxController.prototype.name = 'ProductQuantityBoxController';

// Pattern for base element
ProductQuantityBoxController.prototype.findPattern = 'span.quantity-box-container';

// Controller associated main widget
ProductQuantityBoxController.prototype.block = null;

// Controller associated main widget
ProductQuantityBoxController.prototype.isCartPage = jQuery('div#cart').length > 0;

// Initialize controller
ProductQuantityBoxController.prototype.initialize = function()
{
  var o = this;

  this.base.bind(
    'reload',
    function(event, box) {
      o.bind(box);
    }
  );
}

/**
 * Show error
 */
ProductQuantityBoxController.prototype.showError = function()
{
  this.block.addClass('wrong-amount');

  this.actionButton
    .attr('disabled', 'disabled')
    .addClass('disabled add2cart-disabled');
}

/**
 * Hide error
 */
ProductQuantityBoxController.prototype.hideError = function()
{

  this.block.removeClass('wrong-amount');

  if (this.isCartPage && jQuery('input.wrong-amount').length > 0)
    return;

  this.actionButton
    .attr('disabled', '')
    .removeClass('disabled add2cart-disabled');
}

core.autoload(ProductQuantityBoxController);
