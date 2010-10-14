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

  return !popup.load(URLHandler.buildURL(this.URLAJAXParams), 'product-quicklook', false, 50000);
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
  var cartTrayFadeOutDuration = 400;

  var draggablePattern = '.products-grid .product, .products-list .product';
  var draggableMarkPattern = '.products-grid .product .drag-n-drop-handle, .products-list .product .drag-n-drop-handle';

  var countRequests = 0;
  var isProductDrag = false;

  $(draggablePattern, handler.container).draggable(
    {
      revert: 'invalid',
      revertDuration: 300,
      zIndex: 500,

      helper: function()
      {
        var clone = $(this)
          .clone()
          .css(
            {
              'width':   $(this).outerWidth() + 'px',
              'height':  $(this).outerHeight() + 'px'
            }
          );

        $(this).addClass('drag-owner');

        return clone;
      },

      start: function(event, ui)
      {
        isProductDrag = true;
        $('.cart-tray').not('.cart-tray-adding').not('.cart-tray-added')
          .addClass('cart-tray-active')
          .addClass('cart-tray-moving')
          .attr('style', '');
      },

      stop: function(event, ui)
      {
        isProductDrag = false;
        $('.cart-tray').not('.cart-tray-adding').not('.cart-tray-added')
          .fadeOut(
            cartTrayFadeOutDuration,
            function() {
              if (isProductDrag) {
                $(this).show();

              } else {
                $(this)
                  .removeClass('cart-tray-active')
                  .removeClass('cart-tray-moving')
                  .removeClass('cart-tray-added');
              }
            }
          );

        $('.drag-owner').removeClass('drag-owner');
      },
    }
  );

  $('.cart-tray').droppable(
    {
      tolerance: 'touch',

      over: function(event, ui)
      {
        $('.cart-tray .tray-area').addClass('droppable');
      },

      out: function(event, ui)
      {
        $('.cart-tray .tray-area').removeClass('droppable');
      },

      drop: function(event, ui)
      {
        if (isProductDrag) {
          $('.cart-tray')
            .removeClass('cart-tray-moving')
            .removeClass('cart-tray-added')
            .addClass('cart-tray-adding')
            .find('.tray-area')
            .removeClass('droppable');

          countRequests++;

          var m = $(ui.draggable)
            .attr('class')
            .match(/productid-([0-9]+)/);
          if (m) {
            core.post(
              URLHandler.buildURL({}),
              {
                target:     'cart',
                action:     'add',
                product_id: m[1]
              },
              function(XMLHttpRequest, textStatus, data, isValid)
              {
                countRequests--;
                if (0 == countRequests) {
                  $('.cart-tray')
                    .removeClass('cart-tray-adding')
                    .addClass('cart-tray-added');
                  setTimeout(
                    function() {
                      if (isProductDrag) {
                        $('.cart-tray')
                          .removeClass('cart-tray-added')
                          .addClass('cart-tray-moving');

                      } else {
                        $('.cart-tray').not('.cart-tray-adding')
                          .fadeOut(
                            cartTrayFadeOutDuration,
                            function() {
                              if (isProductDrag) {
                                $(this)
                                  .removeClass('cart-tray-added')
                                  .addClass('cart-tray-moving')
                                  .show();

                              } else {
                                $(this)
                                .removeClass('cart-tray-active')
                                .removeClass('cart-tray-added');
                              }
                            }
                          );
                      }
                    },
                    3000
                  );
                }
              }
            );
          }
        }
      },
    }
  );
}

ProductsList.prototype.listeners.quickLookButtons = function(handler)
{
  $('.products .product .quicklook button.action', handler.container).click(
    function() {
      return !handler.openQuickLookPopup(this);
    }
  );
}

