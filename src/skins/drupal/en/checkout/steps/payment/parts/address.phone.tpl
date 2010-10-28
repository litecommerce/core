{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing address : phone
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.payment.address", weight="70")
 *}
<li class="phone">
  <label for="billing_address_phone">{t(#Phone#)}:</label>
  <input type="text" id="billing_address_phone" name="billingAddress[phone]" value="{sameAddress.phone}" class="field-phone" />
</li>
