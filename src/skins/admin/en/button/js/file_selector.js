/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * File selector button and popup controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.6
 */

var lastFileSelectorButton;

function PopupButtonFileSelector()
{
  PopupButtonFileSelector.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonFileSelector, PopupButton);

PopupButtonFileSelector.prototype.pattern = '.file-selector-button';

/**
 * Connect radio and text input fields
 * If one of the field gets focus then the connected one gets focus also
 */
PopupButtonFileSelector.prototype.connectInputs = function (radioInput, textInput) {

  var valueRadioInput = jQuery(radioInput).val();

  jQuery(radioInput).bind(
    'focus',
    function () {
      jQuery(textInput).focus();
    }
  );

  jQuery(textInput).bind(
    'focus',
    function () {
      jQuery('input[name="file_select"]').filter(
        function (index) {
          return jQuery(this).val() == valueRadioInput
          }
        ).attr('checked', 'checked');
    }
  );

  jQuery(textInput).bind(
    'click',
    function () {
      jQuery('input[name="file_select"]').filter(
        function (index) {
          return jQuery(this).val() == valueRadioInput
          }
        ).attr('checked', 'checked');
    }
  );

};

decorate(
  'PopupButtonFileSelector',
  'callback',
  function (selector)
  {
    // Autoloading of browse server link (popup widget).
    // TODO. make it dynamically and move it to ONE widget initialization (Main widget)
    core.autoload(PopupButtonBrowseServer);

    // Connect local server file field
    PopupButtonFileSelector.prototype.connectInputs.call(
      this,
      '.local-server-label input[name="file_select"]',
      'input[name="local_server_file"]'
    );

    // Connect URL field
    PopupButtonFileSelector.prototype.connectInputs.call(
      this,
      '.url-label input[name="file_select"]',
      'input[name="url"]'
    );

    // Connect uploaded file (local computer file) field
    PopupButtonFileSelector.prototype.connectInputs.call(
      this,
      '.local-computer-label input[name="file_select"]',
      'input[name="uploaded_file"]'
    );

    jQuery("#url").bind(
      'focus',
      function () {
        jQuery("#url-copy-to-local").attr('disabled', '');
      }
    ).bind(
      'blur',
      function () {
        if (jQuery(this).val() == '') {
          jQuery("#url-copy-to-local").attr({'checked' : '', 'disabled': 'disabled'});
        }
      }
    );

    lastFileSelectorButton = lastPopupButton;
  }
);

core.autoload(PopupButtonFileSelector);
