/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Main class
function ItemsList(cell, URLParams, URLAJAXParams)
{
  this.container = $('.items-list.' + cell).eq(0);

  if (!this.container.length) {
    return;
  }

  this.cell = cell;
  this.URLParams = URLParams;
  this.URLAJAXParams = URLAJAXParams;

  this.addListeners();
}

ItemsList.prototype.container = null;

ItemsList.prototype.cell = null;
ItemsList.prototype.urlParams = null;
ItemsList.prototype.urlAJAXParams = null;
ItemsList.prototype.listeners = {};

ItemsList.prototype.listeners.pager = function(handler)
{
  $('.pager a', handler.container).click(
    function() {
      return !handler.showPage(this);
    }
  );
}

ItemsList.prototype.listeners.pagesCount = function(handler)
{
  $('input.page-length', handler.container).change(
    function() {
      return !handler.changePageLength(this);
    } 
  );
}


// Change current page
ItemsList.prototype.showPage = function(handler)
{
  return this.process('pageId', $(handler).attr('class'));
}

// Change items per page number
ItemsList.prototype.changePageLength = function(handler)
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
ItemsList.prototype.addListeners = function()
{
  for (x in this.listeners) {
    this.listeners[x](this);
  }
}

// Change URL param
ItemsList.prototype.setURLParam = function(paramName, paramValue)
{
  var result = (paramValue != this.URLParams[paramName]) || (paramValue != this.URLAJAXParams[paramName]);

  if (result) {
    this.URLParams[paramName] = paramValue;
    this.URLAJAXParams[paramName] = paramValue;
  }

  return result;
}

// Set a param and send the request
ItemsList.prototype.process = function(paramName, paramValue)
{
  if (this.setURLParam(paramName, paramValue)) {
    this.loadWidget();
  }

  return true;
}

// Load (reload) widget
ItemsList.prototype.loadWidget = function()
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
ItemsList.prototype.showModalScreen = function()
{
  this.container.block(
    {
      message: '<div></div>',
      css: {
        width: '30%',
        top: '35%',
        left: '35%'
      },
      overlayCSS: {
        opacity: 0.1
      }
    }
  );

  // FIXME - check if there is more convinient way
  $('.blockElement')
    .css({padding: null, border: null, margin: null, textAlign: null, color: null, backgroundColor: null, cursor: null})
    .addClass('wait-block');
  $('.blockOverlay')
    .css({padding: null, border: null, margin: null, textAlign: null, color: null, backgroundColor: null, cursor: null})
    .addClass('wait-block-overlay');
}

// Hide modal screen
ItemsList.prototype.hideModalScreen = function()
{
  this.container.unblock();
}

// Build URL
ItemsList.prototype.buildURL = function(forAJAX)
{
  return URLHandler.buildURL(forAJAX ? this.URLAJAXParams : this.URLParams);
}

// AJAX onload event handler
ItemsList.prototype.loadHandler = function(xhr, s)
{
  var processed = false;

  if (xhr.status == 200 && xhr.responseText) {
    var div = document.createElement('DIV');
    $(div).html(xhr.responseText);

    div = $('.items-list.' + this.cell, div).eq(0);
    if (div.length) {
      this.container.replaceWith(div);
      this.container = $('.items-list.' + this.cell);

      this.addListeners();

      processed = true;
    }
  }

  this.hideModalScreen();

  if (!processed) {
    self.location = this.buildURL();
  }
}
