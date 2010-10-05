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

// Products list class

extend(ProductsList, ItemsList);

function ProductsList(cell, URLParams, URLAJAXParams)
{
  if (!cell) {
    return;
  }

  this.callSupermethod('constructor', arguments);
}

//extend(ProductsList, ItemsList);

// Set new display mode
ProductsList.prototype.changeDisplayMode = function(handler)
{
  return this.process('displayMode', $(handler).attr('class'));
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

// Open the QuickLook popup
ProductsList.prototype.openQuickLookPopup = function(button)
{
  this.URLAJAXParams['target'] = 'quick_look';
  this.URLAJAXParams['product_id'] = $(button).attr('id');

  return !popup.load(URLHandler.buildURL(this.URLAJAXParams), 'product-quicklook');
}

ProductsList.prototype.listeners.displayModes = function(handler)
{
  $('.display-modes a', handler.container).click(
    function() {
      return !handler.changeDisplayMode(this);
    }
  );
}

ProductsList.prototype.listeners.sortByModes = function(handler)
{
  $('select.sort-crit', handler.container).change(
    function() {
      return !handler.changeSortByMode(this);
    }
  );
}

ProductsList.prototype.listeners.sortOrderModes = function(handler)
{
  $('a.sort-order', handler.container).click(
    function() {
      return !handler.changeSortOrder();
    }
  );
}

// TODO - to improve
ProductsList.prototype.listeners.dragNDrop = function(handler)
{
  var isDropped = false;

  $('table.list-body-grid td.hproduct, table.list-body-list tr.info', handler.container).draggable({
    helper: function() {
      clone = $(this).clone();

      // TODO - check if there is more convinient way
      clone.css('width', $(this).css('width'));
      clone.css('height', $(this).css('height'));

      clone.css('opacity', 0.5);
      clone.css('z-index', 500);

      return clone;
    },
    start: function(event, ui) {
      isDropped = false;
      $('div.cart-tray-box div.text div').html('<span>Drop items here</span><br /><span>to shop</span>');
      $('div.cart-tray-box').show();
    },
    stop: function(event, ui) {
      if (!isDropped) {
        $('div.cart-tray-box').hide();
      }
    },
  });

  $('div.cart-tray-box').droppable({
    hoverClass: 'droppable',
    tolerance: 'touch',
    over: function(event, ui) {
      $('', 'div.cart-tray-box div.text').addClass('droppable');
    },
    out: function(event, ui) {
      $('span', 'div.cart-tray-box div.text').removeClass('droppable');
    },
    drop: function(event, ui) {
      if (!isDropped) {
        isDropped = true;

        $('div', 'div.cart-tray-box div.text').html('');
        $('div.cart-tray-box div.text').addClass('wait-block');

        core.post(
          URLHandler.buildURL({}),
          {target: 'cart', action: 'add', product_id: $(ui.draggable).attr('id')},
          function(XMLHttpRequest, textStatus, data, isValid) {
            $('div.cart-tray-box div.text').removeClass('wait-block');
            $('div', 'div.cart-tray-box div.text').html('<span>Product added</span><br /><span>to the bag</span>');
            setTimeout(function() {$('div.cart-tray-box').hide();}, 1500);
          }
        );
      }
    },
  });
}

ProductsList.prototype.listeners.quickLookButtons = function(handler)
{
  $('.quick-look-cell button.action', handler.container).click(
    function() {
      return !handler.openQuickLookPopup(this);
    }
  );
}

