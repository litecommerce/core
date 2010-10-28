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
<widget class="\XLite\View\Form\Checkout\PaymentMethod" name="paymentMethod" className="methods" />

  <ul class="payments">
    <li FOREACH="getPaymentMethods(),method">
      <input type="radio" id="pmethod{method.method_id}" name="methodId" value="{method.method_id}" {if:isPaymentSelected(method)} checked="{isPaymentSelected(method)}"{end:} {if:disabled} disabled="disabled"{end:} />
      <label for="pmethod{method.method_id}">{if:method.getDescription()}{method.getDescription()}{else:}{method.getName()}{end:}</label>
    </li>
  </ul>

<widget name="paymentMethod" end />
