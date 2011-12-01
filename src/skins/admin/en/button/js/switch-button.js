/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Swicth button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

// Switch button widget constructor
function SwitchButton()
{
  var obj = this;

  // Every 'pattern' DOM element is changed to Switch button widget
  jQuery(this.pattern).each(
    function () {
      var button;

      button = this;
      // callbacks are defined from HTML inner comments
      button.callbacks = core.getCommentedData(button, 'callbacks');

      // 'first' callback is called first
      button.currentCallback = button.callbacks.first;

      jQuery(this).click(
        function () {
          // inner calling of current callback
          window[button.currentCallback](this);

          // current callback is changed to another one (switch to next callback. There are two callbacks now TODO?)
          button.currentCallback = button.currentCallback == button.callbacks.first
            ? button.callbacks.second
            : button.callbacks.first;
        }
      );
    }
  );
}

// Switch button widgets are buttons with 'switch-button' CSS class
SwitchButton.prototype.pattern = 'button.switch-button';

// Autoloading of new Switch button widget
core.autoload(SwitchButton);
