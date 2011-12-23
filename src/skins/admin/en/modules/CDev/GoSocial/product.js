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
    jQuery('#useCustomOGFF').click(
      function () {
        if (this.checked) {
          jQuery('.og-tags textarea').removeAttr('disabled').removeClass('disabled');

        } else {
          jQuery('.og-tags textarea').attr('disabled', 'disabled').addClass('disabled');
        }
      }
    );
    jQuery('.og-tags textarea').filter(function () { return this.disabled; }).addClass('disabled');
  }
);
