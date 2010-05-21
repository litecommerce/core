/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Shopping cart controller
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
    $('.selected-products form input[name="amount"]').change(
      function() {
        var result = true;
        var amount = parseInt(this.value);
        result = !isNaN(amount) && 0 < amount;

        if (result) {
          $(this.form).submit();

        } else {
          this.value = this.initialValue;
        }
 
        return result; 
      }
    )
    .each(
      function() {
        this.initialValue = this.value;
      }
    );
  }
);
