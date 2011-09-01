/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Cart / checkout additional controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.8
 */
core.bind(
  'load',
  function() {

    var func = function(isSuccess, initial)
    {
      arguments.callee.previousMethod.apply(this, arguments);

      if (isSuccess) {

        jQuery('.cart-including-modifiers', this.base).each(
          function() {
            var link = jQuery(this).parents('li:eq(0)').find('span.value');
            link.addClass('including-owner');
            attachTooltip(link, jQuery(this).html());
          }
        );
      }
    }


    decorate('CartView', 'postprocess', func);
    decorate('CheckoutView', 'postprocess', func);
  }
);
