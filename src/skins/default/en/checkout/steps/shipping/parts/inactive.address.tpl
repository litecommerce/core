{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : shipping step : inactive state : address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="checkout.shipping.inactive", weight="10")
 *}

{if:isAddressCompleted()}
  <widget template="checkout/parts/address.plain.tpl" address="{cart.profile.getShippingAddress()}" />
{else:}
  <p class="address-not-defined">{t(#Shipping address is not defined yet#)}</p>
{end:}
