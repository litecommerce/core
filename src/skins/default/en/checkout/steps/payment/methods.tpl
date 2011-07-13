{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<widget class="\XLite\View\Form\Checkout\PaymentMethod" name="paymentMethod" className="methods" />

  <ul class="payments">
    <li FOREACH="getPaymentMethods(),method">
      <input type="radio" id="pmethod{method.method_id}" name="methodId" value="{method.method_id}" {if:isPaymentSelected(method)} checked="{isPaymentSelected(method)}"{end:} {if:disabledSelector} disabled="disabled"{end:} />
      <label for="pmethod{method.method_id}"><widget template="{method.processor.getCheckoutTemplate(method)}" order="{getCart()}" method="{method}" /></label>
    </li>
  </ul>

<widget name="paymentMethod" end />
