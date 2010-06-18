{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.methods", weight="10")
 *}
<widget class="XLite_View_Form_Checkout_PaymentMethod" name="payment_method_form" className="payment-methods" />

  {displayViewListContent(#checkout.methods.payment#)}

  <div class="checkout-button-row">
    <widget class="XLite_View_Button_Submit" label="Continue..." />
  </div>

<widget name="payment_method_form" end />
