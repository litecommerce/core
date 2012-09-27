/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Autogenerate CleanURL
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonForm.elementControllers.push(
  {
    pattern: '.input-field-wrapper.auto-clean-url',
    handler: function () {
      var chk = jQuery(':checkbox', this);
      var input = jQuery('.input-field-wrapper.clean-url input', this.form).eq(0);
      if (input.length) {
        chk.change(
          function () {
            if (this.checked) {
              input.attr('disabled', 'disabled');

            } else {
              input.removeAttr('disabled');
            }
          }
        );
      }
    }
  }
);

