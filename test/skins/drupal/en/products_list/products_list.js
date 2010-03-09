/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Products list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function productsList(cellName, config) {
  if (!config) {
    return;
  }

  this.config = config;

  this.container = $('#' + cellName + 'Container');

  if (this.container.length != 1) {
    return;
  }

  this.cellName = cellName;

  var o = this;

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
    var xhr = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
    if (xhr) {
      this.ajaxSupport = true;
    }
  } catch(e) { }

  /* TODO - do it later
  $('.draggable', this.container)
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

  this.isValid = true;
}

/**
 * Properties
 */

// Valid object pr not
productsList.prototype.isValid = false;

// Page id argument
productsList.prototype.pageId = 0;

// Sort criterion argument
productsList.prototype.sortCriterion = 'name';

// Sort order argument
productsList.prototype.sortOrder = 'asc';

// Display mode argument
productsList.prototype.displayMode = 'grid';

// Items-per-page argument
productsList.prototype.itemsPerPage = '10';

// List container element
productsList.prototype.container = null;

// AJAX is enabled or not
productsList.prototype.ajaxSupport = false;

// Object options
productsList.prototype.config = null;

// Object cell name
productsList.prototype.cellName = null;

/**
 * Public methods
 */

// Change list current page 
productsList.prototype.changePage = function(link)
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
    pageId = this.config.pagerItemsCount - 1;

  } else if (li.hasClass('page-item')) {
    var m = li.get(0).className.match(/page-([0-9]+)/);
    pageId = parseInt(m[1]) - 1;
  }

  pageId = Math.min(this.config.pagerItemsCount - 1, Math.max(0, pageId));

  if (pageId != this.pageId) {
    this.pageId = pageId;
    this.loadWidget();
  }

  return true;
}

// Change list display mode
productsList.prototype.changeType = function (link)
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
  for (var i = 0; i < this.config.displayModes.length && !found; i++) {
    if (this.config.displayModes[i] == m[1]) {
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
}

// Change sort criterion
productsList.prototype.changeSortCriterion = function(list)
{
  var sortCriterion = list.options[list.selectedIndex].value;
  if (sortCriterion != this.sortCriterion) {
    this.sortCriterion = sortCriterion;
    this.loadWidget();
  }

  return true;
}

// Change sort order
productsList.prototype.changeSortOrder = function(link)
{
  var sortOrder = $(link).hasClass('sort-order-asc') ? 'desc' : 'asc';
  if (sortOrder != this.sortOrder) {
    this.sortOrder = sortOrder;
    this.loadWidget();
  }

  return true;
}

// Change items-per-page
productsList.prototype.changePageLength = function(input)
{
  var itemsPerPage = parseInt(input.value);
  if (isNaN(itemsPerPage)) {
    input.value = this.itemsPerPage;
    return false;
  }

  itemsPerPage = Math.min(this.config.itemsPerPageRange.max, Math.max(this.config.itemsPerPageRange.min, itemsPerPage));

  if (itemsPerPage != this.itemsPerPage) {
    this.itemsPerPage = itemsPerPage;
    this.loadWidget();
  }

  return true;
}

/**
 * Protected methods
 */

// Load (reload) widget
productsList.prototype.loadWidget = function()
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

  return true;
}

// Show modal screen
productsList.prototype.showModalScreen = function()
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
}

// Hide modal screen
productsList.prototype.hideModalScreen = function()
{
  $('.products-list').unblock();
}

// Build URL
productsList.prototype.buildURL = function(forAJAX)
{
  var url = forAJAX ? this.config.ajaxURL : this.config.URL;

  var fields = ['pageId', 'sortCriterion', 'sortOrder', 'itemsPerPage', 'displayMode'];
  for (var i = 0; i < fields.length; i++) {
    url = url.replace(new RegExp(this.config.urlTranslationTable[fields[i]]), this[fields[i]]);
  }

  return url;
}

// AJAX onload event handler
productsList.prototype.loadHandler = function(xhr, s)
{
  var processed = false;

  if (xhr.status == 200 && xhr.responseText) {
    var div = document.createElement('DIV');
    $(div).html(xhr.responseText);

    div = $('.products-list', div);

    if (div.length > 0) {
      this.container.replaceWith(div.eq(0));
      this.container = $('#' + this.cellName + 'Container');

      this.addListeners();

      this.config.pagerItemsCount = Math.ceil(this.config.itemsCount / this.itemsPerPage);
      this.pageId = Math.min(this.pageId, this.config.pagerItemsCount - 1);

      processed = true;
    }
  }

  this.hideModalScreen();

  if (!processed) {
    self.location = this.buildURL();
  }
}

// Add event listeners
productsList.prototype.addListeners = function()
{
  var o = this;

  // Add page links listeners
  $('.pager a', this.container).click(
    function() {
      return !o.changePage(this);
    }
  );

  // Add items-per-page input listener
  $('input.page-length', this.container).change(
    function() {
      return o.changePageLength(this);
    }
  );

  // Add display modes links listeners
  $('.display-modes a', this.container).click(
    function() {
      return !o.changeType(this);
    }
  );

  // Add sort criterion selector listener
  $('select.sort-crit', this.container).change(
    function() {
      return o.changeSortCriterion(this);
    }
  );

  // Add sort order link listener
  $('a.sort-order', this.container).click(
    function() {
      return !o.changeSortOrder(this);
    }
  );
}
