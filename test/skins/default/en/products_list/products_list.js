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

var productsList = {

  /**
   * Properties
   */

  container: null,
  ajaxSupport: false,


  /**
   * Public methods
   */

  // Initialization
  initialization: function()
  {
    this.container = $('.products-list');

    // Detect AJAX support
    try {
      var xhr = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
      if (xhr) {
        this.ajaxSupport = true;
      }
    } catch(e) { }
  },

  // Set new display mode
  changeDisplayMode: function(mode)
  {
    this.process('displayMode', mode);
  },

  // Change current page
  showPage: function(pageId)
  {
    this.process('pageId', pageId);
  },

  // Change sort criterion
  changeSortByMode: function(mode)
  {
    this.process('sortBy', mode);
  },

  // Change sort order
  changeSortOrder: function()
  {
    this.process('sortOrder', ('asc' == this.URLParams.sortOrder) ? 'desc' : 'asc');
  },

  // Change items per page number
  changePageLength: function(inputBox)
  {
    count = parseInt(inputBox.value);

    if (isNaN(count)) {
      count = 0;
    }

    if (count != inputBox.value) {
      inputBox.value = count;
    }

    this.process('itemsPerPage', count);
  },


  /**
   * Protected methods
   */

  // Change URL param
  setURLParam: function(paramName, paramValue)
  {
    result = (paramValue != this.URLParams[paramName]) || (paramValue != this.URLAJAXParams[paramName]);

    if (result) {
      this.URLParams[paramName] = paramValue;
      this.URLAJAXParams[paramName] = paramValue;
    }

    return result;
  },

  // Set a param and send the request
  process: function(paramName, paramValue)
  {
    if (this.setURLParam(paramName, paramValue)) {
      this.loadWidget();
    }
  }, 

  // Load (reload) widget
  loadWidget: function()
  {
    if (this.ajaxSupport) {

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
  },

  // Show modal screen
  showModalScreen: function()
  {
    $('.products-list').block(
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
  },

  // Hide modal screen
  hideModalScreen: function()
  {
    $('.products-list').unblock();
  },

  // Build URL
  buildURL: function(forAJAX)
  {
    return URLHandler.buildURL(forAJAX ? this.URLAJAXParams : this.URLParams);
  },

  // AJAX onload event handler
  loadHandler: function(xhr, s)
  {
    var processed = false;

    if (xhr.status == 200 && xhr.responseText) {
      var div = document.createElement('DIV');
      $(div).html(xhr.responseText);

      this.container.replaceWith($('.products-list', div));
      this.container = $('.products-list');

      processed = true;
    }

    this.hideModalScreen();

    if (!processed) {
      self.location = this.buildURL();
    }
  },
}

// onready event handler
$('.products-list').ready(
  function() {
    return productsList.initialization();
  }
);

