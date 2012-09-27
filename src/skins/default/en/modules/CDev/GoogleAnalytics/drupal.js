/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Drupal-specific controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

jQuery().ready(
  function() {

    // Detect add to cart
    core.bind(
      'updateCart',
      function(event, data) {

        if (data.items) {
          for (var i = 0; i < data.items.length; i++) {
            var item = data.items[i];

            if (item.quantity_change > 0 && item.quantity_change == item.quantity) {

              // Add to cart
              _gaq.push(['_trackEvent', 'cart', 'add', item.key, item.quantity_change]);

            } else if (item.quantity_change < 0 && (item.quantity == 0 || (item.quantity + item.quantity_change) <= 0)) {

              // Remove from cart
              _gaq.push(['_trackEvent', 'cart', 'remove', item.key, item.quantity_change]);

            } else {

              // Change quantity
              _gaq.push(['_trackEvent', 'cart', 'change', item.key, item.quantity_change]);
            }
          }
        }
      }
    );

  }
);

