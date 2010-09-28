/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Cart controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

/**
 * Controller
 */

function CartController(base)
{
  this.callSupermethod('constructor', arguments);

  if (this.base.length) {
    this.block = new CartView(this.base);

    var o = this;

    core.bind(
      'updateCart',
      function(event, data) {
        if (!o.selfUpdated) {
          o.block.load();
        }
      }
    );
  }
}

extend(CartController, AController);

// Controller name
CartController.prototype.name = 'CartController';

// Find pattern
CartController.prototype.findPattern = '#cart';

// Controller associated main widget
CartController.prototype.block = null;

// Self-updated status
CartController.prototype.selfUpdated = false;

// Any action postprocessing callback
CartController.prototype.postprocessActionCallback = null;

// Initialize controller
CartController.prototype.initialize = function()
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
 * Main widget
 */

function CartView(base)
{
  this.callSupermethod('constructor', arguments);

  var o = this;

  this.postprocessActionCallback = function (XMLHttpRequest, textStatus, data, isValid) {
    return o.postprocessAction(XMLHttpRequest, textStatus, data, isValid);
  }
}

extend(CartView, ALoadable);

// Shade widget
CartView.prototype.shadeWidget = true;

// Update page title
CartView.prototype.updatePageTitle = true;

// Widget target
CartView.prototype.widgetTarget = 'cart';

// Widget class name
CartView.prototype.widgetClass = '\\XLite\\View\\Cart';

// Update item quantity action TTL
CartView.prototype.updateActionTTL = 2000;

// Update quantity timeout resource
CartView.prototype.submitTO = null;

// Postprocess widget
CartView.prototype.postprocess = function(isSuccess, initial)
{
  this.callSupermethod('postprocess', arguments);

  if (isSuccess) {

    var o = this;

    // Remove item
    $('.selected-product form input.remove', this.base).parents('form').eq(0).submit(
      function(event) {
        return o.removeItem(event, this);
      }
    );

    // Update item
    $('.selected-product input.quantity', this.base)
      .each(
        function() {
          $(this).parents('form').get(0).initialValue = this.value;
        }
      )
      .blur(
        function(event) {
          return $(this.form).submit();
        }
      )
      .keypress(
        function(event) {
          if (o.submitTO) {
            clearTimeout(o.submitTO);
            o.submitTO = null;
          }

          var i = this;
          o.submitTO = setTimeout(
            function() {
              $(i).blur();
            },
            o.updateActionTTL
          );
        }
      )
      .parents('form').eq(0)
      .submit(
        function(event) {
          return o.updateQuantity(event, this);
        }
      );

    // Clear cart
    $('form .clear-bag', this.base).parents('form').eq(0).submit(
      function(event) {
        return o.clearCart(event, this);
      }
    );
  }
}

// Remove item
CartView.prototype.removeItem = function(event, form)
{
  if (!this.base.get(0).controller.selfUpdated && this.submitForm(form, this.postprocessActionCallback)) {
    if (this.submitTO) {
      clearTimeout(this.submitTO);
      this.submitTO = null;
    }
    this.base.get(0).controller.selfUpdated = true;
    this.shade();
  }

  return false;
}

// Update quantity
CartView.prototype.updateQuantity = function(event, form)
{
  var value = $('input.quantity', form).get(0).value;
  if (!this.base.get(0).controller.selfUpdated && form.initialValue != value && this.submitForm(form, this.postprocessActionCallback)) {
    if (this.submitTO) {
      clearTimeout(this.submitTO);
      this.submitTO = null;
    }
    this.base.get(0).controller.selfUpdated = true;
    this.shade();
  }

  return false;
}

// Clear cart
CartView.prototype.clearCart = function(event, form)
{
  if (!this.base.get(0).controller.selfUpdated && this.submitForm(form, this.postprocessActionCallback)) {
    if (this.submitTO) {
      clearTimeout(this.submitTO);
      this.submitTO = null;
    }
    this.shade();
    this.base.get(0).controller.selfUpdated = true;
  }

  return false;
}

// Form POST processor
CartView.prototype.postprocessAction = function(XMLHttpRequest, textStatus, data, isValid)
{
  this.base.get(0).controller.selfUpdated = false;

  if (isValid) {
    this.load();

  } else {
    this.unshade();
  }
}

// Get base element for shade / unshade operation
CartView.prototype.getShadeBase = function() {
  return this.base.parents('#content').eq(0);
}

core.autoload(CartController);
