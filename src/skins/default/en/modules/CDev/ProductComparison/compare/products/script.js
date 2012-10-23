/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product comparison
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

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
    
          jQuery('.add-to-compare').not('.binded').each(
            function() {
              var pr = jQuery(this);
              pr.change(
                function() {
                  pr.attr('disabled', 'disabled');
                  var action = pr.attr('checked') ? 'add' : 'delete';
                  core.post(
                    URLHandler.buildURL(
                      {
                        target: 'product_comparison',
                        action: action
                      }
                    ),
                    function(){},
                    {
                      target:     'product_comparison',
                      action:     action,
                      product_id: pr.data('id')
                    },
                    {
                      rpc: true
                    }
                  );
                }
              );
              core.bind('updateProductComparison',
                function(event, data) {
                  if (data.productId == pr.data('id')) {
                    pr.removeAttr('disabled');
                    if (data.action == 'add') {
                      pr.attr('checked', 'checked');

                    } else {
                      pr.removeAttr('checked');
                    }  
                  }
                }
              );
            }
          ).addClass('binded');
        }
      }
    );
  }
);
