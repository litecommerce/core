/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Common items list controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

// Main class
function ItemsList(cell, URLParams, URLAJAXParams)
{
  this.container = jQuery('.items-list').eq(0);

  if (!this.container.length) {
    return;
  }

  this.cell = cell;
  this.URLParams = URLParams;
  this.URLAJAXParams = URLAJAXParams;

  // Common form support
  CommonForm.autoassign(this.container);

  this.addListeners();
}

ItemsList.prototype.container = null;

ItemsList.prototype.cell = null;
ItemsList.prototype.urlParams = null;
ItemsList.prototype.urlAJAXParams = null;
ItemsList.prototype.listeners = {};

ItemsList.prototype.listeners.pager = function(handler)
{
  jQuery('.pager a', handler.container).click(
    function() {
      return !handler.showPage(this);
    }
  );
}

ItemsList.prototype.listeners.pagesCount = function(handler)
{
  jQuery('input.page-length', handler.container).change(
    function() {
      if (this.form) {
        var hnd = function() { return false; }
        jQuery(this.form).submit(hnd);
        var f = this.form;
        setTimeout(function() { jQuery(f).unbind('submit', hnd); }, 500);
      }

      return !handler.changePageLength(this);
    }
  );
}

ItemsList.prototype.listeners.checkboxes = function(handler)
{
  jQuery('input:checkbox.check-all', handler.container).click(
    function() {
      return handler.checkAll(this);
    }
  );
}

ItemsList.prototype.listeners.sortByModes = function(handler)
{
  jQuery('.sort-order .part.sort-crit a', handler.container).click(
    function() {
      return !handler.changeSortByMode(this);
    }
  );
}

ItemsList.prototype.listeners.sortOrderModes = function(handler)
{
  jQuery('.sort-order .part.order-by a', handler.container).click(
    function() {
      return !handler.changeSortOrder();
    }
  );

}

// Change sort criterion
ItemsList.prototype.changeSortByMode = function(handler)
{
  return this.process('sortBy', jQuery(handler).attr('class'));
}

// Change sort order
ItemsList.prototype.changeSortOrder = function()
{
  return this.process('sortOrder', ('asc' == this.URLParams.sortOrder) ? 'desc' : 'asc');
}


// Check all checkboxes in list
ItemsList.prototype.checkAll = function(handler)
{
  return this.container.find('input:checkbox.checkbox').attr('checked', jQuery(handler).attr('checked') ? 'checked' : '');
}

// Change current page
ItemsList.prototype.showPage = function(handler)
{
//TODO change to getCommentedData() -> also in templates
  return this.process('pageId', core.getValueFromClass(handler,'page'));
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

  return this.process('itemsPerPage', count);
}

// Add event listeners
ItemsList.prototype.addListeners = function()
{
  for (var x in this.listeners) {
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
    jQuery.ajax(
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
  jQuery('.blockElement')
    .css({padding: null, border: null, margin: null, textAlign: null, color: null, backgroundColor: null, cursor: null})
    .addClass('wait-block');
  jQuery('.blockOverlay')
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
  var list = forAJAX ? this.URLAJAXParams : this.URLParams;

  if (typeof(list.sessionCell) != 'undefined') {
      list.sessionCell = null;
      delete list.sessionCell;
  }

  return URLHandler.buildURL(list);
}

// AJAX onload event handler
ItemsList.prototype.loadHandler = function(xhr, s)
{
  var processed = false;

  if (xhr.status == 200 && xhr.responseText) {

    var div = document.createElement('div');

    jQuery(div).html(jQuery('.items-list.sessioncell-' + this.cell, xhr.responseText));

    this.container.replaceWith(div);

    new ItemsList(this.cell, this.URLParams, this.URLAJAXParams);

    processed = true;
  }

  this.hideModalScreen();

  if (!processed) {
    self.location = this.buildURL();
  }
}
