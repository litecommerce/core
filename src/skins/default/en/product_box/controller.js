/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product box controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

jQuery().ready(
  function() {
    var base = jQuery('.product-block .product');

    // Register "Quick look" button handler
    jQuery('.quicklook a.quicklook-link', base).click(
      function () {
        return !popup.load(
          URLHandler.buildURL({
            target:      'quick_look',
            action:      '',
            product_id:  core.getValueFromClass(this, 'quicklook-link'),
            only_center: 1
          }),
          'product-quicklook',
          false,
          50000
        );
      }
    );
  }
);

