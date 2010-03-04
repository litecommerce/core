/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Products list
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
    var f = productsList.buildURL;

    productsList.buildURL = function(forAJAX) {
      var url = f.call(productsList, forAJAX);

      if (forAJAX) {
        var block = this.container.parents('.block').get(0);
        if (block && block.id) {
          var m = block.id.match(/block-lc-connector-([0-9]+)/);
          if (m) {
            url = url.replace(new RegExp(productsListConfig.urlTranslationTable.blockDelta), m[1]);
          }
        }
      }

      return url;
    }
  }
);
