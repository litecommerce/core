{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping address : phone
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.shipping.address", weight="70")
 *}
<li class="phone">
  <label for="shipping_address_phone">{t(#Phone#)}:</label>
  <input type="text" id="shipping_address_phone" name="shippingAddress[phone]" value="{address.phone}" class="field-phone" />
</li>
