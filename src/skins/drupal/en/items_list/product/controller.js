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

function ProductsListView(base)
{
  ProductsListView.superclass.constructor.apply(this, arguments);
}

extend(ProductsListView, ListView);

// Products list class
function ProductsListController(base)
{
  ProductsListController.superclass.constructor.apply(this, arguments);
}

extend(ProductsListController, ListsController);

ProductsListController.prototype.name = 'ProductsListController';

ProductsListController.prototype.getListView = function()
{
  return new ProductsListView(this.base);
}

ProductsListView.prototype.postprocess = function(isSuccess, initial) 
{
  ProductsListView.superclass.postprocess.apply(this, arguments);

  var o = this;

  if (isSuccess) {

    // Register "Changing display mode" handler
    $('.display-modes a', this.base).click(
      function() {
        return !o.load({'displayMode': $(this).attr('class')});
      }
    );

    // Register "Sort by" selector handler
    $('select.sort-crit', this.base).change(
      function () {
        return !o.load({'sortBy': $(this).val()});
      }
    );

    // Register "ASC/DESC" selector handler
    $('a.sort-order', this.base).click(
      function () {
        // TODO sort order value should be not defined from the content of a.sort-order
        return !o.load({'sortOrder': $(this).html().charCodeAt(0) == 8595 ? 'desc' : 'asc'});
      }
    );

    // Register "Quick look" button handler
    $('.quicklook button.action', this.base).click(
      function () {
        return !popup.load(
          URLHandler.buildURL({
            'target' : 'quick_look',
            'product_id' : core.getValueFromClass(this, 'productid')
          }), 
          'product-quicklook', 
          false, 
          50000
        );
      }
    );

    var cartTrayFadeOutDuration = 400;

    var draggablePattern = '.products-grid .product, .products-list .product';
    var draggableMarkPattern = '.products-grid .product .drag-n-drop-handle, .products-list .product .drag-n-drop-handle';

    var countRequests = 0;
    var isProductDrag = false;

    $(draggablePattern, this.base).draggable(
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
      }, // helper()

      start: function(event, ui)
      {
        isProductDrag = true;
        $('.cart-tray').not('.cart-tray-adding').not('.cart-tray-added')
          .addClass('cart-tray-active')
          .addClass('cart-tray-moving')
          .attr('style', '');
      }, // start()

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
      }, // stop()
    }
    ); // $(draggablePattern, this.base).draggable

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

          var m = core.getValueFromClass($(ui.draggable), 'productid');

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
                  ); // setTimeout()
                } // if (0 == countRequests)
              } // function(XMLHttpRequest, textStatus, data, isValid)
            ); // core.post()
          } // if (m) 
        } // if (isProductDrag)
      }, // drop()
    }
    ); // $('.cart-tray').droppable()

  } // if (isSuccess)
} // ProductsListView.prototype.postprocess()


/**
 * Load product lists controller  
 */
core.autoload(ProductsListController);

