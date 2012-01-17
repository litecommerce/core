/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Float field microcontroller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

jQuery().ready(
  function () {
    jQuery('input.float').each(
      function () {

        this.sanitizeValue = function (value, e)
        {
          if (0 < e) {
            value = Math.round(value * Math.pow(10, e)).toString();
            value = value.substr(0, value.length - e) + '.' + value.substr(-1 * e);

          } else {
            value = Math.round(value);
          }

          return value;
        }

        this.commonController.isEqualValues = function (oldValue, newValue)
        {
          var e = this.$element.data('e');
          e = e ? e : 0;

          return this.element.sanitizeValue(oldValue, e) == this.element.sanitizeValue(newValue, e);
        }

      }
    );
  }
);

