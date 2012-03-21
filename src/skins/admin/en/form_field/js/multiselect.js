/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Multiselect microcontroller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.19
 */

CommonElement.prototype.handlers.push(
  {
    canApply: function () {
      return 0 < this.$element.filter('select.multiselect').length;
    },
    handler: function () {
      this.$element.multiselect();
    }
  }
);
