/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Order items short list controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */
function OrderItemsShortListController(container)
{
  // Check and initialize widget
  if (!container) {
    return false;
  }

  this.container = jQuery(container).eq(0);
  if (!this.container.length) {
    return false;
  }

  this.shortList = jQuery('table', this.container).eq(0);
  if (!this.shortList.length) {
    return false;
  }

  var m = this.container.attr('class').match(/order-([0-9]+)/);
  if (m) {
    this.orderId = parseInt(m[1]);
    if (isNaN(this.orderId) || 0 > this.orderId) {
      return false;
    }
  }

  if (typeof(this.container.get(0).orderItemsShortListController) != 'undefined') {
    return false;
  }

  this.container.get(0).orderItemsShortListController = this;

  this.modalTarget = this.container;

  this.widgetTarget = 'order_history';
  this.widgetClass = '\XLite\View\OrderItemsShort';

  // Add event listeners
  var o = this;

  jQuery('a', this.container).click(
    function (event) {
      event.stopPropagation();
    }
  );

  jQuery('.more a', this.container).click(
    function(event) {
      return !o.loadList(event);
    }
  );
}

// Inheritance
OrderItemsShortListController.prototype = new LoadableWidgetAbstract();
OrderItemsShortListController.prototype.constructor = LoadableWidgetAbstract;

// Properties
OrderItemsShortListController.prototype.container = null;
OrderItemsShortListController.prototype.orderId = null;

OrderItemsShortListController.prototype.shortList = null;
OrderItemsShortListController.prototype.fullList = null;

// Methods

// Load list
OrderItemsShortListController.prototype.loadList = function(event)
{
  return this.fullList ? this.switchList() : this.loadWidget();
}

// Add widget arguments to parameters list
OrderItemsShortListController.prototype.addWidgetParams = function(params)
{
  params = this.constructor.prototype.addWidgetParams(params);

  params.order_id = this.orderId;
  params.full = 1;

  return params;
}

// Place request data
OrderItemsShortListController.prototype.placeRequestData = function(box)
{
  var processed = false;

  if (box.get(0).tagName.toUpperCase() == 'TABLE') {
    this.fullList = box;
    this.fullList.hide();
    this.fullList.insertAfter(this.shortList);

    var o = this;
    jQuery('.more a', this.fullList).click(
      function(event) {
        event.stopPropagation();
        return !o.loadList(event);
      }
    );

    processed = true;
  }

  return processed;
}

// Widget post processing (after new widge data placing)
OrderItemsShortListController.prototype.postprocess = function(isSuccess)
{
  if (isSuccess) {
    this.switchList();

  } else {
    this.goToOrder();
  }
}

// Switch lists
OrderItemsShortListController.prototype.switchList = function()
{
  if (this.fullList.get(0).style.display == 'none') {
    this.shortList.hide();
    this.fullList.show();

  } else {
    this.fullList.hide();
    this.shortList.show();
  }

  return true;
}

// Go to order page
OrderItemsShortListController.prototype.goToOrder = function()
{
  self.location = jQuery('a.number', jQuery(this.container).parenst('li').eq(0)).attr('href');
}
