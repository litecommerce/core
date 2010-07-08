{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart shipping total
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.totals", weight="30")
 *}
<li><em>{t(#Shipping cost#)}:</em>
  <span IF="!cart.shippingAvailable">{t(#n/a#)}</span>
  <span IF="cart.shippingAvailable">
    <span IF="!cart.shipped">{t(#Free#)}</span>
    <span IF="cart.shipped">{price_format(cart,#shipping_cost#):h}</span>
  </span>
</li>
