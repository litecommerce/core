{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing address : street
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.payment.address", weight="20")
 *}
<li class="street">
  <label for="billing_address_street">{t(#Address#)}:</label>
  <input type="text" id="billing_address_street" name="billingAddress[street]" value="{sameAddress.street}" class="field-required" />
</li>
