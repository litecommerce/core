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

CommonForm.elementControllers.push(
  {
    pattern: '.input-field-wrapper input.float',
    handler: function () {

      this.sanitizeValue = function (value, e)
      {
        if (0 < e) {
          value = Math.round(value * Math.pow(10, e));
          if (0 == value) {
            value = '0' + '.' + (new Array(e + 1).join('0'));

          } else {
            value = value.toString();
            value = value.substr(0, value.length - e) + '.' + value.substr(-1 * e);
          }

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
  }
);

