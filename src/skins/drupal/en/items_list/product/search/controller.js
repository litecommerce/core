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

// Decoration of the products list widget class
core.bind(
  'load',
  function() {
    decorate(
      'ProductsListView',
      'postprocess',
      function(isSuccess, initial)
      {
        arguments.callee.previousMethod.apply(this, arguments);

        if (isSuccess) {
          var o = this;

          // handle "Search" button in the search products form
          if ('search' == core.getValueFromClass(this.base, 'widgettarget')) {
            $('.search-product-form form').unbind('submit').submit(
              function (event)
              {
                if (
                  o.submitForm(
                    this,
                    function (XMLHttpRequest, textStatus, data, isValid) {
                      if (isValid) {
                        o.load();
                      } else {
                        o.unshade();
                      }
                    }
                  )
                ) {
                  o.shade();
                }

                return false;
              }
            );
          }

        } // if (isSuccess) {
      } // function(isSuccess, initial)
    ); // 'postprocess' method decoration (EXISTING method)
  }
); // core.bind()

