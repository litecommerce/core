{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout details shipping block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="chekout.details", weight="20")
 *}
<div IF="cart.shippingMethod" class="shipping-method">
  <span class="title">Shipping method:</span>&nbsp;&nbsp;<span class="text">{cart.shippingMethod.name} ({price_format(cart,#shipping_cost#):h})</span>
  <widget class="XLite_View_Button_Link" label="Change shipping method" style="change" location="{buildURL(#checkout#,##,_ARRAY_(#mode#^#paymentMethod#))}" />
</div>
