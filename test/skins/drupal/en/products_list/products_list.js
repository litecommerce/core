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

  pageId: null,

  sortCriterion: null,

  sortOrder: null,

  displayMode: null,

  itemsPerPage: null,

  container: null,

  ajaxSupport: false,

  /**
   * Public methods
   */

  // Initialization
  initialization: function()
  {
    var o = this;

    this.container = $('.products-list');

    // Detect page
    var l = $('.pager li.selected', this.container).get(0);
    if (l) {
      var m = l.className.match(/page-([0-9]+)/);
      this.pageId = parseInt(m[1]) - 1;
    }

    // Detect display mode
    $('.list-head .display-modes li', this.container).each(
      function() {
        if ($(this).hasClass('selected')) {
          var m = this.className.match(/list-type-([^ ]+)/);
          o.displayMode = m[1];
        }
      }
    );

    // Detect sort criterion
    var s = $('.list-head .sort-crit', this.container).get(0);
    if (s) {
      this.sortCriterion = s.options[s.selectedIndex].value
    }

    // Detect sort order
    var l = $('.list-head .sort-order', this.container).get(0);
    if (l) {
      this.sortOrder = $(l).hasClass('sort-order-asc') ? 'asc' : 'desc';
    }

    // Detect items-per-page
    var l = $('.list-pager input', this.container).get(0);
    if (l) {
      this.itemsPerPage = parseInt(l.value);
    }

    // Add listeners
    this.addListeners();

    // Detect AJAX support
    try {
      var xhr = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
      if (xhr) {
        this.ajaxSupport = true;
      }
    } catch(e) { }
    
  },

  // Change list current page 
  changePage: function(link)
  {
    var li = $(link).parents('li').eq(0);
    if (!li.length) {
      return false;

    } else if (li.hasClass('disabled')) {
      return true;
    }

    var pageId = this.pageId;

    if (li.hasClass('first')) {
      pageId = 0;

    } else if (li.hasClass('previous')) {
      pageId--;

    } else if (li.hasClass('next')) {
      pageId++;

    } else if (li.hasClass('last')) {
      pageId = productsListConfig.pagerItemsCount - 1;

    } else if (li.hasClass('page-item')) {
      var m = li.get(0).className.match(/page-([0-9]+)/);
      pageId = parseInt(m[1]) - 1;
    }

    pageId = Math.min(productsListConfig.pagerItemsCount - 1, Math.max(0, pageId));

    if (pageId != this.pageId) {
      this.pageId = pageId;
      this.loadWidget();
    }

    return true;
  },

  // Change list display mode
  changeType: function (link)
  {
    var li = $(link).parents('li').get(0);
    if (!li) {
      return false;
    }

    var m = li.className.match(/list-type-([^ ]+)/);
    if (!m[1]) {
      return false;
    }

    var found = false;
    for (var i = 0; i < productsListConfig.displayModes.length && !found; i++) {
      if (productsListConfig.displayModes[i] == m[1]) {
        found = true;
      }
    }

    if (!found) {
      return false;
    }

    if (m[1] != this.displayMode) {
      this.displayMode = m[1];
      this.loadWidget();
    }

    return true;
  },

  // Change sort criterion
  changeSortCriterion: function(list)
  {
    var sortCriterion = list.options[list.selectedIndex].value;
    if (sortCriterion != this.sortCriterion) {
      this.sortCriterion = sortCriterion;
      this.loadWidget();
    }

    return true;
  },

  // Change sort order
  changeSortOrder: function(link)
  {
    var sortOrder = $(link).hasClass('sort-order-asc') ? 'desc' : 'asc';
    if (sortOrder != this.sortOrder) {
      this.sortOrder = sortOrder;
      this.loadWidget();
    }

    return true;
  },

  // Change items-per-page
  changePageLength: function(input)
  {
    var itemsPerPage = parseInt(input.value);
    if (isNaN(itemsPerPage)) {
      input.value = this.itemsPerPage;
      return false;
    }

    itemsPerPage = Math.min(productsListConfig.itemsPerPageRange.max, Math.max(productsListConfig.itemsPerPageRange.min, itemsPerPage));

    if (itemsPerPage != this.itemsPerPage) {
      this.itemsPerPage = itemsPerPage;
      this.loadWidget();
    }

    return true;
  },

  /**
   * Protected methods
   */

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
    var url = forAJAX ? productsListConfig.ajaxURL : productsListConfig.URL;

    var fields = ['pageId', 'sortCriterion', 'sortOrder', 'itemsPerPage', 'displayMode'];
    for (var i = 0; i < fields.length; i++) {
      url = url.replace(new RegExp(productsListConfig.urlTranslationTable[fields[i]]), this[fields[i]]);
    }

    return url;
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

      this.addListeners();

      productsListConfig.pagerItemsCount = Math.ceil(productsListConfig.itemsCount / this.itemsPerPage);
      this.pageId = Math.min(this.pageId, productsListConfig.pagerItemsCount - 1);

      processed = true;
    }

    this.hideModalScreen();

    if (!processed) {
      self.location = this.buildURL();
    }
  },

  // Add event listeners
  addListeners: function()
  {
    var o = this;

    // Add page links listeners
    $('.pager a', this.container).click(
      function() {
        return !o.changePage(this);
      }
    );

    // Add display modes links listeners
    $('.display-modes a', this.container).click(
      function() {
        return !o.changeType(this);
      }
    );

    // Add sort order link listener
    $('a.sort-order', this.container).click(
      function() {
        return !o.changeSortOrder(this);
      }
    );

  }
}

// onready event handler
$(document).ready(
  function() {
    productsList.initialization();

    /* TODO - do it later
    $('.draggable', productsList.container)
      .hover(
        function() {
          $(this).addClass('ui-draggable-hover');
        },
        function() {
          $(this).removeClass('ui-draggable-hover');
        }
      )
      .draggable(
        {
          helper: function() {
            var e = $(this);
            return e.clone().width(e.width());
          },
          opacity: 0.5,
          revert: 'invalid',
          zIndex: 2000
        }
      );
    */
  }
);
