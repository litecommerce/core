/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Add payment method JS controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function PopupButtonAddPaymentMethod()
{
  PopupButtonAddPaymentMethod.superclass.constructor.apply(this, arguments);
}

// New POPUP button widget extends POPUP button class
extend(PopupButtonAddPaymentMethod, PopupButton);

// New pattern is defined
PopupButtonAddPaymentMethod.prototype.pattern = '.add-payment-method-button';

decorate(
  'PopupButtonAddPaymentMethod',
  'callback',
  function (selector)
  {
    jQuery('.headers .header').click(function () {
      jQuery('.headers .header, .body .body-item').removeClass('selected');

      jQuery(this).hasClass('all-in-one-solutions')
        ? jQuery('.headers .header.all-in-one-solutions, .body .body-item.all-in-one-solutions').addClass('selected')
        : jQuery('.headers .header.payment-gateways, .body .body-item.payment-gateways').addClass('selected');

      jQuery('.ui-widget-overlay').css('height', jQuery(document).height());
    });

  }
);

// Autoloading new POPUP widget
core.autoload(PopupButtonAddPaymentMethod);
