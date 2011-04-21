/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function SwitchButton()
{
  var obj = this;

  jQuery(this.pattern).each(
    function () {
      var button;

      button = this;
      button.callbacks = core.getCommentedData(button, 'callbacks');

      button.currentCallback = button.callbacks.first;

      jQuery(this).click(
        function () {
          window[button.currentCallback](this);

          if (button.currentCallback == button.callbacks.first) {

            button.currentCallback = button.callbacks.second;

          } else {

            button.currentCallback = button.callbacks.first;
          }
        }
      );
    }
  );
}

SwitchButton.prototype.pattern = '.switch-button';

core.autoload(SwitchButton);
