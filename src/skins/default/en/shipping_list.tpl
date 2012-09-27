{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping rates list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<ul class="shipping-rates">
  <li FOREACH="getRates(),rate">
    <input type="radio" id="method{getMethodId(rate)}" name="methodId" value="{getMethodId(rate)}" checked="{isRateSelected(rate)}" />
    <label for="method{getMethodId(rate)}">{getMethodName(rate):h}<img src="images/spacer.gif" alt="" class="fade" /></label>
    <span class="value"><widget class="XLite\View\Surcharge" surcharge="{getTotalRate(rate)}" currency="{cart.getCurrency()}" /></span>
  </li>
</ul>
