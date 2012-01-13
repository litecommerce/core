/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * JS support for the attributes book
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 */

jQuery().ready(
  function() {
    jQuery('div.group .delete').click(
      function () {
        jQuery(this).closest('li.row').css('background-color', 'red');
      }
    );
  }
);
