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

  core.bind(
    'updateCart',
    function(event, data) {
      for (var i = 0; i < data.items.length; i++) {
        if (data.items[i].object_type == 'product') {
          productPattern = '.product.productid-' + data.items[i].object_id;
          if (data.items[i].quantity > 0) {
            $(productPattern, base).addClass('product-added');
          } else {
            $(productPattern, base).removeClass('product-added');
          }
        }
      }
    }
  );

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
    $('.quicklook a.quicklook-link', this.base).click(
      function () {
        return !popup.load(
          URLHandler.buildURL({
            'target':     'quick_look',
            'product_id': core.getValueFromClass(this, 'quicklook-link')
          }), 
          'product-quicklook', 
          false, 
          50000
        );
      }
    );

    var cartTrayFadeOutDuration = 400;
    var draggablePattern = '.products-grid .product, .products-list .product';
    var cartTray = $('.cart-tray', this.base).eq(0);
    var countRequests = 0;

    cartTray.data('isProductDrag', false);

    $(draggablePattern, this.base).draggable(
    {
      revert: 'invalid',
      revertDuration: 300,
      zIndex: 500,

      helper: function()
      {
        var base = $(this);
        var clone = base
          .clone()
          .css(
            {
              'width':   base.outerWidth() + 'px',
              'height':  base.outerHeight() + 'px'
            }
          );

        base.addClass('drag-owner');

        return clone;
      }, // helper()

      start: function(event, ui)
      {
        cartTray.data('isProductDrag', true);
        cartTray.not('.cart-tray-adding').not('.cart-tray-added')
          .addClass('cart-tray-active')
          .addClass('cart-tray-moving')
          .attr('style', '');
      }, // start()

      stop: function(event, ui)
      {
        cartTray.data('isProductDrag', false);
        cartTray.not('.cart-tray-adding').not('.cart-tray-added')
          .fadeOut(
            cartTrayFadeOutDuration,
            function() {
              if (cartTray.data('isProductDrag')) {
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

    cartTray.droppable(
    {
      tolerance: 'touch',

      over: function(event, ui)
      {
        cartTray.find('.tray-area').addClass('droppable');
      },

      out: function(event, ui)
      {
        cartTray.find('.tray-area').removeClass('droppable');
      },

      drop: function(event, ui)
      {
        var pid = core.getValueFromClass(ui.draggable, 'productid');
        if (pid) {
          cartTray
            .removeClass('cart-tray-moving')
            .removeClass('cart-tray-added')
            .addClass('cart-tray-adding')
            .find('.tray-area')
            .removeClass('droppable');

          countRequests++;

          core.post(
            URLHandler.buildURL(
              {
                target: 'cart',
                action: 'add'
              }
            ),
            function(XMLHttpRequest, textStatus, data, isValid)
            {
              countRequests--;
              if (!isValid) {
                core.trigger(
                  'message',
                  {
                    text: 'An error occurred during adding the product to cart. Please refresh the page and try to drag the product to cart again or contact the store administrator.',
                    type: 'error'
                  }
                );
              }

              if (0 == countRequests) {
                if (isValid) {
                  cartTray
                    .removeClass('cart-tray-adding')
                    .addClass('cart-tray-added');

                  setTimeout(
                    function() {
                      if (cartTray.data('isProductDrag')) {
                        cartTray
                          .removeClass('cart-tray-added')
                          .addClass('cart-tray-moving');

                      } else {
                        cartTray.not('.cart-tray-adding')
                         .fadeOut(
                            cartTrayFadeOutDuration,
                            function() {
                              if (cartTray.data('isProductDrag')) {
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

                } else {
                  cartTray
                    .removeClass('cart-tray-adding')
                    .removeClass('cart-tray-active');

                }
              } // if (0 == countRequests)
            },
            {
              target:     'cart',
              action:     'add',
              product_id: pid
            },
            {
              rpc: true
            }
          ); // core.post()
        } // if (isProductDrag)
      }, // drop()
    }
    ); // cartTray.droppable()

  } // if (isSuccess)
} // ProductsListView.prototype.postprocess()


/**
 * Load product lists controller  
 */
core.autoload(ProductsListController);

