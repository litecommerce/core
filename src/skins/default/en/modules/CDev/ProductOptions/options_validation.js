/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product options functions
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

// Get product option element by name
function product_option(name_of_option)
{
  var e = jQuery('form[name="add_to_cart"] :input').filter(
    function() {
      return this.name && this.name.search(name_of_option) != -1;
    }
  );

  return e.get(0);
}
