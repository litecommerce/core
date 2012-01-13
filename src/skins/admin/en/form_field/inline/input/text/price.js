/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Price field controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

jQuery().ready(
  function () {
    jQuery('.inline-field.inline-price').each(
      function () {
        this.saveField = function ()
        {
          var input = jQuery(this).find('.field :input').eq(0);
          var value = input.val();
          var e = input.data('e');
          e = e ? e : 0;
          if (0 < e) {
            value = Math.round(value * Math.pow(10, e)).toString();
            value = value.substr(0, value.length - e) + '.' + value.substr(-1 * e);
          }

          jQuery('.view .value', this).html(value);
        }
      }
    );
  }
);

