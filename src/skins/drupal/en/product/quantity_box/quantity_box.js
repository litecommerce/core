/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product quantity box
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

jQuery('input.quantity').change(
  function() {

    var quantityBox = jQuery('input.quantity');
    var maxQuantity = parseInt(jQuery('span.product-max-qty-container').html());

    quantityBox.data('max', maxQuantity);

    if (maxQuantity < parseInt(quantityBox.val())) {
      quantityBox.addClass('wrong-amount');
      jQuery('.product-max-qty').show();
      jQuery('button.add2cart').attr('disabled', 'disabled');
      jQuery('button.buy-more').attr('disabled', 'disabled');
    } else {
      quantityBox.removeClass('wrong-amount');
      jQuery('.product-max-qty').hide();
      jQuery('button.add2cart').attr('disabled', '');
      jQuery('button.buy-more').attr('disabled', '');
    }
  }
);
