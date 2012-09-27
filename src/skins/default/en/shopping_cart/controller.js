/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Cart controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * Main widget
 */

function CartView(base)
{
  this.callSupermethod('constructor', arguments);

  var o = this;

  core.bind(
    'updateCart',
    function() {
      if (o.selfUpdated) {
        o.cartUpdated = true;
      } else {
        o.load();
      }
    }
  );

  this.validate();
}

extend(CartView, ALoadable);

CartView.autoload = function()
{
  jQuery('#cart').each(
    function() {
      new CartView(this);
    }
  );
}

// Shade widget
CartView.prototype.shadeWidget = true;

// Update page title
CartView.prototype.updatePageTitle = true;

// Update breadcrumb last node from loaded request or not
CartView.prototype.updateBreadcrumb = true;

// Widget target
CartView.prototype.widgetTarget = 'cart';

// Widget class name
CartView.prototype.widgetClass = '\\XLite\\View\\Cart';

// Checkout button
CartView.prototype.checkoutButton = jQuery('#cart ul.totals li.button button');

// Widget updated status
CartView.prototype.selfUpdated = false;

// Cart silence updated status
CartView.prototype.cartUpdated = false;

// Postprocess widget
CartView.prototype.postprocess = function(isSuccess, initial)
{
  this.callSupermethod('postprocess', arguments);

  if (isSuccess) {

    var o = this;

    // Item subtotal including scharges
    jQuery('td.item-subtotal div.including-modifiers', this.base).each(
      function() {
        attachTooltip(
          jQuery(this).parents('td.item-subtotal').find('.subtotal'),
          jQuery(this).html()
        );
      }
    );

    // Cart subtotal including scharges
    jQuery('.totals li.subtotal div.including-modifiers', this.base).each(
      function() {
        attachTooltip(
          jQuery(this).parents('li.subtotal').find('.cart-subtotal'),
          jQuery(this).html()
        );
      }
    );

    // Remove item
    jQuery('.selected-product form input.remove', this.base).parents('form')
      .commonController(
        'enableBackgroundSubmit',
        function() {
          return o.preprocessAction();
        },
        function (event, XMLHttpRequest, textStatus, data, isValid) {
          return o.postprocessAction(XMLHttpRequest, textStatus, data, isValid);
        }
      );

    // Update item
    jQuery('.selected-product form.update-quantity', this.base)
      .commonController(
        'enableBackgroundSubmit',
        function() {
          return o.preprocessAction();
        },
        function (event, XMLHttpRequest, textStatus, data, isValid) {
          return o.postprocessAction(XMLHttpRequest, textStatus, data, isValid);
        }
      )
      .commonController('submitOnlyChanged', true);

    // Clear cart
    jQuery('form .clear-bag', this.base).parents('form').eq(0)
      .commonController(
        'enableBackgroundSubmit',
        function() {
          return o.preprocessAction();
        },
        function (event, XMLHttpRequest, textStatus, data, isValid) {
          return o.postprocessAction(XMLHttpRequest, textStatus, data, isValid);
        }
      );

    // Shipping estimator
    jQuery('.estimator button.estimate', this.base).parents('form').eq(0).submit(
      function(event) {
        return o.openShippingEstimator(event, this);
      }
    );
    jQuery('.estimator a.estimate', this.base).click(
      function(event) {
        return o.openShippingEstimator(event, this);
      }
    );

  }
}

CartView.prototype.preprocessAction = function()
{
  var result = false;

  if (!this.selfUpdated) {
    this.selfUpdated = true;
    this.shade();

    // Remove validation errors from other quantity boxes
    jQuery('form.validationEngine', this.base).validationEngine('hide');

    result = true;
  }

  return result;
}

// Open Shipping estimator popup
CartView.prototype.openShippingEstimator = function(event, elm)
{
  if (!this.selfUpdated && !this.submitTO) {
    var o = this;
    this.selfUpdated = true;
    popup.load(
      elm,
      null,
      function(event) {
        o.closePopupHandler();
      }
    );
  }

  return false;
}

// Close Shipping estimator popup handler
CartView.prototype.closePopupHandler = function()
{
  if (this.cartUpdated) {
    this.load();
  }

  this.selfUpdated = false;
  this.cartUpdated = false;
}

// Validate using validation engine plugin
CartView.prototype.validate = function()
{
  if (!jQuery('form.validationEngine', this.base).validationEngine('validate')) {
    this.checkoutButton.attr('disabled','disabled')
      .addClass('disabled add2cart-disabled');
  }
}

// Form POST processor
CartView.prototype.postprocessAction = function(XMLHttpRequest, textStatus, data, isValid)
{
  this.selfUpdated = false;
  this.cartUpdated = false;

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

core.autoload(CartView);
