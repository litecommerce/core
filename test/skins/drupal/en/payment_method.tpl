{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="payment-methods-box">

  <h4>Payment methods</h4>

  <widget class="XLite_View_Form_Checkout_PaymentMethod" name="payment_method_form" className="payment-methods" />

  <ul IF="paymentMethods">
    {foreach:paymentMethods,payment_method}
    <li {if:isPaymentSelected(payment_method)} class="selected"{end:}>
      <input type="radio" name="payment_id" value="{payment_method.payment_method}" id="payment_{payment_method.payment_method}" checked="isPaymentSelected(payment_method)" />
      <label for="payment_{payment_method.payment_method}">{payment_method.name:h}</label>
      <span IF="{payment_method.details}">({payment_method.details:h})</span>
    </li>
    {end:}
  </ul>

  <div class="center">
    <widget class="XLite_View_Button_Submit" label="Continue..." />
  </div>

  <widget name="payment_method_form" end />

</div>
