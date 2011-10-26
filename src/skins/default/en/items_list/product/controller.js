/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Products list controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
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

          // Added mark
          productPattern = '.product.productid-' + data.items[i].object_id;
          if (data.items[i].quantity > 0) {
            jQuery(productPattern, base).addClass('product-added');
          } else {
            jQuery(productPattern, base).removeClass('product-added');
          }

          // Check inventory limit
          if (data.items[i].is_limit) {
            jQuery(productPattern, base)
              .addClass('out-of-stock')
              .draggable('disable');

          } else {
            jQuery(productPattern, base)
              .removeClass('out-of-stock')
              .draggable('enable');
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

    // Column switcher for 'table' display mode
    jQuery('.products-table .column-switcher', this.base).commonController('markAsColumnSwitcher');

    // Must be done before any event handled on 'A' tags. IE fix
    if (jQuery.browser.msie) {
      jQuery(draggablePattern, this.base).find('a')
        .each(
          function() {
            this.defferHref = this.href;
            this.href = 'javascript:void(0);';
          }
        )
        .click(
          function() {
            if (!o.base.hasClass('ie-link-blocker')) {
              self.location = this.defferHref;
            }
          }
        );
    }

    // Register "Changing display mode" handler
    jQuery('.display-modes a', this.base).click(
      function() {
        return !o.load({'displayMode': jQuery(this).attr('class')});
      }
    );

    // Register "Sort by" selector handler
    jQuery('select.sort-crit', this.base).change(
      function () {
        return !o.load({'sortBy': jQuery(this).val()});
      }
    );

    // Register "ASC/DESC" selector handler
    jQuery('a.sort-order', this.base).click(
      function () {
        // TODO sort order value should be not defined from the content of a.sort-order
        return !o.load({'sortOrder': jQuery(this).html().charCodeAt(0) == 8595 ? 'desc' : 'asc'});
      }
    );

    // Register "Quick look" button handler
    jQuery('.quicklook a.quicklook-link', this.base).click(
      function () {
        return !popup.load(
          URLHandler.buildURL({
            target:      'quick_look',
            action:      '',
            product_id:  core.getValueFromClass(this, 'quicklook-link'),
            only_center: 1
          }),
          'product-quicklook',
          function () {
            jQuery('.formError').hide();
          },
          50000
        );
      }
    );

    var cartTrayFadeOutDuration = 400;
    var draggablePattern = '.products-grid .product, .products-list .product, .products-sidebar .product';
    var cartTray = jQuery('.cart-tray', this.base).eq(0);
    var countRequests = 0;

    cartTray.data('isProductDrag', false);

    jQuery(draggablePattern, this.base).draggable(
    {
      revert: 'invalid',
      revertDuration: 300,
      zIndex: 500,

      helper: function()
      {
        var base = jQuery(this);
        var clone = base
          .clone()
          .css(
            {
              'width':   base.width() + 'px',
              'height':  base.height() + 'px'
            }
          );

        base.addClass('drag-owner')
        if (jQuery.browser.msie) {
          base.addClass('ie-link-blocker');
        }

        clone.find('a').click(
          function() {
            return false;
          }
        );

        return clone;
      }, // helper()

      start: function(event, ui)
      {
        cartTray.data('isProductDrag', true);
        cartTray.not('.cart-tray-adding, .cart-tray-added')
          .addClass('cart-tray-active cart-tray-moving')
          .attr('style', 'display:block');
      }, // start()

      stop: function(event, ui)
      {
        cartTray.data('isProductDrag', false);
        cartTray.not('.cart-tray-adding, .cart-tray-added')
          .fadeOut(
            cartTrayFadeOutDuration,
            function() {
              if (cartTray.data('isProductDrag')) {
                jQuery(this).show();

              } else {
                jQuery(this)
                  .removeClass('cart-tray-active cart-tray-moving cart-tray-added');
              }
            }
          );

        jQuery('.drag-owner').removeClass('drag-owner');

        if (jQuery.browser.msie) {
          var downer = jQuery('.drag-owner');
          setTimeout(
            function() {
              downer.removeClass('ie-link-blocker');
            },
            1000
          );
        }

      } // stop()
    }
    ); // jQuery(draggablePattern, this.base).draggable

    // Disable out-of-stock product to drag
    var draggableDisablePattern = '.products-grid .product.out-of-stock, .products-list .product.out-of-stock, .products-sidebar .product.out-of-stock';
    jQuery(draggableDisablePattern, this.base).draggable('disable');

    // Disable not-available product to drag
    draggableDisablePattern = '.products-grid .product.not-available, .products-list .product.not-available, .products-sidebar .product.not-available';
    jQuery(draggableDisablePattern, this.base).draggable('disable');

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
            .removeClass('cart-tray-moving cart-tray-added')
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
                                jQuery(this)
                                  .removeClass('cart-tray-added')
                                  .addClass('cart-tray-moving')
                                  .show();

                              } else {
                                jQuery(this)
                                .removeClass('cart-tray-active cart-tray-added');
                              }
                            }
                          );
                      }
                    },
                    3000
                  ); // setTimeout()

                } else {
                  cartTray
                    .removeClass('cart-tray-adding cart-tray-active');

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
      } // drop()
    }
    ); // cartTray.droppable()

  } // if (isSuccess)
} // ProductsListView.prototype.postprocess()


/**
 * Load product lists controller
 */
core.autoload(ProductsListController);
