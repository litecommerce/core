{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing address : zipcode
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.payment.address", weight="60")
 *}
<li class="zipcode">
  <label for="billing_address_zipcode">{t(#Zipcode#)}:</label>
  <input type="text" id="billing_address_zipcode" name="billingAddress[zipcode]" value="{sameAddress.zipcode}" class="field-required field-zipcode" />
</li>
