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

      this.sanitizeValue = function (value, input)
      {
        if (!input) {
          input = jQuery(this);
        }

        var e = input.data('e');
        var dDelim = input.data('decimal-delim');
        var tDelim = input.data('thousand-delim');

        value = core.stringToNumber(value, dDelim, tDelim);

        if ('undefined' == typeof(e)) {
          value = parseFloat(value);
          if (isNaN(value)) {
            value = 0;
          }

        } else {
          var pow = Math.pow(10, e);
          value = Math.round(value * pow) / pow;
        }

        return value;
      }

      this.commonController.isEqualValues = function (oldValue, newValue, element)
      {
        return this.element.sanitizeValue(oldValue, element) == this.element.sanitizeValue(newValue, element);
      }

    }
  }
);

