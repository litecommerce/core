/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Text-bases input field controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

core.bind(
  'load',
  function(event) {
    jQuery('.input-field-wrapper').each(function () {
      var obj = jQuery(this);

      var inputField = jQuery('input,textarea', obj);
      var defaultValue = core.getCommentedData(obj, 'defaultValue');
      var type = inputField.attr('type');

      if (
        '' !== defaultValue
        && null !== defaultValue
        && 'checkbox' != type
        && 'radio' != type
        && 'file' != type
        && 'image' != type
        && 'submit' != type
        && 'button' != type
      ) {

        if ('' === inputField.val()) {
          inputField.val(defaultValue).addClass('default-value');
        }

        inputField.click(
          function () {
            if (defaultValue === inputField.val()) {
              inputField.removeClass('default-value').val('');
            }
          }
        ).blur(
          function () {
            if ('' === inputField.val()) {
              inputField.addClass('default-value').val(defaultValue);
            }
          }
        );

        inputField
          .parents('form')
          .submit(
            function() {
              if (defaultValue === inputField.val()) {
                inputField.val('');
              }
  
              return true;
            }
          );
      }
    });
  }
);
