/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * JavaScript for the module
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

decorate(
  'ProductsListView',
  'getProductSearchParams',
  function()
  {
    return jQuery.extend(
      arguments.callee.previousMethod.apply(this, arguments),
      {
        'attributes': jQuery('.search-product-form input[name="substring"]').val()
      }
    );
  }
);
