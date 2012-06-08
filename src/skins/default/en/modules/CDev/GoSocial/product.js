/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product details controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

/**
 * Product details Controller
 */

core.bind(
  'load',
  function() {
    decorate(
      'ProductDetailsView',
      'postprocess',
      function(isSuccess, initial)
      {
        arguments.callee.previousMethod.apply(this, arguments);

        if (isSuccess) {
          FB.XFBML.parse();
          // TODO: Check it. It seems to be an FB bug
          jQuery(".FB_Loader").remove();
        }
      }
    );
  }
);
