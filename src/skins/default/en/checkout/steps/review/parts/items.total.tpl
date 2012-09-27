{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : order review step : items : total
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.review.selected.items", weight="40")
 * @ListChild (list="checkout.review.inactive.items", weight="40")
 *}
<hr />

<div class="total clearfix">
  {t(#Total#)}:
  <span class="value"><widget class="XLite\View\Surcharge" surcharge="{cart.getTotal()}" currency="{cart.getCurrency()}" /></span>
</div>
