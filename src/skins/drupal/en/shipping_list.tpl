{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping rates list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="shipping-rates">
  <li FOREACH="getShippingRates(),rate">
    <input type="radio" id="method{getMethodId(rate)}" name="methodId" value="{getMethodId(rate)}" checked="{isRateSelected(rate)}" />
    <label for="method{getMethodId(rate)}">{getMethodName(rate)}</label>
    <span>{formatPrice(getMarkup(rate),cart.getCurrency())}</span>
  </li>
</ul>
