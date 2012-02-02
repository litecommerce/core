/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Additional product page controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

jQuery().ready(
  function () {
    jQuery('.og-tags .control select').change(
      function () {
        if (1 == this.options[this.selectedIndex].value) {
          jQuery('.og-tags .og-textarea').show();

        } else {
          jQuery('.og-tags .og-textarea').hide();
        }
      }
    );
  }
);
