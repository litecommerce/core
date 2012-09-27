/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Price field controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonForm.elementControllers.push(
  {
    pattern: '.inline-field.inline-price',
    handler: function () {

      jQuery('.field :input', this).eq(0).bind('sanitize', function () {
        var input = jQuery(this);
        var dDelim = input.data('decimal-delim');
        var tDelim = input.data('thousand-delim');

        var value = core.stringToNumber(input.val(), dDelim, tDelim);

        input.val(value);
      });

      this.viewValuePattern = '.view .value';

      this.sanitize = function ()
      {
        var input = jQuery('.field :input', this).eq(0);

        if (input.length) {

          input.val(input.get(0).sanitizeValue(input.val(), input));
        }
      }

      this.getFieldFormattedValue = function ()
      {
        var input = jQuery('.field :input', this).eq(0);
        var dDelim = input.data('decimal-delim');
        var tDelim = input.data('thousand-delim');

        return core.numberToString(input.val(), dDelim, tDelim);
      }

    }
  }
);
