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

var productsListHandlers = [];
var sessionCell = '';

function ProductsList(cell) {

  /**
   * Properties
   */

  var container = $('.products-list.' + cell);

  /**
   * Methods
   */

  // Set new display mode
  this.changeDisplayMode = function(handler)
  {
    return this.process('displayMode', handler.getAttribute('class'));
  };

  // Change current page
  this.showPage = function(handler)
  {
    return this.process('pageId', handler.getAttribute('class'));
  };

  // Change sort criterion
  this.changeSortByMode = function(handler)
  {
    return this.process('sortBy', handler.value);
  };

  // Change sort order
  this.changeSortOrder = function()
  {
    return this.process('sortOrder', ('asc' == this.URLParams.sortOrder) ? 'desc' : 'asc');
  };

  // Change items per page number
  this.changePageLength = function(handler)
  {
    count = parseInt(handler.value);

    if (isNaN(count)) {
      count = 0;
    }

    if (count != handler.value) {
      handler.value = count;
    }

    return this.process('itemsPerPage', count);
  };


  // Add event listeners
  this.addListeners = function()
  {
    var o = this;

    $('.pager a', container).click(
      function() {
        return !o.showPage(this);
      }
    );

    $('input.page-length', container).change(
      function() {
        return !o.changePageLength(this);
      }
    );

    $('.display-modes a', container).click(
      function() {
        return !o.changeDisplayMode(this);
      }
    );

    $('select.sort-crit', container).change(
      function() {
        return !o.changeSortByMode(this);
      }
    );

    $('a.sort-order', container).click(
      function() {
        return !o.changeSortOrder();
      }
    );
  };

  // Change URL param
  this.setURLParam = function(paramName, paramValue)
  {
    result = (paramValue != this.URLParams[paramName]) || (paramValue != this.URLAJAXParams[paramName]);

    if (result) {
      this.URLParams[paramName] = paramValue;
      this.URLAJAXParams[paramName] = paramValue;
    }

    return result;
  };

  // Set a param and send the request
  this.process = function(paramName, paramValue)
  {
    if (this.setURLParam(paramName, paramValue)) {
      this.loadWidget();
    }

    return true;
  }; 

  // Load (reload) widget
  this.loadWidget = function()
  {
    if (hasAJAXSupport()) {

      this.showModalScreen();
      var o = this;
      $.ajax(
        {
          type: 'get',
          url: this.buildURL(true),
          complete: function(xhr, s) {
            return o.loadHandler(xhr, s);
          }
        }
      );

    } else {
      self.location = this.buildURL();
    }
  };

  // Show modal screen
  this.showModalScreen = function()
  {
    container.block(
      {
        message: '<div></div>',
        css: {
          margin:         0,
          width:          '30%',
          top:            '35%',
          left:           '35%',
          textAlign:      'center',
          cursor:         'wait'
        },
        overlayCSS: {
          backgroundColor: '#000',
          opacity:         0.1
        },
      }
    );

    $('.blockElement').addClass('wait-block');
    $('.blockOverlay').addClass('wait-block-overlay');
  };

  // Hide modal screen
  this.hideModalScreen = function()
  {
    container.unblock();
  };

  // Build URL
  this.buildURL = function(forAJAX)
  {
    return URLHandler.buildURL(forAJAX ? this.URLAJAXParams : this.URLParams);
  };

  // AJAX onload event handler
  this.loadHandler = function(xhr, s)
  {
    var processed = false;

    if (xhr.status == 200 && xhr.responseText) {
      var div = document.createElement('DIV');
      $(div).html(xhr.responseText);

      container.replaceWith($('.products-list.' + cell, div));
      container = $('.products-list.' + cell);

      this.addListeners();

      processed = true;
    }

    this.hideModalScreen();

    if (!processed) {
      self.location = this.buildURL();
    }
  };

  this.addListeners();
}

