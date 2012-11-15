/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Float field microcontroller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonForm.elementControllers.push(
  {
    pattern: '.input-field-wrapper div.combobox-select',
    handler: function () {

      jQuery(this).click(
        function () {
          var input = jQuery(this).parent().find('input');
          var minLength = input.autocomplete('option', 'minLength');
          input.autocomplete('option', 'minLength', 0);
          input.autocomplete('search', '');
          input.autocomplete('option', 'minLength', minLength);
          input.focus();
        }
      );
    }
  }
);
