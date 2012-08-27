/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sale widget controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

decorate(
  'CheckoutView',
  'postprocess',
  function(isSuccess, initial)
  {
    if (jQuery('.review-step.current').length) {
        if (jQuery('#xpc_iframe').length) {
            jQuery('.review-step.current').addClass('xpc');

            jQuery('div.hide-submit').mouseover(
              function() {
                jQuery('.terms').addClass('non-agree');
              }
            ).mouseout(
              function() {
                jQuery('.terms').removeClass('non-agree');
              }
            );

            jQuery('#place_order_agree').change(
              function() {
                if (jQuery(this).attr('checked')) {
                  jQuery('div.hide-submit').hide();
                } else {
                  jQuery('div.hide-submit').show();
                }
              }
            );

        } else {
            jQuery('.review-step.current').removeClass('xpc');
        }
    }

    arguments.callee.previousMethod.apply(this, arguments);
  }
);
