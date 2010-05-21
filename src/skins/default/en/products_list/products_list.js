/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Products list controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function ProductsList(cell, URLParams, URLAJAXParams) {
  this.container = $('.products-list.' + cell).eq(0);

  if (!this.container.length) {
    return;
  }

  this.cell = cell;
  this.URLParams = URLParams;
  this.URLAJAXParams = URLAJAXParams;

  this.addListeners();
}

ProductsList.prototype.container = null;

ProductsList.prototype.cell = null;
ProductsList.prototype.urlParams = null;
ProductsList.prototype.urlAJAXParams = null;

/**
 * Methods
 */

// Set new display mode
ProductsList.prototype.changeDisplayMode = function(handler)
{
  return this.process('displayMode', $(handler).attr('class'));
}

// Change current page
ProductsList.prototype.showPage = function(handler)
{
  return this.process('pageId', $(handler).attr('class'));
}

// Change sort criterion
ProductsList.prototype.changeSortByMode = function(handler)
{
  return this.process('sortBy', handler.options[handler.selectedIndex].value);
}

// Change sort order
ProductsList.prototype.changeSortOrder = function()
{
  return this.process('sortOrder', ('asc' == this.URLParams.sortOrder) ? 'desc' : 'asc');
}

// Change items per page number
ProductsList.prototype.changePageLength = function(handler)
{
  count = parseInt(handler.value);

  if (isNaN(count)) {
    count = this.URLParams.itemsPerPage;

  } else if (count < 1) {
    count = 1;
  }

  if (count != handler.value) {
    handler.value = count;
  }

  this.process('itemsPerPage', count);

  return true;
}

// Add event listeners
ProductsList.prototype.addListeners = function()
{
  var o = this;

  $('.pager a', this.container).click(
    function() {
      return !o.showPage(this);
    }
  );

  $('input.page-length', this.container).change(
    function() {
      return !o.changePageLength(this);
    }
  );

  $('.display-modes a', this.container).click(
    function() {
      return !o.changeDisplayMode(this);
    }
  );

  $('select.sort-crit', this.container).change(
    function() {
      return !o.changeSortByMode(this);
    }
  );

  $('a.sort-order', this.container).click(
    function() {
      return !o.changeSortOrder();
    }
  );
}

// Change URL param
ProductsList.prototype.setURLParam = function(paramName, paramValue)
{
  var result = (paramValue != this.URLParams[paramName]) || (paramValue != this.URLAJAXParams[paramName]);

  if (result) {
    this.URLParams[paramName] = paramValue;
    this.URLAJAXParams[paramName] = paramValue;
  }

  return result;
}

// Set a param and send the request
ProductsList.prototype.process = function(paramName, paramValue)
{
  if (this.setURLParam(paramName, paramValue)) {
    this.loadWidget();
  }

  return true;
} 

// Load (reload) widget
ProductsList.prototype.loadWidget = function()
{
  if (hasAJAXSupport()) {

    this.showModalScreen();
    var o = this;
    $.ajax(
      {
        type: 'get',
        url: this.buildURL(true),
        timeout: 15000,
        complete: function(xhr, s) {
          return o.loadHandler(xhr, s);
        }
      }
    );

  } else {
    self.location = this.buildURL();
  }
}

// Show modal screen
ProductsList.prototype.showModalScreen = function()
{
  this.container.block(
    {
      message: '<div></div>',
      css: {
        width: '30%',
        top: '35%',
        left: '35%',
      },
      overlayCSS: {
        opacity: 0.1
      },
    }
  );

  $('.blockElement')
    .css({padding: null, border: null, margin: null, textAlign: null, color: null, backgroundColor: null, cursor: null})
    .addClass('wait-block');
  $('.blockOverlay')
    .css({padding: null, border: null, margin: null, textAlign: null, color: null, backgroundColor: null, cursor: null})
    .addClass('wait-block-overlay');
}

// Hide modal screen
ProductsList.prototype.hideModalScreen = function()
{
  this.container.unblock();
}

// Build URL
ProductsList.prototype.buildURL = function(forAJAX)
{
  return URLHandler.buildURL(forAJAX ? this.URLAJAXParams : this.URLParams);
}

// AJAX onload event handler
ProductsList.prototype.loadHandler = function(xhr, s)
{
  var processed = false;

  if (xhr.status == 200 && xhr.responseText) {
    var div = document.createElement('DIV');
    $(div).html(xhr.responseText);

    div = $('.products-list.' + this.cell, div).eq(0);
    if (div.length) {
      this.container.replaceWith(div);
      this.container = $('.products-list.' + this.cell);

      this.addListeners();

      processed = true;
    }
  }

  this.hideModalScreen();

  if (!processed) {
    self.location = this.buildURL();
  }
}
