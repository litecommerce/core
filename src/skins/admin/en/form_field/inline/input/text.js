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
    pattern: '.inline-field.inline-text',
    handler: function () {

      var field = jQuery(this);

      var inputs = jQuery('.field :input', this);

      // Sanitize-and-set value into field
      this.sanitize = function()
      {
        inputs.each(
          function () {
            this.value = this.value.replace(/^ +/, '').replace(/ +$/, '');
          }
        );
      }

      // Save field into view
      this.saveField = function()
      {
        var value = this.getFieldFormattedValue();
        field.find(this.viewValuePattern).find('.value').html(htmlspecialchars("" == value ? " " : value, null, null, false));
      }

    }
  }
);
