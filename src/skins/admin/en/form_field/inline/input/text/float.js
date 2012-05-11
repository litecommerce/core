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

CommonForm.elementControllers.push(
  {
    pattern: '.inline-field.inline-float',
    handler: function () {

      this.sanitize = function ()
      {
        var input = jQuery('.field :input', this).eq(0);
        if (input.length) {
          var e = input.data('e');
          input.val(input.get(0).sanitizeValue(input.val(), e ? e : 0));
        }
      }

    }
  }
);
