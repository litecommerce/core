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
 * @ListChild (list="checkout.methods.payment", weight="10")
 *}
<div id="payment-methods-box">

  <h2>{t(#Payment methods#)}</h2>

  <ul IF="getPaymentMethods()">
    {foreach:getPaymentMethods(),method}
    <li {if:isPaymentSelected(method)} class="selected"{end:}>
      <input type="radio" name="payment_id" value="{method.getMethodId()}" id="payment_{method.getMethodId()}" checked="isPaymentSelected(method)" />
      <label for="payment_{method.getMethodId()}">{method.getName():h}</label>
      <span IF="{method.getDescription()}">{method.description:h}</span>
    </li>
    {end:}
  </ul>

</div>
