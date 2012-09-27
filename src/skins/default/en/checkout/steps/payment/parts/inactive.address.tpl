{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : payment step : inactive state : address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.payment.inactive", weight="10")
 *}
{if:isAddressCompleted()}
  <widget template="checkout/parts/address.plain.tpl" address="{cart.profile.getBillingAddress()}" />
{else:}
  <p class="address-not-defined">{t(#Billing address is not defined yet#)}</p>
{end:}
