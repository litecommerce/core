/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Orders search form controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

jQuery(document).ready(
  function() {

    // Add search form visibility switcher
    jQuery('a.search-orders').click(
      function() {
        var elm = jQuery('form.search-orders');
        if (elm.css('display') == 'none') {
          jQuery(this).addClass('dynamic-open').removeClass('dynamic-close');
          elm.show();

        } else {
          jQuery(this).addClass('dynamic-close').removeClass('dynamic-open');
          elm.hide();
        }

        return false;
      }
    );

    // Reset form
    jQuery('form.search-orders .reset a').click(
      function() {

        var form = jQuery(this).parents('form').eq(0);
        jQuery('input:text', form).val('');
        jQuery('select[name="status"]', form).val('');

        form.trigger('customReset');

        form.submit();

        return false;
      }
    );
  }
);
