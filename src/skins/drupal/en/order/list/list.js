/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Orders list controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
function OrdersListController(container, params)
{
  // Check and initialize widget
  if (!container) {
    return false;
  }

  this.container = jQuery(container).eq(0);
  if (!this.container.length) {
    return false;
  }

  if (this.container.get(0).ordersListController) {
    return true;
  }

  this.container.get(0).ordersListController = this;

  this.modalTarget = this.container;

  this.widgetTarget = params.widgetTarget;
  this.widgetAction = params.widgetAction;
  this.widgetClass  = params.widgetClass;
  this.widgetParams = params.widgetParams;

  // Add event listeners
  this.addListeners();

  return true;
}

// Inheritance
OrdersListController.prototype = new LoadableWidgetAbstract();
OrdersListController.prototype.constructor = LoadableWidgetAbstract;

// Properties
OrdersListController.prototype.container = null;

// Methods

// Changes sort criterion / order
OrdersListController.prototype.changeSortCriterion = function(event, form)
{
  var result = true;

  form = jQuery(form);

  if (form.length) {

    event.stopPropagation();
    this.showModalScreen();

    var o = this;
    jQuery.ajax(
      {
        type:     form.attr('method'),
        url:      form.attr('action'),
        data:     form.serialize(),
        timeout:  15000,
        complete: function(xhr, s) {
          return o.listChanged(xhr, s);
        }
      }
    );

    result = false;
  }

  return result;
}

// Change list page
OrdersListController.prototype.changePage = function(event, link)
{
  var result = true;

  link = jQuery(link);

  var li = link.parents('li').eq(0);
  if (
    li.hasClass('selected')
    || li.hasClass('disabled')
  ) {
    event.stopPropagation();
    return false;
  }

  var m = link.attr('class').match(/page-([0-9]+)/);
  var pageId = false;
  if (m) {
    pageId = parseInt(m[1]);
    if (isNaN(pageId) || 0 > pageId) {
      pageId = false;
    }
  }

  if (pageId !== false) {

    event.stopPropagation();
    this.showModalScreen();

    var o = this;
    jQuery.ajax(
      {
        type:     'get',
        url:      link.attr('href'),
        timeout:  15000,
        complete: function(xhr, s) {
          return o.listChanged(xhr, s);
        }
      }
    );

    result = false;
  }

  return result;
}

// List changed
OrdersListController.prototype.listChanged = function(xhr, s)
{
  if (
    xhr.readyState == 4
    && (xhr.status == 200 || xhr.status == 302 || parseInt(XMLHttpRequest.getResponseHeader('ajax-response-status')) == 278)
  ) {
    this.loadWidget();

  } else {
    // TODO - add top message
    this.hideModalScreen();
    self.location.reload();
  }
}

// Widget post processing (after new widge data placing)
OrdersListController.prototype.postprocess = function(isSuccess)
{
  if (isSuccess) {

//    this.container = this.modalTarget;
//    this.addListeners();

  } else {
    // TODO - add top message
    self.location.reload();
  }
}

// Go to order page
OrdersListController.prototype.goToOrder = function(li)
{
  var href = jQuery('a.number', li).attr('href');
  if (href) {
    self.location = href;

  } else {
    // TODO - add top message
  }
}

// Add listeners
OrdersListController.prototype.addListeners = function()
{
  var o = this;

  jQuery('ul li', this.container).click(
    function() {
      o.goToOrder(this);
    }
  );

  if (jQuery.browser.msie && jQuery.browser.version < 8) {

    // Hover emulation
    jQuery('ul li', this.container).hover(
      function() {
        jQuery(this).addClass('hover');
      },
      function() {
        jQuery(this).removeClass('hover');
      }
    );
  }

  jQuery('ul li a', this.container).click(
    function (event) {
      event.stopPropagation();
    }
  );

  jQuery('form.sort-box', this.container).submit(
    function (event) {
      return o.changeSortCriterion(event, this);
    }
  );

  jQuery('.pager a', this.container).click(
    function (event) {
      return o.changePage(event, this);
    }
  );
}
