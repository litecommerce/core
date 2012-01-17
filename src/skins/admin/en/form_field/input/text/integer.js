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
    jQuery('input.integer').each(
      function () {

        this.sanitizeValue = function (value)
        {
          return Math.round(value);
        }

        this.commonController.isEqualValues = function (oldValue, newValue)
        {
          return this.element.sanitizeValue(oldValue) == this.element.sanitizeValue(newValue);
        }

      }
    );
  }
);

