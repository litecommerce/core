/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product class field controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonForm.elementControllers.push(
  {
    pattern: '.inline-field.inline-product-class',
    handler: function () {
      this.viewValuePattern = '.view .value';
    }
  }
);
