/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Cart controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
$(document).ready(
  function() {

    // Add hover listener for Move to cart button
    $('#shopping-cart .move-to-wishlist')
      .each(
        function() {
          this.moveQuantityWidget = $('.move-quantity', $(this).parents('.item-buttons'));
        }
      )
      .hover(
        function() {
          if (this.moveQuantityWidget.get(0).hideTo) {
            clearTimeout(this.moveQuantityWidget.get(0).hideTo);
            this.moveQuantityWidget.get(0).hideTo = false;
          }

          this.moveQuantityWidget.show();
        },
        function() {
          var o = this;
          this.moveQuantityWidget.get(0).hideTo = setTimeout(
            function() {
              o.moveQuantityWidget.hide();
            },
            500
          );
        }
      );

      $('#shopping-cart .move-quantity').hover(
        function() {
          if (this.hideTo) {
            clearTimeout(this.hideTo);
            this.hideTo = false;
          }

          $(this).show();
        },
        function() {
          var o = this;
          this.hideTo = setTimeout(
            function() {
              $(o).hide();
            },
            500
          );
        }
      );
  }
);
