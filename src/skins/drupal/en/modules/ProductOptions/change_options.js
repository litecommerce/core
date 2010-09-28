/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Change options additional controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

core.bind(
  'load',
  function() {
    decorate(
      'CartView',
      'postprocess',
      function(isSuccess, initial)
      {
        arguments.callee.previousMethod.apply(this, arguments);

        if (isSuccess) {

          var o = this;

          $('.item-change-options a', this.base).click(
            function(event) {
              return o.changeOptions(event, this);
            }
          );
        }
      }
    );
    decorate(
      'CartView',
      'changeOptions',
      function(event, link)
      {
        return !popup.load(link.href);
      }
    );
  }
);
