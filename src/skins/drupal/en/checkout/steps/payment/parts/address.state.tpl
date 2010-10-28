{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing address : state
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.payment.address", weight="40")
 *}
<li class="state">
  <label for="billing_address_state">{t(#State#)}:</label>
  <widget class="\XLite\View\StateSelect" field="billingAddress[state]" fieldId="billing_address_state" state="{sameAddress.state}" isLinked="1" className="field-required" />
</li>
