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
    pattern: '.input-field-wrapper input.color',
    handler: function () {

      var options = {
        onShow: function (colpkr) {
          jQuery(this).ColorPickerSetColor(this.value);
          jQuery(colpkr).fadeIn(500);

          return false;
        },
        onHide: function (colpkr) {
          jQuery(colpkr).fadeOut(500);

          return false;
        },
        onSubmit: function(hsb, hex, rgb, el) {
          var inp = jQuery('.colorpicker').get(0).owner;
          jQuery(inp).val(hex)
            .ColorPickerHide();
        },
        onBeforeShow: function () {
          jQuery('.colorpicker').get(0).owner = this;
          jQuery(this).ColorPickerSetColor(this.value);
        }
      };
      jQuery(this).ColorPicker(options);

    }
  }
);

